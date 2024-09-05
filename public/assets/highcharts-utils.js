Highcharts.setOptions({
    lang: {
        decimalPoint: '.',
        thousandsSep: ','
    }
});

var hasCalledPointsWrap = false;

function BuildDonut(id, title, data_array, colors) {

    if (colors === undefined || colors === null) {
        colors = ['#3A356E', '#A3A8E2', '#9d9b03', '#08bf7A78020f'];
    }

    var chart = new Highcharts.Chart({
        chart: {
            renderTo: id,
            type: 'pie'
        },
        title: {
            text: '',
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            pie: {
                //colors: pieColors,
                shadow: false,
                dataLabels: {
                    format: '{point.y:,.0f}<br /> ({point.percentage:.1f} %)'
                }
            }
        },
        tooltip: {
            pointFormat: '{point.y} ({point.percentage:.1f}%)'
        },
        series: [{
            data: data_array,
            size: '80%',
            innerSize: '60%',
            showInLegend: true,

        }],
        credits: {
            enabled: false
        },
        colors: colors,
        exporting: {enabled: false},
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    chart: {
                        height: 300
                    },
                    subtitle: {
                        text: null
                    },
                    navigator: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    }
                }
            }]
        }
    });
}

function Build_Pos_Neg_Chart(id, title, categories, series_data, max) {

    Highcharts.chart(id, {
        chart: {
            type: 'bar',
            height: 300
        },
        title: {
            text: ''
        },
        xAxis: [{
            categories: categories,
            reversed: true,
            labels: {
                step: 1
            },
            title: {
                text: "Age Groups"
            },
        }, { // mirror axis on right side
            opposite: true,
            reversed: true,
            categories: categories,
            linkedTo: 0,
            labels: {
                step: 1
            },
            title: {
                text: "Age Groups"
            },
        }],
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return Math.abs(this.value);
                }
            },
            title: {
                text: "No. of Persons"
            },
            max: (max + percentage(max, 10)),
            min: -(max + percentage(max, 10))
        },

        plotOptions: {
            series: {
                stacking: 'normal',
                grouping: false,
                pointWidth: 16
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + ', age ' + this.point.category + '</b><br/>' +
                    'Total: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
            }
        },

        exporting: { enabled: false },

        series: series_data

    });
}

function Build_Pos_Neg_Chart1(id, title, categories, series_data, max) {

    Highcharts.chart(id, {
        chart: {
            type: 'bar',
            height: 500
        },
        title: {
            text: 'Number of Dead Clients Disaggregated by Age and Sex'
        },
        colors: ['#000000','#000000'],
        xAxis: [{
            categories: categories,
            reversed: false,
            labels: {
                step: 1,
            },

            title: {
                text: "Age (Male)"
            },
        }, { // mirror axis on right side
            opposite: true,
            reversed: false,
            categories: categories,
            linkedTo: 0,
            labels: {
                step: 1
            },
            title: {
                text: "Age (Female)"
            },
        }],
        yAxis: {
            gridLineWidth: 0,
            minorGridLineWidth: 0,
            labels: {
                formatter: function () {
                    return Math.abs(this.value);
                },
            },
            title: {
                text: "No. of Deaths",
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            max: (max + percentage(max, 10)),
            min: -(max + percentage(max, 10))
        },

        plotOptions: {
            series: {
                stacking: 'normal',
                grouping: false,
                pointWidth: 8
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + ', age ' + this.point.category + '</b><br/>' +
                    'Total: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
            }
        },

        /*tooltip:
            {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>' + Math.abs(this.point.y) + '<b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },*/

        exporting: { enabled: true },

        series: series_data

    });
}

function percentage(num, per) {
    var result = num * (per / 100);
    return Math.round(result);
}

function plotMapAdvanced(container_id, selectedIdicator, state_chart_container_id, xaxis_categories, statedata, state_detail_title, series_name, title, show_subtitle, legend_title, legendTransformFunc = null, reverseLegendStopsColours = false) {

    $("#" + state_chart_container_id).hide(); //clear the state chart already shown.

    var tooltipText = null;
    if (selectedIdicator !== "tx_curr") {
        tooltipText = '<span style="font-size: 10px">(Click for details)</span>';
    }

    var subTitle = null;
    if (selectedIdicator !== "tx_curr") {
        subTitle =
            'Click states to view details. <br /> <small><em> (Shift + Click on map to compare different states)</em></small>';
    }

    if (selectedIdicator == "tx_new") {
        state_detail_title = 'Monthly Trend of Patients Newly Enrolled on ART';

    } else if (selectedIdicator == "tx_pvls") {
        state_detail_title = 'Monthly Trend of Patients Virally Suppressed';

    }


    statedata = statedata.filter(s => s.value > 0);

    var boundaries = buildMapLegends(statedata);
    var hasThousand = statedata.some(x => Math.abs(x.value) >= 1000);

    var legendTitle = legend_title + (hasThousand ? " " : ""); //(thousands)

    if (!legendTransformFunc) {
        legendTransformFunc = ht => ht ? "rotate(270deg) translateX(-195px) translateY(-27px)" : "rotate(270deg) translateX(-160px) translateY(-27px)";
    }

    var stops = null;

    var mapData = Highcharts.geojson(Highcharts.maps['countries/ng/ng-all']);

    // Initiate the map chart
    var mapChart = Highcharts.mapChart(container_id, {

        title: {
            text: ''
        },

        // colors: ['#ffebee', '#ffcdd2', '#ef9a9a',
        //     '#e57373', '#ef5350', '#e53935', '#b71c1c'],
        colors: ['#DCDCDC', '#B2D8C4', '#398F66', '#437268', '#026242', '#14352A'],

        subtitle: {
            text: show_subtitle ? subTitle : ''
        },
        mapNavigation: {
            enabled: true,
            enableMouseWheelZoom: false,
            buttonOptions: {
                verticalAlign: 'bottom'
            }
        },

        legend: {
            title: {
                text: legendTitle,
                style: {
                    fontSize: '12px',
                }
            },
            align: 'right',
            verticalAlign: 'bottom',
            floating: true,
            layout: 'vertical',
            valueDecimals: 0,
            symbolRadius: 0,
            symbolHeight: 14
        },

        colorAxis: {
            //stops: stops,
            dataClassColor: 'category',
            labels: {
                formatter: function () {
                    return hasThousand ? parseInt(this.value) / 1000 : this.value;
                }
            },
            min: 0,
            dataClasses: boundaries
        },

        tooltip: {
            footerFormat: tooltipText
        },

        xAxis: {
            categories: xaxis_categories,
            title: {
                text: title,
                enabled: false
            }
        },

        exporting: { enabled: false },

        series: [{
            name: series_name, //'Number of Patients',
            data: statedata,
            mapData: mapData,
            joinBy: ['hc-key', 'code3'],
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '{point.properties.name}'
            },
            states: {
                select: {
                    color: '#a4edba',
                    borderColor: 'black',
                    dashStyle: 'shortdot'
                }
            }
        }]
    });

    mapChart.state_chart_container_id = state_chart_container_id;
    mapChart.state_detail_title = state_detail_title;
    mapChart.xaxis_categories = xaxis_categories;

    if (!hasCalledPointsWrap) {
        hasCalledPointsWrap = true;

        // Wrap point.select to get to the total selected points
        Highcharts.wrap(Highcharts.Point.prototype, 'select', function (proceed) {

            proceed.apply(this, Array.prototype.slice.call(arguments, 1));

            var cht = this.series.chart;
            var points = cht.getSelectedPoints();
            if (points.length) {

                //$('#info .subheader').html('<small><em>Shift + Click on map to compare different states</em></small>');
                var state_chart_container_id = cht.state_chart_container_id;
                var state_detail_title = cht.state_detail_title;
                var xaxis_categories = cht.xaxis_categories;
                var stateChart;

                if (cht.state_chart_index === undefined) {
                    stateChart = Highcharts.chart(state_chart_container_id, {
                        chart: {
                            height: 400,
                            spacingLeft: 0
                        },
                        //credits: {
                        //    enabled: false
                        //},
                        title: {
                            text: state_detail_title,
                            style: {
                                fontSize: '12px'
                            }
                        },
                        subtitle: {
                            text: subTitle
                        },
                        legend: {
                            enabled: true
                        },

                        xAxis: {
                            tickPixelInterval: 50,
                            categories: xaxis_categories,
                            crosshair: true
                        },
                        yAxis: {
                            title: {
                                text: "Number of Patients"
                            },
                            opposite: true
                        },
                        tooltip: {
                            split: true
                        },
                        plotOptions: {
                            series: {
                                animation: {
                                    duration: 500
                                },
                                marker: {
                                    enabled: false
                                },
                                threshold: 0
                            }
                        }
                    });

                    cht.state_chart_index = stateChart.index;


                } else {
                    stateChart = Highcharts.charts[cht.state_chart_index];
                }

                $.each(points, function (i) {
                    // Update
                    if (stateChart.series[i]) {
                        var seriesObj = stateChart.series[i].update({
                            name: this.name,
                            data: this.monthData, //statedata.find(x => x.code3 === this.code3).monthData,
                            type: points.length > 1 ? 'line' : 'area'
                        }, false);
                    } else {
                        var newSeriesObj = stateChart.addSeries({
                            name: this.name,
                            data: this.monthData, //statedata.find(x => x.code3 === this.code3).monthData,
                            type: points.length > 1 ? 'line' : 'area'
                        }, false);

                        stateChart.series[i].update({
                            //  color: newSeriesObj.color["stops"][0][1] //THIS IS AN EDUCATED HACK!!! to force the colors to be set again.
                        }, false);
                    }
                });

                while (stateChart.series.length > points.length) {
                    stateChart.series[stateChart.series.length - 1].remove(false);
                }

                stateChart.redraw();

                $("#" + state_chart_container_id).show();
            }
        });
    }
}

