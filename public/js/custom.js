function currentEdit(id) {
    currentEditEntityId = id;

    let tr = $("tr[data-employee-id='" + currentEditEntityId + "']");

    $("#edit_fio").val(tr.find("td[data-employee-field=\"fio\"]").text());
    $("#edit_position").val(tr.find("td[data-employee-field=\"position\"]").text());
}

$(function () {
    $('[data-tooltip="tooltip"]').tooltip();
    $("#save_fio").val('');
    $("#save_position").val('');
})

$("#employee_save_button").click(function() {
    const fio = $("#save_fio").val();
    const position = $("#save_position").val();

    if (isEmpty(fio) || isEmpty(position)) {
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/employee",
        data: JSON.stringify({
            fio: fio,
            position: position,
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

$("#employee_edit_button").click(function() {
    const fio = $("#edit_fio").val();
    const position = $("#edit_position").val();

    if (isEmpty(fio) || isEmpty(position) || null === currentEditEntityId) {
        return;
    }

    $.ajax({
        type: "PUT",
        url: "/api/employee/" + currentEditEntityId,
        data: JSON.stringify({
            fio: fio,
            position: position,
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
        }
    });
});