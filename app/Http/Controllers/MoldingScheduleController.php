<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MoldingScheduleController extends Controller
{
    private const CONNECTION = 'mysql';
    private const MOLDING_TABLE = 'molding_diagnose_masters';

    public function monitoringForms(Request $request)
    {
        $db = DB::connection(self::CONNECTION);
        $periods = $db->table('molding_maintenance_schedules')
            ->whereNull('deleted_at')
            ->orderBy('period', 'desc')
            ->pluck('period')
            ->map(function ($period) { return substr($period, 0, 7); })
            ->all();
        $period = $request->input('period');
        if ($period !== null && (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $period))) {
            return response()->json(['status' => false, 'message' => 'Format periode harus YYYY-MM.'], 422);
        }
        $showAll = $request->input('all') === '1';
        if (!$showAll && $period === null) {
            $period = count($periods) ? $periods[0] : null;
        }
        $schema = Schema::connection(self::CONNECTION);
        $hasFormDeletedAt = $schema->hasColumn('molding_diagnose_forms', 'deleted_at');
        $query = $db->table('molding_maintenance_schedules as s')
            ->join('molding_maintenance_schedule_items as si', 'si.schedule_id', '=', 's.id')
            ->leftJoin('molding_diagnose_forms as f', function ($join) use ($hasFormDeletedAt) {
                $join->on(
                    DB::raw('f.fixed_asset_number COLLATE utf8mb4_unicode_ci'),
                    '=',
                    DB::raw('si.molding_id COLLATE utf8mb4_unicode_ci')
                )
                    ->on('f.period', '=', 's.period')
                    ->where('f.remark', '=', 'periodic');
                if ($hasFormDeletedAt) {
                    $join->whereNull('f.deleted_at');
                }
            })
            ->whereNull('s.deleted_at')
            ->whereNull('si.deleted_at')
            ->select(
                'si.id as schedule_item_id',
                'si.molding_id',
                'si.molding_name',
                'si.status as schedule_status',
                's.period',
                'f.id as form_id',
                'f.form_number',
                'f.fixed_asset_number as form_fixed_asset_number',
                'f.fixed_asset_name',
                'f.vendor'
            );
        foreach (['status' => 'form_status', 'location' => 'location',
            'form_product_status' => 'product_status',
            'form_molding_status' => 'molding_status',
            'form_evaluation_status' => 'evaluation_status'] as $column => $alias) {
            if ($schema->hasColumn('molding_diagnose_forms', $column)) {
                $query->addSelect('f.' . $column . ' as ' . $alias);
            } else {
                $query->selectRaw('NULL as ' . $alias);
            }
        }
        if (!$showAll) {
            if ($period === null) {
                return response()->json([
                    'status' => true, 'period' => null, 'periods' => [], 'rows' => [],
                ]);
            }
            $query->where('s.period', $period . '-01');
        }
        $rows = $query->orderBy('s.period', 'desc')
            ->orderBy('si.molding_name')
            ->orderBy('f.id', 'desc')
            ->get();
        $formNumbers = $rows->pluck('form_number')->filter()->unique()->values()->all();
        $approvalSteps = collect();
        if (count($formNumbers)) {
            $approvalSteps = $db->table('molding_diagnose_approvers')
                ->whereIn('form_number', $formNumbers)
                ->orderBy('form_number')
                ->orderBy('ordering')
                ->select('form_number', 'approver_name', 'ordering', 'status', 'comment')
                ->get()
                ->groupBy('form_number');
        }
        $rows->transform(function ($formRow) use ($approvalSteps) {
            $steps = $approvalSteps->get($formRow->form_number);
            $formRow->approvals = $steps ? $steps->map(function ($step) {
                return [
                    'ordering' => $step->ordering,
                    'name' => $step->approver_name,
                    'status' => $step->status,
                    'comment' => $step->comment,
                ];
            })->values() : collect();
            return $formRow;
        });
        return response()->json([
            'status' => true,
            'period' => $showAll ? null : $period,
            'periods' => $periods,
            'rows' => $rows,
        ]);
    }
    public function monitoringSummary()
    {
        $schedules = DB::connection(self::CONNECTION)
            ->table('molding_maintenance_schedules as s')
            ->join('molding_maintenance_schedule_items as si', 'si.schedule_id', '=', 's.id')
            ->whereNull('s.deleted_at')
            ->whereNull('si.deleted_at')
            ->select('s.period', 'si.molding_id', 'si.molding_name', 'si.status')
            ->selectRaw("DATE_FORMAT(s.period, '%Y %b') as period_name")
            ->orderBy('s.period')
            ->get();
        return response()->json(['status' => true, 'schedules' => $schedules]);
    }
    public function index()
    {
        $db = DB::connection(self::CONNECTION);
        $moldings = $db->table(self::MOLDING_TABLE)
            ->select('fixed_asset_number', 'fixed_asset_name as molding_name')
            ->whereNotNull('fixed_asset_number')
            ->where('fixed_asset_number', '!=', '')
            ->orderBy('fixed_asset_name')
            ->get()
            ->unique('fixed_asset_number')
            ->values();
        $schedules = $db->table('molding_maintenance_schedules as s')
            ->select('s.id', 's.period', 's.created_at')
            ->whereNull('s.deleted_at')
            ->orderBy('s.period', 'desc')
            ->get();
        $items = $db->table('molding_maintenance_schedule_items as si')
            ->join('molding_maintenance_schedules as s', 's.id', '=', 'si.schedule_id')
            ->select('si.id', 'si.schedule_id', 'si.molding_id', 'si.molding_name', 'si.status')
            ->whereNull('si.deleted_at')
            ->whereNull('s.deleted_at')
            ->orderBy('si.molding_name')
            ->get()
            ->groupBy('schedule_id');
        foreach ($schedules as $schedule) {
            $schedule->items = $items->get($schedule->id, collect())->values();
            $schedule->period_month = substr($schedule->period, 0, 7);
        }
        return view('molding.molding_schedules', compact('schedules', 'moldings'));
    }
    public function store(Request $request)
    {
        $data = $this->validateInput($request);
        $period = $data['period'] . '-01';
        $db = DB::connection(self::CONNECTION);
        $db->transaction(function () use ($db, $period, $data) {
            $now = now();
            $names = $db->table(self::MOLDING_TABLE)
                ->whereIn('fixed_asset_number', $data['molding_ids'])
                ->pluck('fixed_asset_name', 'fixed_asset_number');
            $oldSchedule = $db->table('molding_maintenance_schedules')
                ->where('period', $period)->lockForUpdate()->first();
            if ($oldSchedule && $oldSchedule->deleted_at === null) {
                throw ValidationException::withMessages(['period' => 'Periode sudah memiliki schedule.']);
            }
            if ($oldSchedule) {
                $id = $oldSchedule->id;
                $db->table('molding_maintenance_schedules')->where('id', $id)
                    ->update(['deleted_at' => null, 'updated_at' => $now]);
            } else {
                $id = $db->table('molding_maintenance_schedules')->insertGetId([
                    'period' => $period,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]);
            }
            $previous = $db->table('molding_maintenance_schedule_items')
                ->where('schedule_id', $id)->get()->keyBy('molding_id');
            if ($oldSchedule) {
                $db->table('molding_maintenance_schedule_items')
                    ->where('schedule_id', $id)->whereNull('deleted_at')
                    ->update(['deleted_at' => $now, 'updated_at' => $now]);
            }
            $rows = [];
            foreach ($data['molding_ids'] as $moldingId) {
                $values = [
                    'schedule_id' => $id,
                    'molding_id' => $moldingId,
                    'molding_name' => $names[$moldingId],
                    'status' => 'Open',
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
                if ($previous->has($moldingId)) {
                    $db->table('molding_maintenance_schedule_items')
                        ->where('id', $previous->get($moldingId)->id)->update($values);
                } else {
                    $values['created_at'] = $now;
                    $rows[] = $values;
                }
            }
            if ($rows) {
                $db->table('molding_maintenance_schedule_items')->insert($rows);
            }
        });
        return response()->json(['status' => true, 'message' => 'Schedule berhasil ditambahkan.']);
    }
    public function update(Request $request, $id)
    {
        $data = $this->validateInput($request, (int) $id);
        $db = DB::connection(self::CONNECTION);
        $db->transaction(function () use ($db, $id, $data) {
            $schedule = $db->table('molding_maintenance_schedules')
                ->where('id', $id)->whereNull('deleted_at')->lockForUpdate()->first();
            abort_unless($schedule, 404);
            $db->table('molding_maintenance_schedules')->where('id', $id)->update([
                'period' => $data['period'] . '-01',
                'updated_at' => now(),
            ]);
            // Pertahankan ID item yang masih dipilih agar relasi lain tetap utuh.
            $db->table('molding_maintenance_schedule_items')
                ->where('schedule_id', $id)
                ->whereNull('deleted_at')
                ->whereNotIn('molding_id', $data['molding_ids'])
                ->update(['deleted_at' => now(), 'updated_at' => now()]);
            $previous = $db->table('molding_maintenance_schedule_items')
                ->where('schedule_id', $id)
                ->get()->keyBy('molding_id');
            $now = now();
            $rows = [];
            $names = $db->table(self::MOLDING_TABLE)
                ->whereIn('fixed_asset_number', $data['molding_ids'])
                ->pluck('fixed_asset_name', 'fixed_asset_number');
            foreach ($data['molding_ids'] as $moldingId) {
                if ($previous->has($moldingId)) {
                    $item = $previous->get($moldingId);
                    if ($item->deleted_at !== null) {
                        $db->table('molding_maintenance_schedule_items')->where('id', $item->id)->update([
                            'molding_name' => $names[$moldingId],
                            'status' => 'Open',
                            'deleted_at' => null,
                            'updated_at' => $now,
                        ]);
                    }
                    continue;
                }
                $rows[] = [
                    'schedule_id' => $id,
                    'molding_id' => $moldingId,
                    'molding_name' => $names[$moldingId],
                    'status' => 'Open',
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }
            if ($rows) {
                $db->table('molding_maintenance_schedule_items')->insert($rows);
            }
        });
        return response()->json(['status' => true, 'message' => 'Schedule berhasil diperbarui.']);
    }
    public function destroy($id)
    {
        $db = DB::connection(self::CONNECTION);
        $db->transaction(function () use ($db, $id) {
            $schedule = $db->table('molding_maintenance_schedules')
                ->where('id', $id)->whereNull('deleted_at')->lockForUpdate()->first();
            abort_unless($schedule, 404);
            $now = now();
            $db->table('molding_maintenance_schedule_items')
                ->where('schedule_id', $id)->whereNull('deleted_at')
                ->update(['deleted_at' => $now, 'updated_at' => $now]);
            $db->table('molding_maintenance_schedules')->where('id', $id)
                ->update(['deleted_at' => $now, 'updated_at' => $now]);
        });
        return response()->json(['status' => true, 'message' => 'Schedule berhasil dihapus.']);
    }
    public function destroyMolding($scheduleId, $itemId)
    {
        $db = DB::connection(self::CONNECTION);
        $db->transaction(function () use ($db, $scheduleId, $itemId) {
            $schedule = $db->table('molding_maintenance_schedules')
                ->where('id', $scheduleId)->whereNull('deleted_at')->lockForUpdate()->first();
            abort_unless($schedule, 404);
            $deleted = $db->table('molding_maintenance_schedule_items')
                ->where('schedule_id', $scheduleId)->where('id', $itemId)
                ->whereNull('deleted_at')
                ->update(['deleted_at' => now(), 'updated_at' => now()]);
            abort_unless($deleted, 404);
            // Schedule tanpa molding tidak disimpan.
            if (!$db->table('molding_maintenance_schedule_items')
                ->where('schedule_id', $scheduleId)->whereNull('deleted_at')->exists()) {
                $db->table('molding_maintenance_schedules')->where('id', $scheduleId)
                    ->update(['deleted_at' => now(), 'updated_at' => now()]);
            }
        });
        return response()->json(['status' => true, 'message' => 'Molding dihapus dari schedule.']);
    }
    private function validateInput(Request $request, $ignoreId = null)
    {
        $validator = Validator::make($request->all(), [
            'period' => ['required', 'date_format:Y-m'],
            'molding_ids' => ['required', 'array', 'min:1'],
            'molding_ids.*' => ['required', 'string', 'max:100', 'distinct'],
        ]);
        $data = $validator->validate();
        $db = DB::connection(self::CONNECTION);
        $periodQuery = $db->table('molding_maintenance_schedules')
            ->where('period', $data['period'] . '-01');
        if ($ignoreId !== null) {
            $periodQuery->where('id', '!=', $ignoreId);
        } else {
            // Saat membuat ulang periode yang terhapus, baris lama dipulihkan.
            $periodQuery->whereNull('deleted_at');
        }
        $conflict = $periodQuery->first();
        if ($conflict) {
            $message = $conflict->deleted_at === null
                ? 'Periode sudah memiliki schedule. Silakan edit schedule tersebut.'
                : 'Periode pernah digunakan. Buat schedule baru untuk memulihkan periode tersebut terlebih dahulu.';
            throw ValidationException::withMessages(['period' => $message]);
        }
        $validCount = $db->table(self::MOLDING_TABLE)
            ->whereIn('fixed_asset_number', $data['molding_ids'])
            ->distinct()->count('fixed_asset_number');
        if ($validCount !== count($data['molding_ids'])) {
            throw ValidationException::withMessages(['molding_ids' => 'Salah satu molding tidak ditemukan.']);
        }
        return $data;
    }
}