function build_Line_chart(id, title, yaxistitle, xaxisCategory, data, average_value, series_name, xaxistitle) {
    var colors = get_color_shades(2);

    if (!data || data.length === 0) {
        console.error('Data array is null or empty.');
        return;
    }

    var hasThousand = data.some(x => Math.abs(x) >= 1000);

    if (hasThousand) {
        yaxistitle = yaxistitle + " (thousands)";
    }

    Highcharts.chart(id, {
        chart: {
            type: 'line'
        },
        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },
        xAxis: {
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxistitle,
                enabled: false
            }

        },
        yAxis: {
            min: 0,
            title: {
                text: yaxistitle
            },
            plotLines: [{
                color: '#F2BF48',
                value: average_value,
                width: 1,
                zIndex: 2,
                label: {
                    text: 'avg',
                    align: 'right',
                    y: 15,
                    x: 0,
                    style: {
                        color: 'red',
                        fontWeight: 'normal'
                    }
                }
            }],
            labels: {
                formatter: function () {
                    return hasThousand ? parseInt(this.value) / 1000 : this.value;
                }
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:, 1f}</b>'
        },
        colors: ['#E85137', '#053E2B'],
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:, 1f}'
                }
            }
        },

        exporting: { enabled: false },

        series: [{
            name: series_name,
            data: data
        }]
    });
}

function build_trend_chart(container_id, title, y_title, x_title, series_data) {
    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],

        yAxis: {
            title: {
                text: y_title,
                rotation: 270,
            },
            labels: {
                format: '{value:,.0f}',
            },
            min: 0
        },
        //legend: {
        //    layout: 'vertical',
        //    align: 'right',
        //    verticalAlign: 'middle'
        //},

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },

        series: series_data
    });
}
function build_dummytrend_chart(container_id, title, y_title, x_title, series_data, colors) {
    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],

        yAxis: {
            title: {
                text: y_title,
                rotation: 270,
            },
            labels: {
                format: '{value:,.0f}',
            },
            min: 0
        },
        //legend: {
        //    layout: 'vertical',
        //    align: 'right',
        //    verticalAlign: 'middle'
        //},

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        colors: colors,
        series: series_data
    });
}

function build_dqa_trend_chart_drilldown(container_id, title, y_title, x_title, series_data, drilldown_data) {

    if ((drilldown_data || []).length > 0) {
        drilldown_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }


    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            // crosshair: true
        }],

        yAxis: {
            title: {
                text: y_title,
                rotation: 270,
            },
            labels: {
                //format: '{value:,.0f}',
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
            min: 0
        },
        //legend: {
        //    layout: 'vertical',
        //    align: 'right',
        //    verticalAlign: 'middle'
        //},

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}% </b> '
        },
        exporting: { enabled: false },


        series: series_data,
        drilldown: {
            series: drilldown_data
        }
    });
}


function build_trend_chart_drilldown(container_id, title, y_title, x_title, series_data, drilldown_data, colors) {

    if ((drilldown_data || []).length > 0) {
        drilldown_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }


    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            // crosshair: true
        }],

        yAxis: {
            title: {
                text: y_title,
                rotation: 270,
            },
            labels: {
                //format: '{value:,.0f}',
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
            min: 0
        },
        //legend: {
        //    layout: 'vertical',
        //    align: 'right',
        //    verticalAlign: 'middle'
        //},

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y} </b> '
        },
        exporting: { enabled: false },


        colors: colors,
        series: series_data,
        drilldown: {
            series: drilldown_data
        }
    });
}

