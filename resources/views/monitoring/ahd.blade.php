<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AHD Analytics') }}
        </h2>
    </x-slot>

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
            <div class="grid grid-cols-12 gap-4">

                <!--Start side bar -->
                <div class="col-span-2 bg-white h-fit rounded p-4 drop-shadow-md">
                    <p class="text-sm font-medium">APPLY FILTERS BELOW TO LOAD DATA</p>

                    <!--begin::Form-->
                    <form id="filters" method="POST">

                        <div class="col-span-6 sm:col-span-3 hidden">
                            <label for="directorate" class="block text-sm text-gray-500 mt-3">
                                <i class="uil uil-list-ul"></i> Select MER Indicator
                            </label>

                            <select id="selectIndicator" name="selectIndicator" class="select2 mt-1 block w-full py-2 px-3
                                border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500
                                focus:border-indigo-500 sm:text-sm">
                                <option value="ahd" selected></option>
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
                                <option value="" disabled></option>
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

                        <!-- Sex -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="state" class="block text-sm text-gray-500 mt-5">
                                <i class="uil uil-map-pin-alt"></i> Sex
                            </label>
                            <select id="sex" name="sex"
                                    class="e2 select2 mt-1 block w-full py-2 px-3 border
                                        border-gray-300 bg-white rounded-md shadow-sm focus:outline-none
                                        focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    multiple="multiple">
                                <option value="" disabled></option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <!-- End States -->

                        <!-- Age Group -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="state" class="block text-sm text-gray-500 mt-5">
                                <i class="uil uil-map-pin-alt"></i> Select Age Group
                            </label>
                            <select id="age_groups" name="age_group"
                                    class="e2 select2 mt-1 block w-full py-2 px-3 border
                                        border-gray-300 bg-white rounded-md shadow-sm focus:outline-none
                                        focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    multiple="multiple">
                                <option value="" disabled></option>
                                <option value="LessThan1"><1</option>
                                <option value="1To4">1-4</option>
                                <option value="5To9">5-9</option>
                                <option value="10To14">10-14</option>
                                <option value="15To19">15-19</option>
                                <option value="20To24">20-24</option>
                                <option value="25To29">25-29</option>
                                <option value="30To34">30-34</option>
                                <option value="35To39">35-39</option>
                                <option value="40To44">40-44</option>
                                <option value="45To49">45-49</option>
                                <option value="50">50+</option>
                            </select>
                        </div>
                        <!-- End States -->

                        <div id="div1">
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
                    <div class="grid grid-cols-1">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-heading ml-4 mt-2 mb-4 font-bold text-sm">
                                Advanced HIV Disease (AHD) Cascade
                            </div>
                            <div class="box-content">
                                <div class="chart" id="testingCascades">
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="grid grid-cols-1 mt-8">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-heading ml-4 mt-2 mb-4 font-bold text-sm">
                                Cryptococcal Meningitis (CM) Cascade
                            </div>
                            <div class="box-content">
                                <div class="chart" id="crAgCascades">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 mt-8">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-heading ml-4 mt-2 mb-4 font-bold text-sm">
                                Tuberculosis (TB) Cascade
                            </div>
                            <div class="box-content">
                                <div class="chart" id="tbCascades">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End main content -->

            </div>
        </div>
    </div>


    @section('footer_scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <script src="{{asset('assets/select2.js') }}"></script>
        <script src="{{asset('assets/filter.js')}}"></script>

        <script>
            $(".e2").select2({
                allowClear: true
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
                $(".tx_dead").html('...')
                $(".tx_states").html('...')
                $(".tx_lgas").html('...')
                $(".tx_eligible").html('...')

                console.log(formData);
                $.ajax({
                    type: "POST",
                    url: "{{route('ahd.filter')}}",
                    data: formData,
                    dataType: 'json',
                    encode: true,
                }).done(function(data) {
                    var response = data.ahd;
                    if (selectReports.val() === 'ahd') {
                        //console.log(data.regimenLineQs);
                        build_ahd_charts(data);
                    }
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
        </script>
        <script>
            function build_ahd_charts(data) {
                console.log(data);
                populateTestingCascades(data);
                populateCryptococcalMeningitisCascades(data);
                populateTuberculosissCascades(data)
            }

            function build_AHD_bar_chart(container_id, title, y_title, seriesData, colors, categories) {

                Highcharts.chart(container_id, {

                    chart: {
                        type: 'column'
                    },

                    title: {
                        text: title,
                        style: {
                            fontSize: '10px'
                        }
                    },

                    xAxis: {
                        categories: categories
                    },

                    yAxis: [
                        {
                            labels: {
                                enabled: true
                            },
                            title: {
                                text: ''
                            }
                        },
                        { //secondary axis
                            min: 0,
                            max: 100,
                            title: {
                                text: 'Percentage'
                            },
                            labels: {
                                format: '{value} %',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            opposite: true
                        }
                    ],
                    tooltip: {
                        shared: true
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.ycomma} {point.p}'
                            }
                        }
                    },
                    colors: colors,
                    series: seriesData,
                    exporting: { enabled: true }
                });


            }

            function populateTestingCascades(data) {
                var ahdData = data.ahd;
                const Obj = [{
                    name: [""], type: 'column', showInLegend: false,
                    data: [
                        {
                            name: "New HIV Positive",
                            y: ahdData.hivPosClient

                        }, {
                            name: "WHO Clinical Staging",
                            y: ahdData.whoClinicalStagingCount

                        }, {
                            name: "CD4 Results",
                            y: ahdData.cd4Result

                        }, {
                            name: " WHO Staging III and IV",
                            y: ahdData.whoClinicalStaging3n4Count

                        }, {
                            name: "CD4 < 200cell/mm3",
                            y: ahdData.cD4LessCount

                        }
                    ]
                }, {
                    name: ["% WHO Clinical Staging"], color: "#F88944", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                    marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, ahdData.percentageWhoClinicalStaging, null, null, null]
                },
                    {
                        name: ["% CD4 Results"], color: "#FF8D78", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, ahdData.percentageCD4Results, null, null]
                    },
                    {
                        name: ["% WHO Clinical Staging III & IV"], color: "#7A76A4", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, null, ahdData.percentageWhoClinicalStaging3n4, null]
                    },
                    {
                        name: ["%CD4 < 200cell/mm3"], color: "#C5E0B4", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, null, null, ahdData.percentageCD4Less]
                    }];

                var colors = ['#494FA3', '#FFB913'];
                build_AHD_bar_chart("testingCascades", '', '', Obj, colors, ['New HIV Positive', 'WHO Clinical Staging', 'CD4 Results', 'WHO Staging III and IV', 'CD4 < 200cell/mm3']);

            }

            function populateCryptococcalMeningitisCascades(data) {
                var cmData = data.cm;
                const Obj = [
                    {
                        name: [" "], type: 'column', showInLegend: false,
                        data: [
                            {
                                name: "AHD Client",
                                y: cmData.presumptiveAHDClients

                            }, {
                                name: "Serum CrAg",
                                y: cmData.serumCragCount

                            }, {
                                name: "Serum CrAg Positive",
                                y: cmData.serumCragPosCount

                            }, {
                                name: "CSF CrAg",
                                y: cmData.csfCragCount

                            },
                            {
                                name: "CSF CrAg Positive",
                                y: cmData.csfCragPosCount

                            },
                            {
                                name: "CM Treatment",
                                y: 0

                            }
                        ]
                    },
                    {
                        name: ["% Serum CrAg"], color: "#F88944", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, cmData.percentageSerumCrag, null, null, null, null]
                    }
                    , {
                        name: ["% Serum CrAg Positive"], color: "#FF8D78", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, cmData.percentageSerumCragPos, null, null, null]
                    },
                    {
                        name: ["% CSF CrAg"], color: "#7A76A4", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, null, cmData.percentageCsfCrag, null, null]
                    },
                    {
                        name: ["% CM-HIV Co-Infection"], color: "#C5C5902", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, null, null, cmData.percentageCsfCragPos, null]
                    },
                    {
                        name: ["% CM Treatment"], color: "#C5E0B4", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, null, null, null, 0]
                    }
                ];

                var colors = ['#494FA3', '#FFB913'];
                build_AHD_bar_chart("crAgCascades", '', '', Obj, colors, ['AHD Client', 'Serum CrAg', 'Serum CrAg Positive', 'CSF CrAg', 'CSF CrAg Positive', 'CM Treatment']);

            }

            function populateTuberculosissCascades(data) {
                var tbData = data.tb;
                const Obj = [{
                    name: [""], type: 'column', showInLegend: false,
                    data: [
                        {
                            name: "AHD Clients",
                            y: tbData.presumptiveAHDClients
                        }, {
                            name: "TB-LAM Test",
                            y: tbData.tB_LAMCount

                        }, {
                            name: "TB-LAM Positive",
                            y: tbData.tB_LAMPosCount,

                        }, {
                            name: "TB Treatment",
                            y: tbData.tB_LAMTreatmentCount,

                        }
                    ]
                },
                    {
                        name: ["% TB-LAM Test"], color: "#F88944", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, tbData.percentageTB_LamTest, null, null]
                    },
                    {
                        name: ["% TB-HIV Co-Infection"], color: "#FF8D78", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, tbData.percentageAHD_TBCoInfection, null]
                    },
                    {
                        name: ["% TB Treatment"], color: "#7A76A4", type: 'spline', yAxis: 1, lineWidth: 0, states: { hover: { lineWidth: 0, lineWidthPlus: 0, marker: { radius: 7 } } },
                        marker: { enable: true, symbol: "radius", radius: 7 }, data: [null, null, null, tbData.percentageAHDClientTB]
                    }];

                var colors = ['#494FA3', '#FFB913'];
                build_AHD_bar_chart("tbCascades", '', '', Obj, colors, ['AHD Clients', 'TB-LAM Test', 'TB-LAM Positive', 'TB Treatment']);

            }
        </script>
    @endsection
</x-app-layout>
