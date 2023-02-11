var customer = {};

(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on(
            "input keydown keyup mousedown mouseup select contextmenu drop",
            function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(
                        this.oldSelectionStart,
                        this.oldSelectionEnd
                    );
                } else {
                    this.value = "";
                }
            }
        );
    };
})(jQuery);

$(".number-input").inputFilter(function (value) {
    return /^-?\d*$/.test(value);
});

$(document).on("input propertychange paste", ".input-notzero", function (e) {
    var val = $(this).val();
    var reg = /^0/gi;
    if (val.match(reg)) {
        $(this).val(val.replace(reg, ""));
    }
});

$(".tipe-customer").on("change", function () {
    console.log($(this).val());
    $(".table-checkout tr").each(function () {
        const el = $(this);
        const produk = decrypt(el.data("raw"));
        const status = el.data("status");
        el.remove();
        tambahData(produk, status);
    });
});

$(function () {
    $("form[name='transaction_form']").validate({
        rules: {
            diskon: "required",
            bayar: "required",
        },
        errorPlacement: function (error, element) {
            var name = element.attr("name");
            $("input[name=" + name + "]").addClass("is-invalid");
        },
        submitHandler: function (form) {
            form.submit();
        },
    });
});

function subtotalBarang() {
    var subtotal_barang = 0;

    $(".total_barang").each(function () {
        subtotal_barang += parseInt($(this).val());
    });

    const ppnEl = $(".ppn-td");
    let total_ppn = 0;
    if (ppnEl.length) {
        const ppn = parseInt(ppnEl.data("ppn"));
        total_ppn = (subtotal_barang * ppn) / 100;
        subtotal_barang += total_ppn;

        $(".ppn-input").val(total_ppn);

        $(".nilai-ppn-span").text(`Rp.${toRupiah(total_ppn)}`);
    }

    $(".nilai-subtotal1-td").html(
        "Rp. " + parseInt(subtotal_barang - total_ppn).toLocaleString()
    );
    $(".nilai-subtotal2-td").val(subtotal_barang);

    $("[name='total_dp']").attr("max", subtotal_barang);
}

function diskonBarang() {
    var subtotal = parseInt($("input[name=subtotal]").val());
    var diskon = parseInt($("input[name=diskon]").val());
    var total = subtotal - (subtotal * diskon) / 100;
    $(".nilai-total1-td").html("Rp. " + parseInt(total).toLocaleString());
    $(".nilai-total2-td").val(total);
    $("[name='total_dp']").attr("max", total);
}

function jumlahBarang() {
    var jumlah_barang = 0;
    $(".jumlah_barang_text").each(function () {
        jumlah_barang += parseInt($(this).text());
    });
    $(".jml-barang-td").html(jumlah_barang + " Barang");
}

function checkKredit() {
    var total = isKredit() ? $(".nilai-total2-td").val() : 0;

    $(".bayar-input").val(total).trigger("keyup");
}

function tambahData(produk, status, withTr = true) {
    const tipe_customer = $(".tipe-customer").val();

    console.log({ produk, tipe_customer }, "klanceng");

    const { kode_barang, nama_barang, stok } = produk;

    let tambah_data =
        `<td><input type="text" name="kode_barang[]" hidden="" value="` +
        kode_barang +
        '"><span class="nama-barang-td">' +
        nama_barang +
        '</span><span class="kode-barang-td">' +
        kode_barang +
        '</span></td><td><input type="text" name="harga_barang[]" hidden="" value="' +
        (produk[`harga_${tipe_customer}`] || 0) +
        '"><span>Rp. ' +
        parseInt(produk[`harga_${tipe_customer}`] || 0).toLocaleString() +
        `</span></td>
        <td>
            <div class="d-flex justify-content-start align-items-center jumlah-barang-wrapper">
                <input type="text" name="jumlah_barang[]" hidden="" value="1">
                <a href="#" class="btn-operate mr-2 btn-tambah">
                    <i class="mdi mdi-plus"></i>
                </a>
                <span class="ammount-product mr-2">
                <input type="text" style="background-color:transparent;" name="jumlah_barang_text" class="ammount-product mr-2 jumlah_barang_text" value="1" onkeyup="doUpdateStock(event)">
                </span>
                <a href="#" class="btn-operate btn-kurang">
                    <i class="mdi mdi-minus"></i>
                </a>
            </div>
        </td>
        <td>
        <input type="text" class="total_barang" name="total_barang[]" hidden="" value="` +
        (produk[`harga_${tipe_customer}`] || 0) +
        '"><span>Rp. ' +
        parseInt(produk[`harga_${tipe_customer}`] || 0).toLocaleString() +
        '</span></td><td><a href="#" class="btn btn-icons btn-rounded btn-secondary ml-1 btn-hapus"><i class="mdi mdi-close"></i></a></td><td hidden=""><span>' +
        stok +
        "</span><span>" +
        status +
        "</span></td>";

    if (withTr) {
        tambah_data = `<tr id="product${
            produk.id
        }" data-status="${status}" data-raw="${encrypt(
            produk
        )}">${tambah_data}</tr>`;
    }

    $(".table-checkout").append(tambah_data);
    subtotalBarang();
    diskonBarang();
    jumlahBarang();
    checkKredit();
    $(".close-btn").click();
}

