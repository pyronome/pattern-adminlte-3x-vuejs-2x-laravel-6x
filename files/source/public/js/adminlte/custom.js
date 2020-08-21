/* {{@snippet:begin}} */

function loadPageWidgets(page_widgets) {
    renderPageWidgets();

    removeUnusedWidgets();
    
    initializeInfoBoxes();

    initializeRecordGraphs();

    /* renderModelRecordListSearch(); */
    renderModelRecordListTable();
    /* renderModelRecordListPagination(); */
    
    
    $(".buttonSHWidget").on("click", function () {
        showHideWidget(this);
    });
    
    initializeShowHideWidget();
    
    return;
    
    
    
    
    renderModelHTMLDBTables();
    
    renderModelHTMLDBTemplates();
    renderModelModalDialogs();
    
    initializeSecondaryHTMLDBTables();
    initializeSecondaryHTMLDBTemplates();
    initializeSecondaryHTMLDBPaginations();
    initializeSecondaryReadQueue();
    
    
}

function renderPageWidgets() {
    var Widgets = $("body").data("widgets");

    var templateHTML = document.getElementById("widgetTemplates").innerHTML;
    var widgetHTML = "";
    var countWidgets = Widgets.length;
    
    for (let index = 0; index < countWidgets; index++) {
        const Widget = Widgets[index];
        if ("1" == Widget.visibility) {
            widgetHTML = templateHTML.replace(/__id__/g, Widget.id)
                .replace(/__type__/g, Widget.type)
                .replace(/__model__/g, Widget.model)
                .replace(/__size__/g, Widget.size)
                .replace(/__visibility__/g, Widget.visibility)
                .replace(/__href__/g, Widget.href)
                .replace(/__icon__/g, Widget.icon)
                .replace(/__iconbackground__/g, Widget.iconbackground)
                .replace(/__onlylastrecord__/g, Widget.onlylastrecord)
                .replace(/__text__/g, Widget.text)
                .replace(/__container_state__/g, "active-container");
            
            document.getElementById("widgetContainer").innerHTML += widgetHTML;
        }
    }
    
}

function removeUnusedWidgets() {
    $(".widget-visible0").detach();
    
    $(".empty-widget-infobox").detach();
    $(".empty-widget-recordgraph").detach();
    $(".empty-widget-recordlist").detach();
    
    $(".infobox-empty").detach();
    $(".infobox-recordlist").detach();
    $(".infobox-recordgraph").detach();
    
    $(".recordlist-empty").detach();
    $(".recordlist-infobox").detach();
    $(".recordlist-recordgraph").detach();
    
    $(".recordgraph-empty").detach();
    $(".recordgraph-infobox").detach();
    $(".recordgraph-recordlist").detach();
}

function initializeInfoBoxes() {
    var infoboxes = $(".infobox-infobox");
    var count = infoboxes.length;
    for (let index = 0; index < count; index++) {
        const infobox = infoboxes[index];
        const model = infobox.getAttribute("data-model");
        axios.get(AdminLTEHelper.getAPIURL(model.toLowerCase() + "/get_infoboxvalue"))
                .then(({ data }) => {
                    document.getElementById(model + "InfoboxValue").innerHTML = data.value;
                });
    }

    $(".clickable-infobox").on("click", function () {
        window.location = this.getAttribute("data-href");
    });
}

function initializeRecordGraphs() {
    var recordgraphs = $(".recordgraph-recordgraph");
    var count = recordgraphs.length;
    for (let index = 0; index < count; index++) {
        const recordgraph = recordgraphs[index];
        const model = recordgraph.getAttribute("data-model");
        axios.get(AdminLTEHelper.getAPIURL(model.toLowerCase() + "/get_recordgraphdata"))
                .then(({ data }) => {
                    initializeRecordGraphChart(model, data.graphJSON);
                });
    }
}

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

function initializeRecordLists() {
    var recordlists = $(".recordlist-recordlist");
    var count = recordlists.length;
    for (let index = 0; index < count; index++) {
        const recordlist = recordlists[index];
        const model = recordlist.getAttribute("data-model");
        axios.get(AdminLTEHelper.getAPIURL(model.toLowerCase() + "/get_recordlist"))
                .then(({ data }) => {
                    initializeRecordListTable(data.columns);
                });
    }
}

function initializeRecordListTable(columns) {

}