function build_trend_chart_comparison(container_id, title, y_title, x_title, series_data, drilldown_data) {

    if ((drilldown_data || []).length > 0) {
        drilldown_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }


    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            // crosshair: true
        }],

        yAxis: [{
            title: {
                text: y_title,
                rotation: 270,
            },
            labels: {
                format: '{value:,.0f}',
            },
            min: 0
        }, {
            min: 0,
            title: {
                text: 'Proportion %'
            },
            opposite: true
        }],
        //legend: {
        //    layout: 'vertical',
        //    align: 'right',
        //    verticalAlign: 'middle'
        //},

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y} </b> '
        },
        exporting: { enabled: false },

        series: series_data,
        drilldown: {
            series: drilldown_data
        }
    });
}

function resizeChart(chartContainerElement) {
    try {
        $container = $(chartContainerElement); // context container
        chart = $container.highcharts(); // cast from JQuery to highcharts obj
        if (chart)
            chart.setSize($container.width(), chart.chartHeight, doAnimation = true); // adjust chart size with animation transition
    } catch (err) {
        // do nothing
    }
}

function build_stacked_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: null
        },
        exporting: {
            enabled: false
        },


        xAxis: {
            categories: xaxisCategory,
            title: {
                text: xaxistitle,
                enabled: false
            }
        },

        yAxis: [
            {
                min: 0,
                title: {
                    text: yAxistitle
                }
            }
        ],

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total Diagnosed: ' + this.point.stackTotal;
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: series_data
    });
}

function build_drilldown_bar_chart(id, title, yaxistitle, principal_data, drill_down_data, addSigntovalue = "", percent_data, percent_data_name, y2_title, useLine = true) {

    var colors = get_color_shades(2);

    var series = [];

    var hasThousand = false;

    if ((drill_down_data || []).length > 0) {
        drill_down_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }

    if (principal_data.length > 0) {
        series.push({
            name: "States",
            colorByPoint: false,
            data: principal_data,
            yAxis: 0,
            events: {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            }
        });

        hasThousand = hasThousand || principal_data.some(x => Math.abs(x.y) >= 1000);
    }

    if (hasThousand) {
        //yaxistitle = yaxistitle + " (thousands)";
        yaxistitle = yaxistitle;
    }

    var yAxis = [{
        title: {
            text: yaxistitle
        },
        labels: {
            formatter: function () {
                //return hasThousand ? parseInt(this.value) / 1000 : this.value;
                return Highcharts.numberFormat(this.value, 0, '', ' ');
            }
        }
    }];

    if ((percent_data || []).length > 0) {
        var percentSeries = {
            name: percent_data_name,
            type: 'spline',
            data: percent_data,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 5
            }
        };

        if (!useLine) {
            percentSeries.lineWidth = 0;
            percentSeries.states = {
                hover: {
                    lineWidth: 0,
                    lineWidthPlus: 0,
                    marker: {
                        radius: 5
                    }
                }
            };
        }

        series.push(percentSeries);

        //add its axis
        yAxis.push({
            labels: {
                format: '{value}%'
            },
            title: {
                text: y2_title
            },
            opposite: true,
            //max: 100,
            //min: -100
        });
    }

    Highcharts.chart(id, {

        chart: {
            type: 'column'
        },
        title: {
            text: null,
            style: {
                fontSize: '12px'
            }
        },
        subtitle: {
            text: 'Click the columns to drill down'
        },
        xAxis: {
            type: 'category',
            title: {
                text: "States",
                enabled: false
            }
        },
        yAxis: yAxis,
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                colors: ['#494FA3', '#494FA3'],
                //colors: ['#F2BF48', '#F2BF48'],
                dataLabels: {
                    color: '#444'
                },
                nullColor: '#444444'
                //borderWidth: 0,
                //dataLabels: {
                //    enabled: true,
                //    format: '{point.y:, 1f} ' + addSigntovalue
                //    //format: '{point.y:.1f}%'
                //}
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}' + addSigntovalue + '</b> '
        },
        exporting: { enabled: false },
        series: series,
        colors: ['#F88944'],
        drilldown: {
            series: drill_down_data
        }
    });
}

function get_color_shades(start) {
    var colors = [],
        base = Highcharts.getOptions().colors[start],
        i;

    for (i = 0; i < 100; i += 1) {
        colors.push(Highcharts.Color(base).brighten((i) / 100).get());
    }
    return colors;
}

function build_stacked_bar(container_id, title, y_title, formedData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis:

            {
                min: 0,
                max: 100,
                title: {
                    text: y_title
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }
            }
        ,
        tooltip: {
            shared: true,
            useHTML: true,
            formatter: function () {
                var s = '<b></b>';
                $.each(this.points, function (i, point) {

                    s += '<b>' + point.series.name + '</b><br/>' + point.point.name + '<br/> Percent: ' + point.point.y + '&nbsp; Numerator : ' + point.point.numerator + '&nbsp;Denominator: ' + point.point.denominator + '<br/>'
                })
                return s;
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal',
            }
        },
        colors: colors,
        series: formedData,
        exporting: { enabled: false },
    });


};