$(document).on("click", ".btn-tambah", function (e) {
    e.preventDefault();
    var stok = parseInt(
        $(this).parent().parent().next().next().next().children().first().text()
    );
    var status = parseInt(
        $(this).parent().parent().next().next().next().children().eq(1).text()
    );

    var jumlah_barang = parseInt($(this).prev().val());
    if ((stok > jumlah_barang && status == 1) || status == 0) {
        var tambah_barang = jumlah_barang + 1;
        $(this).prev().val(tambah_barang);
        $(this).next().children().first().val(tambah_barang);
        var harga = parseInt(
            $(this).parent().parent().prev().children().first().val()
        );
        var total_barang = harga * tambah_barang;
        $(this).parent().parent().next().children().first().val(total_barang);

        $(this)
            .parent()
            .parent()
            .next()
            .children()
            .eq(1)
            .html("Rp. " + parseInt(total_barang).toLocaleString());
        subtotalBarang();
        diskonBarang();
        jumlahBarang();
    }
});

$(document).on("click", ".btn-kurang", function (e) {
    e.preventDefault();
    var jumlah_barang = parseInt($(this).prev().prev().prev().val());
    if (jumlah_barang > 1) {
        var kurang_barang = jumlah_barang - 1;
        $(this).prev().prev().prev().val(kurang_barang);
        $(this).prev().children().first().val(kurang_barang);
        var harga = parseInt(
            $(this).parent().parent().prev().children().first().val()
        );
        var total_barang = harga * kurang_barang;
        $(this).parent().parent().next().children().first().val(total_barang);
        $(this)
            .parent()
            .parent()
            .next()
            .children()
            .eq(1)
            .html("Rp. " + parseInt(total_barang).toLocaleString());
        subtotalBarang();
        diskonBarang();
        jumlahBarang();
    }
});
function doUpdateStock(e) {
    e.preventDefault();

    const target = $(e.target);
    const tr = target.parents("tr");

    const td = tr.find("td").last().children();
    var stok = parseInt(td.first().text());
    var status = parseInt(td.eq(1).text());

    // var jumlah_barang = parseInt(target.prev().prev().prev().val());
    var jumlah_barang = parseFloat(target.val());
    if ((stok >= jumlah_barang && status == 1) || status == 0) {
        var tambah_barang = jumlah_barang;

        const jmlBrang = target
            .parents(".jumlah-barang-wrapper")
            .find("[name='jumlah_barang[]']");

        jmlBrang.val(tambah_barang);

        var harga = parseInt(tr.find("[name='harga_barang[]']").val());
        var total_barang = harga * tambah_barang;
        tr.find("[name='total_barang[]']").val(total_barang);
        tr.find("[name='total_barang[]']")
            .next()
            .html("Rp. " + parseInt(total_barang).toLocaleString());
        subtotalBarang();
        diskonBarang();
        jumlahBarang();
    } else if (stok < jumlah_barang && status == 1) {
        target.val(stok).trigger("keyup");
    }
}

$(document).on("click", ".btn-hapus", function (e) {
    e.preventDefault();
    $(this).parent().parent().remove();
    subtotalBarang();
    diskonBarang();
    jumlahBarang();
});

$(document).on("click", ".ubah-diskon-td", function (e) {
    e.preventDefault();
    $(".diskon-input").prop("hidden", false);
    $(".nilai-diskon-td").prop("hidden", true);
    $(".simpan-diskon-td").prop("hidden", false);
    $(this).prop("hidden", true);
});

$(document).on("click", ".simpan-diskon-td", function (e) {
    e.preventDefault();
    $(".diskon-input").prop("hidden", true);
    $(".nilai-diskon-td").prop("hidden", false);
    $(".ubah-diskon-td").prop("hidden", false);
    $(this).prop("hidden", true);
    diskonBarang();
});

