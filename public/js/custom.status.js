function isEmpty (val) {
    return (val.length === 0 || !val.trim());
}

var currentEditEntityId = null

function currentEdit(id) {
    currentEditEntityId = id

    var tr = $("tr[data-status-id='" + currentEditEntityId + "']");

    $("#edit_employee").val(tr.find("td[data-status-field=\"employee\"]").attr("data-employee-id"))
    $("#edit_laptop").val(tr.find("td[data-status-field=\"status\"]").text())
    $("#edit_date_start").val(tr.find("td[data-status-field=\"dateStart\"]").text())
    $("#edit_date_end").val(tr.find("td[data-status-field=\"dateEnd\"]").text())
}

$(function () {
    for (var id of ['save_date_start', 'save_date_end', 'edit_date_start', 'edit_date_end']) {
        $('#' + id).datetimepicker({
            timeZone: 'Europe/Moscow',
            format: 'YYYY-MM-DD'
        });
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
            currentEditEntityId = null
        }
    });
});