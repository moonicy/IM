function isEmpty (val) {
    return (val.length === 0 || !val.trim());
}

var currentEditEntityId = null

function currentEdit(id) {
    currentEditEntityId = id

    var tr = $("tr[data-laptop-id='" + currentEditEntityId + "']");

    $("#edit_number").val(tr.find("td[data-laptop-field=\"number\"]").find("a").text())
    $("#edit_firm").val(tr.find("td[data-laptop-field=\"firm\"]").text())
    $("#edit_model").val(tr.find("td[data-laptop-field=\"model\"]").text())
    $("#edit_date_buy").val(tr.find("td[data-laptop-field=\"dateBuy\"]").text())
    $("#edit_interval").val(tr.find("td[data-laptop-field=\"interval\"]").text().match(/\d/g).join(""))
    $("#edit_cores").val(tr.find("td[data-laptop-field=\"numberCores\"]").text())
    $("#edit_memory").val(tr.find("td[data-laptop-field=\"memory\"]").text())
    $("#edit_disk").val(tr.find("td[data-laptop-field=\"disk\"]").text())
}

$(function () {
    $('#save_date_buy').datetimepicker({
        timeZone: 'Europe/Moscow',
        format: 'YYYY-MM-DD'
    });

    $('#edit_date_buy').datetimepicker({
        timeZone: 'Europe/Moscow',
        format: 'YYYY-MM-DD'
    });
});

$("#laptop_save_button").click(function() {
    const number = $("#save_number").val();
    const firm = $("#save_firm").val();
    const model = $("#save_model").val();
    const dateBuy = $("#save_date_buy").val();
    const interval = $("#save_interval").val();
    const cores = $("#save_cores").val();
    const memory = $("#save_memory").val();
    const disk = $("#save_disk").val();

    if (isEmpty(number) || isEmpty(firm) || isEmpty(model) || isEmpty(dateBuy) || isEmpty(interval) || isEmpty(cores) || isEmpty(memory) || isEmpty(disk)) {
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/laptop",
        data: JSON.stringify({
            number: number,
            firm: firm,
            model: model,
            dateBuy: dateBuy,
            interval: "P0Y0M" + interval.match(/\d/g).join("") + "DT0H0M0S",
            numberCores: cores,
            memory: memory,
            disk: disk
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

$("#laptop_edit_button").click(function() {
    const number = $("#edit_number").val();
    const firm = $("#edit_firm").val();
    const model = $("#edit_model").val();
    const dateBuy = $("#edit_date_buy").val();
    const interval = $("#edit_interval").val();
    const cores = $("#edit_cores").val();
    const memory = $("#edit_memory").val();
    const disk = $("#edit_disk").val();

    if (isEmpty(number) || isEmpty(firm) || isEmpty(model) || isEmpty(dateBuy) || isEmpty(interval) || isEmpty(cores) || isEmpty(memory) || isEmpty(disk)  || null === currentEditEntityId) {
        return;
    }

    $.ajax({
        type: "PUT",
        url: "/api/laptop/" + currentEditEntityId,
        data: JSON.stringify({
            number: number,
            firm: firm,
            model: model,
            dateBuy: dateBuy,
            interval: "P0Y0M" + interval.match(/\d/g).join("") + "DT0H0M0S",
            numberCores: cores,
            memory: memory,
            disk: disk
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