function renderModelRecordListTable() {
    /* var recordlists = $(".recordlist-recordlist");
    var count = recordlists.length;
    for (let index = 0; index < count; index++) {
        const recordlist = recordlists[index];
        const model = recordlist.getAttribute("data-model");
    } */

    var template = document.getElementById("modelRecordListTemplate").innerHTML;   
    var tableRecordListClass = "";    
    var containers = $(".recordlist-container.active-container");
    var containerLength = containers.length;
    
    for (var i = 0; i < containerLength; i++) {
        const container = containers[i];
        const rowId = container.getAttribute("data-row-id");
        const Widget = getWidgetById(rowId);

        const onlylastrecord = parseInt(Widget["onlylastrecord"]);
        if (1 == onlylastrecord) {
            tableRecordListClass = "table-object-list";
        } else {
            tableRecordListClass = "checkbox-table table-object-list";
        }

        const modelName = Widget["model"];
        const recordListTH = getRecordListTH(Widget);
        const tdHTML = getRecordListTD(Widget);
        const tableHTML = template.replace(/__MODEL__/g, modelName)
            .replace(/__MODEL_LOWERCASE__/g, modelName.toLowerCase()) 
            .replace(/__TABLE_RECORD_LIST_CLASS__/g, tableRecordListClass)
            .replace(/__ONLYLASTRECORD__/g, onlylastrecord)
            .replace(/__TH__/g, recordListTH)
            .replace(/__VFOR__/g, (modelName + " in " + modelName + "List"))
            .replace(/__KEY__/g, (modelName + ".id"))
            .replace(/__RECORDLIST_TD__/g, tdHTML);

        container.innerHTML = tableHTML;
    }
}

function getTHCheckboxHTML(model) {
    var thCheckboxTemplate = document.getElementById("thCheckboxTemplate").innerHTML;
    return thCheckboxTemplate.replace(/__MODEL__/g, model);
}

function getRecordListTH(widget) {
    var columnCSV = widget["columns"];
    var columnTemplate = document.getElementById("thTemplate").innerHTML;
    var recordListTH = "";
    
    if ("" == columnCSV) {
        recordListTH = columnTemplate.replace(/__COLUMN__/g, "");
        return recordListTH;
    } 
    
    var onlylastrecord = (1 == parseInt(widget["onlylastrecord"]));
    var model = widget["model"];
    
    if (onlylastrecord) {
        columnTemplate = document.getElementById("thLastRecordTemplate").innerHTML;
    } else {
        var thCheckboxHTML = getTHCheckboxHTML(model);
        recordListTH += thCheckboxHTML;
    }
    
    var columns = columnCSV.split(",");
    var columnLength = columns.length;
    var columnHTML = "";
    
    var valueCSV = widget["values"];
    var values = valueCSV.split(",");
    
    for (var i = 0; i < columnLength; i++) {
        columnHTML = columnTemplate;
        columnHTML = columnHTML.replace(/__MODEL__/g, model)
        .replace(/__COLUMN__/g, columns[i])
        .replace(/__VALUE__/g, values[i]);
        recordListTH += columnHTML;
    }
    
    var thButtonHTML = getTHButtonHTML(model);
    recordListTH += thButtonHTML;
    
    return recordListTH;
}

function getTHButtonHTML(model) {
    var thButtonTemplate = document.getElementById("thButtonTemplate").innerHTML;
    var thButtonHTML = thButtonTemplate.replace(/__MODEL__/g, model)
    .replace(/__MODEL_LOWERCASE__/g, model.toLowerCase());
    
    return thButtonHTML;
}