function build_bar_chart_age_sex_dual_axis(container_id, title, y1_title, y2_title, xaxisCategory, parent_data, parent_data_name, child_data, child_data_name,

                                           percent_data, percent_data_name, percent_data2, percent_data_name2, useLine = true, xaxisTitle, height) {
    //filter unneeded zero data

    var idxsToDelete = [];
    parent_data = (parent_data || []).filter((n, i) => {
        if (n > 0) {
            return true;
        }

        idxsToDelete.push(i);
        return false;
    });

    if (idxsToDelete.length > 0) {
        xaxisCategory = (xaxisCategory || []).filter((n, i) => !idxsToDelete.includes(i));
        child_data = (child_data || []).filter((n, i) => !idxsToDelete.includes(i));
        percent_data = (percent_data || []).filter((n, i) => !idxsToDelete.includes(i));
        percent_data2 = (percent_data2 || []).filter((n, i) => !idxsToDelete.includes(i));

    }

    var series = [];

    var hasThousand = false;

    if (parent_data.length > 0) {
        series.push({
            name: parent_data_name,
            type: 'column',
            data: parent_data
        });

        hasThousand = hasThousand || parent_data.some(x => Math.abs(x) >= 1000);
    }

    if ((child_data || []).length > 0) {
        series.push({
            name: child_data_name,
            type: 'column',
            data: child_data
        });

        hasThousand = hasThousand || child_data.some(x => Math.abs(x) >= 1000);
    }

    var yAxis = [{ // Secondary yAxis
        title: {
            //text: y1_title + (hasThousand ? " (thousands)" : ""),
            text: y1_title,
            rotation: 270
        },
        labels: {
            formatter: function () {
                // return hasThousand ? parseInt(this.value) / 1000 : this.value;
                return Highcharts.numberFormat(this.value, 0, '', ' ');
            }
            //format: '{value}'
        },
        max: Math.max.apply(Math, parent_data),
        min: 0
    }];

    if ((percent_data || []).length > 0) {
        var percentSeries = {
            name: percent_data_name,
            type: 'scatter', //useLine ? 'spline' : 'scatter',
            data: percent_data,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 5
            }
        };

        series.push(percentSeries);

        //add its axis
        yAxis.push({ // Primary yAxis
            labels: {
                format: '{value}%'
            },
            title: {
                text: y2_title
            },
            opposite: true,
            max: 100,
            min: 0
        });
    }

    if ((percent_data2 || []).length > 0) {
        var percentSeries2 = {
            name: percent_data_name2,
            type: 'scatter', //useLine ? 'spline' : 'scatter',
            data: percent_data2,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 10
            }
        };


        series.push(percentSeries2);

        //add its axis
        //yAxis.push({ // Primary yAxis
        //    labels: {
        //        format: '{value}%'
        //    },
        //    title: {
        //        text: y2_title
        //    },
        //    opposite: true,
        //    max: 100,
        //    min: 0
        //});
    }

    Highcharts.chart(container_id, {
        chart: {
            zoomType: 'xy',
            height: height
        },
        title: {
            text: null,
            style: {
                fontSize: '12px'
            }
        },
        xAxis: [{
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxisTitle
            }
        }],
        yAxis: yAxis,
        tooltip: {
            shared: true
        },
        //colors: ['steelblue', 'sandybrown', 'green', 'purple'],
        colors: ['#A3A8E2', '#494FA3', 'green', 'purple'],
        legend: {
            enabled: true
        },
        exporting: { enabled: false },
        series: series
    });
}

function build_bar_chart_dual_axis(container_id, title, y1_title, y2_title, xaxisCategory, parent_data, parent_data_name, child_data, child_data_name, percent_data, percent_data_name, useLine = true, xaxisTitle, height) {
    //filter unneeded zero data

    var idxsToDelete = [];
    parent_data = (parent_data || []).filter((n, i) => {
        if (n > 0) {
            return true;
        }

        idxsToDelete.push(i);
        return false;
    });

    if (idxsToDelete.length > 0) {
        xaxisCategory = (xaxisCategory || []).filter((n, i) => !idxsToDelete.includes(i));
        child_data = (child_data || []).filter((n, i) => !idxsToDelete.includes(i));
        percent_data = (percent_data || []).filter((n, i) => !idxsToDelete.includes(i));
    }

    var series = [];

    var hasThousand = false;

    if (parent_data.length > 0) {
        series.push({
            name: parent_data_name,
            type: 'column',
            data: parent_data
        });

        hasThousand = hasThousand || parent_data.some(x => Math.abs(x) >= 1000);
    }

    if ((child_data || []).length > 0) {
        series.push({
            name: child_data_name,
            type: 'column',
            data: child_data
        });

        hasThousand = hasThousand || child_data.some(x => Math.abs(x) >= 1000);
    }

    var yAxis = [{ // Secondary yAxis
        title: {
            //text: y1_title + (hasThousand ? " (thousands)" : ""),
            text: y1_title,
            rotation: 270
        },
        labels: {
            formatter: function () {
                //return hasThousand ? parseInt(this.value) / 1000 : this.value;
                return Highcharts.numberFormat(this.value, 0, '', ' ');
            }
            //format: '{value}'
        },
        max: Math.max.apply(Math, parent_data),
        min: 0
    }];

    if ((percent_data || []).length > 0) {
        var percentSeries = {
            name: percent_data_name,
            type: 'spline', //useLine ? 'spline' : 'scatter',
            data: percent_data,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 5
            }
        };

        if (!useLine) {
            percentSeries.lineWidth = 0;
            percentSeries.states = {
                hover: {
                    lineWidth: 0,
                    lineWidthPlus: 0,
                    marker: {
                        radius: 5
                    }
                }
            };
        }

        series.push(percentSeries);

        //add its axis
        yAxis.push({ // Primary yAxis
            labels: {
                format: '{value}%'
            },
            title: {
                text: y2_title
            },
            opposite: true,
            max: 100,
            min: 0
        });
    }

    Highcharts.chart(container_id, {
        chart: {
            zoomType: 'xy',
            height: height
        },
        title: {
            text: null,
            style: {
                fontSize: '12px'
            }
        },
        xAxis: [{
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxisTitle
            }
        }],
        yAxis: yAxis,
        tooltip: {
            shared: true
        },
        //colors: ['#615D8B', '#F88944', '#959335'],
        colors: ['#BA6733', '#4E611C', '#3A356E'],
        legend: {
            enabled: true
        },
        exporting: { enabled: false },
        series: series
    });
}

function build_clustered_bar_dual_axis(container_id, title, y1_title, y2_title, xaxisCategory, formedData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis:
            [
                { // primary axis
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: y1_title
                    },
                    labels: {
                        formatter: function () {
                            return this.value;
                        }
                    }
                },
                { //secondary axis
                    min: 0,
                    title: {
                        text: y2_title
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
            column: {
                borderWidth: 0,
                grouping: true,
                pointPadding: 0
            },
            spline: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return this.y + '%'
                    }
                }
            }
        },
        colors: colors,
        series: formedData,
        exporting: { enabled: false },
    });


};
function build_stacked_bar_dual_axis(container_id, title, y1_title, y2_title, xaxisCategory, formedData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis:
            [
                { // primary axis
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: y1_title
                    },
                    labels: {
                        formatter: function () {
                            return this.value;
                        }
                    }
                },
                { //secondary axis
                    min: 0,
                    max: 100,
                    title: {
                        text: y2_title
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
            column: {
                stacking: 'normal',
                //dataLabels: {
                //    enabled: true
                //}
            }
            //spline: {
            //    dataLabels: {
            //        enabled: true,
            //        formatter: function () {
            //            return this.y + '%'
            //        }
            //    }
            //}
        },
        colors: colors,
        series: formedData,
        exporting: { enabled: false },
    });


};

