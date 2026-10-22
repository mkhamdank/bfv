<?php

namespace App\Http\Controllers;

use App\Services\QuotaService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class QuotaAdminController extends Controller
{
    public function __construct(private QuotaService $quotaService)
    {
    }

    public function policies(): JsonResponse
    {
        $policies = DB::connection('quota')->table('quota_policies')
            ->orderBy('endpoint_slug')
            ->get()
            ->map(function ($policy) {
                $policy->quota_status = $this->quotaService->getQuotaStatus($policy->endpoint_slug);

                return $policy;
            });

        return response()->json(['data' => $policies]);
    }

    public function storePolicy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint_slug' => ['required', 'string', 'max:255'],
            'daily_limit' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:512'],
        ]);

        $connection = DB::connection('quota');
        if ($connection->table('quota_policies')->where('endpoint_slug', $data['endpoint_slug'])->exists()) {
            return response()->json(['message' => 'Policy untuk endpoint tersebut sudah ada.'], 409);
        }

        $now = CarbonImmutable::now('UTC')->format('Y-m-d H:i:s');
        $connection->table('quota_policies')->insert($data + [
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json([
            'data' => $connection->table('quota_policies')->where('endpoint_slug', $data['endpoint_slug'])->first(),
        ], 201);
    }

    public function updatePolicy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint_slug' => ['required', 'string', 'max:255'],
            'daily_limit' => ['sometimes', 'integer', 'min:0', 'max:4294967295'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:512'],
        ]);

        $endpointSlug = $data['endpoint_slug'];
        unset($data['endpoint_slug']);

        $connection = DB::connection('quota');
        $policy = $connection->table('quota_policies')->where('endpoint_slug', $endpointSlug);
        if (! $policy->exists()) {
            return response()->json(['message' => 'Policy endpoint tidak ditemukan.'], 404);
        }

        $data['updated_at'] = CarbonImmutable::now('UTC')->format('Y-m-d H:i:s');
        $policy->update($data);

        return response()->json([
            'data' => $connection->table('quota_policies')->where('endpoint_slug', $endpointSlug)->first(),
        ]);
    }

    public function deletePolicy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint_slug' => ['required', 'string', 'max:255'],
        ]);

        $deleted = DB::connection('quota')->table('quota_policies')
            ->where('endpoint_slug', $data['endpoint_slug'])
            ->delete();

        if ($deleted === 0) {
            return response()->json(['message' => 'Policy endpoint tidak ditemukan.'], 404);
        }

        return response()->json(null, 204);
    }

    public function status(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint_slug' => ['required', 'string', 'max:255'],
        ]);

        return response()->json(['data' => $this->quotaService->getQuotaStatus($data['endpoint_slug'])]);
    }

    public function usage(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, true);
        $query = DB::connection('quota')->table('quota_usage')
            ->select([
                'id', 'endpoint_slug', 'usage_date', 'employee', 'call_count',
                'input_tokens', 'output_tokens', 'total_tokens', 'metadata', 'created_at',
            ]);
        $this->applyFilters($query, $filters);

        return response()->json($query->orderByDesc('created_at')->paginate($filters['per_page'] ?? 50));
    }

    public function dailySummary(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request);
        $query = DB::connection('quota')->table('quota_usage')
            ->select(['usage_date', 'endpoint_slug'])
            ->selectRaw('SUM(call_count) AS calls')
            ->selectRaw('COALESCE(SUM(input_tokens), 0) AS input_tokens')
            ->selectRaw('COALESCE(SUM(output_tokens), 0) AS output_tokens')
            ->selectRaw('COALESCE(SUM(total_tokens), 0) AS total_tokens')
            ->selectRaw('ROUND(SUM(total_tokens) / NULLIF(SUM(call_count), 0), 2) AS average_tokens_per_call')
            ->selectRaw('MIN(created_at) AS first_call_at')
            ->selectRaw('MAX(created_at) AS last_call_at')
            ->selectRaw('COUNT(DISTINCT employee) AS unique_employees');
        $this->applyFilters($query, $filters);

        return response()->json([
            'data' => $query->groupBy('usage_date', 'endpoint_slug')
                ->orderByDesc('usage_date')
                ->orderBy('endpoint_slug')
                ->get(),
        ]);
    }

    public function employeeSummary(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request);
        $query = DB::connection('quota')->table('quota_usage')
            ->whereNotNull('employee')
            ->select(['employee', 'usage_date', 'endpoint_slug'])
            ->selectRaw('SUM(call_count) AS calls')
            ->selectRaw('COALESCE(SUM(input_tokens), 0) AS input_tokens')
            ->selectRaw('COALESCE(SUM(output_tokens), 0) AS output_tokens')
            ->selectRaw('COALESCE(SUM(total_tokens), 0) AS total_tokens')
            ->selectRaw('MIN(created_at) AS first_call_at')
            ->selectRaw('MAX(created_at) AS last_call_at');
        $this->applyFilters($query, $filters);

        return response()->json([
            'data' => $query->groupBy('employee', 'usage_date', 'endpoint_slug')
                ->orderByDesc('usage_date')
                ->orderBy('employee')
                ->get(),
        ]);
    }

    public function hourlyPattern(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, false, false);
        $connection = DB::connection('quota');
        $hourExpression = $connection->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m-%d %H:00:00', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')";
        $query = $connection->table('quota_usage')
            ->where('created_at', '>=', CarbonImmutable::now('UTC')->subDay()->format('Y-m-d H:i:s'))
            ->where('created_at', '<=', CarbonImmutable::now('UTC')->format('Y-m-d H:i:s'))
            ->selectRaw($hourExpression.' AS hour_bucket')
            ->select('endpoint_slug')
            ->selectRaw('SUM(call_count) AS calls')
            ->selectRaw('COALESCE(SUM(total_tokens), 0) AS total_tokens')
            ->selectRaw('COUNT(DISTINCT employee) AS unique_employees');
        $this->applyFilters($query, $filters);

        return response()->json([
            'data' => $query->groupByRaw($hourExpression)->groupBy('endpoint_slug')
                ->orderBy('hour_bucket')
                ->orderBy('endpoint_slug')
                ->get(),
        ]);
    }

    public function recentCalls(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, false, false);
        $connection = DB::connection('quota');
        $query = $connection->table('quota_usage')
            ->select([
                'id', 'endpoint_slug', 'employee', 'input_tokens',
                'output_tokens', 'total_tokens', 'created_at',
            ]);

        if ($this->supportsWindowFunctions($connection)) {
            $query->selectRaw('LAG(created_at) OVER (PARTITION BY endpoint_slug ORDER BY created_at, id) AS previous_call_at');
        } else {
            $query->selectRaw('NULL AS previous_call_at');
        }

        $this->applyFilters($query, $filters);
        $rows = $query->orderByDesc('created_at')->orderByDesc('id')
            ->limit($filters['per_page'] ?? 50)
            ->get();

        $rows->transform(function ($row) {
            $row->seconds_since_previous_call = $row->previous_call_at === null
                ? null
                : CarbonImmutable::parse($row->previous_call_at, 'UTC')
                    ->diffInSeconds(CarbonImmutable::parse($row->created_at, 'UTC'));

            return $row;
        });

        return response()->json(['data' => $rows]);
    }

    private function validatedFilters(Request $request, bool $includeEmployee = true, bool $includeDates = true): array
    {
        $rules = [
            'endpoint_slug' => ['sometimes', 'string', 'max:255'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];

        if ($includeEmployee) {
            $rules['employee'] = ['sometimes', 'string', 'max:199'];
        }
        if ($includeDates) {
            $rules['from'] = ['sometimes', 'date_format:Y-m-d'];
            $rules['to'] = ['sometimes', 'date_format:Y-m-d', 'after_or_equal:from'];
        }

        return $request->validate($rules);
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['endpoint_slug'])) {
            $query->where('endpoint_slug', $filters['endpoint_slug']);
        }
        if (isset($filters['employee'])) {
            $query->where('employee', $filters['employee']);
        }
        if (isset($filters['from'])) {
            $query->where('usage_date', '>=', $filters['from']);
        }
        if (isset($filters['to'])) {
            $query->where('usage_date', '<=', $filters['to']);
        }
    }

    private function supportsWindowFunctions($connection): bool
    {
        try {
            $version = (string) $connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
            if ($connection->getDriverName() === 'sqlite') {
                return version_compare($version, '3.25.0', '>=');
            }
            if (stripos($version, 'MariaDB') !== false) {
                preg_match('/(?:^|-)((?:10)\.\d+\.\d+)/', $version, $matches);

                return isset($matches[1]) && version_compare($matches[1], '10.2.0', '>=');
            }

            preg_match('/^(\d+\.\d+\.\d+)/', $version, $matches);

            return isset($matches[1]) && version_compare($matches[1], '8.0.0', '>=');
        } catch (\Throwable $exception) {
            return false;
        }
    }
}