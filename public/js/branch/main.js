$(document).on("click", ".upload-btn", function () {
    $("#foto").click();
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#preview-foto").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$("#foto").change(function () {
    readURL(this);
});

$(function () {
    $("form[name='update_form']").validate({
        rules: {
            name: "required",
            address: "required",
            branch_admin: "required",
        },
        messages: {
            name: "Nama tidak boleh kosong",
            address: "Alamat tidak boleh kosong",
            branch_admin: "Silakan pilih admin",
        },
        errorPlacement: function (error, element) {
            var name = element.attr("name");
            $("#" + name + "_error").html(error);
            $("#" + name + "_error")
                .children()
                .addClass("col-form-label");
        },
        submitHandler: function (form) {
            form.submit();
        },
    });
});
