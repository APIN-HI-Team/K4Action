<?php

namespace App\Helpers;

use App\Models\TreatmentPerformance;
use Illuminate\Support\Facades\DB;

class Ahd
{
    public static function getAgeFilter($age_group): string
    {
        return match ($age_group) {
            'LessThan1' => "age < 1",
            '1To4' => "age BETWEEN 1 AND 4",
            '5To9' => "age BETWEEN 5 AND 9",
            '10To14' => "age BETWEEN 10 AND 14",
            '15To19' => "age BETWEEN 15 AND 19",
            '20To24' => "age BETWEEN 20 AND 24",
            '25To29' => "age BETWEEN 25 AND 29",
            '30To34' => "age BETWEEN 30 AND 34",
            '35To39' => "age BETWEEN 35 AND 39",
            '40To44' => "age BETWEEN 40 AND 44",
            '45To49' => "age BETWEEN 45 AND 49",
            '50' => "age BETWEEN >=50",
            default => "",
        };
    }

    public static function getSexFilter($sex): string
    {
        return match ($sex) {
            'M' => "sex = 'M'",
            'F' => "sex = 'F'",
            default => "",
        };
    }

    public static function ahd_Performance($data,$start_date,$end_date,$age_group,$sex): array
    {

        $statsql = "
            FORMAT(COALESCE(COUNT(DISTINCT CASE WHEN `ARTStartDate` BETWEEN '$start_date' AND '$end_date' THEN `state` END),0),0) AS `states`,
            FORMAT(COALESCE(COUNT(DISTINCT CASE WHEN `ARTStartDate` BETWEEN '$start_date' AND '$end_date' THEN `lga` END),0),0) AS `lga`,
            FORMAT(COALESCE(COUNT(DISTINCT CASE WHEN `ARTStartDate` BETWEEN '$start_date' AND '$end_date' THEN `datim_code` END),0),0) AS `facility`,
            COALESCE(SUM(`ARTStartDate` BETWEEN '$start_date' AND '$end_date'),0) AS `newdiagnose`,
            `ip`
	    ";

        $age_filter = self::getAgeFilter($age_group);
        $sex_filter = self::getSexFilter($sex);

        $query = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->withoutGlobalScopes();

        if ($age_filter) {
            $query->whereRaw($age_filter);
        }

        if ($sex_filter) {
            $query->whereRaw($sex_filter);
        }

        $list = $query->groupBy('ip')->first();

        return [
            'card' => (!empty($list)) ? (array) $list->getAttributes() : [],
            'ahd' => self::ahd($data,$start_date, $end_date,$age_group,$sex),
            'cm' => self::cm($data,$start_date, $end_date,$age_group,$sex),
            'tb' => self::tb($data,$start_date, $end_date,$age_group,$sex),
        ];
    }

    public static function ahd($data, $start_date, $end_date,$age_group,$sex)
    {
        $statsql = "
        COUNT(pepid) AS hivPosClient,
        CAST(COALESCE(SUM(Whostage IN (1,2,3,4)),0) AS UNSIGNED) AS whoClinicalStagingCount,
        CAST(COALESCE(SUM(Whostage IN (3, 4)),0) AS UNSIGNED) AS whoClinicalStaging3n4Count,
        CAST(COALESCE(SUM(CD4_LFA_Result IN ('LessThan200','GreaterTE200') OR FirstCD4 >=0),0) AS UNSIGNED) AS cd4Result,
        CAST(COALESCE(SUM(CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200),0) AS UNSIGNED) AS cD4LessCount,
        CAST(COALESCE(SUM(Whostage IN (1,2,3,4)) / COUNT(pepid) * 100, 0) AS UNSIGNED) AS percentageWhoClinicalStaging,
        CAST(COALESCE(ROUND(SUM(Whostage IN (3, 4)) / SUM(Whostage IN (1,2,3,4)) * 100, 1),0) AS UNSIGNED) AS percentageWhoClinicalStaging3n4,
        CAST(COALESCE(ROUND(SUM(CD4_LFA_Result IN ('LessThan200','GreaterTE200') OR FirstCD4 >= 0) /  COUNT(pepid) * 100, 1),0) AS UNSIGNED) AS percentageCD4Results,
        CAST(COALESCE(ROUND(SUM(CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200) / SUM(CD4_LFA_Result IN ('LessThan200','GreaterTE200') OR FirstCD4 >= 0) * 100, 1),0) AS UNSIGNED) AS percentageCD4Less
        ";

        $query = self::constructQuery($data, $start_date, $end_date, $statsql,$age_group,$sex);

        $list = $query->first();

        return (!empty($list)) ? $list->toArray() : [];
    }

