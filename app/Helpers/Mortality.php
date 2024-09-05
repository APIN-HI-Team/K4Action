<?php

namespace App\Helpers;

use App\Models\TreatmentPerformance;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class Mortality
{
    #[ArrayShape(['mortality_stats' => "array", 'new_state_data' => "mixed", 'regimenLineQs' => "array", 'sex_total' => "array", 'vl' => "array", 'artd' => "array", 'ageband' => "array", 'deadAgeSex' => "array"])] public static function mortality_stats($data, $start_date, $end_date): array
    {
        $statsql = "FORMAT(COALESCE(COUNT(DISTINCT CASE WHEN `Outcomes_Date` BETWEEN '$start_date' AND '$end_date'  AND (`Outcomes` = 'Dead' OR `Outcomes` = 'Death') THEN `state` END),0),0) AS `states`,
        FORMAT(COALESCE(COUNT(DISTINCT CASE WHEN `Outcomes_Date` BETWEEN '$start_date' AND '$end_date'  AND (`Outcomes` = 'Dead' OR `Outcomes` = 'Death') THEN `lga` END),0),0) AS `lga`,
        FORMAT(COALESCE(COUNT(DISTINCT CASE WHEN `Outcomes_Date` BETWEEN '$start_date' AND '$end_date'  AND (`Outcomes` = 'Dead' OR `Outcomes` = 'Death') THEN `datim_code` END),0),0) AS `facility`,
        COALESCE(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `Outcomes_Date` BETWEEN '$start_date' AND '$end_date'),0) AS `Dead`,
        `ip`";
        $list = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->groupBy('ip')
            ->withoutGlobalScopes()
            ->first();

        return [
            'mortality_stats' => (!empty($list)) ? (array) $list->getAttributes() : [],
            'new_state_data' => self::MortalityGraph($data, $start_date, $end_date),
            'regimenLineQs' => self::regimenLineByMortality($data, $start_date, $end_date),
            'sex_total' => self::sexByMortality($data, $start_date, $end_date),
            'vl'  => self::vlByMortality($data, $start_date, $end_date),
            'artd'  => self::ArtDuration($data, $start_date, $end_date),
            'ageband' => self::ageBand($data, $start_date, $end_date),
            'deadAgeSex' => self::deadAgeSex($data,$start_date, $end_date)
        ];
    }

    public static function MortalityGraph($data, $start_date, $end_date)
    {
        $statsql = "
            year(Outcomes_Date) as `year`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) >= 1 AND MONTH(Outcomes_Date) <= 3) as unsigned) AS `q1`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) >= 4 AND MONTH(Outcomes_Date) <= 6) as unsigned) AS `q2`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) >= 7 AND MONTH(Outcomes_Date) <= 9) as unsigned) AS `q3`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) >= 10 AND MONTH(Outcomes_Date) <= 12) as unsigned) AS `q4`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 1) as unsigned) AS `january`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 2) as unsigned) AS `february`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 3) as unsigned) AS `march`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 4) as unsigned) AS `april`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 5) as unsigned) AS `may`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 6) as unsigned) AS `june`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 7) as unsigned) AS `july`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 8) as unsigned) AS `august`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 9) as unsigned) AS `september`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 10) as unsigned) AS `october`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 11) as unsigned) AS `november`,
            CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and MONTH(Outcomes_Date) = 12) as unsigned) AS `december`";

        return TreatmentPerformance::select(DB::raw($statsql))
            ->whereIn('Outcomes', ['Dead', 'Death'])
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
            ->state($data->states)
            ->lga($data->lgas)
            ->facilities($data->facilities)
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->withoutGlobalScopes()
            ->get();
    }

    public static function regimenLineByMortality($data, $start_date, $end_date): array
    {

        $statsql = "
        CASE
            WHEN CurrentRegimenLine IN ('Adult.1st.Line','Adult 1st line ARV regimen','Adult- 1st line ARV regimen') THEN 'Adult 1st line ARV regimen'
            when CurrentRegimenLine IN ('Adult.2nd.Line','Adult 2nd line ARV regimen') THEN 'Adult 2nd line ARV regimen'
            when CurrentRegimenLine IN ('Adult.3rd.Line','Adult 3rd Line ARV Regimens') THEN 'Adult 3rd line ARV regimen'
            when CurrentRegimenLine IN ('Peds.1st.Line','Child 1st line ARV regimen') THEN 'Child 1st line ARV regimen'
            when CurrentRegimenLine IN ('Peds.2nd.Line','Child 2nd line ARV regimen') THEN 'Child 2nd line ARV regimen'
            when CurrentRegimenLine IN ('Peds.3rd.Line','Child 3rd Line ARV Regimens') THEN 'Child 3rd line ARV regimen'
            when CurrentRegimenLine is NULL THEN 'No Regimen'
        END as name,

        CAST(COUNT(pepid) AS UNSIGNED) AS y
        ";

        $list = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->whereIn('Outcomes', ['Dead', 'Death'])
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
            ->groupBy('name')
            ->orderBy('name','ASC')
            ->withoutGlobalScopes()
            ->get();

        return (!empty($list)) ? $list->toArray() : [];
    }

    public static function sexByMortality($data, $start_date, $end_date): array
    {

        $statsql = "
        CAST(SUM(CASE WHEN sex = 'M' THEN 1 ELSE 0 END) as signed) AS males,
        CAST(SUM(CASE WHEN sex = 'F' THEN 1 ELSE 0 END) as unsigned) AS females
        ";

        $list = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->whereIn('Outcomes', ['Dead', 'Death'])
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
           /* ->groupBy('name')
            ->orderBy('name','ASC')*/
            ->withoutGlobalScopes()
            ->get();

        return (!empty($list)) ? $list->toArray() : [];
    }

    public static function vlByMortality($data, $start_date, $end_date): array
    {

        $statsql = "
        CAST(SUM(CASE WHEN CurrentViralLoad < 1000  THEN 1 ELSE 0 END) as unsigned) AS suppressed,
        CAST(SUM(CASE WHEN CurrentViralLoad >= 1000  THEN 1 ELSE 0 END) as unsigned) AS unsuppressed,
        CAST(SUM(CASE WHEN CurrentViralLoad is null THEN 1 ELSE 0 END) as unsigned) AS no_vl
        ";

        $list = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->whereIn('Outcomes', ['Dead', 'Death'])
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
            /* ->groupBy('name')
             ->orderBy('name','ASC')*/
            ->withoutGlobalScopes()
            ->get();

        return (!empty($list)) ? $list->toArray() : [];
    }

    public static function ArtDuration($data, $start_date, $end_date): array
    {
        $statsql = "
        state as states,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and DATEDIFF(Outcomes_Date, ARTStartDate) < 365) as unsigned) AS `less_1`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and DATEDIFF(Outcomes_Date, ARTStartDate) > 365 and DATEDIFF(Outcomes_Date, ARTStartDate) <= 1095) as unsigned) AS `b1_3`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and DATEDIFF(Outcomes_Date, ARTStartDate) > 1095 and DATEDIFF(Outcomes_Date, ARTStartDate) <= 1825) as unsigned) AS `b3_5`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and DATEDIFF(Outcomes_Date, ARTStartDate) > 1825) as unsigned) AS `gt_5`
        ";

        $list = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
            ->groupBy('state')
             ->orderBy('state','ASC')
            ->withoutGlobalScopes()
            ->get();
        return (!empty($list)) ? $list->toArray() : [];
    }

    public static function ageBand($data, $start_date, $end_date): array
    {
        $statsql = "
        state as states,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') and age < 1) as unsigned) AS `less_1`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 1 AND 4) AS UNSIGNED) AS `age_1_to_4`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 4 AND 9) AS UNSIGNED) AS `age_4_to_9`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 10 AND 14) AS UNSIGNED) AS `age_10_to_14`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 15 AND 19) AS UNSIGNED) AS `age_15_to_19`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 20 AND 24) AS UNSIGNED) AS `age_20_to_24`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 25 AND 29) AS UNSIGNED) AS `age_25_to_29`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 30 AND 34) AS UNSIGNED) AS `age_30_to_34`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 35 AND 39) AS UNSIGNED) AS `age_35_to_39`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 40 AND 44) AS UNSIGNED) AS `age_40_to_44`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND `age` BETWEEN 45 AND 49) AS UNSIGNED) AS `age_45_to_49`,
        CAST(SUM((`Outcomes` = 'Dead' OR `Outcomes` = 'Death') AND age > 49) AS UNSIGNED) AS `age_50`


        ";

        $list = TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
            ->groupBy('state')
            ->orderBy('state','ASC')
            ->withoutGlobalScopes()
            ->get();
        return (!empty($list)) ? $list->toArray() : [];
    }


    public static function deadAgeSex($data,$start_date, $end_date): array
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
            WHEN age BETWEEN 50 AND 54 THEN '50-54'
            WHEN age BETWEEN 55 AND 59 THEN '55-59'
            WHEN age BETWEEN 60 AND 64 THEN '60-64'
            WHEN age BETWEEN 65 AND 69 THEN '65-69'
            WHEN age >= 70 THEN '70+'
        END AS age_range,
        CAST(SUM(CASE WHEN sex = 'M' THEN -1 ELSE 0 END) as signed) AS male_count,
        CAST(SUM(CASE WHEN sex = 'F' THEN 1 ELSE 0 END) as unsigned) AS female_count
        ";

        $deadAgeSex= TreatmentPerformance::select(DB::raw($statsql))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->whereIn('Outcomes', ['Dead', 'Death'])
            ->whereBetween('Outcomes_Date', [$start_date,$end_date])
            ->groupBy('age_range')
            ->orderByRaw("FIELD(age_range, '<1','1-4','5-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50-54','55-59','60-64','65-69','70+')")
            ->withoutGlobalScopes()
            ->get();

        $male_count = [];
        $female_count = [];
        foreach ($deadAgeSex as $index => $list) {
            $male_count[$index] =  $list->male_count;
            $female_count[$index] =  $list->female_count;
        }

        $result=[
            'male_data'=>$male_count,
            'female_data' => $female_count
        ];

        return (!empty($result)) ?  $result : [];
    }


}