function renderRecordListHTMLDBTemplates() {
    var template = document.getElementById("modelRecordListHTMLBTemplateTemplate").innerHTML;
    var scriptHTML = "";
    var htmldbTable = HTMLDB.e("WidgetHTMLDB");
    var rowId = 0;
    var widget = null;
    var modelName = "";
    var modelNameLowercase = "";
    var recordListTD = "";
    
    var container = null;
    var containers = $(".recordlist-htmldb-template-container");
    var containerLength = containers.length;
    
    for (var i = 0; i < containerLength; i++) {
        container = containers[i];
        rowId = container.getAttribute("data-row-id");
        widget = HTMLDB.get(htmldbTable, rowId);
        
        modelName = widget["model"];
        modelNameLowercase = modelName.toLowerCase();
        recordListTD = getRecordListTD(widget);
        
        scriptHTML = template.replace(/__HTMLDBCLASS__/g, "htmldb-template secondary-htmldb-template")
        .replace(/__MODEL__/g, modelName)
        .replace(/__TD__/g, recordListTD);
        
        container.innerHTML = scriptHTML;
        
        $("#tbody" + modelName + "RecordListTemplate").on("htmldbrender", function (e) {
            AdminLteHTMLDB.initializeTableCheckbox(e.target);
            
            $(".showBigPhoto").off("click").on("click", function() {
                doShowBigPhotoClick(this);
            });
            
            HTMLDB.read(HTMLDB.e("__pagepermissionHTMLDB"));
        });
    }
    
    /*var lastTemplateSelectorText = "#tbody" + modelName + "RecordListTemplate";
    
    // son modelin render'ından sonra
    $(lastTemplateSelectorText).on("htmldbrender", function (e) {
        
    });*/
}

function getTDCheckboxHTML(model) {
    var tdCheckboxTemplate = document.getElementById("tdCheckboxTemplate").innerHTML;
    return tdCheckboxTemplate.replace(/__MODEL__/g, model);
}

function getRecordListTD(Widget) {
    var valueCSV = Widget["values"];
    var valueTemplate = document.getElementById("tdTemplate").innerHTML;
    var recordListTD = "";
    
    if ("" == valueCSV) {
        recordListTD = valueTemplate.replace(/__VALUE__/g, "");
        return recordListTD;
    }
    
    var onlylastrecord = (1 == parseInt(Widget["onlylastrecord"]));
    var model = Widget["model"];
    
    if (onlylastrecord) {
        columnTemplate = document.getElementById("tdLastRecordTemplate").innerHTML;
    } else {
        var tdCheckboxHTML = getTDCheckboxHTML(model, onlylastrecord);
        recordListTD += tdCheckboxHTML;
    }
    
    
    
    var values = valueCSV.split(",");
    var valueLength = values.length;
    var valueHTML = "";
    var valueText = "";
    
    for (var i = 0; i < valueLength; i++) {
        valueText = "{{" + model + "." + values[i] + "}}";
        valueHTML = valueTemplate;
        valueHTML = valueHTML.replace(/__VALUE__/g, valueText);
        recordListTD += valueHTML;
    }
    
    var tdButtonHTML = getTDButtonHTML(model);
    recordListTD += tdButtonHTML;
    
    return recordListTD;
}

function getTDButtonHTML(modelName) {
    return document.getElementById("tdButtonTemplate").innerHTML
        .replace(/__MODEL_LOWERCASE__/g, modelName.toLowerCase())
        .replace(/__URL_KEY__/g, (modelName + ".id"));
}
function getWidgetById(id) {
    var Widgets = $("body").data("widgets");
    return Widgets[id-1];
}
function initializeShowHideWidget() {
    var controller = document.getElementById("controller").value;
    var buttons = $(".buttonSHWidget");
    var buttonLength = buttons.length;
    var buttonElement = null;
    var buttonId = "";
    var cookieName = "";

    for (var i = 0; i < buttonLength; i++) {
        buttonElement = buttons[i];
        buttonId = buttonElement.id;
        cookieName = controller + buttonId;

        if ("no" === getCookie(cookieName)) {
            continue;
        }

        buttonElement.click();
    }
}
function showHideWidget(sender) {
    if (!sender) {
        return;
    }
    
    var controller = document.getElementById("controller").value;

    var buttonId = sender.id;
    var displayState = ("yes" == sender.getAttribute("data-display")) ? "no" : "yes";
    
    var cookieName = controller + "" + buttonId;

    setCookie(cookieName,displayState, 365);
    
    sender.setAttribute("data-display", displayState);
}
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}
/* *************************************************** */
/* *************************************************** */
/* *************************************************** */
/* *********** Yukarıdakiler kullanılıyor ************ */
/* *************************************************** */
/* *************************************************** */
/* *************************************************** */



function renderModelHTMLDBTables() {
    renderRecordListHTMLDBTables();
    /*renderRecordListSessionHTMLDBTables();*/
}

function renderModelHTMLDBTemplates() {
    renderRecordListHTMLDBTemplates();
}

