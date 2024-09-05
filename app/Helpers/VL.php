<?php

namespace App\Helpers;

use App\Models\VLDashboard;
use App\Models\VLPerformance;
use Illuminate\Support\Facades\DB;

class VL
{
    public static function vLGraph($data): array
    {
        $facilityList = VLDashboard::select(DB::raw(
            "
            CAST(COALESCE(SUM(`active`),0)  AS UNSIGNED) AS `active`,
            CAST(COALESCE(SUM(`eligible`),0)  AS UNSIGNED) AS `eligible`,
            CAST(COALESCE(SUM(`suppressed`),0)  AS UNSIGNED) AS `supressedVl`,
            CAST(COALESCE(SUM(`no_vl_result`),0)  AS UNSIGNED) AS `eligibleWithVl`,
            state"
        ))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->groupBy('state')
            ->orderBy('state', 'ASC')
            ->withoutGlobalScopes()
            ->get();

        $facilityList2 = VLDashboard::select(DB::raw(
            "
            CAST(COALESCE(SUM(`active`),0)  AS UNSIGNED) AS `active`,
            CAST(COALESCE(SUM(`eligible`),0)  AS UNSIGNED) AS `eligible`,
            CAST(COALESCE(SUM(`suppressed`),0)  AS UNSIGNED) AS `supressedVl`,
            CAST(COALESCE(SUM(`no_vl_result`),0)  AS UNSIGNED) AS `eligibleWithVl`,
            ip"
        ))
            ->state($data->states)->lga($data->lgas)->facilities($data->facilities)
            ->groupBy('ip')
            ->withoutGlobalScopes()
            ->first();



        $state = [];
        $lga = [];
        $facility =[];
        $txCurr = [];
        $eligible = [];
        $eligibleWithVl = [];
        $viralLoadSuppressed = [];
        $txVlCoverage = [];
        $percentageViralLoadSuppressed = [];
        foreach ($facilityList as $index => $list) {
            $state[$index] =  $list->state;
            $txCurr[$index] =  $list->active;
            $eligible[$index] =  $list->eligible;
            $eligibleWithVl[$index] = $list->eligibleWithVl;
            $viralLoadSuppressed[$index] =  $list->supressedVl;
            $txVlCoverage[$index] = round((($list->eligible/$list->eligibleWithVl)*100),2);
            $percentageViralLoadSuppressed[$index] =  round((($list->supressedVl/$list->eligibleWithVl)*100),2);
        }

        $result=[
            'treatment_perfomance' => (!empty($facilityList2)) ? (array) $facilityList2->getAttributes() : [],
            'states'=>$state,
            'lgas'=>$lga,
            'facilities'=>$facility,
            'eligible' => $eligible,
            'eligibleWithVl' => $eligibleWithVl,
            'tx_curr'=>$txCurr,
            'viralLoadSuppressed'=>$viralLoadSuppressed,
            'tx_Vl_Coverage'=>$txVlCoverage,
            'percentage_viral_load_suppressed'=>$percentageViralLoadSuppressed
        ];

        return (!empty($result)) ?  $result : [];
    }

}
