<?php

namespace App\Http\Controllers;

use App\Models\TreatmentCurrent;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(): Renderable
    {
        $students = TreatmentCurrent::select("sex as sex", "COUNT as count")
            ->groupBy('sex')
            ->get();

        $studentsNew = DB::table('txnew_table')
            ->select(DB::raw("sex as sex, count as count"))
            ->groupBy('sex')
            ->get();

        $txcurrAge = DB::table('txcurrage_table')
            ->select(DB::raw("age_range as age_range, count as count"))
            ->groupBy('age_range')
            ->get();

        $txnewAge = DB::table('txnewage_table')
            ->select(DB::raw("age_range as age_range, count as count"))
            ->groupBy('age_range')
            ->get();

        $performance = DB::table('treatment_report')
            ->select(DB::raw("*"))
            ->orderBy('state', 'asc')
            ->orderBy('lga', 'asc')
            ->orderBy('facility_name', 'asc')
            ->get();


        $tx_curr = DB::table('curr_table')
            ->select(DB::raw('
                name,x,y,z
            '))
            ->groupBy('name')
            ->get();


        return view('dashboard', compact('students','txcurrAge','studentsNew','txnewAge','performance','tx_curr'));
        //dd($tx_curr);
        //dd(json_encode($tx_curr));
    }

    public function showPerformance(Request $request): JsonResponse
    {
        $performance = DB::table('treatment_report')
            ->select(DB::raw("*"))
            ->orderBy('state', 'asc')
            ->orderBy('lga', 'asc')
            ->orderBy('facility_name', 'asc')
            ->get();
        return response()->json(['performance'=>$performance]);
    }
}