function renderModelModalDialogs() {
    var template = document.getElementById("modelRecordListModalTemplate").innerHTML;
    var modalHTML = "";
    var htmldbTable = HTMLDB.e("WidgetHTMLDB");
    var rowId = 0;
    var widget = null;
    var modelName = "";
    
    var container = null;
    var containers = $(".recordlist-modal-container");
    var containerLength = containers.length;
    
    for (var i = 0; i < containerLength; i++) {
        container = containers[i];
        rowId = container.getAttribute("data-row-id");
        widget = HTMLDB.get(htmldbTable, rowId);
        
        if (1 == parseInt(widget["onlylastrecord"])) {
            continue;
        }
        
        modelName = widget["model"];
        
        modalHTML = template.replace(/__MODEL__/g, modelName);
        
        container.innerHTML = modalHTML;
    }
}

function initializeSecondaryHTMLDBTables() {
    var tableElements = HTMLDB.q(".secondary-htmldb-table");
    var tableElementCount = tableElements.length;
    var tableElement = null;
    var priority = 0;
    
    for (var i = 0; i < tableElementCount; i++) {
        tableElement = tableElements[i];
        HTMLDB.validateHTMLDBTableDefinition(tableElement);
    }
    
    for (var i = 0; i < tableElementCount; i++) {
        tableElement = tableElements[i];
        HTMLDB.createHelperElements(tableElement);
        tableElement.style.display = "none";
        tableElement.setAttribute("data-htmldb-loading", 0);
        tableElement.HTMLDBTemplateTargets = [];
        priority = parseInt(
            HTMLDB.getHTMLDBParameter(
                tableElement,
                "priority"));
        if (isNaN(priority)) {
            priority = 0;
            tableElement.setAttribute("data-htmldb-priority", priority);
        }
    }
            
    var parents = HTMLDB.extractParentTables();
    
    for (var i = 0; i < tableElementCount; i++) {
        tableElement = tableElements[i];
        
        if (0 == parents[tableElement.getAttribute("id")]) {
            continue;
        }
        
        if (HTMLDB.getHTMLDBParameter(tableElement, "priority")
        > HTMLDB.getMaxPriority(parents[tableElement.getAttribute("id")])) {
            continue;
        }
        
        priority = HTMLDB.getMaxPriority(parents[tableElement.getAttribute("id")]) + 1;
        tableElement.setAttribute("data-htmldb-priority", priority);
    }
}
        
function initializeSecondaryHTMLDBTemplates() {
    var templateElements = HTMLDB.q(".secondary-htmldb-template");
    var templateElementCount = templateElements.length;
    var templateElement = null;
    var tableElementId = "";
    var targetElementId = "";
    var functionBody = "";
            
    for (var i = 0; i < templateElementCount; i++) {
    templateElement = templateElements[i];
    templateElement.HTMLDBGUID = HTMLDB.generateGUID();
    HTMLDB.validateHTMLDBTemplateDefinition(templateElement);
    tableElementId = HTMLDB.getHTMLDBParameter(
    templateElement,
    "table");
    targetElementId = HTMLDB.getHTMLDBParameter(
    templateElement,
    "template-target");
    functionBody = HTMLDB.generateTemplateRenderFunctionString(
    templateElement,
    tableElementId,
    targetElementId);

    try {
    templateElement.renderFunction
    = new Function(
    "tableElement",
    "rows",
    functionBody);

    } catch(e) {
    throw(new Error("HTMLDB template (index:"
    + i
    + ") render function could not be created."));
    return false;
    }
    }
}
                        
function initializeSecondaryHTMLDBPaginations () {
    var paginationElements
    = HTMLDB.q(
        ".htmldb-secondary-pagination");
        var paginationElementCount = paginationElements.length;
        var paginationElement = null;
        for (var i = 0; i < paginationElementCount; i++) {
            paginationElement = paginationElements[i];
            HTMLDB.validateHTMLDBPaginationDefinition(paginationElement);
        }
}
                            
