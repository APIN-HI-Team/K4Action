<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Helpers\Scripts as Helper;
use App\Helpers\Treatment_new as Helper1;
use App\Helpers\VL as Helper2;
use App\Helpers\Mortality as Helper3;
use App\Helpers\Biometrics as Helper4;
use App\Helpers\VLAnalytics as Helper5;
use App\Helpers\Ahd as Helper6;
use App\Helpers\DsdModel as Helper7;
use Illuminate\Support\Facades\View;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/treatment-filter', function(Request $request) {
    error_log(print_r($request->all(), true));
    $selectIndicator = $request->selectIndicator;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    return match ($selectIndicator) {
        'pvls' => Helper2::vLGraph($request, $selectIndicator),
        'tx_curr' => Helper::treamentPerformance($request, $selectIndicator, $start_date, $end_date),
        'tx_new' => Helper1::treament_new_Performance($request, $selectIndicator, $start_date, $end_date),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('treatment.filter');


Route::post('/vl-filter', function(Request $request) {
    $selectIndicator = $request->selectIndicator;
    return match ($selectIndicator) {
        'vl' => Helper5::vl_analytics($request, $selectIndicator),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('vl.filter');

Route::post('/quality-care', function(Request $request){
    $selectIndicator = $request->selectIndicator;
    return match ($selectIndicator) {
        'regimen' => Helper::regimenGraph($request),
        'ped_regimen' => Helper::pedregimenGraph($request),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('quality.filter');

Route::post('/mortality', function(Request $request){
    $selectIndicator = $request->selectIndicator;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    return match ($selectIndicator) {
        'ms' => Helper3::mortality_stats($request, $start_date, $end_date),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('mortality.filter');

Route::post('/ahd', function(Request $request){
    $selectIndicator = $request->selectIndicator;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    $age_group = $request->age_group;
    $sex = $request->sex;
    return match ($selectIndicator) {
        'ahd' => Helper6::ahd_Performance($request, $start_date, $end_date,$age_group,$sex),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('ahd.filter');


Route::post('/dsd', function(Request $request){
    $selectIndicator = $request->selectIndicator;
    return match ($selectIndicator) {
        'dsd' => Helper7::dsd_analysis($request),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('dsd.filter');

Route::post('/pbs', function(Request $request){
    $selectIndicator = $request->selectIndicator;
    return match ($selectIndicator) {
        'coverage' => Helper4::biometrics($request),
        default => response()->json(['error' => 'Invalid selectIndicator value'], 400),
    };
})->name('pbs.filter');

Route::get('/get-wiget/{id}', function($page){
    return View::make('monitoring.reports.'.$page);
});

Route::get('/get-widget/{id}', function($page){
    return View::make('monitoring.qoc.'.$page);
});

Route::get('/pbs-widget/{id}', function($page){
    return View::make('monitoring.pbs.'.$page);
});

Route::get('/vl-widget/{id}', function($page){
    return View::make('monitoring.vl.'.$page);
});

Route::get('/sendSMS', function(Request $request){
    $appointment = DB::table('next_day_appointments')
        ->where(['status'=>0])
        ->where('next_appointment', Carbon::today()->addDays(2)->toDate())
        ->whereNotNull('phone_no')->get();

    $sent = [];
    $res = "";
    $ids = [];

    foreach ($appointment as $key => $value){
        $ids[$key]['id'] = $value->id;
        $ids[$key]['State'] = $value->state;
        $ids[$key]['LGA'] = $value->lga;
        $ids[$key]['Datim_Code'] = $value->datim_code;
        $ids[$key]['status'] = 1;
        $sent[$key]['PepId'] = $value->pepid;
        $sent[$key]['VisitDate'] = $value->next_appointment;
        $sent[$key]['PhoneNumber'] = $value->phone_no;
        $sent[$key]['AppointmentDate'] = $value->next_appointment;
        $sent[$key]['AppointmentOffice'] = 'P';
        $sent[$key]['AppointmentData'] = array('DrugToCollect'=>"AZT/3TC/NVP",'NextApptDate'=> $value->next_appointment);
    }

    /*dd(json_encode($sent,JSON_UNESCAPED_SLASHES));*/

    $client = new \GuzzleHttp\Client([
        'verify' => base_path('public/cacert.pem')
    ]);

    $response = $client->post('https://pbs.apin.org.ng/Integration/MessageDeliveryRequest/PushNextAppointment',[
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($sent,JSON_UNESCAPED_SLASHES)
    ]);

    $res = $response->getBody();

    foreach($ids as $key => $id){
        DB::table('next_day_appointments')
            ->where('id',$id)
            ->update(['status' => 1]);

        $appointmentHistoryLog = new AppointmentHistoryLog;
        $appointmentHistoryLog->state = $id['State'];
        $appointmentHistoryLog->lga = $id['LGA'];
        $appointmentHistoryLog->datim_code = $id['Datim_Code'];
        $appointmentHistoryLog->pepid = $sent[$key]['PepId'];
        $appointmentHistoryLog->phone_no = $sent[$key]['PhoneNumber'];
        $appointmentHistoryLog->status = 1;
        $appointmentHistoryLog->save();
    }

    return $res;
    /*dd(json_encode($res,JSON_UNESCAPED_SLASHES));**/
})->name('appointments');

Route::get('/getLogs', function(Request $request){
    $params = [
        'query' => [
            'startRange' => "202210010000",
            'endRange' => "202210191200",
        ]
    ];
    $client = new Client([
        'verify' => base_path('public/cacert.pem')
    ]);

    $response = $client->get('https://pbs.apin.org.ng/Integration/MessageDeliveryRequest/GetLog', $params);
    $res = $response->getBody()->getContents();
    $formattedResult = json_decode($res);
    /*dd($formattedResult);*/

    foreach($formattedResult->Data as $data){
        $phoneNumber = str_replace("234", "0",$data->PhoneNumber);
        /*$timestamp = preg_replace( '/[^0-9]/', '', $data->MessageDate);
        $date = date("Y-m-d H:i:s", $timestamp / 1000);*/
        if(strlen($phoneNumber) == 11){
            DB::table('appointments_logs')
                ->where('phone_no',$phoneNumber)
                /*->where('created_at',$date)*/
                ->update(['status' => "Delivered"]);
        }
    }
    return "success";
})->name('getLogs');