    public static function cm($data, $start_date, $end_date,$age_group,$sex)
    {
        $statsql = "
        CAST(COALESCE(SUM(CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)),0) AS UNSIGNED) AS presumptiveAHDClients,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg in ('Positive', 'Negative')),0) AS UNSIGNED) AS serumCragCount,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive'),0) AS UNSIGNED) AS serumCragPosCount,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive' and CSF_CrAg in ('Positive', 'Negative')),0) AS UNSIGNED) AS csfCragCount,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive' and CSF_CrAg = 'Positive'),0) AS UNSIGNED) AS csfCragPosCount,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg in ('Positive', 'Negative')) / SUM(CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) * 100, 0) AS UNSIGNED) AS percentageSerumCrag,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive') / sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg in ('Positive', 'Negative')) * 100, 0) AS UNSIGNED) AS percentageSerumCragPos,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive' and CSF_CrAg in ('Positive', 'Negative')) / sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive') * 100, 0) AS UNSIGNED) AS percentageCsfCrag,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive' and CSF_CrAg in ('Positive')) / sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and Serology_CrAg = 'Positive' and CSF_CrAg in ('Positive', 'Negative')) * 100, 0) AS UNSIGNED) AS percentageCsfCragPos
        ";

        $query = self::constructQuery($data, $start_date, $end_date, $statsql,$age_group,$sex);

        $list = $query->first();
        return (!empty($list)) ? $list->toArray() : [];
    }

    public static function tb($data, $start_date, $end_date,$age_group,$sex)
    {
        $statsql = "
        CAST(COALESCE(SUM(CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)),0) AS UNSIGNED) AS presumptiveAHDClients,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM in ('Positive', 'Negative')),0) AS UNSIGNED) AS tB_LAMCount,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM = 'Positive'),0) AS UNSIGNED) AS tB_LAMPosCount,
        cast(coalesce(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM = 'Positive' and TBStatus='On treatment for disease'),0) AS UNSIGNED) AS tB_LAMTreatmentCount,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM in ('Positive', 'Negative')) / SUM(CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) * 100, 0) AS UNSIGNED) AS percentageTB_LamTest,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM = 'Positive') / sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM in ('Positive', 'Negative')) * 100, 0) AS UNSIGNED) AS percentageAHD_TBCoInfection,
        CAST(COALESCE(sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM = 'Positive' and TBStatus='On treatment for disease') / sum((CD4_LFA_Result = 'LessThan200' OR FirstCD4 <200 or age<5 or Whostage IN (3, 4)) and TB_LAM = 'Positive')  * 100, 0) AS UNSIGNED) AS percentageAHDClientTB
        ";

        // Execute the query with filters
        $query = self::constructQuery($data, $start_date, $end_date, $statsql,$age_group,$sex);

        $list = $query->first();
        return (!empty($list)) ? $list->toArray() : [];
    }


    private static function constructQuery($data, $start_date, $end_date, $statsql,$age_group,$sex)
    {
        // Apply filters based on age group and sex
        $age_filter = self::getAgeFilter($age_group);
        $sex_filter = self::getSexFilter($sex);

        // Construct the query object with common conditions
        $query = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->whereBetween('ARTStartDate', [$start_date, $end_date]);

        // Add additional conditions if applicable
        if ($age_filter) {
            $query->whereRaw($age_filter);
        }

        if ($sex_filter) {
            $query->whereRaw($sex_filter);
        }

        return $query->withoutGlobalScopes();
    }
    
}