function initializeSecondaryReadQueue() {
    var tableElements = HTMLDB.q(".secondary-htmldb-table");
    var tableElementCount = tableElements.length;
    var tableElement = null;
    var indices = [];
    var priorities = [];
    var index = 0;
    var priority = 0;
    
    HTMLDB.readQueue = null;
    
    for (var i = 0; i < tableElementCount; i++) {
        tableElement = tableElements[i];
        priority = parseInt(
        HTMLDB.getHTMLDBParameter(
            tableElement,
            "priority"));
        if (-1 == indices.indexOf(priority)) {
            indices.push(priority);
        }
    }
        
    indices.sort();
    
    for (var i = 0; i < tableElementCount; i++) {
        tableElement = tableElements[i];
        if (HTMLDB.getHTMLDBParameter(tableElement, "form") != "") {
            continue;
        }
        priority = parseInt(
            HTMLDB.getHTMLDBParameter(
                tableElement,
                "priority"));
        index = indices.indexOf(priority);
        if (undefined === priorities[index]) {
            priorities[index] = [];
        }
        priorities[index].push(tableElement.getAttribute("id"));
    }
            
    HTMLDB.readQueue = priorities;
    
    HTMLDB.processReadQueue();
}
                                            
                                            
                                            
                                            
                                            
// Recod List
function renderRecordListHTMLDBTables() {
    var template = document.getElementById("modelRecordListHTMLBTableTemplate").innerHTML;
    var tableHTML = "";
    var htmldbTable = HTMLDB.e("WidgetHTMLDB");
    var rowId = 0;
    var widget = null;
    var modelName = "";
    var modelNameLowercase = "";
    
    var container = null;
    var containers = $(".recordlist-htmldb-table-container");
    var containerLength = containers.length;
    
    for (var i = 0; i < containerLength; i++) {
        container = containers[i];
        rowId = container.getAttribute("data-row-id");
        widget = HTMLDB.get(htmldbTable, rowId);
        
        modelName = widget["model"];
        modelNameLowercase = modelName.toLowerCase();
        
        tableHTML = template.replace(/__HTMLDBCLASS__/g, "htmldb-table secondary-htmldb-table")
        .replace(/__SECONDARY_CLASS__/g, "")
        .replace(/__PRIORITY__/g, (4000 + i))
        .replace(/__MODEL__/g, modelName)
        .replace(/__MODEL_LOWERCASE__/g, modelNameLowercase);
        
        container.innerHTML = tableHTML;
    }
    
    $(".recordlist-htmldb-table").on("htmldbread", function (e) {
        afterRecordListHTMLDBTableRead(e.target);
    });
    
    $(".secondary-htmldb-table").on("htmldberror", function (event) {
        AdminLteHTMLDB.showError(event);
    });
}

function afterRecordListHTMLDBTableRead(htmldbTable) {
    var model = htmldbTable.id.replace("HTMLDB", "");
    
    var buttonNew = document.getElementById("buttonNew" + model);
    
    $(buttonNew).off("click").on("click", function () {
        window.location = this.getAttribute("data-href");
    });
    
    
    var buttonMultipleDelete = document.getElementById("buttonDelete" + model);
    
    $(buttonMultipleDelete).hide();
    $(buttonNew).show();     
}

function renderModelRecordListSearch() {
    var template = document.getElementById("modelRecordListSearchTemplate").innerHTML;
    var searchHTML = "";
    var htmldbTable = HTMLDB.e("WidgetHTMLDB");
    var rowId = 0;
    var widget = null;
    var modelName = "";
    
    var container = null;
    var containers = $(".recordlist-search-container");
    var containerLength = containers.length;
    
    for (var i = 0; i < containerLength; i++) {
        container = containers[i];
        rowId = container.getAttribute("data-row-id");
        widget = HTMLDB.get(htmldbTable, rowId);
        
        if (1 == parseInt(widget["onlylastrecord"])) {
            continue;
        }
        
        modelName = widget["model"];
        
        searchHTML = template.replace(/__MODEL__/g, modelName);
        
        container.innerHTML = searchHTML;
    }
}



function renderModelRecordListPagination() {
    var template = document.getElementById("modelRecordListPaginationTemplate").innerHTML;
    var paginationHTML = "";
    var htmldbTable = HTMLDB.e("WidgetHTMLDB");
    var rowId = 0;
    var widget = null;
    var modelName = "";
    
    var container = null;
    var containers = $(".recordlist-pagination-container");
    var containerLength = containers.length;
    
    for (var i = 0; i < containerLength; i++) {
        container = containers[i];
        rowId = container.getAttribute("data-row-id");
        widget = HTMLDB.get(htmldbTable, rowId);
        
        if (1 == parseInt(widget["onlylastrecord"])) {
            continue;
        }
        
        modelName = widget["model"];
        
        paginationHTML = template.replace(/__MODEL__/g, modelName);
        
        container.innerHTML = paginationHTML;
    }
}


/* {{@snippet:end}} */