$(document).on("click", ".ubah-plafon-td", function (e) {
    e.preventDefault();
    $(".plafon-input").prop("hidden", false);
    $(".nilai-plafon-td").prop("hidden", true);
    $(".simpan-plafon-td").prop("hidden", false);
    $(this).prop("hidden", true);
});

$(document).on("click", ".simpan-plafon-td", function (e) {
    e.preventDefault();
    $(".plafon-input").prop("hidden", true);
    $(".nilai-plafon-td").prop("hidden", false);
    $(".ubah-plafon-td").prop("hidden", false);
    $(this).prop("hidden", true);
    ubahPlafon();
});

$(document).on("input", ".diskon-input", function () {
    $(".nilai-diskon-td").html($(this).val());
    if ($(this).val().length > 0) {
        $(this).removeClass("is-invalid");
    } else {
        $(this).addClass("is-invalid");
    }
});

$(document).on("input", ".bayar-input", function () {
    if ($(this).val().length > 0) {
        $(this).removeClass("is-invalid");
        $(".nominal-error").prop("hidden", true);
    } else {
        $(this).addClass("is-invalid");
    }
});

function stopScan() {
    Quagga.stop();
}

$("#scanModal").on("hidden.bs.modal", function (e) {
    $("#area-scan").prop("hidden", true);
    $("#btn-scan-action").prop("hidden", true);
    $(".barcode-result").prop("hidden", true);
    $(".barcode-result-text").html("");
    $(".kode_barang_error").prop("hidden", true);
    stopScan();
});

$(document).ready(function () {
    $("input[name=search]").on("keyup", function () {
        var searchTerm = $(this).val().toLowerCase();
        $(".product-list li").each(function () {
            var lineStr = $(this).text().toLowerCase();
            console.log(lineStr);
            if (lineStr.indexOf(searchTerm) == -1) {
                $(this).addClass("non-active-list");
                $(this).removeClass("active-list");
            } else {
                $(this).addClass("active-list");
                $(this).removeClass("non-active-list");
            }
        });
    });
});

function encrypt(data) {
    return window.btoa(JSON.stringify(data));
}

function decrypt(data) {
    return JSON.parse(window.atob(data));
}

function ubahPlafon() {
    var plafon = $(".plafon-input").val();

    if (plafon.length > 0 && customer.id) {
        $.ajax({
            url: "/customer/" + customer.id,
            method: "PUT",
            data: {
                _token: $("[name='_token']").val(),
                plafon: plafon,
            },
            success: function (response) {
                console.log(response, "adalh");
                if (response.status) {
                    toastr.success("Plafon berhasil diubah");
                } else {
                    toastr.error("Plafon gagal diubah");
                }
            },
            error: function (response) {
                console.log(response);
                toastr.error("Plafon gagal diubah");
            },
        });
    }
}

function getCustomerByNik(nik, cb = () => {}) {
    $.ajax({
        url: "customer/" + nik + "/nik",
        method: "GET",
        success: function (response) {
            if (response.customer) {
                customer.nama = response.customer.nama;
                customer.alamat = response.customer.alamat;
                customer.nohp = response.customer.nohp;
                customer.npwp = response.customer.npwp;
                customer.id = response.customer.id;
                customer.plafon = response.customer.plafon;
                customer.sisa_plafon = response.customer.sisa_plafon;

                $("[name='nama_customer']").val(customer.nama);
                $("[name='alamat_customer']").val(customer.alamat);
                $("[name='nohp_customer']").val(customer.nohp);
                $("[name='npwp_customer']").val(customer.npwp);
                $(".total-plafon input")
                    .val(customer.plafon || 0)
                    .trigger("keyup");
                $(".sisa-plafon input")
                    .val(customer.sisa_plafon || 0)
                    .trigger("change");
            } else {
                customer.name = null;
                customer.alamat = null;
                customer.nohp = null;
                customer.npwp = null;
                customer.id = null;
                customer.plafon = null;
                customer.sisa_plafon = null;

                toastr.warning(
                    "Customer tidak ditemukan, pastikan semua data sesuai untuk menambahkan customer baru"
                );
                $("[name='nama_customer']").val("");
                $("[name='alamat_customer']").val("");
                $("[name='nohp_customer']").val("");
                $("[name='npwp_customer']").val("");
            }

            cb();
        },
    });
}

