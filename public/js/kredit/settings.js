$(document).ready(function () {
    loadData();

    $(".unit-input").keyup(function (e) {
        e.preventDefault();
        if (e.keyCode == 13) {
            $(".btn-saveunit").click();
        }
    });
});

const _token = $("meta[name=CSRF_TOKEN]").attr("content");

function editUnit(e) {
    e.preventDefault();
    target = $(e.target);

    $(".unit-input").val(target.text()).attr("data-id", target.attr("data-id"));
    $(".btn-delete").removeClass("d-none");
    $(".btn-cancel").removeClass("d-none");
}

function btn_action(e) {
    e.preventDefault();
    const target = $(e.target);
    input = $(".unit-input");

    if (target.attr("data-action") == "cancel") {
        input.val("").attr("data-id", 0);
        $(".btn-delete").addClass("d-none");
        $(".btn-cancel").addClass("d-none");
        return;
    }

    input.attr("readonly", true);
    input.parent().find("button").attr("disabled", true);

    const params = {
        id: input.attr("data-id"),
        name: input.val(),
        _token,
        action: target.attr("data-action"),
    };

    $.post(urlSetProduct, params, (res) => {
        console.log(res);
    }).done((res) => {
        loadData();

        input.attr("readonly", false).val("").attr("data-id", 0);
        input.parent().find("button").attr("disabled", false);
    });
}

function loadData() {
    $(".btn-delete").addClass("d-none");
    $(".btn-cancel").addClass("d-none");
    $.get(urlGetProduct, (data) => {
        let contents = "";

        for (let i = 0; i < data.length; i++) {
            const unit = data[i];
            contents += `
                            <tr onclick="editUnit(event)" data-id="${unit.id}">
                                <td data-id="${unit.id}">${unit.name}</td>
                            </tr>`;
        }

        $("#table_data").html(contents);
    });
}
