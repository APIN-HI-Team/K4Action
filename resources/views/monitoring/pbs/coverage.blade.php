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

<div class="grid grid-cols-1 mt-6">
    <div class="col-span-1 bg-white drop-shadow-md relative">
        <div class="box-heading ml-2 mt-2 mb-4 font-bold text-sm">
            TX_CURR Biometrics Coverage
        </div>
        <div class="box-content">
            <div class="chart" id="totalpbs">
            </div>
        </div>
    </div>
</div>
