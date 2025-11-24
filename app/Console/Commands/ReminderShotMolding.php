<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class ReminderShotMolding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'molding:shot_reminder {category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $email_list = [
        ['vendor' => 'Arisa', 'email' => 'arisa@music.yamaha.com'],
        ['vendor' => 'Preshion', 'email' => 'preshion@music.yamaha.com'],
        ['vendor' => 'Purchasing', 'email' => 'purchasing@music.yamaha.com'],
        ['vendor' => 'Injection RC', 'email' => 'injection.rc@music.yamaha.com'],
        ['vendor' => 'Pianica', 'email' => 'pianica@music.yamaha.com'],
    ];
    public function handle()
    {
        // ------------------------ REMINDER SHOT ------------------------
        if($this->argument('category') == "shot") {
            $date = new \DateTime();
            $week_number = $date->format("oW");
    
            $data = DB::SELECT("SELECT master_data.*, $week_number as week_number, shots.total_shot AS qty_shot
                FROM (
                    SELECT fixed_asset_number, fixed_asset_name, vendor, total_shot
                    FROM molding_diagnose_masters
                    WHERE deleted_at IS NULL
                ) AS master_data
                LEFT JOIN (
                    SELECT s1.fixed_asset_number, s1.week_number, s1.total_shot
                    FROM molding_diagnose_shots s1
                    JOIN (
                        SELECT fixed_asset_number, week_number, MAX(id) AS last_id
                        FROM molding_diagnose_shots
                        WHERE week_number = '$week_number'
                        and deleted_at IS NULL
                        GROUP BY fixed_asset_number, week_number
                    ) AS s2 
                    ON s1.id = s2.last_id
                ) AS shots 
                ON master_data.fixed_asset_number = shots.fixed_asset_number
                order by qty_shot ASC");
    
            // check if there is has empty data on column qty_shot
            $vendor_empty_data = [];
            foreach ($data as $key => $value) {
                if (empty($value->qty_shot)) {
                    if(!in_array($value->vendor, $vendor_empty_data)) {
                        $vendor_empty_data[] = $value->vendor;
                    }
                }
            }
    
            foreach ($vendor_empty_data as $key => $value) {
                //filter data by vendor php
                $filtered_data = array_filter($data, function($item) use ($value) {
                    return $item->vendor == $value;
                });
    
                //reindex array
                $filtered_data = array_values($filtered_data);
    
                Mail::to($this->email_list[$key]['email'])
                // ->cc(['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com'])
                ->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($filtered_data, 'reminder_shot_molding'));
            }
            
            return Command::SUCCESS;
        } else if($this->argument('category') == "form") {
            // ------------------------ REMINDER FORM ------------------------
            $master_data = DB::SELECT("SELECT mstr.*, molding_diagnose_forms.form_number, molding_diagnose_forms.`status` as status_form  FROM (SELECT fixed_asset_number, fixed_asset_name, vendor, total_shot, standard_shot, `status` FROM molding_diagnose_masters where deleted_at is null and total_shot > standard_shot) as mstr LEFT JOIN molding_diagnose_forms on mstr.fixed_asset_number = molding_diagnose_forms.fixed_asset_number");

            //get fixed_number only
            $fa_number_list = array_map(function($item) {
                return $item->fixed_asset_number;
            }, $master_data);

            $fa_number_list = implode("', '", $fa_number_list);

            $shot_history = DB::SELECT("SELECT 
                s.fixed_asset_number,
                m.fixed_asset_name,
                m.standard_shot,
                s.week_number,
                s.total_shot,
                s.accumulative_shot,
                s.created_at AS first_exceed_date
            FROM molding_diagnose_shots AS s
            JOIN (
                SELECT 
                    s2.fixed_asset_number,
                    MIN(s2.created_at) AS first_exceed_date
                FROM molding_diagnose_shots AS s2
                JOIN molding_diagnose_masters AS m2 
                    ON s2.fixed_asset_number = m2.fixed_asset_number
                WHERE 
                    s2.accumulative_shot >= m2.standard_shot
                    AND s2.fixed_asset_number IN (
                        '$fa_number_list'
                    )
                GROUP BY s2.fixed_asset_number
            ) AS first_exceed
            ON s.fixed_asset_number = first_exceed.fixed_asset_number
            AND s.created_at = first_exceed.first_exceed_date
            LEFT JOIN molding_diagnose_masters AS m 
            ON s.fixed_asset_number = m.fixed_asset_number
            ORDER BY s.created_at");

            foreach ($this->email_list as $key => $value) {
                //if $value available on column vendor of $master_data
                $filtered_data = array_filter($master_data, function($item) use ($value) {
                    return $item->vendor == $value['vendor'];
                });

                if (empty($filtered_data)) {
                    continue;
                }

                //reindex array
                $filtered_data = array_values($filtered_data);
                //join $filtered_data with $shot_history get column accumulative_shot and first_exceed_date only from $shot_history
                $filtered_data = array_map(function($item) use ($shot_history) {
                    // Find the matching shot history for this item
                    $matching_shot = array_filter($shot_history, function($shot) use ($item) {
                        return $shot->fixed_asset_number === $item->fixed_asset_number;
                    });
                    
                    // If we found a match, use the first one
                    if (!empty($matching_shot)) {
                        $shot = reset($matching_shot);
                        $item->accumulative_shot = $shot->accumulative_shot;
                        $item->first_exceed_date = $shot->first_exceed_date;
                    }
                    
                    return $item;
                }, $filtered_data);

                Mail::to($value['email'])->cc(['yoga.karunia.perdana@music.yamaha.com', 'arief.asmo.saputro@music.yamaha.com'])->bcc(['nasiqul.ibat@music.yamaha.com'])->send(new SendEmail($filtered_data, 'reminder_form_molding'));
            }
        }
    }

}
