<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Treatment Dashboard</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <link href=" https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<!--    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->

    <script src="{{asset('code/highcharts.js')}}"></script>
    <script src="{{asset('code/modules/pareto.js')}}"></script>
    <script src="{{asset('code/modules/exporting.js')}}"></script>
    <script src="{{asset('code/modules/data.js')}}"></script>
    <script src="{{asset('code/modules/export-data.js')}}"></script>
    <script src="http://github.highcharts.com/f808ab117823082ca137fab775cbdf52cce27b90/modules/offline-exporting.src.js"></script>
    <script src="{{asset('code/modules/accessibility.js')}}"></script>
    <script src="{{asset('code/modules/drilldown.js')}}"></script>
    <script src="{{asset('assets/highcharts-utils.js')}}"></script>



</head>
<body class="font-sans antialiased">
<style>
    /* Adjust the layout for screens smaller than 640px */
    @media (max-width: 640px) {
        .grid-cols-12 {
            grid-template-columns: 1fr;
        }

        .md\:grid-cols-3 {
            grid-template-columns: 1fr;
        }
    }

    /* Adjust the layout for screens larger than 640px */
    @media (min-width: 641px) {
        .grid-cols-12 {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }

        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
</style>
<x-jet-banner />

<div class="min-h-screen bg-gray-100">
    @livewire('navigation-menu')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif


<!-- Page Content -->
    <main>
        <div class="flex items-center justify-center" id="loading" style="display:none">
            <div class="inline-block h-8 w-8 animate-[spinner-grow_0.75s_linear_infinite] rounded-full bg-current align-[-0.125em]
            text-primary opacity-0 motion-reduce:animate-[spinner-grow_1.5s_linear_infinite]" role="status">
                <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
            </div>
            <div class="inline-block h-8 w-8 animate-[spinner-grow_0.75s_linear_infinite] rounded-full bg-current align-[-0.125em]
            text-secondary opacity-0 motion-reduce:animate-[spinner-grow_1.5s_linear_infinite]" role="status">
                <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
            </div>
            <div class="inline-block h-8 w-8 animate-[spinner-grow_0.75s_linear_infinite] rounded-full bg-current align-[-0.125em]
            text-success opacity-0 motion-reduce:animate-[spinner-grow_1.5s_linear_infinite]" role="status">
                <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-2">

                    <!--Start side bar -->
                    <div class="col-span-2 bg-white h-fit rounded p-4 drop-shadow-md">
                        <p class="text-sm font-medium">APPLY FILTERS BELOW TO LOAD DATA</p>

                        <!--begin::Form-->
                        <form id="filters" method="POST">

                            <div class="col-span-6 sm:col-span-3">
                                <label for="directorate" class="block text-sm text-gray-500 mt-3">
                                    <i class="uil uil-list-ul"></i> Select MER Indicator
                                </label>

                                <select id="selectIndicator" name="selectIndicator" class="select2 mt-1 block w-full py-2 px-3
                                border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500
                                focus:border-indigo-500 sm:text-sm">
                                    <option value="" disabled selected></option>
                                    <option value="tx_new">Treatment New</option>
                                    <option value="tx_curr">Treatment Current</option>
                                    <option value="pvls">Treatment PVLS</option>
                                </select>
                            </div>

                            <!-- States -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="state" class="block text-sm text-gray-500 mt-5">
                                    <i class="uil uil-map-pin-alt"></i> States
                                </label>
                                <select id="state" name="state"
                                        class="e2 select2 mt-1 block w-full py-2 px-3 border
                                        border-gray-300 bg-white rounded-md shadow-sm focus:outline-none
                                        focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        multiple="multiple">
                                    <option value="" disabled>Choose ...</option>
                                    <option value="7">Benue</option>
                                    <option value="28">Ogun</option>
                                    <option value="29">Ondo</option>
                                    <option value="31">Oyo</option>
                                    <option value="32">Plateau</option>
                                </select>
                            </div>
                            <!-- End States -->

                            <div class="col-span-6 sm:col-span-3">
                                <label for="lga" class="block text-sm text-gray-500 mt-5">
                                    <i class="uil uil-map-pin-alt"></i> LGAs
                                </label>
                                <select id="lga" name="lga" class="e2 select2 mt-1 block w-full h-4 py-2 px-3 border border-gray-300
                                bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                sm:text-sm" data-toggle="select2" multiple="multiple">
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="facility" class="block text-sm text-gray-500 mt-5">
                                    <i class="uil uil-box"></i> Facilities
                                </label>
                                <select id="facility" name="facility" class="e2 select2 select2-selection--multiple mt-1
                                block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none
                                focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" data-toggle="select2" multiple="multiple">
                                </select>
                            </div>
                            <div id="div1" style="display: none;">
                                <div class="col-span-6 sm:col-span-3 mt-5">
                                    <label for="cat" class="block text-sm text-gray-500">
                                        <i class="uil uil-schedule"></i> From Date
                                    </label>
                                    <input type="date" name="start_date" id="start_date"
                                           class="mt-1 focus:ring-indigo-500 w-full
                                               shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3 mt-5 ">
                                    <label for="cat" class="block text-sm text-gray-500">
                                        <i class="uil uil-schedule"></i> To Date
                                    </label>
                                    <input type="date" name="end_date" id="end_date"
                                           class="mt-1 focus:ring-indigo-500 block
                                           w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    />
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3 mt-5 text-left">
                                <button class="inline-flex justify-center py-2 px-2 border border-transparent
                                    shadow-sm text-sm font-bold rounded-md text-white bg-indigo-500 hover:bg-indigo-700
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Load Data
                                </button>

                                <button type="reset" class="inline-flex justify-center py-2 px-2 border border-transparent shadow-sm
                                        text-sm font-bold rounded-md text-white bg-gray-500 hover:bg-gray-700 focus:ring-2
                                        focus:ring-offset-2 focus:ring-indigo-500">Clear
                                </button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>

                    <!-- end side bar -->

                    <!-- Main content -->
                    <div class="col-span-10">
                        @yield('content')
                    </div>
                    <!-- End main content -->
                </div>
            </div>
        </div>
    </main>
</div>



<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="{{asset('assets/select2.js') }}"></script>
<script src="{{asset('assets/filter.js')}}"></script>

<script type="text/javascript">
    var baseUrlApi = '<?php echo URL::to('/api').'/';?>';
    var baseUrlWiget = '<?php echo URL::to('/api').'/get-wiget/';?>';

    const dropdown = document.getElementById("selectIndicator");
    const divToShow = document.getElementById("div1");

    dropdown.addEventListener("change", function() {
        if (dropdown.value === "tx_new") {
            divToShow.style.display = "block";
        } else {
            divToShow.style.display = "none";
        }
    });

    var today = new Date().toISOString().slice(0, 10);
    // Set the value of the date field to the current date
    document.getElementById("end_date").value = today;

    var month = new Date();
    // Set the day of the date object to 1
    month.setDate(1);

    // Get the date in YYYY-MM-DD format
    var firstDayOfMonth = month.toISOString().slice(0, 10);
    document.getElementById("start_date").value = firstDayOfMonth;

    $('#report_containner').load(baseUrlWiget);

    $(".e2").select2({
        allowClear: true
    });

    $(document).ready(function(){
        $("#filters").submit(function(event){
            $('#loading').show();
            let selectReports = $('#selectIndicator');

            var state = [];
            $('#state :selected').each(function(){
                state.push($(this).val());
            });

            var lga = [];
            $('#lga :selected').each(function(){
                lga.push($(this).val());
            });

            var facility = [];
            $('#facility :selected').each(function(){
                facility.push($(this).val());
            });

            var formData = $("#filters").serializeArray();
            formData.push({
                name: 'states',
                value: state
            }, {
                name: 'lgas',
                value: lga
                },{
                name: 'facilities',
                value: facility
            });
            $(".tx_curr").html('...')
            $(".tx_new").html('...')
            $(".tx_facilities").html('...')
            $(".tx_states").html('...')
            $(".tx_lgas").html('...')
            $(".tx_eligible").html('...')

            console.log(formData);

            $.ajax({
                type: "POST",
                url: "{{route('treatment.filter')}}",
                data: formData,
                dataType: 'json',
                encode: true,
            }).done(function(data){
                var response = data.treatment_perfomance;

                if (selectReports.val() === 'tx_curr') {
                    var age_group_categories = [
                        '<1','1-4','5-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50+'
                    ];

                    var tx_new_series = [
                        {
                            name: 'Male',
                            data: data.tx_new_age_sex.male_data,
                            color: '#A3A8E2'
                        }, {
                            name: 'Female',
                            data: data.tx_new_age_sex.female_data,
                            color: '#494FA3'
                        }
                    ];


                    var male_max = Math.abs(Math.min.apply(Math, data.tx_new_age_sex.male_data));
                    var female_max = Math.abs(Math.max.apply(Math, data.tx_new_age_sex.female_data));
                    var max = female_max > male_max ? female_max : male_max;

                    $(".tx_patient").html(Number(response.active).toLocaleString());
                    $(".tx_facilities").html(response.facilities);
                    $(".tx_states").html(response.states)
                    $(".tx_lgas").html(response.lga)

                    build_drilldown_bar_chart(
                        'drilldownBar',
                        'Patients Newly Enrolled on ART by Location',
                        'Number of Patients',
                        data.tx_new_state_data,
                        data.tx_new_lga_drill_data);

                    BuildDonut(
                        "sexChart",
                        "Patients Currently Receiving ART by Sex",
                        data.tx_curr_graph, ['#A3A8E2', '#494FA3']);

                    BuildDonut(
                        'ageChart',
                        'Patients Currently Receiving ART By Age Group',
                        data.tx_age_group_graph, ['#7A7802', '#959335', '#AFAE67', '#CAC99A']);


                    Build_Pos_Neg_Chart(
                        'ageSexChart',
                        'Patients Currently Receiving ART by Age and Sex within COP Year',
                        age_group_categories,
                        tx_new_series,
                        max);


                } else if (selectReports.val() === 'tx_new') {
                    var age_group_categories = [
                        '<1','1-4','5-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50+'
                    ];

                    var tx_new_series = [
                        {
                            name: 'Male',
                            data: data.tx_new_age_sex.male_data,
                            color: '#A3A8E2'
                        }, {
                            name: 'Female',
                            data: data.tx_new_age_sex.female_data,
                            color: '#494FA3'
                        }
                    ];


                    var male_max = Math.abs(Math.min.apply(Math, data.tx_new_age_sex.male_data));
                    var female_max = Math.abs(Math.max.apply(Math, data.tx_new_age_sex.female_data));
                    var max = female_max > male_max ? female_max : male_max;

                    $(".tx_new").html(Number(response.new).toLocaleString());
                    $(".tx_facility").html(response.facility)
                    $(".tx_states").html(response.states)
                    $(".tx_lgas").html(response.lga)

                    BuildDonut(
                        "sexChart",
                        "Patients Newly Enrolled ART by Sex",
                        data.tx_curr_graph, ['#A3A8E2', '#494FA3']);

                    BuildDonut(
                        'ageChart',
                        'Patients Newly Enrolled on ART By Age Group',
                        data.tx_age_group_graph, ['#7A7802', '#959335', '#AFAE67', '#CAC99A']);

                    build_drilldown_bar_chart(
                        'newdrilldownBar',
                        'Patients Newly Enrolled on ART by Location',
                        'Number of Patients',
                        data.new_state_data,
                        data.new_lga_drill_data);

                    Build_Pos_Neg_Chart(
                        'ageSexChart',
                        'Newly Enrolled Patients by Age and Sex',
                        age_group_categories,
                        tx_new_series,
                        max);

                    build_Line_chart(
                        'initiationTrend',
                        null,
                        'Number of Patients',
                        data.tx_trends_data.tx_new_trend_months,
                        data.tx_trends_data.tx_new_trend_data,
                        null,
                        "Number of Patients",
                        "Month");

                } else if (selectReports.val() === 'pvls') {

                    $(".tx_curr").html(Number(response.active).toLocaleString())
                    $(".tx_eligible").html(Number(response.eligible).toLocaleString())
                    /*$(".tx_lgas").html(response.eligibleWithVl)
                    $(".tx_curr").html(response.active);*/

                    build_bar_chart_dual_axis(
                        "pvlsStateChart",
                        null,
                        'Number of Patients',
                        '% Suppression',
                        data.states,
                        data.eligibleWithVl,
                        'Viral Load Results',
                        data.viralLoadSuppressed,
                        'Suppression',
                        data.percentage_viral_load_suppressed,
                        '% Suppression',
                        false,
                        "State");
                }
                console.log(data);
            }).fail(function(xhr) {
                if (xhr.status === 401) {
                    alert("Your Session has expired. Click ok to redirect to login page");
                    window.location.reload();
                } else {
                    alert("There was an error fetching the data");
                }
            }).always(function() {
                $('#loading').hide();
            });
            event.preventDefault();
        });
    });
</script>

<script>
    function downloadPng(containerId) {
        var chart = $('#' + containerId).highcharts();
        if (chart != null) {
            chart.exportChartLocal({ type: 'image/png' })
        }
    };


</script>
</body>
</html>
