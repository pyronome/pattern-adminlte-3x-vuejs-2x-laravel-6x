/* {{@snippet:begin}} */
var RecordGraphCharts = [];
                                            
function initializeRecordGraphChart(model, graphJSON) {
    if ("" == graphJSON) {
        return false;
    }

    var graphId = model + "RecordGraph";
    
    if (graphId in RecordGraphCharts) {
        RecordGraphCharts[graphId].destroy();
    }
    
    var graphData = JSON.parse(decodeURIComponent(graphJSON));
    var dataCount = graphData.length;
    var data = null;
    
    var labels = [];
    var records = [];
    var recordCount = 0;
    var maxCount = 0;
    
    for (var i = 0; i < dataCount; i++) {
        data = graphData[i];
        
        labels.push(data["date"]);
        recordCount = parseInt(data["record"]);
        records.push(data["record"]);
        
        if (recordCount > maxCount) {
            maxCount = recordCount;
        }
    }
    
    var limit = 1;
    
    if (maxCount > 4) {
        limit = parseInt(maxCount / 5);
    }
    
    var labelText = document.getElementById("recordgraphLabel").innerHTML;
    
    var ctx = document.getElementById(graphId + "Container").getContext('2d');
    
    RecordGraphCharts[graphId] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "# of Records",
                data: records,
                backgroundColor: [
                    'rgba(0, 0, 0, 0)'
                ],
                borderColor: [
                    '#039be5'
                ],
                borderWidth: 1,
                pointStyle: "circle",
                pointBorderWidth: 4,
                pointRadius: 2,
                pointHoverBackgroundColor: '#039be5',
                pointHoverBorderColor: '#039be5'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: limit
                    },
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: labelText
                    }
                }],
                xAxes: [{
                    display: true
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                mode: 'point'
            }
        }
    });
}

function setWidgetState(suffix, state) {
    var controller = document.getElementById("controller").value;
    var cookieName = (`${controller}${suffix}`);
    setCookie(cookieName,state, 365);
}
function getWidgetState(suffix) {
    var controller = document.getElementById("controller").value;
    var cookieName = (`${controller}${suffix}`);
    return getCookie(cookieName);
}
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return false;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}

/* {{@snippet:end}} */