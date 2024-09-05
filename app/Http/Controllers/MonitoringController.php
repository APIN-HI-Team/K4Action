<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Helpers\Scripts as Helper;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class MonitoringController extends Controller
{
    public function treatmentDashboard(): Factory|View|Application
    {
        return view('monitoring.treatment_dashboard');
    }

    public function appointmentDashboard(): Factory|View|Application
    {
        $todaysAppt = Helper::dashbordScript("today_appointments");
        $tomorrowsAppt = Helper::dashbordScript("tomorrow_appointments");

        $data = [
            'dashboardGraphs' => Helper::dashboardGraphs(),
            'today_appointments' => Helper::dashbordScript("today_appointments"),
            'tomorrow_appointments' => Helper::dashbordScript("tomorrow_appointments")
        ];
        return view('monitoring.appointment_dashboard',compact('data','todaysAppt','tomorrowsAppt'));
    }

   /* public function dashboardScript($table): array
    {

        $StatsSql = "SELECT * FROM( SELECT
        COUNT(`PepID`) total_Appointments,
        SUM(IF(`phone_no` IS NOT NULL ,1,0)) total_Appointments_valid_no,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 1 ,1,0)) total_sent,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 0 ,1,0)) total_not_sent,
        SUM(IF(`phone_no` IS NULL ,1,0)) total_appointments_invalid_no
        FROM ".$table." ) as treatment_report ";
        $stats = DB::select(DB::raw($StatsSql))[0];

        $listSql = "lga,
        facility_name,
        COUNT(`PepID`) total_Appointments,
        SUM(IF(`phone_no` IS NOT NULL ,1,0)) total_Appointments_valid_no,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 1 ,1,0)) total_sent,
        SUM(IF(`phone_no` IS NOT NULL && STATUS = 0 ,1,0)) total_not_sent,
        SUM(IF(`phone_no` IS NULL ,1,0)) total_appointments_invalid_no";
        $list =  DB::table($table)
            ->select(DB::raw($listSql))
            ->groupBy('lga','facility_name')
            ->get();

        return array('stats'=> $stats ,'list'=> $list);

    }*/

    public function regimenDashboard(): Factory|View|Application
    {
        /*$data = [
            'dashboardGraphs' => Helper::dashboardGraphs()
        ];*/
        return view('monitoring.quality_of_care');
    }

    public function mortality(): Factory|View|Application
    {
        return view('monitoring.mortality');

    }

    public function pbsDashboard(): Factory|View|Application
    {
        return view('monitoring.biometrics_report');
    }

    public function vlDashboard()
    {
        return view('monitoring.vl');
    }

    public function ahd(): Factory|View|Application
    {
        return view('monitoring.ahd');
    }

    public function dsd(): Factory|View|Application
    {
        return view('monitoring.dsd');
    }





}