function build_superimposed_column_chart_dual_axis(container_id, title, y1_title, y2_title, xaxisCategory, parent_data, parent_data_name, child_data, child_data_name, percent_data, percent_data_name, useLine = true, xaxisTitle, height, drill_down_series, drilldown_event, drillup_event) {
    //filter unneeded zero data

    var idxsToDelete = [];
    parent_data = (parent_data || []).filter((n, i) => {
        if ((n.y || n) > 0) {
            return true;
        }

        idxsToDelete.push(i);
        return false;
    });

    if (idxsToDelete.length > 0) {
        xaxisCategory = (xaxisCategory || []).filter((n, i) => !idxsToDelete.includes(i));
        child_data = (child_data || []).filter((n, i) => !idxsToDelete.includes(i));
        percent_data = (percent_data || []).filter((n, i) => !idxsToDelete.includes(i));
    }

    var series = [];

    var hasThousand = false;

    if (parent_data.length > 0) {
        series.push({
            name: parent_data_name,
            type: 'column',
            data: parent_data,
            //pointPadding: 0.25,
            //groupPadding: 0.5,
            //pointWidth: 60
            //pointPadding: 0
            yAxis: 0,
            //events: {
            //    afterAnimate: function (event) {
            //        resizeChart(this.chart.container.parentElement);
            //        this.chart.reflow();
            //    }
            //}
            color: '#E85137'
        });

        hasThousand = hasThousand || parent_data.some(x => Math.abs(x.y || x) >= 1000);
    }

    if ((child_data || []).length > 0) {
        series.push({
            name: child_data_name,
            type: 'column',
            data: child_data,
            //groupPadding: 0.5,
            //pointWidth: 30
            pointPadding: 0.3,
            color: '#D29C26'
        });

        hasThousand = hasThousand || child_data.some(x => Math.abs(x.y || x) >= 1000);
    }

    var yAxis = [{ // Secondary yAxis
        title: {
            text: y1_title + (hasThousand ? " (thousands)" : ""),
            rotation: 270
        },
        labels: {
            formatter: function () {
                return hasThousand ? parseInt(this.value) / 1000 : this.value;
            }
            //format: '{value}'
        },
        //max: Math.max.apply(Math, parent_data.map(x => x.y)),
        min: 0
    }];

    if ((percent_data || []).length > 0) {
        var percentSeries = {
            name: percent_data_name,
            type: 'spline', //useLine ? 'spline' : 'scatter',
            data: percent_data,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 5,
                symbol: "circle"
            },
            color: '#376555'
        };

        if (!useLine) {
            percentSeries.lineWidth = 0;
            percentSeries.states = {
                hover: {
                    lineWidth: 0,
                    lineWidthPlus: 0,
                    marker: {
                        radius: 5
                    }
                }
            };
        }

        series.push(percentSeries);

        //add its axis
        yAxis.push({ // Primary yAxis
            labels: {
                format: '{value}%'
            },
            title: {
                text: y2_title
            },
            opposite: true,
            max: 100,
            min: 0
        });
    }

    Highcharts.chart(container_id, {
        chart: {
            zoomType: 'xy',
            height: height,
            events: {
                drilldown: function (e) {
                    if (drilldown_event)
                        drilldown_event.apply(this, [e]);

                    resizeChart(this.container.parentElement);
                    this.reflow();
                },
                drillup: function (e) {
                    if (drillup_event)
                        drillup_event.apply(this, [e]);

                    resizeChart(this.container.parentElement);
                    this.reflow();
                }
            }
        },
        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },
        xAxis: [{
            type: "category",
            //categories: xaxisCategory,
            //crosshair: true,
            title: {
                text: xaxisTitle
            }
        }],
        yAxis: yAxis,
        tooltip: {
            shared: true
        },
        colors: ['#E85137', '#D29C26', '#376555'],
        legend: {
            enabled: true
        },
        series: series,
        plotOptions: {
            column: {
                grouping: false
            }
        },
        drilldown: {
            series: drill_down_series
        },
        exporting: { enabled: false }
    });
}

function build_bar_chart(container_id, title, y1_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis: {
            min: 0,
            title: {
                text: y1_title
            },
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
        },
        //yAxis:
        //    [

        //        { // primary axis
        //            allowDecimals: false,
        //            min: 0,
        //            title: {
        //                text: y1_title
        //            },
        //            labels: {
        //                formatter: function () {
        //                    return this.value;
        //                }
        //            }
        //        }
        //    ],

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function build_clustered_bar_chart(container_id, title, y1_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },
        yAxis: {
            min: 0,
            title: {
                text: y1_title
            }
        },

        //yAxis:
        //    [

        //        { // primary axis
        //            allowDecimals: false,
        //            min: 0,
        //            title: {
        //                text: y1_title
        //            },
        //            labels: {
        //                formatter: function () {
        //                    return this.value;
        //                }
        //            }
        //        }
        //    ],

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0,
                borderWidth: 0,
                groupPadding: 0
            }
        },
        //        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function build_bar_chart_and_stacked_dual_axis(container_id, title, y1_title, y2_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis:
            [
                { // primary axis
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: y1_title
                    },
                    labels: {
                        formatter: function () {
                            return this.value;
                        }
                    }
                }, { //secondary axis
                min: 0,
                max: 100,
                title: {
                    text: y2_title
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
            column: {
                stacking: 'normal'
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function downloadCsv(containerId) {
    var chart = $('#' + containerId).highcharts();
    console.log(chart);
    if (chart != null) {
        chart.exportChartLocal({ type: 'text/csv' });
    }
}

function downloadPng(containerId) {
    var chart = $('#' + containerId).highcharts();
    if (chart != null) {
        chart.exportChartLocal({ type: 'image/png' })
    }
}

function downloadPdf(containerId) {
    var chart = $('#' + containerId).highcharts();
    if (chart != null) {
        chart.exportChartLocal({ type: 'application/pdf' })
    }
}

function buildMapLegends(statedata) {
    var boundaries = "";
    var maxStateValue = Math.max(...statedata.map(c => (Math.abs(c.value))));
    // var maxStateValue ="111000";
    if (maxStateValue < 100) {

        var limit = Math.ceil(maxStateValue / 10) * 10;
        var bandFloat = limit / 5;
        var band = Math.ceil(bandFloat / 10) * 10;

        boundaries = [
            {
                to: 0,
                name: 'No data'
            }, {
                to: band
            }, {
                from: band,
                to: (band * 2) - 1
            }, {
                from: band * 2,
                to: (band * 3) - 1
            }, {
                from: band * 3,
                to: (band * 4) - 1
            },
            {
                from: band * 4
            }];
    } else if ((maxStateValue > 100) && (maxStateValue < 1000)) {

        var limit = Math.ceil(maxStateValue / 100) * 100;

        var bandFloat = limit / 5;
        var band = Math.ceil(bandFloat / 100) * 100;

        boundaries = [
            {
                to: 0,
                name: 'No data'
            }, {
                to: band
            }, {
                from: band,
                to: (band * 2) - 1
            }, {
                from: band * 2,
                to: (band * 3) - 1
            }, {
                from: band * 3,
                to: (band * 4) - 1
            },
            {
                from: band * 4
            }];
    } else if ((maxStateValue > 1000) && (maxStateValue < 10000)) {

        var limit = Math.ceil(maxStateValue / 1000) * 1000;
        var bandFloat = limit / 5;
        var band = Math.ceil(bandFloat / 1000) * 1000;

        boundaries = [
            {
                to: 0,
                name: 'No data'
            }, {
                to: band
            }, {
                from: band,
                to: (band * 2) - 1
            }, {
                from: band * 2,
                to: (band * 3) - 1
            }, {
                from: band * 3,
                to: (band * 4) - 1
            },
            {
                from: band * 4
            }];
    } else if ((maxStateValue > 10000) && (maxStateValue < 100000)) {

        var limit = Math.ceil(maxStateValue / 10000) * 10000;
        var bandFloat = limit / 5;
        var band = Math.ceil(bandFloat / 10000) * 10000;

        boundaries = [
            {
                to: 0,
                name: 'No data'
            }, {
                to: band
            }, {
                from: band,
                to: (band * 2) - 1
            }, {
                from: band * 2,
                to: (band * 3) - 1
            }, {
                from: band * 3,
                to: (band * 4) - 1
            },
            {
                from: band * 4
            }];
    } else if ((maxStateValue > 100000) && (maxStateValue < 1000000)) {

        var limit = Math.ceil(maxStateValue / 100000) * 100000;
        var bandFloat = limit / 5;
        var band = Math.ceil(bandFloat / 100000) * 100000;

        boundaries = [
            {
                to: 0,
                name: 'No data'
            }, {
                to: band
            }, {
                from: band,
                to: (band * 2) - 1
            }, {
                from: band * 2,
                to: (band * 3) - 1
            }, {
                from: band * 3,
                to: (band * 4) - 1
            },
            {
                from: band * 4
            }];
    } else if (maxStateValue > 1000000) {

        var limit = Math.ceil(maxStateValue / 1000000) * 1000000;
        var bandFloat = limit / 5;
        var band = Math.ceil(bandFloat / 1000000) * 1000000;

        boundaries = [
            {
                to: 0,
                name: 'No data'
            }, {
                to: band
            }, {
                from: band,
                to: (band * 2) - 1
            }, {
                from: band * 2,
                to: (band * 3) - 1
            }, {
                from: band * 3,
                to: (band * 4) - 1
            },
            {
                from: band * 4
            }];
    }

    return boundaries;
}
function build_bar_chat(container_id, title, y1_title, x_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },


        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],
        yAxis: {
            min: 0,
            title: {
                text: y1_title
            },
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
        },

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};
function build_comparison_column_bar_chart(container_id, title, yTitle, parent_data, child_data) {
    var chart = new Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        title: {
            text: null
        },
        exporting: {
            enabled: false
        },
        xAxis: {
            type: 'category'
        },
        //yAxis: [{
        //    min: 0,
        //    title: {
        //        text: yTitle
        //    }
        //}],
        yAxis: [
            {
                min: 0,
                title: {
                    text: yTitle
                }
            }, {
                min: 0,
                title: {
                    text: 'Proportion %'
                },
                opposite: true
            }
        ],
        legend: {
            shadow: false
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: parent_data
    });
}

