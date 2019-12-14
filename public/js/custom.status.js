function currentEdit(id, laptopId) {
    currentEditEntityId = id;
    currentLaptop = laptopId;

    let tr = $("tr[data-status-id='" + currentEditEntityId + "']");

    $("#edit_employee").val(tr.find("td[data-status-field=\"employee\"]").attr("data-employee-id"));
    $("#edit_laptop").val(tr.find("td[data-status-field=\"status\"]").text());
    $("#edit_date_start").val(tr.find("td[data-status-field=\"dateStart\"]").text());
    $("#edit_date_end").val(tr.find("td[data-status-field=\"dateEnd\"]").text());
    $("#edit_status").val(tr.find("td[data-status-field=\"status\"]").text())
}

$(function () {
    for (let id of ['save_date_start', 'save_date_end', 'edit_date_start', 'edit_date_end', 'filter_date_start', 'filter_date_end']) {
        $('#' + id).datetimepicker({
            timeZone: 'Europe/Moscow',
            format: 'YYYY-MM-DD'
        });
    }

    let urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('dateStart')) {
        $("#filter_date_start").val(urlParams.get('dateStart'));
    }

    if (urlParams.has('dateEnd')) {
        $("#filter_date_end").val(urlParams.get('dateEnd'));
    }

    if (urlParams.has('firm')) {
        $('#laptopFirm').val(urlParams.get('firm'))
    }

    if (urlParams.has('number')) {
        $('#filter_laptop_number').val(urlParams.get('number'))
    }

    if (urlParams.has('status')) {
        $('#laptopStatus').val(urlParams.get('status'))
    }

    if (urlParams.has('employee')) {
        $('#employee_fio').val(urlParams.get('employee'))
    }

    if (urlParams.has('relevant')) {
        $('#relevant').prop('checked', true);
    }

    if (urlParams.has('outdated')) {
        $('#outdated').prop('checked', true);
    }
});

$("#status_save_button").click(function() {
    const laptopId = currentLaptop;
    const employeeId = $("#save_employee").children("option:selected").val();
    const status = $("#save_status").children("option:selected").val();
    const dateStart = $("#save_date_start").val();
    const dateEnd = $("#save_date_end").val();

    if (isEmpty(employeeId) || isEmpty(status) || isEmpty(dateStart) || isEmpty(dateEnd)) {
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/status",
        data: JSON.stringify({
            employee: employeeId,
            laptop: laptopId,
            status: status,
            dateStart: dateStart,
            dateEnd: dateEnd
        }),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data) {
            location.reload();
        },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });
});

$("#status_edit_button").click(function() {
    const laptopId = currentLaptop;
    const employeeId = $("#edit_employee").children("option:selected").val();
    const status = $("#edit_status").children("option:selected").val();
    const dateStart = $("#edit_date_start").val();
    const dateEnd = $("#edit_date_end").val();

    if (isEmpty(employeeId) || isEmpty(status) || isEmpty(dateStart) || isEmpty(dateEnd) || null === currentEditEntityId) {
        return;
    }

    $.ajax({
        type: "PUT",
        url: "/api/status/" + currentEditEntityId,
        data: JSON.stringify({
            employee: employeeId,
            laptop: laptopId,
            status: status,
            dateStart: dateStart,
            dateEnd: dateEnd
        }),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data) {
            location.reload();
        },
        failure: function(errMsg) {
            console.error(errMsg);
        },
        finally: function() {
            currentEditEntityId = null;
            currentLaptop = null;
        }
    });
});

$("#search").click(function() {
    let firm = $('#laptopFirm').children("option:selected").val()
    let dateStart = $("#filter_date_start").val();
    let dateEnd = $("#filter_date_end").val();
    let number = $('#filter_laptop_number').val();
    let status = $('#laptopStatus').val();
    let employee = $('#employee_fio').children("option:selected").val()
    let relevant = $("#relevant").prop('checked')
    let outdated = $("#outdated").prop('checked')

    let url = new URL(window.location);

    let urlParams = new URLSearchParams(window.location.search);
    let newUrlParams = Object.assign({}, urlParams);

    setUrlParam(urlParams, 'firm', firm);
    setUrlParam(urlParams, 'dateStart', dateStart);
    setUrlParam(urlParams, 'dateEnd', dateEnd);
    setUrlParam(urlParams, 'number', number);
    setUrlParam(urlParams, 'status', status);
    setUrlParam(urlParams, 'employee', employee);
    setUrlParam(urlParams, 'relevant', relevant ? 'true' : false);
    setUrlParam(urlParams, 'outdated', outdated ? 'true' : false);

    if (newUrlParams.toString() != urlParams.toString()) {
        url.search = urlParams.toString()

        location.replace(url.href)
    }
})

$('#clear_filter_date_start').click(function () {
    $('#filter_date_start').datetimepicker('clear')
})

$('#clear_filter_date_end').click(function () {
    $('#filter_date_end').datetimepicker('clear')
})