'use strict';

var chartType         = document.getElementById('type'),
    smoothLines       = document.getElementById('smoothLines'),
    smoothLineSlider  = document.getElementById('smoothLineSlider'),
    pointRadius       = document.getElementById('pointRadius'),
    pointRadiusSlider = document.getElementById('pointRadiusSlider'),
    fillSpace         = document.getElementById('fillSpace');

var jsonfile = window.data;

var labels = jsonfile.map(function (e) {
    return e.name;
});


var canvas = document.getElementById('canvas'),
    context = canvas.getContext('2d');

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

var color = Chart.helpers.color;
var cfg = {
    type: 'bar',
    data: {
        labels: labels,
        datasets: createDataSets(jsonfile),
    },
    options: {
        scales: {
            xAxes: [{
                stacked: true,
                type: 'category',
                labels: labels,

            }],
            yAxes: [{
                stacked: true,
                ticks: {
                    beginAtZero: true
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Total Hours'
                }
            }]
        }
    }
};
var chart = new Chart(context, cfg);

window.addEventListener("load", function(){
    hideConfigElements(chartType, smoothLines, pointRadius, fillSpace);
    chart.config.data.datasets.forEach(function(entry) {
        updateSlider(entry, smoothLineSlider, pointRadiusSlider);
        var rgba = entry.backgroundColor.substring(entry.backgroundColor.indexOf('(') + 1, entry.backgroundColor.lastIndexOf(')')).split(/,\s*/);
        //set the alpha of the rgb on initial load
        rgba[3] = "0.4";
		entry.backgroundColor = "rgba("+rgba+")";
		chart.update();
	});

});

chartType.addEventListener('change', function () {
	hideConfigElements(chartType, smoothLines, pointRadius, fillSpace);

});

smoothLineSlider.addEventListener('change', function () {
    //change each dataset
    chart.config.data.datasets.forEach(function(entry) {
        updateSlider(entry, smoothLineSlider, pointRadiusSlider);
    });
});

pointRadiusSlider.addEventListener('change', function () {
    //change each dataset
    chart.config.data.datasets.forEach(function(entry) {
        updateSlider(entry, smoothLineSlider, pointRadiusSlider);
    });
});

//Update Graph
document.getElementById('reportUpdate').addEventListener('click', function () {
		

});

document.getElementById('reportSettings').addEventListener('click', function () {

    var stackRadioBoolean  = document.getElementById('stackTrue').checked,
        fillLinesBoolean   = document.getElementById('fillTrue').checked,
        highContrastMode   = document.getElementById('highContrastTrue').checked,
        type 			   = chartType.value;

    //Update the graph config from input values
    cfg.type = type;
    cfg.options.scales.xAxes[0].stacked = stackRadioBoolean;
    cfg.options.scales.yAxes[0].stacked = stackRadioBoolean;

    //change each dataset
    chart.config.data.datasets.forEach(function(entry) {

        var rgba = entry.backgroundColor.substring(entry.backgroundColor.indexOf('(') + 1, entry.backgroundColor.lastIndexOf(')')).split(/,\s*/);
        //set the alpha of the rgb depending on highContrastMode value
        rgba[3] = highContrastMode ? "1" : "0.4";

        entry.type = type;
        entry.fill = fillLinesBoolean;
        entry.backgroundColor = "rgba("+rgba+")";

        updateSlider(entry, smoothLineSlider, pointRadiusSlider);
        setHighContrastMode(document.getElementById('highContrastTrue').checked, chartType.value);


    });

    chart.update();
});


function setHighContrastMode(radioChecked, type){

    var stackRadio = document.getElementById('stackRadio');

    if(radioChecked && type !== "bar"){
        document.getElementById('stackTrue').checked = true;
        document.getElementById('stackFalse').checked = false;
        stackRadio.hidden = true;
    }
    else{
        stackRadio.hidden = false;
    }
}

function updateSlider(entry, sliderLineTension, sliderPointRadius){
    if (entry.lineTension === 1 - sliderLineTension.value &&
        entry.pointRadius === sliderPointRadius.value){
        return;
    }
    entry.lineTension = 1 - sliderLineTension.value;
    entry.pointRadius = sliderPointRadius.value;
    chart.update();
}

//hides line stiffness and point radius
function hideConfigElements(chartType, smoothLines, pointRadius, fillSpace){
	smoothLines.hidden = chartType.value === "bar";
	pointRadius.hidden = chartType.value === "bar";
	fillSpace.hidden   = chartType.value === "bar";
}

function createDataSets(json){

    var dataSets = [];

    var hoursData = json.map(function (e) {
        return e.normal;
    });
    var overtimeData = json.map(function (e) {
        return e.overtime;
    });
    var holidayData = json.map(function (e) {
        return e.holiday;
    });
    var absentData = json.map(function (e) {
        return e.absent;
    });
    var sickData = json.map(function (e) {
        return e.sick;
    });
    var underData = json.map(function (e) {
        return e.under;
    });
    var objects = [];
    objects.push(hoursData);
    objects.push(overtimeData);
    objects.push(holidayData);
    objects.push(absentData);
    objects.push(sickData);
    objects.push(underData);


    var keys = [];

    //get chart labels using keys from the json file
    Object.keys(json[0]).forEach(function(key){
        if (key !== "name")
            keys.push(key.charAt(0).toUpperCase() + key.slice(1));
    });

    var i = 0;

    //creates each dataset
   objects.forEach(function(data){
       var dataSet = {
        label: keys[i],
        backgroundColor:  window.chartColors[Object.keys(window.chartColors)[i]],
        borderColor: window.chartColors[Object.keys(window.chartColors)[i]],
        data: Object.values(data),
        type: 'bar',
        pointRadius: 5,
        fill: false,
        lineTension: 0.2,
        borderWidth: 2
    };

    i++;
    dataSets.push(dataSet);
   });

    return dataSets;
}