function build_histogram_line(container_id, title, yAxistitle, yAxistitle2, xAxistitle, xaxisCategory, series_data, series_data2) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        title: {
            text: null
        },
        exporting: {
            enabled: false
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            title: {
                text: xAxistitle
            },
            categories: xaxisCategory,
            crosshair: true
        },
        yAxis: [
            {
                min: 0,
                title: {
                    text: yAxistitle
                }
            }
        ],
        tooltip: {
            headerFormat: '<span style="font-size:10px"> {point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0,
                borderWidth: 0,
                groupPadding: 0,
                shadow: false
            }
        },
        series: [{
            type: 'column',
            name: yAxistitle,
            data: series_data

        }
            //, {
            //    name: yAxistitle2,
            //    type: 'scatter',
            //    yAxis: 0,
            //    data: series_data2

            //    }
        ],
        credits: {
            enabled: true
        }
    });

}

function build_side_by_side_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        title: {
            text: null
        },
        exporting: {
            enabled: false
        },
        xAxis: {
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxistitle,
                enabled: true
            }
        },
        yAxis: [
            {
                min: 0,
                title: {
                    text: yAxistitle
                },
                labels: {
                    format: '{value}',
                    formatter: function () {
                        return Highcharts.numberFormat(this.value, 0, '', ' ');
                    }
                }
            }, {
                title: {
                    text: ''
                },
                opposite: true
            }
        ],

        colors: ['#615D8B', '#F2BF48', '#F88944', '#4E611C', '#808000', '#A52A2A'],

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: series_data
    });
}

function build_trend_charts(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {

    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: {
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxistitle,
                enabled: true
            }
        },

        yAxis: [
            {
                min: 0,
                title: {
                    text: yAxistitle
                },
                labels: {
                    format: '{value}',
                    formatter: function () {
                        return Highcharts.numberFormat(this.value, 0, '', ' ');
                    }
                }
            }
        ],

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            }
        },

        series: series_data
    });
}

function build_side_by_side_column_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        title: {
            text: title
        },
        xAxis: {
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxistitle,
                enabled: false
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: yAxistitle
            },
            labels: {
                format: '{value} %'
            }
        },

        colors: ['Red', 'blue', 'pink', 'steelblue', 'green', 'sandybrown'],

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}%</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: series_data
    });
}

function build_column_stacked_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: null
        },
        exporting: {
            enabled: false
        },

        xAxis: {
            categories: xaxisCategory,
            title: {
                text: xaxistitle,
                enabled: false
            }
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: yAxistitle
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>'
                //+ 'Total PLHIV: ' + this.point.stackTotal;
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: series_data
    });
}

