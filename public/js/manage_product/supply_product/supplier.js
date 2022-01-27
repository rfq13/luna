var validator = $("form[name='update_form']").validate({
    rules: {
        name: "required",
    },
    messages: {
        nama_barang: "Nama Supplier tidak boleh kosong",
    },
    errorPlacement: function (error, element) {
        var name = element.attr("name");
        $("#" + name + "_error").html(error);
    },
    submitHandler: function (form) {
        form.submit();
    },
});

$(document)
    .on("click", ".filter-btn", function (e) {
        e.preventDefault();
        var data_filter = $(this).attr("data-filter");
        loadData(urlGetData + data_filter);
    })
    .ready(function () {
        // alert(urlGetData);
        loadData();
    });

$(document).on("click", ".btn-edit", function () {
    var modalTarget = $($(this).attr("data-target"));
    var data_edit = $(this).attr("data-edit");
    $.ajax({
        method: "GET",
        url: urlEditData.replace("idspace", data_edit),
        success: function (supplier) {
            modalTarget.find("input[name=id]").val(supplier.id);
            modalTarget.find("input[name=name]").val(supplier.name);
            validator.resetForm();
        },
    });
});

$(document).on("click", ".btn-delete", function (e) {
    e.preventDefault();
    var data_delete = $(this).attr("data-delete");
    swal({
        title: "Apa Anda Yakin?",
        text: "Data suppplier akan terhapus, klik oke untuk melanjutkan",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            window.open(urlDeleteData + data_delete, "_self");
        }
    });
});

$(document).on("click", ".btn-delete-img", function () {
    $(".img-edit").attr("src", "{{ asset('pictures') }}/default.jpg");
    $("input[name=nama_foto]").val("default.jpg");
});

$("#editModal").on("hidden.bs.modal", () => $("#editModal form input").val(""));

function loadData(url = false) {
    $.ajax({
        method: "GET",
        url: url == false ? urlGetData : url,
        success: function (suppliers) {
            let contents = "";

            for (let i = 0; i < suppliers.length; i++) {
                const supplier = suppliers[i];

                contents += `
                <tr>
                    <td>
                        ${i + 1}
                    </td>
                    <td>
                        ${supplier.name}
                    </td>
                    <td>${supplier.products_count}</td>
                    <td>
                        <button type="button"
                            class="btn btn-edit btn-icons btn-rounded btn-secondary"
                            data-toggle="modal" data-target="#editModal"
                            data-edit="${supplier.id}">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button type="button" data-delete="${supplier.id}"
                            class="btn btn-icons btn-rounded btn-secondary ml-1 btn-delete">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </td>
                </tr>
                `;
            }

            $("tbody").html(contents);
        },
    });
}
