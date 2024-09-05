<div class="grid grid-cols-4 gap-4">
    <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
        <div class="text-indigo-700">
            <p class="text-xl"><i class="uil uil-users-alt"></i> PATIENTS</p>
            <p class="tx_curr">...</p>
        </div>
    </div>
    <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
        <div class="text-red-500 ">
            <p class="text-xl"><i class="uil uil-hospital"></i> ELIGIBLE</p>
            <p class="tx_eligible">...</p>
        </div>
    </div>
    <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
        <div class="text-green-600">
            <p class="text-xl"><i class="uil uil-location-point"></i> LGAs</p>
            <p class="tx_lgas">...</p>
        </div>
    </div>
    <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
        <div class="text-red-700">
            <p class="text-xl"><i class="uil uil-location-point"></i> SUPPRESSED</p>
            <p class="tx_states">...</p>
        </div>
    </div>
</div>



    {{--<div class="grid grid-cols-1 mt-6">
        <div class="col-span-1 bg-white drop-shadow-md">
            <div class="box-heading ml-2 mt-2 font-bold text-sm">
                Viral Load Suppression (VLS) Rates by State
            </div>
            <div class="box-content">
                <div class="chart" id="pie_chart">
                </div>
            </div>
        </div>
    </div>--}}


<div class="col-span-1 bg-white drop-shadow-md relative">
    <div class="box-heading ml-2 mt-2 mb-4 font-bold text-sm">
        Viral Load Suppression (VLS) Rates by State
    </div>
    <div class="box-content">
        <div id="pvlsStateChart" class="chart" data-highcharts-chart="16"></div>
    </div>
</div>
