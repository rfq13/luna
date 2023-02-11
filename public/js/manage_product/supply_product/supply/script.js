$(document).ready(function () {
    // $(".total-field").each(function () {
    //     var harga = $(this).prev().children().first().val();
    //     var jumlah = $(this).prev().prev().text();
    //     var total = parseFloat(harga) * parseFloat(jumlah);
    //     $(this).text("- Rp. " + parseFloat(total).toLocaleString() + ",00");
    // });

    $("input[name=search]").on("keyup", function () {
        var searchTerm = $(this).val().toLowerCase();
        $(".list-date table").each(function () {
            var lineStr = $(this).text().toLowerCase();
            if (lineStr.indexOf(searchTerm) == -1) {
                $(this).hide();
                $(this).parent().prev().hide();
            } else {
                $(this).show();
                $(this).parent().prev().show();
            }
        });
    });
});

$(".dropdown-search").on("hide.bs.dropdown", function () {
    $(".list-date table").show();
    $(".list-date table").parent().prev().show();
    $("input[name=search]").val("");
});