function build_drilldown_charts(id, title, yaxistitle, principal_data, drill_down_data, addSigntovalue = "", percent_data, percent_data_name, y2_title, useLine = true) {

    var colors = get_color_shades(2);

    var series = [];

    var hasThousand = false;

    if ((drill_down_data || []).length > 0) {
        drill_down_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }

    if (principal_data.length > 0) {
        series.push({
            name: "States",
            colorByPoint: false,
            data: principal_data,
            yAxis: 0,
            events: {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            }
        });

        hasThousand = hasThousand || principal_data.some(x => Math.abs(x.y) >= 1000);
    }

    if (hasThousand) {
        yaxistitle = yaxistitle + " (thousands)";
    }

    var yAxis = [{
        title: {
            text: yaxistitle
        },
        labels: {
            formatter: function () {
                return hasThousand ? parseInt(this.value) / 1000 : this.value;
            }
        }
    }];

    if ((percent_data || []).length > 0) {
        var percentSeries = {
            name: percent_data_name,
            type: 'spline',
            data: percent_data,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 5
            }
        };

        if (!useLine) {
            percentSeries.lineWidth = 0;
            percentSeries.states = {
                hover: {
                    lineWidth: 0,
                    lineWidthPlus: 0,
                    marker: {
                        radius: 5
                    }
                }
            };
        }

        series.push(percentSeries);

        //add its axis
        yAxis.push({
            labels: {
                format: '{value}%'
            },
            title: {
                text: y2_title
            },
            opposite: true,
            //max: 100,
            //min: -100
        });
    }

    Highcharts.chart(id, {

        chart: {
            type: 'column'
        },
        title: {
            text: null,
            style: {
                fontSize: '12px'
            }
        },
        subtitle: {
            text: 'Click the columns to drill down'
        },
        xAxis: {
            type: 'category',
            title: {
                text: "States",
                enabled: false
            }
        },
        yAxis: yAxis,
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                colors: ['#494FA3', '#494FA3'],
                dataLabels: {
                    color: '#444'
                },
                nullColor: '#444444'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}' + addSigntovalue + '</b> '
        },
        exporting: { enabled: false },
        series: series,
        colors: ["Sandybrown"],
        drilldown: {
            series: drill_down_data
        }
    });
}

function build_trend_graph(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {

    Highcharts.chart(container_id, {

        title: {
            text: null
        },

        exporting: {
            enabled: false
        },

        xAxis: {
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xaxistitle,
                enabled: true
            }
        },

        yAxis: [
            {
                min: 0,
                title: {
                    text: yAxistitle
                },
                labels: {
                    format: '{value}'
                }
            }, {
                title: {
                    text: ''
                },
                opposite: true
            }
        ],

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true
                }
            }
        },

        series: series_data
    });
}

function build_dual_stacked_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        title: {
            text: null
        },
        exporting: {
            enabled: false
        },

        xAxis: {
            categories: xaxisCategory
        },
        yAxis: [
            {
                min: 0,
                title: {
                    text: yAxistitle
                }
            }, {
                title: {
                    text: 'Female'
                },
                opposite: true
            }
        ],
        legend: {
            shadow: false
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: series_data
    });
}

function build_drilldown_chart(id, title, yaxistitle, principal_data, drill_down_data, addSigntovalue = "", percent_data, percent_data_name, y2_title, useLine = true) {

    var colors = get_color_shades(2);

    var series = [];

    var hasThousand = false;

    if ((drill_down_data || []).length > 0) {
        drill_down_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }

    if (principal_data.length > 0) {
        series.push({
            name: "States (Current on treatment)",
            colorByPoint: false,
            data: principal_data,
            yAxis: 0,
            events: {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            }
        });

        hasThousand = hasThousand || principal_data.some(x => Math.abs(x.y) >= 1000);
    }

    if (hasThousand) {
        yaxistitle = yaxistitle + " (thousands)";
    }

    var yAxis = [{
        title: {
            text: yaxistitle
        },
        labels: {
            formatter: function () {
                return hasThousand ? parseInt(this.value) / 1000 : this.value;
            }
        }
    }];

    if ((percent_data || []).length > 0) {
        var percentSeries = {
            name: percent_data_name,
            type: 'spline',
            data: percent_data,
            yAxis: 1,
            tooltip: {
                pointFormat: '<b>{point.y:.1f}%</b>'
            },
            marker: {
                radius: 5
            }
        };

        if (!useLine) {
            percentSeries.lineWidth = 0;
            percentSeries.states = {
                hover: {
                    lineWidth: 0,
                    lineWidthPlus: 0,
                    marker: {
                        radius: 5
                    }
                }
            };
        }

        series.push(percentSeries);

        //add its axis
        yAxis.push({
            labels: {
                format: '{value}%'
            },
            title: {
                text: y2_title
            },
            opposite: true,
            //max: 100,
            //min: -100
        });
    }

    Highcharts.chart(id, {

        chart: {
            type: 'column'
        },
        title: {
            text: null,
            style: {
                fontSize: '12px'
            }
        },
        subtitle: {
            text: 'Click the columns to drill down'
        },
        xAxis: {
            type: 'category',
            title: {
                text: "States",
                enabled: false
            }
        },
        yAxis: yAxis,
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                colors: ['#494FA3', '#494FA3'],
                dataLabels: {
                    color: '#444'
                },
                nullColor: '#444444'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}' + addSigntovalue + '</b> '
        },
        exporting: { enabled: false },
        series: series,
        colors: ['#16f291'],
        drilldown: {
            series: drill_down_data
        }
    });
}

function build_simple_column_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {
    Highcharts.chart(container_id,
        {
            chart: {
                type: 'column'
            },
            title: {
                text: null
            },
            exporting: {
                enabled: false
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                categories: xaxisCategory
            },
            yAxis: [
                {
                    min: 0,
                    title: {
                        text: yAxistitle
                    }
                }
            ],

            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat:
                    '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
            },

            series: series_data

        });
}

function buildColumnChart(response) {

    Highcharts.chart('linkageTimeToLink', {
        chart: {
            type: 'column',
            height: 500
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: ['0', '1 - 7', '8 - 14', '15 - 30', '>30'],
            title: {
                text: 'Time to Linkage (Days)'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of Individuals (thousands)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'horizontal',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        exporting: { enabled: false },
        series: [{
            name: 'Number of Individuals (thousands)',
            data: response
        }]
    });
}

function build_percent_bar_chart(container_id, title, y_title, seriesData, colors, colorByPoint = true) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis:
            [
                { //secondary axis
                    allowDecimals: true,
                    min: 0,
                    max: 100,
                    title: {
                        text: y_title
                    },
                    labels: {
                        format: '{value} %',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }
                }
            ],
        tooltip: {
            // shared: true,
            formatter: function () {
                return 'Percent: ' + this.y + '<br/>Numerator : ' + this.point.numerator + '<br/>Denominator: ' + this.point.denominator;
            }
        },

        plotOptions: {
            column: {
                colorByPoint: colorByPoint

            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function build_staked_bar(container_id, title, y_title, formedData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },

        xAxis: {
            type: 'category'
        },

        yAxis:

            {
                min: 0,
                max: 100,
                title: {
                    text: y_title
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }
            }
        ,
        tooltip: {
            shared: true,
            useHTML: true,
            formatter: function () {
                var s = '<b></b>';
                $.each(this.points, function (i, point) {

                    s += '<b>' + point.series.name + '</b><br/>' + point.point.name + '<br/> Percent: ' + point.point.y + '&nbsp; Numerator : ' + point.point.numerator + '&nbsp;Denominator: ' + point.point.denominator + '<br/>'
                })
                return s;
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal',
            }
        },
        colors: colors,
        series: formedData,
        exporting: { enabled: false },
    });


};

function build_cbs_line_chart(id, title, xaxisCategory, data, yAxisTitle, xAxisTitle, colors) {
    //var colors = get_color_shades(2);

    Highcharts.chart(id, {
        chart: {
            type: 'line'
        },
        title: {
            text: title,
            style: {
                fontSize: '12px'
            }
        },
        xAxis: {
            categories: xaxisCategory,
            crosshair: true,
            title: {
                text: xAxisTitle,
                enabled: false
            }

        },
        yAxis: {
            allowDecimals: true,
            min: 0,
            max: 100,
            title: {
                text: yAxisTitle
            },
            labels: {
                format: '{value} %',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
            // shared: true,
            formatter: function () {
                return this.series.name + ' ( ' + this.point.category + ' )<br/>Percent: ' + this.y + '<br/>Numerator : ' + this.point.numerator + '<br/>Denominator: ' + this.point.denominator;
            }
        },
        colors: colors,
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                colorByPoint: true

            },
            series: {
                borderWidth: 0,
                //dataLabels: {
                //    enabled: true,
                //    format: '{point.y:, 1f}'
                //}
            }
        },

        exporting: { enabled: false },
        series: data
    });
}

