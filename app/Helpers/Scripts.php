<?php

namespace App\Helpers;

use App\Models\TreatmentPerformance;
use App\Models\QualPerformance;
use Illuminate\Support\Facades\DB;

class Scripts
{
    public static function dashboardSummary()
    {
        $statsql = "
        COALESCE(SUM(`total_patients`),0) `total_patients`,
        COALESCE(SUM(`tx_new`),0) tx_new,
        COALESCE(SUM(`pbs`),0) pbs,
        COALESCE(SUM(`active`),0) active,
        COALESCE(SUM(`transferred_out`),0) transferred_out,
        COALESCE(SUM(`Dead`),0) dead,
        COALESCE(SUM(`stopped`),0) stopped,
        COALESCE(SUM(`ltfu`),0) `ltfu`,
        `IP` ";

        $list =  DB::table('treament_report')
            ->select(DB::raw($statsql))
            //->where(['State'=>'Benue'])
            ->groupBy('IP')
            ->first();
        return $list;
    }

    public static function summaryList()
    {
        $statsql = "*";
        $list =  DB::table('treament_report')
            ->select(DB::raw($statsql))
            //->where(['State'=>'Benue'])
            ->get();
        return $list;
    }

    public static function dashboardGraphs()
    {
        $graphSql  = " `state` AS 'name', COUNT(`PepID`) AS  'y', `state` AS 'drilldown' ";
        $graphSql2 = " `lga` as 'lga',count(pepid) as  'patients'";
        $today_appointments_stats = DB::table('today_appointments')->select(DB::raw($graphSql))->groupBy('state')->get();
        $tomorrow_appointments_stats = DB::table('tomorrow_appointments')->select(DB::raw($graphSql))->groupBy('state')->get();

        return  [
            'today_lga_list' =>  $today_appointments_stats,
            'tomorrow_lga_list' =>  $tomorrow_appointments_stats,
            'today_appointments_graph_drilldown' => self::appPerformanceLgaGraph('today_appointments'),
            'tomorrow_appointments_graph_drilldown' => self::appPerformanceLgaGraph('tomorrow_appointments'),
        ];
    }

    public static function plotGraphByLGA($tableName, $lgaList, $graphSql): array
    {
        $graphSqlDrilldown = [];
        foreach ($lgaList  as $key1  => $data) {
            $dataD = [];
            $fac = [];
            $graphSqlDrilldown[$key1]['name'] = $data->name;
            $graphSqlDrilldown[$key1]['id'] = $data->name;
            $drilldownData = [];
            $drilldownDataArray = [];
            $drilldown =  DB::table($tableName)->select(DB::raw($graphSql))
                ->where(['state' => $data->name])
                ->groupBy('state')
                ->groupBy('lga')
                //->groupBy('facility')
                //->groupBy('fac_id')
                ->get();

            foreach ($drilldown as $key => $data) {
                $drilldownData[0] = $data->lga;
                $drilldownData[1] = $data->patients;
                $drilldownDataArray[] = $drilldownData;
            }
            $graphSqlDrilldown[$key1]["data"] = $drilldownDataArray;
        }
        return  $graphSqlDrilldown;
    }

