<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mortality Surveillance') }}
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
                <div class="col-span-3 bg-white h-fit rounded p-4 drop-shadow-md">
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
                                <option value="ms" selected></option>
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
                            <button class="inline-flex justify-center py-2 px-4 border border-transparent
                                    shadow-sm text-sm font-bold rounded-md text-white bg-indigo-500 hover:bg-indigo-700
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Load Data
                            </button>

                            <button type="reset" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm
                                        text-sm font-bold rounded-md text-white bg-gray-500 hover:bg-gray-700 focus:ring-2
                                        focus:ring-offset-2 focus:ring-indigo-500">Clear
                            </button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>

                <!-- end side bar -->

                <!-- Main content -->
                <div class="col-span-9">
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-indigo-700">
                                <p class="text-xl"><i class="uil uil-users-alt"></i> PATIENTS</p>
                                <p class="tx_patient" >...</p>
                            </div>
                        </div>
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-red-500 ">
                                <p class="text-xl"><i class="uil uil-exclamation-triangle"></i> DEAD</p>
                                <p class="tx_dead">...</p>
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
                                <p class="text-xl"><i class="uil uil-location-point"></i> STATES</p>
                                <p class="tx_states">...</p>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 mt-6">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-content">
                                <div class="chart" id="msDrillDown">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 mt-6">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-content">
                                <div class="chart" id="msRegs">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="grid sm:grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                        <div class="grid grid-cols-1 mt-6">
                            <div class="col-span-1 bg-white drop-shadow-md relative">
                                <div class="box-content">
                                    <div class="chart" id="msSexPie">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 mt-6">
                            <div class="col-span-1 bg-white drop-shadow-md relative">
                                <div class="box-content">
                                    <div class="chart" id="msVlPie">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 mt-6">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-content">
                                <div class="chart" id="msArtDurationState">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 mt-6">
                        <div class="col-span-1 bg-white drop-shadow-md relative">
                            <div class="box-content">
                                <div class="chart" id="msAgeDisaggregate">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 mt-6">
                        <div class="col-span-2 bg-white drop-shadow-md relative">
                            <div class="box-content">
                                <div class="chart" id="ageSexChart">
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
                    url: "{{route('mortality.filter')}}",
                    data: formData,
                    dataType: 'json',
                    encode: true,
                }).done(function(data) {
                    var response = data.mortality_stats;
                    if (selectReports.val() === 'ms') {

                        var age_group_categories = [
                            '<1','1-4','5-9','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50-54','55-59','60-64','65-69','70+'
                        ];

                        var tx_new_series = [
                            {
                                name: 'Male',
                                data: data.deadAgeSex.male_data,
                                color: '#A3A8E2'
                            }, {
                                name: 'Female',
                                data: data.deadAgeSex.female_data,
                                color: '#494FA3'
                            }
                        ];

                        var male_max = Math.abs(Math.min.apply(Math, data.deadAgeSex.male_data));
                        var female_max = Math.abs(Math.max.apply(Math, data.deadAgeSex.female_data));
                        var max = female_max > male_max ? female_max : male_max;
                        //console.log(data.regimenLineQs);

                        $(".tx_states").html(response.states)
                        $(".tx_dead").html(Number(response.Dead).toLocaleString());
                        Build_Pos_Neg_Chart1(
                            'ageSexChart',
                            'Number of Dead Clients Disaggregated by Age and Sex',
                            age_group_categories,
                            tx_new_series,
                            max);

                        buildDataCharts(data);

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
            function buildDataCharts(data)
            {
                buildMsDrilldownChart(data.new_state_data);



                //-------Sex Disaggregation-------------------------
                //Sex Disaggregation Pie chart
                let ttMales = data.sex_total[0].males;

                let ttFemales = data.sex_total[0].females;

                let ttx = ttMales + ttFemales;

                let totalMales = (ttMales / ttx) * 100;
                let totalFemales = (ttFemales / ttx) * 100;

                //VL suppression pie chart
                let ttSuppressed = data.vl[0].suppressed;
                let ttUnsuppressed = data.vl[0].unsuppressed;
                let ttNoViralLoadResult = data.vl[0].no_vl;

                let ttVl = (ttSuppressed + ttUnsuppressed + ttNoViralLoadResult);
                let percentSuppressed = (ttSuppressed / ttVl) * 100;
                let percentUnsuppressed = (ttUnsuppressed / ttVl) * 100;
                let percentNoVl = (ttNoViralLoadResult / ttVl) * 100;


                //----------------Regimen Line-----------------------
                /*let rgLines = {};
                let clls = [ 'rgb(121,150,164)', 'rgb(76,130,55)', 'rgb(121,150,164)', '#0d47a1', '#93911b', '#607d8b'];
                if(data.regimenLineQs && data.regimenLineQs.length > 0)
                {
                    data.regimenLineQs.forEach(function(rg, i)
                    {
                        rgLines.push({ name: rg.name, y: parseInt(rg.y), color: clls[i] });
                    });
                }*/

                let rgLines = {name: 'No. of Deaths', type: 'column',data: [], yAxis: 1 };
                let rgLineCategories = [];
                let rgLinesSpline = {name: 'Death Rate', data: [], type: 'spline', tooltip: { valueSuffix: '%'} };
                let regSeries = [];
                let regLineT = data.regimenLineQs.reduce((accumulator, object) =>
                {
                    return accumulator + object.y;
                }, 0);

                let clls = [ 'rgb(121,150,164)', 'rgb(76,130,55)', 'rgb(121,150,164)', '#0d47a1', '#93911b', '#607d8b'];

                if(data.regimenLineQs && data.regimenLineQs.length > 0)
                {
                    data.regimenLineQs.forEach(function(rg, i)
                    {
                        rgLineCategories.push(rg.name);
                        rgLines.data.push(parseInt(rg.y));
                        let percT = (rg.y/regLineT) * 100;
                        rgLinesSpline.data.push(parseInt(percT));
                    });
                }

                regSeries.push(rgLines);
                regSeries.push(rgLinesSpline);

                //------------ART Duration---------------------
                let artDurationLt1 = [];
                let artDurationGt2LtEq3 = [];
                let artDurationGt4LtEq5 = [];
                let artDurationGt5 = [];

                if(data.artd && data.artd.length){
                    data.artd.forEach(function(rg, i){
                        artDurationLt1.push({ name: rg.states, y: rg.less_1 });
                        artDurationGt2LtEq3.push({ name: rg.states, y: rg.b1_3 });
                        artDurationGt4LtEq5.push({ name: rg.states, y: rg.b3_5 });
                        artDurationGt5.push({ name: rg.states, y: rg.gt_5 });
                    });
                }

                let artDurationSeries =
                    [
                        { name: '<1 year', data: artDurationLt1 },
                        { name: '1 year - 3 years', data: artDurationGt2LtEq3 },
                        { name: '> 3 years, 5 years', data: artDurationGt4LtEq5 },
                        { name: '> 5 years', data: artDurationGt5 }
                    ];


                //------------Age Disaggregate---------------------
                let ageLt1 = [];
                let age1to4 = [];
                let age4to9 = [];
                let age10to14 = [];
                let age15to19 = [];
                let age20to24 = [];
                let age25to29 = [];
                let age30to34 = [];
                let age35to39 = [];
                let age40to44 = [];
                let age45to49 = [];
                let age50 = [];

                if(data.ageband && data.ageband.length){
                    data.ageband.forEach(function(rg, i){
                        ageLt1.push({ name: rg.states, y: rg.less_1 });
                        age1to4.push({ name: rg.states, y: rg.age_1_to_4 });
                        age4to9.push({ name: rg.states, y: rg.age_4_to_9 });
                        age10to14.push({ name: rg.states, y: rg.age_10_to_14 });
                        age15to19.push({ name: rg.states, y: rg.age_15_to_19 });
                        age20to24.push({ name: rg.states, y: rg.age_20_to_24 });
                        age25to29.push({ name: rg.states, y: rg.age_25_to_29 });
                        age30to34.push({ name: rg.states, y: rg.age_30_to_34 });
                        age35to39.push({ name: rg.states, y: rg.age_35_to_39 });
                        age40to44.push({ name: rg.states, y: rg.age_40_to_44 });
                        age45to49.push({ name: rg.states, y: rg.age_45_to_49  });
                        age50.push({ name: rg.states, y: rg.age_50 });
                    });
                }

                let ageDisaggregateSeries =
                    [
                        { name: '<1', data: ageLt1 },
                        { name: '1 - 4', data: age1to4 },
                        { name: '4 - 9', data: age4to9 },
                        { name: '10 - 14', data: age10to14 },
                        { name: '15 - 19', data: age15to19 },
                        { name: '20 - 24', data: age20to24 },
                        { name: '25 - 29', data: age25to29 },
                        { name: '30 - 34', data: age30to34 },
                        { name: '35 - 39', data: age35to39 },
                        { name: '40 - 44', data: age40to44 },
                        { name: '45 - 49', data: age45to49 },
                        { name: '50 and above', data: age50 }
                    ];

                Highcharts.chart('msRegs',
                    {
                        plotOptions:
                            {
                                column:
                                    {
                                        pointPadding: 0,
                                        borderWidth: 0
                                    }
                            },
                        title:
                            {
                                text: 'Number of Dead Clients by Last Regimen Line'
                            },
                        colors: ['rgb(74,20,140)', '#607d8b', '#64b5f6', '#7b1fa2', '#1565c0', '#3b5998', '#0d47a1', '#607d8b'],

                        xAxis:
                            [{
                                categories: rgLineCategories,
                                crosshair: true
                            }],
                        yAxis:

                            [{ // Primary yAxis
                                labels: {
                                    //format: '{value}°C',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                },
                                title: {
                                    text: 'Regimen',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                }
                            }, { // Secondary yAxis
                                title: {
                                    text: 'Death Rate',
                                    style: {
                                        color: Highcharts.getOptions().colors[0]
                                    }
                                },
                                labels: {
                                    format: '{value} %',
                                    style: {
                                        color: Highcharts.getOptions().colors[0]
                                    }
                                },
                                min: 0,
                                max: 100,
                                opposite: true
                            }],
                        tooltip:
                            {
                                headerFormat: '<span style="font-size:13px; font-weight: bold">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{point.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y} <b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                        series: regSeries //[ { name: 'Deaths by Regimen Line', data: rgLines } ]

                    });

                Highcharts.chart('msAgeDisaggregate',
                    {
                        chart:
                            {
                                type: 'column'
                            },
                        plotOptions:
                            {
                                column:
                                    {
                                        stacking: 'percent',
                                        dataLabels:
                                            {
                                                enabled: true
                                            },
                                        pointPadding: 0,
                                        borderWidth: 0
                                    }
                            },
                        title:
                            {
                                text: 'Number of Dead Clients Disaggregated by Age Band'
                            },
                        colors: ['#ff9e80', '#ff5722', '#ff9800', '#607d8b', '#64b5f6', '#888888', '#1565c0', '#000000'], //, '#bbdefb', '#0d47a1'
                        xAxis:
                            {
                                title:
                                    {
                                        useHTML: true
                                    },
                                type: "category",
                                //categories: protocolNames,
                                labels:
                                    {
                                        useHTML: true,
                                        //rotation:90
                                    }
                            },
                        yAxis:
                            { // Primary yAxis
                                gridLineWidth: 0,
                                minorGridLineWidth: 0,
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                },
                                title: {
                                    text: 'Total Deaths',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                }
                            },
                        tooltip:
                            {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y} <b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                        series: ageDisaggregateSeries
                    });


                Highcharts.chart('msArtDurationState',
                    {
                        chart:
                            {
                                type: 'column'
                            },
                        plotOptions:
                            {
                                column:
                                    {
                                        stacking: 'percent',
                                        dataLabels:
                                            {
                                                enabled: true
                                            },
                                        pointPadding: 0,
                                        borderWidth: 0
                                    }
                            },
                        title:
                            {
                                text: 'Number of Dead Clients Disaggregated by Duration on ART'
                            },
                        colors: ['#ff9e80', '#ff5722', '#ff9800', '#607d8b', '#64b5f6', '#1565c0'], //, '#bbdefb', '#0d47a1'
                        xAxis:
                            {
                                title:
                                    {
                                        useHTML: true
                                    },
                                type: "category",
                                //categories: protocolNames,
                                labels:
                                    {
                                        useHTML: true,
                                        //rotation:90
                                    }
                            },
                        yAxis:
                            { // Primary yAxis
                                gridLineWidth: 0,
                                minorGridLineWidth: 0,
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                },
                                title: {
                                    text: 'Total Deaths',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                }
                            },
                        tooltip:
                            {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y} <b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                        series: artDurationSeries
                    });

                Highcharts.chart('msVlPie',
                    {
                        chart:
                            {
                                type: 'pie'
                            },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions:
                            {
                                pie:
                                    {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: true,
                                            format: '<b>{point.name}</b>:<br>{point.y} ({point.percentage:.1f}%)'
                                        },
                                        showInLegend: true
                                    }
                            },
                        title:
                            {
                                text: 'Dead Clients Disaggregated by Last Viral Load Status',
                                style: {
                                    fontSize: '14px'
                                }
                            },
                        colors: ['#3b5998', '#0d47a1', '#607d8b', '#64b5f6', '#1565c0'],
                        tooltip:
                            {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y} ({point.percentage:.1f}%) <b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                        series:
                            [
                                {
                                    name: 'Death rates by Viral Load Status',
                                    colorByPoint: true,
                                    innerSize: '60%',
                                    data:
                                        [{ name: 'Suppressed', y: ttSuppressed },
                                            { name: 'Unsuppressed', y: ttUnsuppressed },
                                            { name: 'No Viral Load', y: ttNoViralLoadResult }]
                                }
                            ]
                    });

                Highcharts.chart('msSexPie',
                    {
                        chart:
                            {
                                type: 'pie'
                            },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions:
                            {
                                pie:
                                    {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: true,
                                            format: '<b>{point.name}</b>:<br>{point.y} ({point.percentage:.1f}%)'
                                        },
                                        showInLegend: true
                                    }
                            },
                        title:
                            {
                                text: 'Dead Clients Disaggregated by Sex',
                                style: {
                                    fontSize: '14px'
                                }
                            },
                        colors: ['#4a148c', '#7b1fa2'],
                        tooltip:
                            {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y} ({point.percentage:.1f}%) <b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                        series:
                            [
                                {
                                    name: 'Death rates by Sex',
                                    colorByPoint: true,
                                    innerSize: '60%',
                                    data: [{
                                        name: 'Males',
                                        y: ttMales
                                    }, {
                                        name: 'Females',
                                        y: ttFemales
                                    }]
                                }
                            ]
                    });
            }

            Array.prototype.sumX = function (prop1, prop2, prop3, prop4)
            {
                var total = 0
                for ( var i = 0, _len = this.length; i < _len; i++ )
                {
                    total += (this[i][prop1] + this[i][prop2] + this[i][prop3] + this[i][prop4]);
                }
                return total
            }

            function buildMsDrilldownChart(data)
            {
                let years = [];
                let drillDowns = [];
                let trends = [];
                let drilldownTrends = [];

                let totalYears = data.sumX('q1', 'q2', 'q3', 'q4');

                data.sort((a, b) => a.year - b.year);

                let colors = ['#B7B8BC', '#6bafa7', '#93911b', '#33691e', '#85D1C1', '#CAC99A', '#494FA3', '#ED745F', '#615D8B', '#959335', '#CAC99A', '#d35e13',
                    '#dbc79d', '#003056', '#7ea8ad', '#acc37e', '#0a5d66', '#f7a800', '#b8a999', '#d2ddbb', '#706259'];

                data.forEach(function(qm, i)
                {
                    let q1D = [];
                    let q2D = [];
                    let q3D = [];
                    let q4D = [];
                    let qs = [];

                    q1D.push({ name: 'January', y: qm.january, color: colors[i] });
                    q1D.push({ name: 'February', y: qm.february, color: colors[i+1]});
                    q1D.push({ name: 'March', y: qm.march, color: colors[i+2] });
                    q2D.push({ name: 'April', y: qm.april, color: colors[i+3] });
                    q2D.push({ name: 'May', y: qm.may, color: colors[i+4] });
                    q2D.push({ name: 'June', y: qm.june, color: colors[i+5]});
                    q3D.push({ name: 'July', y: qm.july, color: colors[i+6] });
                    q3D.push({ name: 'August', y: qm.august, color: colors[i+7] });
                    q3D.push({ name: 'September', y: qm.september, color: colors[i+8] });
                    q4D.push({ name: 'October', y: qm.october, color: colors[i+9] });
                    q4D.push({ name: 'November', y: qm.november, color: colors[i+10] });
                    q4D.push({ name: 'December', y: qm.december, color: colors[i+11] });


                    //--------------------------------------------------------------------------------------------

                    //--- Q1 --------
                    let yrJan = (qm.january/qm.q1) * 100;
                    let yrFeb = (qm.february/qm.q1) * 100;
                    let yrMarch = (qm.january/qm.q1) * 100;

                    let q1Rates = [];
                    q1Rates.push({ name: 'January', y: parseInt(yrJan) });
                    q1Rates.push({ name: 'February', y: parseInt(yrFeb) });
                    q1Rates.push({ name: 'March', y: parseInt(yrMarch) });

                    //--- Q2 --------
                    let yrApril = (qm.april/qm.q2) * 100;
                    let yrMay = ( qm.may/qm.q2) * 100;
                    let yrJune = ( qm.june/qm.q2) * 100;

                    let q2Rates = [];
                    q2Rates.push({ name: 'April', y: parseInt(yrApril) });
                    q2Rates.push({ name: 'May', y: parseInt(yrMay) });
                    q2Rates.push({ name: 'June', y: parseInt(yrJune) });

                    //--- Q3 --------
                    let yrJuly = (qm.july /qm.q3 ) * 100;
                    let yrAug = ( qm.august/qm.q3) * 100;
                    let yrSept = ( qm.september/qm.q3) * 100;

                    let q3Rates = [];
                    q3Rates.push({ name: 'July', y: parseInt(yrJuly) });
                    q3Rates.push({ name: 'August', y: parseInt(yrAug) });
                    q3Rates.push({ name: 'September', y: parseInt(yrSept) });

                    //--- Q4 --------
                    let yrOct = (qm.october/qm.q4 ) * 100;
                    let yrNov = (qm.november/qm.q4 ) * 100;
                    let yrDec = (qm.december/qm.q4 ) * 100;

                    let q4Rates = [];
                    q4Rates.push({ name: 'October', y: parseInt(yrOct) });
                    q4Rates.push({ name: 'November', y: parseInt(yrNov) });
                    q4Rates.push({ name: 'December', y: parseInt(yrDec) });

                    //----------------------------------

                    let tDTT = qm.q1 + qm.q2 + qm.q3 + qm.q4;
                    let q1rate = (qm.q1 / tDTT) * 100;
                    let q2rate = (qm.q2 / tDTT) * 100;
                    let q3rate = (qm.q3 / tDTT) * 100;
                    let q4rate = (qm.q4 / tDTT) * 100;

                    drillDowns.push({ name: 'Q1', data: q1D, id: qm.year + 'Q1', type: 'column', qs: 'Q1' });
                    drilldownTrends.push({ name: 'Death Rate', data: q1Rates, type: 'spline', tooltip: { valueSuffix: '%'}, id: qm.year + 'Q1-Rate', qr: 'Q1', yAxis: 1 });

                    drillDowns.push({ name: 'Q2', data: q2D, id: qm.year + 'Q2', type: 'column', qs: 'Q2' });
                    drilldownTrends.push({ name: 'Death Rate', data: q2Rates, type: 'spline', tooltip: { valueSuffix: '%'}, id: qm.year + 'Q2-Rate', qr: 'Q2', yAxis: 1 });

                    drillDowns.push({ name: 'Q3', data: q3D, id: qm.year + 'Q3', type: 'column', qs: 'Q3' });
                    drilldownTrends.push({ name: 'Death Rate', data: q3Rates, type: 'spline', tooltip: { valueSuffix: '%'}, id: qm.year + 'Q3-Rate', qr: 'Q3', yAxis: 1 });

                    drillDowns.push({ name: 'Q4', data: q4D, id: qm.year + 'Q4', type: 'column', qs: 'Q4' });
                    drilldownTrends.push({ name: 'Death Rate', data: q4Rates, type: 'spline', tooltip: { valueSuffix: '%'}, id: qm.year + 'Q4-Rate', qr: 'Q4', yAxis: 1  });

                    qs.push({ name: 'Q1', y: qm.q1, drilldown: qm.year + 'Q1', color: colors[i] });
                    qs.push({ name: 'Q2', y: qm.q2, drilldown: qm.year + 'Q2', color: colors[i + 1] });
                    qs.push({ name: 'Q3', y: qm.q3, drilldown: qm.year + 'Q3' , color: colors[i + 2]});
                    qs.push({ name: 'Q4', y: qm.q4, drilldown: qm.year + 'Q4' , color: colors[i + 3]});


                    let qRates = [];
                    qRates.push({ name: 'Q1', y: parseInt(q1rate) });
                    qRates.push({ name: 'Q2', y: parseInt(q2rate) });
                    qRates.push({ name: 'Q3', y: parseInt(q3rate) });
                    qRates.push({ name: 'Q4', y: parseInt(q4rate) });

                    drillDowns.push({ name: qm.year.toString(), data: qs, type: 'column', id: qm.year + 'Qs', qs: qm.year.toString() });

                    drilldownTrends.push({ name: 'Death Rate', data: qRates, type: 'spline', tooltip: { valueSuffix: '%'}, id: qm.year + 'QR', qr: qm.year.toString(), yAxis: 1 });

                    years.push({ name: qm.year.toString(), y: tDTT, drilldown: qm.year + 'Qs', color: colors[i]});

                    let yRate = ((tDTT / totalYears) * 100);
                    trends.push({ name: qm.year.toString(), y: parseInt(yRate)});

                });

                let dwQ = [{name: 'Death Toll', data: years, type: 'column'},
                    { type: 'spline', tooltip: { valueSuffix: '%' }, name: 'Death rate', data: trends, yAxis: 1, marker: { lineWidth: 2, lineColor: 'orange', fillColor: 'orange' } }
                ];

                var chart = Highcharts.chart('msDrillDown',
                    {
                        chart:
                            {
                                events:
                                    {
                                        drilldown: function(e)
                                        {
                                            var chart = this;
                                            let dt = drillDowns.filter(function (y)
                                            {
                                                return y.qs === e.point.name;
                                            });
                                            let columnSeries = [];
                                            if(dt.length > 0)
                                            {
                                                columnSeries.push(dt[0]);
                                            }

                                            let qr = drilldownTrends.filter(function(y)
                                            {
                                                return y.qr === e.point.name;
                                            });
                                            let lineSeries = [];
                                            if(qr.length > 0)
                                            {
                                                lineSeries.push(qr[0]);
                                            }
                                            if(columnSeries.length > 0 && lineSeries.length > 0)
                                            {
                                                series = [columnSeries[0], lineSeries[0]];
                                                chart.addSingleSeriesAsDrilldown(e.point, series[0]);
                                                chart.addSingleSeriesAsDrilldown(e.point, series[1]);
                                                chart.applyDrilldown();
                                            }
                                        }
                                    }
                            },
                        title: {
                            text: 'Total number of death'
                        },
                        xAxis:
                            {
                                title:
                                    {
                                        useHTML: true
                                    },
                                type: "category",
                                //categories: protocolNames,
                                labels:
                                    {
                                        useHTML: true,
                                        //rotation:90
                                    }
                            },
                        yAxis:
                            [{ // Primary yAxis
                                labels: {
                                    //format: '{value}°C',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                },
                                title: {
                                    text: 'Death Toll',
                                    style: {
                                        color: Highcharts.getOptions().colors[1]
                                    }
                                }
                            }, { // Secondary yAxis
                                title: {
                                    text: 'Death Rate',
                                    style: {
                                        color: Highcharts.getOptions().colors[0]
                                    }
                                },
                                labels: {
                                    format: '{value} %',
                                    style: {
                                        color: Highcharts.getOptions().colors[0]
                                    }
                                },
                                min: 0,
                                max: 100,
                                opposite: true
                            }],
                        tooltip:
                            {
                                headerFormat: '<span style="font-size:13px; font-weight: bold">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{point.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y} <b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                        labels: {
                            items: [{
                                html: '',
                                style: {
                                    left: '50px',
                                    top: '18px',
                                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                                }
                            }]
                        },
                        series: dwQ,
                        drilldown: {
                            series: drillDowns
                        },
                        exporting: {
                            enabled: true
                        }
                    });
            }


            function groupArrayByKey(array, key)
            {
                return array.reduce((hash, obj) =>
                {
                    if(obj[key] === undefined) return hash;
                    return Object.assign(hash, { [obj[key]]:( hash[obj[key]] || [] ).concat(obj)})
                }, {});
            }
        </script>
    @endsection
</x-app-layout>
