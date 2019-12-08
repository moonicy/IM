function isEmpty (val) {
    return (val.length === 0 || !val.trim());
}

var currentEditEntityId = null

function currentEdit(id) {
    currentEditEntityId = id

    var tr = $("tr[data-employee-id='" + currentEditEntityId + "']");

    $("#edit_fio").val(tr.find("td[data-employee-field=\"fio\"]").text())
    $("#edit_position").val(tr.find("td[data-employee-field=\"position\"]").text())
}

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
            $("#employee-table").prepend("<tr data-employee-id=\"" + data.id +  "\"> <td>" + data.id + "</td> <td>" + data.fio + "</td> <td>" + data.position + "</td> "
             + "<td><button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#editEmployee\" onclick=\"currentEdit(" + data.id + ")\">Edit</button></td></tr>");
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
            $("#edit_fio").val(data.fio)
            $("#edit_position").val(data.position)

            var tr = $("tr[data-employee-id='" + currentEditEntityId + "']");

            tr.find("td[data-employee-field=\"fio\"]").text(data.fio)
            tr.find("td[data-employee-field=\"position\"]").text(data.position)
        },
        failure: function(errMsg) {
            console.error(errMsg);
        },
        finally: function() {
            currentEditEntityId = null
        }
    });
});