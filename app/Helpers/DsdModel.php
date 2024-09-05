<?php

namespace App\Helpers;

use App\Models\DSDAnalysis;
use Illuminate\Support\Facades\DB;

class DsdModel
{
    public static function dsd_analysis($data): array
    {
        return [
            'dsd_analysis' => [
                'DSD_by_Cascade' => self::dsd_by_Cascade($data)
            ]
        ];
    }

    public static function dsd_by_Cascade($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->whereNotNull('DSD_Model_Type')
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return [
            [
                'data' => $list,
                'name' => '# of clients on DSD'
            ],
            [
                'data' => self::artDuration($data),
                'name' => 'ART Start Duration(>=1year)'
            ],
            [
                'data' => self::artDurationless($data),
                'name' => 'ART Start Duration(<1year)'
            ],
            [
                'data' => self::whostaging1($data),
                'name' => 'WHO Clinical Staging 1'
            ],
            [
                'data' => self::whostaging2($data),
                'name' => 'WHO Clinical Staging 2'
            ],
            [
                'data' => self::whostaging3($data),
                'name' => 'WHO Clinical Staging 3'
            ],
            [
                'data' => self::whostaging4($data),
                'name' => 'WHO Clinical Staging 4'
            ]
        ];
    }

    public static function artDuration($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->where('DaysOnART', '>=', 365)
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return $list;
    }

    public static function artDurationless($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->where('DaysOnART', '<', 365)
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return $list;
    }

    public static function whostaging1($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->where('Whostage', '=', 1)
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return $list;
    }

    public static function whostaging2($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->where('Whostage', '=', 2)
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return $list;
    }

    public static function whostaging3($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->where('Whostage', '=', 3)
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return $list;
    }

    public static function whostaging4($data)
    {
        $statsql = "
        'APIN' AS `name`,
        Count(pepid) AS `y`";

        $list = DSDAnalysis::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy', '=', 'Active')
            ->where('Whostage', '=', 4)
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes()
            ->get();

        return $list;
    }



}
