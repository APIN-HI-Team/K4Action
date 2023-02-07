<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <link href=" https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css" rel="stylesheet"
          type="text/css" />
    <link href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.bootstrap.min.css" rel="stylesheet"
          type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap.min.css" rel="stylesheet"
          type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

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
    <script src="{{asset('code/modules/accessibility.js')}}"></script>
    <script src="{{asset('code/modules/drilldown.js')}}"></script>
    <script src="{{asset('assets/highcharts-utils.js')}}"></script>


</head>
<body class="font-sans antialiased">
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
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-4">
                    <!--Start side bar -->
                    <div class="col-span-3 bg-white rounded p-4 drop-shadow-md">
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
                                    <option value="" disabled selected hidden>Choose ...</option>
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
                                <select id="state" name="state" class="e2 select2 select2-selection--multiple mt-1 block w-full py-1 px-1.5 border border-gray-300 bg-white
            rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" data-toggle="select2" multiple="multiple">
                                    <option value="">Choose ...</option>
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
                                <select id="lga" name="lga" class="e2 select2 mt-1 block w-full h-4 py-2 px-3 border
            border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500
            focus:border-indigo-500 sm:text-sm" data-toggle="select2" multiple="multiple">
                                    <option value="">Choose ...</option>

                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="facility" class="block text-sm text-gray-500 mt-5">
                                    <i class="uil uil-box"></i> Facilities
                                </label>
                                <select id="facility" name="facility" class="e2 select2 select2-selection--multiple mt-1 block w-full py-2 px-3 border
            border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500
            focus:border-indigo-500 sm:text-sm" data-toggle="select2" multiple="multiple">

                                </select>
                            </div>


<!--                            <div id="div1">
                                <div class="col-span-6 sm:col-span-3 mt-5">
                                    <label for="cat" class="block text-sm text-gray-500">
                                        <i class="uil uil-schedule"></i> From Date
                                    </label>
                                    <input type="date" name="from" id="cat1" class="mt-1 focus:ring-indigo-500
                 w-full shadow-sm sm:text-sm border-gray-300
                rounded-md"/>
                                </div>

                                <div class="col-span-6 sm:col-span-3 mt-5 ">
                                    <label for="cat" class="block text-sm text-gray-500">
                                        <i class="uil uil-schedule"></i> To Date
                                    </label>
                                    <input type="date" name="to" id="cat2" class="mt-1 focus:ring-indigo-500
                block w-full shadow-sm sm:text-sm border-gray-300
                rounded-md"/>
                                </div>
                            </div>-->


                            <div class="col-span-6 sm:col-span-3 mt-5 text-left">
                                <button class="inline-flex justify-center py-2 px-4
            border border-transparent shadow-sm text-sm font-bold rounded-md
            text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none
            focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Load Data
                                </button>

                                <button type="submit" class="inline-flex justify-center py-2 px-4
             border border-transparent shadow-sm text-sm font-bold rounded-md
             text-white bg-gray-500 hover:bg-gray-700 focus:ring-2 focus:ring-offset-2
             focus:ring-indigo-500">Clear
                                </button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>

                    <!-- end side bar -->

                    <!-- Main content -->
                    <div class="col-span-9">
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

    let uri = baseUrlWiget+'tx_curr';
    $('#report_containner').load(uri);

    $(".e2").select2({
        placeholder: "Choose...",
        allowClear: true
    });

    $(document).ready(function(){
        $("#filters").submit(function(event){
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
                if (selectReports.val() == 'tx_curr') {
                    $(".tx_patient").html(response.active);
                    $(".tx_facilities").html(response.facilities);
                    $(".tx_states").html(response.states)
                    $(".tx_lgas").html(response.lga)

                    build_drilldown_bar_chart(
                        'drilldownBar',
                        'Patients Newly Enrolled on ART by Location',
                        'Number of Patients',
                        data.tx_new_state_data,
                        data.tx_new_lga_drill_data);

                } else if (selectReports.val() == 'tx_new') {
                    $(".tx_new").html(response.tx_new);
                    $(".tx_facilites").html(response.facilities)
                    $(".tx_states").html(response.states)
                    $(".tx_lgas").html(response.lga)

                    build_drilldown_bar_chart(
                        'drilldownBar',
                        'Patients Newly Enrolled on ART by Location',
                        'Number of Patients',
                        data.tx_new_state_data,
                        data.tx_new_lga_drill_data);

                } else if (selectReports.val() == 'pvls') {

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
            });
            event.preventDefault();
        });
    });
</script>
</body>
</html>