function dumbuild_bar_chat(container_id, title, y1_title, x_title, seriesData, colors) {
    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },


        xAxis: [{
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],
        yAxis: {
            min: 0,
            title: {
                text: y1_title
            },
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
        },

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });
};

function build_dumcolumn_stacked_chart(container_id, title, yAxistitle, xaxisCategory, series_data, xaxistitle) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: null
        },
        exporting: {
            enabled: false
        },

        xAxis: {
            categories: xaxisCategory,
            title: {
                text: xaxistitle,
                enabled: false
            }
        },

        yAxis:
            [{
                allowDecimals: false,
                min: 0, title: { text: yAxistitle }
            },
                {
                    allowDecimals: false,
                    min: 0, title: { text: '' },
                    labels: { format: '{value} %', style: { color: Highcharts.getOptions().colors[0] } },
                    opposite: true
                }],

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>';
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: series_data
    });
}

function build_bar_vlchat(container_id, title, y1_title, xaxisCategory, x_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },


        xAxis: [{
            categories: xaxisCategory,
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],
        yAxis: [
            {
                min: 0,
                title: { text: y1_title },
                labels: { formatter: function () { return Highcharts.numberFormat(this.value, 0, '', ' '); } }
            },

            {
                min: 0,
                title: { text: '' },
                labels: {format: '{value} %',style: {color: Highcharts.getOptions().colors[0]}},
                opposite: true
                //"% Sample Collection Rate"
            }
        ],

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>';
            }
        },
        //tooltip: {
        //    pointFormat: '{point.y}'
        //},
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function build_bar_mschat(container_id, title, y1_title, xaxisCategory, x_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },


        xAxis: [{
            categories: xaxisCategory,
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],
        yAxis: {
            min: 0,
            title: {
                text: y1_title
            },
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
        },

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function build_bar_mschart(container_id, title, y1_title, xaxisCategory, x_title, seriesData, colors) {

    Highcharts.chart(container_id, {

        chart: {
            type: 'column'
        },

        title: {
            text: '',
            style: {
                fontSize: '12px'
            }
        },

        xAxis: [{
            categories: xaxisCategory,
            title: {
                text: x_title,
            },
            type: 'category',
            crosshair: true
        }],
        yAxis: {
            min: 0,
            title: {
                text: y1_title
            },
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                }
            },
        },

        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        colors: colors,
        series: seriesData,
        exporting: { enabled: false },
    });


};

function build_bar_drilldown_chart(container_id, title, y_title, seriesData, drilldownData, colors ) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        title: {
            text: title
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: y_title,
                rotation: 270,
            }
        },

        legend: {
            enabled: true
        },

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },

        colors: colors,
        series: seriesData,
        drilldown: {
            series: drilldownData
        }
    })
};

function build_line_drilldown_chart(container_id, title, y_title, seriesData, drilldownData, colors) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'line'
        },
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        title: {
            text: title
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: y_title,
                rotation: 270,
            }
        },

        legend: {
            enabled: true
        },

        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                }
            }
        },

        colors: colors,
        series: seriesData,
        drilldown: {
            series: drilldownData
        }
    })
};
//function build_stacked_mschart(container_id, title, y1_title, xaxisCategory, x_title, seriesData, colors) {

//    Highcharts.chart(container_id, {

//        chart: {
//            type: 'column'
//        },

//        title: {
//            text: '',
//            style: {
//                fontSize: '12px'
//            }
//        },

//        xAxis: [{
//            categories: xaxisCategory,
//            title: {
//                text: x_title,
//            },
//            type: 'category',
//            crosshair: true
//        }],
//        yAxis: {
//            min: 0,
//            title: {
//                text: y1_title
//            },
//            labels: {
//                formatter: function () {
//                    return Highcharts.numberFormat(this.value, 0, '', ' ');
//                }
//            },
//        },

//        tooltip: {
//            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
//            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
//                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
//            footerFormat: '</table>',
//            shared: true,
//            useHTML: true
//        },
//        plotOptions: {
//            column: {
//                pointPadding: 0.2,
//                borderWidth: 0
//            }
//        },
//        colors: colors,
//        series: seriesData,
//        exporting: { enabled: false },
//    });


//};

function build_stacked_mschart(container_id, xaxisCategory, yaxistitle, seriesData ) {
    Highcharts.chart(container_id, {
        chart: {
            type: 'column'
        },
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: xaxisCategory
        },
        yAxis: {
            min: 0,
            title: {
                text: yaxistitle
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: seriesData
    });
}

function build_bar_charts_commodity(id, title, yaxistitle, principal_data, drill_down_data, vals) {

    var colors = vals;

    if ((drill_down_data || []).length > 0) {
        drill_down_data.forEach((v) => {
            v.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }
    if (principal_data.length > 0) {
        principal_data.forEach((s) => {
            s.events = {
                afterAnimate: function (event) {
                    resizeChart(this.chart.container.parentElement);
                    this.chart.reflow();
                }
            };
        });
    }

    yaxistitle = yaxistitle;

    var yAxis = [{
        title: {
            text: yaxistitle
        },
        labels: {
            formatter: function () {
                return this.value;
            }
        }
    }];

    var test = {

        chart: {
            type: 'column'
        },
        title: {
            text: null,
            style: {
                fontSize: '12px'
            }
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            title: {
                text: "States",
                enabled: false
            }
        },
        yAxis: yAxis,
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                colors: colors,
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b>'
        },
        exporting: { enabled: false },
        series: principal_data,
        drilldown: {
            series: drill_down_data
        }
    };
    Highcharts.chart(id, test);
}




