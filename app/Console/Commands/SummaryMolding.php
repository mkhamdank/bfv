<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class SummaryMolding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:molding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stat = ['OK', 'Butuh Pemeriksaan', 'Sedang Diperiksa', 'Sudah Diperiksa'];
        $master_molding = DB::table('molding_diagnose_masters')
        ->select('vendor', 'status', db::raw('COUNT(id) as total_molding'))
        ->whereNull('deleted_at')
        ->groupBy('vendor','status')
        ->get();

        // get vendor unique from $master_molding
        $vendor = $master_molding->unique('vendor');
        
        foreach ($vendor as $v) {
            //get all molding in master_molding by vendor
            $molding = $master_molding->where('vendor', $v->vendor);
            $total_molding = $molding->sum('total_molding');

            $data = [
                'daftar_cek' => $molding,
                'vendor' => $v->vendor,
                'total_molding' => $total_molding,
                'month' => date('F Y')
            ];

            //send email
            Mail::to('vendor@mail.com')->cc(['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com'])->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($data, 'summary_molding'));
            
        }
    }
}