function fillNilaiPlafon(e, comp) {
    var target = $(comp);

    var plafonBaru = parseInt(comp.value);
    var plafonAwal = parseInt(customer?.plafon || 0);
    var sisaPlafon = parseInt(customer?.sisa_plafon || 0);
    var totalDp = getNumberFromString(`${$("[name='total_dp']").val() || 0}`);

    // car sisa plafon setelah di tambah
    var sisaPlafonBaru;
    if (plafonBaru > plafonAwal) {
        sisaPlafonBaru = sisaPlafon + (plafonBaru - plafonAwal);
    } else {
        sisaPlafonBaru = sisaPlafon - (plafonAwal - plafonBaru);
    }

    console.log("calculate plafon", {
        plafonBaru,
        plafonAwal,
        sisaPlafon,
        sisaPlafonBaru,
    });
    // set sisa plafon
    $(".sisa-plafon input")
        .val(sisaPlafonBaru - totalDp)
        .trigger("change");

    $(".nilai-plafon-td").text(toRupiah(plafonBaru));

    editCustomerState("plafon", comp.value);
    editCustomerState("sisa_plafon", sisaPlafonBaru);
}

function editCustomerState(field, value) {
    console.log({
        field,
        value,
    });
    customer[field] = value;
}

function startScan() {
    Quagga.init(
        {
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector("#area-scan"),
            },
            decoder: {
                readers: ["ean_reader"],
                multiple: false,
            },
            locate: false,
        },
        function (err) {
            if (err) {
                console.log(err);
                return;
            }
            console.log("Initialization finished. Ready to start");
            Quagga.start();
        }
    );

    Quagga.onDetected(function (data) {
        $("#area-scan").prop("hidden", true);
        $("#btn-scan-action").prop("hidden", false);
        $(".barcode-result").prop("hidden", false);
        $(".barcode-result-text").html(data.codeResult.code);
        $(".kode_barang_error").prop("hidden", true);
        stopScan();
    });
}

function changeDp(e, comp) {
    var target = $(comp);
    var dp = parseInt(target.val());
    var total = parseInt($(".nilai-total2-td").val());

    // dp is in percent
    var dpAmount = (dp / 100) * total;

    // var total = parseInt($(".total-harga").text());
    // var sisa = total - dp;

    // $(".sisa-harga").text(toRupiah(sisa));
    $(".dp-percent").text(dp);
    $("[name='total_dp']").val(dpAmount).trigger("change");
}

function changeTotalDP(e) {
    e.preventDefault();
    var target = $(e.target);
    var value = getNumberFromString(target.val());
    var max = parseInt(target.attr("max"));

    if (value > max) {
        value = getNumberFromString(`${customer.total_dp || 0}`);
    } else {
        editCustomerState("total_dp", value);

        const sp = $(".sisa-plafon input");
        // const spval = getNumberFromString(`${sp.val() || 0}`);

        sp.val(
            getNumberFromString(`${customer.sisa_plafon || 0}`) - value
        ).trigger("change");
    }

    // console.log("Total DP", {
    //     value,
    //     newVal: target.val(),
    //     currentTDP: customer.total_dp,
    //     max,
    // });

    target.val(toRupiah(value));

    tenorChange();
}
function sisaplafonChange(e) {
    console.log("sisa plafon change");
    e.preventDefault();

    const target = $(e.target);
    const value = getNumberFromString(target.val() || "0");

    // editCustomerState("sisa_plafon", value);
    target.val("Rp." + toRupiah(value));
}

function changePaymentMethod(params) {
    $("[name='is_kredit'][value='0']").prop("checked", params != "kredit");
    $("[name='is_kredit'][value='1']").prop("checked", params == "kredit");
}

function isKredit() {
    return $("[name='is_kredit']:checked").val() == "1";
}

function tenorChange() {
    const tenor = parseInt($("[name='tenor']").val() || 1);
    const tenorUnit = $("[name='tenor_unit']").val();
    const totalDp = getNumberFromString(`${$("[name='total_dp']").val() || 0}`);
    const total = parseInt($(".nilai-total2-td").val());

    const pembagi = {
        bulan: 4,
        minggu: 7,
        tahun: 12,
    };
    const pembagiTitle = {
        bulan: "Minggu",
        minggu: "Hari",
        tahun: "Bulan",
    };

    const hasil = Math.ceil(
        parseFloat((total - totalDp) / (pembagi[tenorUnit] * tenor))
    );

    $(".cicilan-amount").text(`Rp.${toRupiah(hasil)}`);
    $(".tenor-unit-span").text(pembagiTitle[tenorUnit]);

    console.log(
        "hasil hitung cicilan per ",
        pembagiTitle[tenorUnit],
        ": ",
        hasil
    );
}
