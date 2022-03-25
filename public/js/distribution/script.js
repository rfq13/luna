(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));

$(".number-input").inputFilter(function(value) {
  return /^-?\d*$/.test(value); 
});

$(document).on('input propertychange paste', '.input-notzero', function(e){
  var val = $(this).val()
  var reg = /^0/gi;
  if (val.match(reg)) {
      $(this).val(val.replace(reg, ''));
  }
});

$(function() {
  $("form[name='transaction_form']").validate({
    rules: {
      diskon: "required",
      bayar: "required"
    },
    errorPlacement: function(error, element) {
        var name = element.attr("name");
        $('input[name='+ name +']').addClass('is-invalid');
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});

function subtotalBarang() {
  var subtotal_barang = 0;
  $('.total_barang').each(function(){
    subtotal_barang += parseInt($(this).val());
  });
  $('.nilai-subtotal1-td').html('Rp. ' + parseInt(subtotal_barang).toLocaleString());
  $('.nilai-subtotal2-td').val(subtotal_barang);
}

function diskonBarang() {
  var subtotal = parseInt($('input[name=subtotal]').val());
  var diskon = parseInt($('input[name=diskon]').val() ?? 0);
  var total = subtotal - (subtotal * diskon / 100);
  $('.nilai-total1-td').html('Rp. ' + parseInt(total).toLocaleString());
  $('.nilai-total2-td').val(total);
}

function jumlahBarang(){
  var jumlah_barang = 0;
  $('.jumlah_barang_text').each(function(){
    jumlah_barang += parseInt($(this).val());
  });
  $('.jml-barang-td').html(jumlah_barang + ' Barang').prev().val(jumlah_barang);
}


{/* <p class="jumlah_barang_text">1</p> 
  <span class="ammount-product mr-2" unselectable="on" onselectstart="return false;" onmousedown="return false;">
        </span>
*/}
function tambahData({id,kode_barang:kode, nama_barang:nama, harga, stok}, status) {
  var tambah_data = `
  <tr>
    <td>
      <input type="text" name="kode_barang[]" hidden="" value="`+ kode +`">
      <span class="nama-barang-td">`+ nama +`</span>
      <span class="kode-barang-td">`+ kode +`</span>
    </td>
    <td>
      <input type="text" name="harga_barang[]" hidden="" value="`+ harga +`">
      <span>Rp. `+ parseInt(harga).toLocaleString() +`</span>
    </td>
    <td>
      <div class="d-flex justify-content-start align-items-center">
        <input type="text" name="id_barang[]" hidden="" value="${id}">
        <input type="text" name="jumlah_barang[]" hidden="" value="1">
        <a href="#" class="btn-operate mr-2 btn-tambah">
          <i class="mdi mdi-plus"></i>
        </a>
        <input type="text" style="background-color:transparent;" name="jumlah_barang_text" class="ammount-product mr-2 jumlah_barang_text" value="1" onkeyup="doUpdateStock(this)">
        <a href="#" class="btn-operate btn-kurang">
          <i class="mdi mdi-minus"></i>
        </a>
      </div>
    </td>
    <td>
      <input type="text" class="total_barang" name="total_barang[]" hidden="" value="`+ harga +`">
      <span>Rp. `+ parseInt(harga).toLocaleString() +`</span>
    </td>
    <td>
      <a href="#" class="btn btn-icons btn-rounded btn-secondary ml-1 btn-hapus">
        <i class="mdi mdi-close"></i>
      </a>
    </td>
    <td hidden="">
      <span>`+ stok +`</span>
      <span>`+ status +`</span>
    </td>
  </tr>
  `;
  $('.table-checkout').append(tambah_data);
  subtotalBarang();
  diskonBarang();
  jumlahBarang();
  $('.close-btn').click();
}

let updateStock = 0;

$(document).on('click', '.btn-tambah', function(e){
  e.preventDefault();

  const tdStokAndStatus = $(this).parent().parent().next().next().next().children();
  
  var stok = parseInt(tdStokAndStatus.first().text());
  var status = parseInt(tdStokAndStatus.eq(1).text());
  var jumlah_barang = updateStock > 0 ? updateStock : parseInt($(this).prev().val());
  
  if((stok >= jumlah_barang && status == 1) || status == 0){
    var tambah_barang = updateStock > 0 ? updateStock : (jumlah_barang + 1);
    
    $(this).prev().val(tambah_barang);
    $(this).next().val(tambah_barang); // jumlah_barang_text

    var harga = parseInt($(this).parent().parent().prev().children().first().val());
    var total_barang = harga * tambah_barang;
    $(this).parent().parent().next().children().first().val(total_barang);
    $(this).parent().parent().next().children().eq(1).html('Rp. ' + parseInt(total_barang).toLocaleString());
    subtotalBarang();
    diskonBarang();
    jumlahBarang();
  }
});

$(document).on('click', '.btn-kurang', function(e){
  e.preventDefault();
  var jumlah_barang = updateStock > 0 ? updateStock : parseInt($(this).prev().prev().prev().val());
  
  if(jumlah_barang > 1 || updateStock > 0){
    var kurang_barang = updateStock > 0 ? updateStock : (jumlah_barang - 1);

    $(this).prev().prev().prev().val(kurang_barang);
    $(this).prev().val(kurang_barang); // jumlah_barang_text

    var harga = parseInt($(this).parent().parent().prev().children().first().val());
    var total_barang = harga * kurang_barang;
    $(this).parent().parent().next().children().first().val(total_barang);
    $(this).parent().parent().next().children().eq(1).html('Rp. ' + parseInt(total_barang).toLocaleString());
    subtotalBarang();
    diskonBarang();
    jumlahBarang();
  }
});

$(document).on('click', '.btn-hapus', function(e){
  e.preventDefault();
  $(this).parent().parent().remove();
  subtotalBarang();
  diskonBarang();
  jumlahBarang();
});

$(document).on('click', '.ubah-diskon-td', function(e){
  e.preventDefault();
  $('.diskon-input').prop('hidden', false);
  $('.nilai-diskon-td').prop('hidden', true);
  $('.simpan-diskon-td').prop('hidden', false);
  $(this).prop('hidden', true);
});

$(document).on('click', '.simpan-diskon-td', function(e){
  e.preventDefault();
  $('.diskon-input').prop('hidden', true);
  $('.nilai-diskon-td').prop('hidden', false);
  $('.ubah-diskon-td').prop('hidden', false);
  $(this).prop('hidden', true);
  diskonBarang();
});

$(document).on('input', '.diskon-input', function(){
  $('.nilai-diskon-td').html($(this).val());
  if($(this).val().length > 0){
    $(this).removeClass('is-invalid');
  }else{
    $(this).addClass('is-invalid');
  }
});

$(document).on('input', '.bayar-input', function(){
  if($(this).val().length > 0){
    $(this).removeClass('is-invalid');
    $('.nominal-error').prop('hidden', true);
  }else{
    $(this).addClass('is-invalid');
  }
});

function stopScan(){
  Quagga.stop();
}

$('#scanModal').on('hidden.bs.modal', function(e) {
  $('#area-scan').prop('hidden', true);
  $('#btn-scan-action').prop('hidden', true);
  $('.barcode-result').prop('hidden', true);
  $('.barcode-result-text').html('');
  $('.kode_barang_error').prop('hidden', true);
  stopScan();
});

$(document).ready(function(){
  $('input[name=search]').on('keyup', function(){
    var searchTerm = $(this).val().toLowerCase();
    $(".product-list li").each(function(){
      var lineStr = $(this).text().toLowerCase();
      console.log(lineStr);
      if(lineStr.indexOf(searchTerm) == -1){
        $(this).addClass('non-active-list');
        $(this).removeClass('active-list');
      }else{
        $(this).addClass('active-list');
        $(this).removeClass('non-active-list');
      }
    });
  });
});


function doUpdateStock(e) {
  const target = $(e);

  
  if ([parseInt(target.val()),target.val().length].includes(0)) {
    target.val(1);
  }
  
  const stok = parseInt(target.parent().parent().next().next().next().children().first().text());
  const prevVal = target.parent().children().first().val();

  if (target.val() > stok) {
    target.val(prevVal);
  }

  updateStock = parseInt(target.val());

  if (updateStock > prevVal) {
    target.prev().click();
  }else{
    target.next().click();
  }

  updateStock = 0;
}




// validate transaction_form
function validateTr(e) {
  e.preventDefault();


  const btn = $(e.target);
  const form = btn.parents('form');
  const data = form.serializeArray();

  if (parseInt(form.find('input[name=total_barang]').val()) <= 0) {
    alert('Belum ada barang yang dipilih');
    return;
  }

  if(confirm('pastikan cabang dan barang yang dipilih sudah benar')){
    form.submit();
  }
}


// $("#transaction_form").validate({
//   rules: {
//     'total_barang': {
//       required: true,
//       minlength: 1,
//     },
//     'branch_id': {
//       required: true,
//       minlength: 1,
//     }
//   },
//   messages: {
//     'total_barang': {
//       required: "Kode barang harus diisi",
//       minlength: "Kode barang harus diisi",
//     },
//     'branch_id': {
//       required: "Cabang harus diisi",
//     }
//   },
//   errorElement: "span",
//   errorPlacement: function(error, element) {
//     error.addClass("invalid-feedback");
//     element.closest(".form-group").append(error);
//   }
// });

