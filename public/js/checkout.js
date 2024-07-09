console.log('test halo min');
let subTotalContent = document.getElementById('subtotal');
// subTotalContent.textContent = subTotal;
let subTotalMoney = Number(subTotalContent.textContent);
let changeIt = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
}).format(subTotalMoney);
subTotalContent.textContent = changeIt;

let total = document.getElementById('total');
total.textContent = subTotalMoney;
let totalMoney = Number(total.textContent);
let ubahDong = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
}).format(totalMoney);
total.textContent = ubahDong;

console.log(jumlahToko);

function validasiForm(){
	for (let index = 1; index <= jumlahToko; index++) {
        if(document.getElementById('merkSepatu-' + index).value === "") return "Nama Merk Sepatu Tidak Boleh Kosong!";
	    else if(document.getElementById('jenisSepatu-' + index).value === "") return "Jenis Sepatu Tidak Boleh Kosong!";
	    else if(document.getElementById('address-' + index) === "") return "Jenis Produk Tidak Boleh Kosong!";
    }
    return true;
}

function alertMain(alertText){
	window.scrollTo(0, 0);
	$("#alert-main").remove();
	$(".alertNih").prepend('<div class="alert alert-warning" id="alert-main" role="alert">'+alertText+'</div>');
}

// function orderButton(){
//     var validasi = validasiForm();
//     if(validasi == true){
//         document.getElementById('submitForm').submit();
//     }else{
//         alertMain(validasi);
//     }
// }
let totalHargaAjax = document.getElementById('totalJs').value;
var tokenHasil = "";
function generateToken() {

    $.ajax({
        url: '/generateToken',
        type: 'POST',
        data: {
            price: totalHargaAjax
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            tokenHasil = response;
            window.snap.pay(tokenHasil, {
                onSuccess: function(result){
                  /* You may add your own implementation here */
                    alert("payment success!"); console.log(result);
                    document.getElementById('orderButton').disabled=true;
                    document.getElementById('paymentStatus-hidden').value=1;
                    document.getElementById('submitForm').submit();
                },
                onPending: function(result){
                  /* You may add your own implementation here */
                    alert("wating your payment!");
                    console.log(result);
                    document.getElementById('orderButton').disabled=true;
                    document.getElementById('paymentStatus-hidden').value=-1;
                    document.getElementById('submitForm').submit();
                },
                onError: function(result){
                  /* You may add your own implementation here */
                    alert("payment failed!"); console.log(result);
                    document.getElementById('orderButton').disabled=true;
                    document.getElementById('paymentStatus-hidden').value=-1;
                    document.getElementById('submitForm').submit();
                },
                onClose: function(){
                  /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                    document.getElementById('orderButton').disabled=true;
                    document.getElementById('paymentStatus-hidden').value=-1;
                    document.getElementById('submitForm').submit();
                }
            })
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            alert('There was an error generating the transaction token.');
            // token = 0;
        }
    });

    // return token;
}


document.getElementById('orderButton').addEventListener('click', function(){
    var validasi = validasiForm();
    // var validasi = true;
    if(validasi == true){
        generateToken();
        // console.log('ini tokennya');
        // console.log(tokenHasil);
        // asalajayangpentinggagal();
        // document.getElementById('submitForm').submit();
    }else{
        alertMain(validasi);
    }
});
