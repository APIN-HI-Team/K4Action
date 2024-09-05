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
            <div class="grid grid-cols-12 gap-2">

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
                                <option value="dsd" selected></option>
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

                        <div class="col-span-3 sm:col-span-3 mt-5 text-left">
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
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-indigo-700">
                                <p class="text-xl"><i class="uil uil-analytics"></i> TX_CURR</p>
                                <p class="tx_curr" >...</p>
                            </div>
                        </div>
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-green-600">
                                <p class="text-xl"><i class="uil uil-analytics"></i> CAPTURED</p>
                                <p class="tx_captured">...</p>
                            </div>
                        </div>
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-black">
                                <p class="text-xl"><i class="uil uil-analytics"></i> COVERAGE (%)</p>
                                <p class="tx_coverage">...</p>
                            </div>
                        </div>
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-darkGrayishBlue">
                                <p class="text-xl"><i class="uil uil-analytics"></i> VALID</p>
                                <p class="tx_valid">...</p>
                            </div>
                        </div>
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-red-300">
                                <p class="text-xl"><i class="uil uil-analytics"></i> INVALID</p>
                                <p class="tx_invalid">...</p>
                            </div>
                        </div>
                        <div class="col-span-1 bg-white drop-shadow-md rounded p-4 text-center">
                            <div class="text-red-700">
                                <p class="text-xl"><i class="uil uil-analytics"></i> YET TO CAPTURE</p>
                                <p class="tx_not_captured">...</p>
                            </div>
                        </div>
                    </div>

                <!-- Charts Starts Here -->
                <div class="grid sm:grid-cols-1 lg:grid-cols-2 gap-2 mt-6">
                    <div class="col-span-1 bg-white drop-shadow-md relative">
                        <div class="box-heading ml-4 mt-2 mb-6 font-bold text-sm">
                            DSD Cascade
                        </div>
                        <div class="absolute top-0 right-0 mt-1 data-te-dropdown-ref" >
                            <div>
                                <div class="relative" data-te-dropdown-position="dropstart">
                                    <a class="pt-1 text-xs mr-2"
                                       href="#"
                                       type="button"
                                       id="dropdownMenuButton2"
                                       data-te-dropdown-toggle-ref
                                       aria-expanded="false"
                                       data-te-ripple-init
                                       data-te-ripple-color="light">
                                        <i class="uil uil-bars"></i>
                                    </a>
                                    <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden
                        rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg
                        dark:bg-neutral-700 [&[data-te-dropdown-show]]:block"
                                        aria-labelledby="dropdownMenuButton2" data-te-dropdown-menu-ref>
                                        <li>
                                            <a class="block w-full whitespace-nowrap py-2 px-4 text-sm font-normal bg-white text-neutral-700
                                hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none
                                disabled:bg-transparent disabled:text-neutral-400" href="#" onclick="downloadPng('sexChart')"
                                               data-te-dropdown-item-ref>Download PNG
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="box-content">
                            <div class="chart" id="DSDbyCascade">
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 bg-white drop-shadow-md relative">
                        <div class="box-heading ml-4 mt-2 mb-6 font-bold text-sm">
                            Total Clients on DSD
                        </div>
                        <div class="absolute top-0 right-0 mt-1 data-te-dropdown-ref" >
                            <div>
                                <div class="relative" data-te-dropdown-position="dropend">
                                    <a class="pt-1 text-xs mr-2"
                                       href="#"
                                       type="button"
                                       id="dropdownMenuButton2"
                                       data-te-dropdown-toggle-ref
                                       aria-expanded="false"
                                       data-te-ripple-init
                                       data-te-ripple-color="light">
                                        <i class="uil uil-bars"></i>
                                    </a>
                                    <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg
                        border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:bg-neutral-700
                        [&[data-te-dropdown-show]]:block" aria-labelledby="dropdownMenuButton2" data-te-dropdown-menu-ref>
                                        <li>
                                            <a class="block w-full whitespace-nowrap py-2 px-4 text-sm font-normal
                                bg-white text-neutral-700 hover:bg-neutral-100 active:text-neutral-800
                                active:no-underline disabled:pointer-events-none disabled:bg-transparent
                                disabled:text-neutral-400" href="#" onclick="downloadPng('ageChart')"
                                               data-te-dropdown-item-ref>Download PNG</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="box-content">
                            <div class="chart" id="totaldsd">
                            </div>
                        </div>
                    </div>
                </div>
                    <!---Charts Ends Here -->
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
                    url: "{{route('dsd.filter')}}",
                    data: formData,
                    dataType: 'json',
                    encode: true,
                }).done(function(data) {
                    var response = data.dsd;
                    console.log(data);


                    if (selectReports.val() === 'dsd') {
                        console.log(data.dsd_analysis);
                        build_dsd_charts(data);
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
            function build_dsd_charts(data) {
                build_bar_charts_commodity('DSDbyCascade','','Number', data.dsd_analysis.DSD_by_Cascade,null,null);
            }


        </script>

    @endsection
</x-app-layout>