    public static function dashbordScript($table): array
    {

        $StatsSql = "SELECT * FROM( SELECT
        COUNT(`PepID`) total_Appointments,
        SUM(IF(`phone_no` IS NOT NULL ,1,0)) total_Appointments_valid_no,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 1 ,1,0)) total_sent,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 0 ,1,0)) total_not_sent,
        SUM(IF(`phone_no` IS NULL ,1,0)) total_appointments_invalid_no
        FROM " . $table . " ) as appointments ";
        $stats = DB::select(DB::raw($StatsSql))[0];

        $listSql = "state as lga,
        COUNT(`PepID`) total_Appointments,
        SUM(IF(`phone_no` IS NOT NULL ,1,0)) total_Appointments_valid_no,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 1 ,1,0)) total_sent,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 0 ,1,0)) total_not_sent,
        SUM(IF(`phone_no` IS NULL ,1,0)) total_appointments_invalid_no";
        $list =  DB::table($table)
            ->select(DB::raw($listSql))
            ->groupBy('state')
            ->get();

        return array('stats' => $stats, 'list' => $list);
    }


    public static function treamentPerformance($data, $tx): array
    {
        $statsql = "
            FORMAT(COALESCE(COUNT(DISTINCT `state`),0),0) AS `states`,
            FORMAT(COALESCE(COUNT(DISTINCT `lga`),0),0) AS `lga`,
            FORMAT(COALESCE(COUNT(DISTINCT `datim_code`),0),0) AS `facilities`,
            COUNT(`pepid`) AS `total_patients`,
            COALESCE(SUM(`PBS` = 'Yes'),0) AS pbs,
            COALESCE(SUM(`CurrentARTStatus_Pharmacy` = 'Active'),0) AS `active`,
            COALESCE(SUM(`CurrentARTStatus_Pharmacy` LIKE '%Transferred%'),0) AS transferred_out,
            COALESCE(SUM(`CurrentARTStatus_Pharmacy` LIKE '%Dead%'),0) AS dead,
            COALESCE(SUM(`CurrentARTStatus_Pharmacy` LIKE '%Stopped%'),0) AS stopped,
            COALESCE(SUM(`CurrentARTStatus_Pharmacy` = 'LTFU' or `CurrentARTStatus_Pharmacy` LIKE '%lost to%'),0) AS `ltfu`,
            MAX(DATE(`Pharmacy_LastPickupdate`)) AS emr_date,
            `ip`
	    ";
        $list =  TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->groupBy('ip')
            ->withoutGlobalScopes()
            ->first();

        return [
            'treatment_perfomance' => (!empty($list)) ? (array) $list->getAttributes() : [],
            'tx_curr_graph' => self::tx_Curr($data,$tx),
            'tx_age_group_graph' => self::ageGroup($data, $tx),
            'tx_new_state_data' => self::treamentPerformanceStateGraph($data,$tx),
            'tx_new_lga_drill_data' => self::treamentPerformanceLgaGraph($data,$tx),
            'tx_new_age_sex' => self::tx_age_sex($data)
        ];
    }

    public static function tx_Curr($data,$tx)
    {
        $statsql = "
        sex AS `name`,
        COUNT('pepid') as `y`";
        $list =  TreatmentPerformance::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy','=','Active')
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->groupBy('sex')
            ->withoutGlobalScopes()
            ->get();
        return (!empty($list)) ?  $list : [];
    }

    public static function ageGroup($data, $tx)
    {
        $statsql = "
        (CASE
            WHEN age <= 9 THEN '≤9'
            WHEN age BETWEEN 10 AND 19 THEN '10 -19'
            WHEN age BETWEEN 20 AND 24 THEN '20 - 24'
            WHEN age >= 25 THEN '25 +'
        END) as name,
        COUNT('pepid') as `y`";
        $list =  TreatmentPerformance::select(DB::raw($statsql))
            ->where('CurrentARTStatus_Pharmacy','=','Active')
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->groupBy('name')
            ->orderby('name','DESC')
            ->withoutGlobalScopes()
            ->get();
        return (!empty($list)) ?  $list : [];
    }

    public static function treamentPerformanceStateGraph($data,$tx)
    {

        $statsql = "
        state AS `name`,
        CAST(COALESCE(SUM(`CurrentARTStatus_Pharmacy` = 'Active'),0)  AS UNSIGNED) AS `y`,
        state AS `drilldown`";
        $list =  TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->groupBy('name')
            ->orderBy('name', 'ASC')
            ->withoutGlobalScopes()
            ->get();

        return (!empty($list)) ?  $list : [];
    }


    public static function treamentPerformanceLgaGraph($data,$tx): array
    {

        $stateListBar = [];
        $stateList =  TreatmentPerformance::select(DB::raw("state AS `name`"))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->groupBy('name')->withoutGlobalScopes()->get();

        $out = [];
        $out2 = [];

        foreach ($stateList  as  $index1  => $states) {
            $stateListBar[$index1]['name'] = $states->name;
            $stateListBar[$index1]['id'] = $states->name;

            $lgaList =  TreatmentPerformance::select(DB::raw(
                " lga,lgaCode, CAST(COALESCE(SUM( `CurrentARTStatus_Pharmacy` = 'Active' ),0)  AS UNSIGNED) as  'patients'"
            ))->lga($data->lgas)->facilities($data->facilities)
                ->where(['state' => $states->name])
                ->groupBy('lga')
                ->groupBy('lgaCode')
                ->get();

            $lgaArray = [];
            $drillDownLga = [];
            $lgaListArray = [];

            foreach ($lgaList as $index2 => $lgas) {
                $lgaArray['name'] = $lgas->lga;
                $lgaArray['y'] = $lgas->patients;
                $lgaArray['drilldown'] = $lgas->lga;
                $drillDownLga[$index2] = $lgaArray;

                $lgaListArray['lgaCode'] = $lgas->lgaCode;
                $lgaListArray['lga'] = $lgas->lga;
                array_push($out, $lgaListArray);
            }
            $stateListBar[$index1]["data"] = $drillDownLga;
        }

        $facilityList =  TreatmentPerformance::select(DB::raw(
            "lga, lgaCode,facility_name , CAST(COALESCE(SUM(`CurrentARTStatus_Pharmacy` = 'Active'),0)  AS UNSIGNED) as  'patients'"
        ))->lga($data->lgas)->facilities($data->facilities)
            ->groupBy('lga')
            ->groupBy('lgaCode')
            ->groupBy('facility_name')
            ->withoutGlobalScopes()
            ->get();


        foreach ($out as $index => $lga) {
            $out2[$index]['name'] = $lga['lga'];
            $out2[$index]['id'] = $lga['lga'];
            $fac = [];
            $facElement = [];
            $i = 0;
            foreach ($facilityList as $key => $facility) {

                if ($facility->lgaCode == $lga['lgaCode']) {
                    $facElement[0] = $facility->facility_name;
                    $facElement[1] = $facility->patients;
                    $fac[$i] =  $facElement;
                    $i++;
                }
            }
            $out2[$index]['data'] = $fac;
        }

        foreach ($out2 as $index => $list) {
            array_push($stateListBar, $list);
        }
        return (!empty($stateListBar)) ?  $stateListBar   : [];
    }

    public static function regimenGraph($data): array
    {
        $facilityList2 = QualPerformance::select("regimen as regimen", \DB::raw("COUNT('regimen') as count"))
            ->where('artstatus','=','Active')
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->groupBy('regimen')
            ->orderBy('count', 'desc')
            ->withoutGlobalScopes()
            ->get();

        $regimen = [];
        $count = [];
        foreach ($facilityList2 as $index => $list) {
            $regimen[$index] =  $list->regimen;
            $count[$index] =  $list->count;
        }

        $result=[
            'quality_care' => (!empty($list)) ? (array) $list->getAttributes() : [],
            'regimen'=>$regimen,
            'count' => $count
        ];

        return (!empty($result)) ?  $result : [];
    }

    public static function pedregimenGraph($data): array
    {
        $facilityList2 = QualPerformance::select("regimen as regimen", DB::raw("COUNT('regimen') as count"))
            ->where('artstatus','=','Active')
            ->whereBetween('age', [0, 14])
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->groupBy('regimen')
            ->orderBy('count', 'desc')
            ->withoutGlobalScopes()
            ->get();

        $regimen = [];
        $count = [];
        foreach ($facilityList2 as $index => $list) {
            $regimen[$index] =  $list->regimen;
            $count[$index] =  $list->count;
        }

        $result=[
            'quality_care' => (!empty($list)) ? (array) $list->getAttributes() : [],
            'regimen'=>$regimen,
            'count' => $count
        ];

        return (!empty($result)) ?  $result : [];
    }

    public static function tx_age_sex($data): array
    {

        $statsql = "
        CASE
            WHEN age < 1 THEN '<1'
            WHEN age BETWEEN 1 AND 4 THEN '1-4'
            WHEN age BETWEEN 5 AND 9 THEN '5-9'
            WHEN age BETWEEN 10 AND 14 THEN '10-14'
            WHEN age BETWEEN 15 AND 19 THEN '15-19'
            WHEN age BETWEEN 20 AND 24 THEN '20-24'
            WHEN age BETWEEN 25 AND 29 THEN '25-29'
            WHEN age BETWEEN 30 AND 34 THEN '30-34'
            WHEN age BETWEEN 35 AND 39 THEN '35-39'
            WHEN age BETWEEN 40 AND 44 THEN '40-44'
            WHEN age BETWEEN 45 AND 49 THEN '45-49'
            WHEN age >= 50 THEN '50+'
        END AS age_range,
        CAST(SUM(CASE WHEN sex = 'M' THEN -1 ELSE 0 END) as signed) AS male_count,
        CAST(SUM(CASE WHEN sex = 'F' THEN 1 ELSE 0 END) as unsigned) AS female_count
        ";

        $txAgeSex= TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->where('CurrentARTStatus_Pharmacy','=','Active')
            ->groupBy('age_range')
            ->orderByRaw("FIELD(age_range, '<1','1-4','5-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50+')")
            ->withoutGlobalScopes()
            ->get();

        $male_count = [];
        $female_count = [];
        foreach ($txAgeSex as $index => $list) {
            $male_count[$index] =  $list->male_count;
            $female_count[$index] =  $list->female_count;
        }

        $result=[
            'treatment_perfomance' => (!empty($list)) ? (array) $list->getAttributes() : [],
            'male_data'=>$male_count,
            'female_data' => $female_count
        ];

        return (!empty($result)) ?  $result : [];
    }

    public static function appPerformanceLgaGraph($tableName): array
    {

        $stateListBar = [];
        $stateList =  DB::table($tableName)->select(DB::raw("state AS `name`"))
            ->groupBy('name')->get();

        $out = [];
        $out2 = [];

        foreach ($stateList  as  $index1  => $states) {
            $stateListBar[$index1]['name'] = $states->name;
            $stateListBar[$index1]['id'] = $states->name;

            $lgaList =   DB::table($tableName)->select(DB::raw(
                " lga,lgaCode, CAST(count(pepid) AS UNSIGNED) as  'patients'"
            ))
                ->where(['state' => $states->name])
                ->groupBy('lga')
                ->groupBy('lgaCode')
                ->get();

            $lgaArray = [];
            $drillDownLga = [];
            $lgaListArray = [];

            foreach ($lgaList as $index2 => $lgas) {
                $lgaArray['name'] = $lgas->lga;
                $lgaArray['y'] = $lgas->patients;
                $lgaArray['drilldown'] = $lgas->lga;
                $drillDownLga[$index2] = $lgaArray;

                $lgaListArray['lgaCode'] = $lgas->lgaCode;
                $lgaListArray['lga'] = $lgas->lga;
                array_push($out, $lgaListArray);
            }
            $stateListBar[$index1]["data"] = $drillDownLga;
        }

        $facilityList =  DB::table($tableName)->select(DB::raw(
            "lga, lgaCode,facility_name , CAST(count(pepid) AS UNSIGNED) as  'patients'"
        ))
            ->groupBy('lga')
            ->groupBy('lgaCode')
            ->groupBy('facility_name')
            ->get();


        foreach ($out as $index => $lga) {
            $out2[$index]['name'] = $lga['lga'];
            $out2[$index]['id'] = $lga['lga'];
            $fac = [];
            $facElement = [];
            $i = 0;
            foreach ($facilityList as $key => $facility) {

                if ($facility->lgaCode == $lga['lgaCode']) {
                    $facElement[0] = $facility->facility_name;
                    $facElement[1] = $facility->patients;
                    $fac[$i] =  $facElement;
                    $i++;
                }
            }
            $out2[$index]['data'] = $fac;
        }

        foreach ($out2 as $index => $list) {
            array_push($stateListBar, $list);
        }
        return (!empty($stateListBar)) ?  $stateListBar   : [];
    }


}
