<?php

use SilverStripe\Control\Email\Email;
use SilverStripe\i18n\i18n;
use Sunnysideup\Ecommerce\Model\Order;
use Sunnysideup\Ecommerce\Pages\AccountPage;
use Sunnysideup\Ecommerce\Pages\CheckoutPage;

/**
 * Indonesian (Indonesia) language pack.
 */
i18n::include_locale_file('modules: ecommerce', 'en_US');

global $lang;

if (array_key_exists('id_ID', $lang) && is_array($lang['id_ID'])) {
    $lang['id_ID'] = array_merge($lang['en_US'], $lang['id_ID']);
} else {
    $lang['id_ID'] = $lang['en_US'];
}

$lang['id_ID'][AccountPage::class]['Message'] = 'Anda perlu login sebelon dapat mengakses halaman account. Jika anda tidak terdaftar, anda tidak akan dapat mengakses halaman tersebut sampai anda membuat pesanan pertama anda, atau anda bisa masukkan perincian anda di bawah ini.';
$lang['id_ID'][AccountPage::class]['NOPAGE'] = 'Tidak ada HalamanAccount di situs ini - mohon dibuat!';
$lang['id_ID']['AccountPage.ss']['COMPLETED'] = 'Pemesanan Yang Telah Diselesaikan';
$lang['id_ID']['AccountPage.ss']['HISTORY'] = 'Sejarah Pesanan Anda';
$lang['id_ID']['AccountPage.ss']['INCOMPLETE'] = 'Pemesanan yang Belum Selesai';
$lang['id_ID']['AccountPage.ss']['Message'] = 'Mohon masukan detail-detail anda ke halaman account.<br />Halaman ini hanya bisa diakses setelah pesanan pertama anda, setelah anda diberikan kata sandi.';
$lang['id_ID']['AccountPage.ss']['NOCOMPLETED'] = 'Tidak ada pemesanan yang telah selesai yang ditemukan';
$lang['id_ID']['AccountPage.ss']['NOINCOMPLETE'] = 'Tidak ada pemesanan yang belum selesai yang ditemukan.';
$lang['id_ID']['AccountPage.ss']['ORDER'] = '# Pemesanan';
$lang['id_ID']['AccountPage.ss']['READMORE'] = 'Baca lagi mengenai #%s Pemesanan';
$lang['id_ID']['AccountPage_order.ss']['ADDRESS'] = 'Alamat';
$lang['id_ID']['AccountPage_order.ss']['AMOUNT'] = 'Jumlah';
$lang['id_ID']['AccountPage_order.ss']['CITY'] = 'Kota';
$lang['id_ID']['AccountPage_order.ss']['COUNTRY'] = 'Negara';
$lang['id_ID']['AccountPage_order.ss']['DATE'] = 'Tanggal';
$lang['id_ID']['AccountPage_order.ss']['DETAILS'] = 'Perincian';
$lang['id_ID']['AccountPage_order.ss']['EMAILDETAILS'] = 'Salinan ini telah dikirim ke alamat email anda untuk konfirmasi perincian pemesanan.';
$lang['id_ID']['AccountPage_order.ss']['NAME'] = 'Nama';
$lang['id_ID']['AccountPage_order.ss']['PAYMENTMETHOD'] = 'Metode';
$lang['id_ID']['AccountPage_order.ss']['PAYMENTSTATUS'] = 'Status Pembayaran';
$lang['id_ID']['Cart.ss']['CheckoutClick'] = 'Klik disini untuk menuju pemeriksaan akhir';
$lang['id_ID']['Cart.ss']['CheckoutGoTo'] = 'Ke pemeriksaan akhir';
$lang['id_ID']['Cart.ss']['HEADLINE'] = 'Keranjang Saya';
$lang['id_ID']['Cart.ss']['NOITEMS'] = 'Tidak ada barang dalam keranjang anda';
$lang['id_ID']['Cart.ss']['PRICE'] = 'Harga';
$lang['id_ID']['Cart.ss']['READMORE'] = 'Klik di sini untuk membaca kelanjutan dari &quot;%s&quot;';
$lang['id_ID']['Cart.ss']['Remove'] = 'Hapus &quot;%s&quot; dari keranjang Anda';
$lang['id_ID']['Cart.ss']['RemoveAlt'] = 'Buang';
$lang['id_ID']['Cart.ss']['SHIPPING'] = 'Pengiriman';
$lang['id_ID']['Cart.ss']['SUBTOTAL'] = 'Sub total';
$lang['id_ID']['Cart.ss']['TOTAL'] = 'Total';
$lang['id_ID'][CheckoutPage::class]['NOPAGE'] = 'Tidak ada CheckoutPage di situs ini - mohon buatlah satu !';
$lang['id_ID']['CheckoutPage.ss']['CHECKOUT'] = 'Checkout';
$lang['id_ID']['CheckoutPage.ss']['ORDERSTEP'] = 'Status Pemesanan';
$lang['id_ID']['CheckoutPage.ss']['PROCESS'] = 'Proses';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['BACKTOCHECKOUT'] = 'Klik di sini untuk kembali ke Checkout';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['CHECKOUT'] = 'Checkout';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['CHEQUEINSTRUCTIONS'] = 'Bila anda memesan menggunakan cek anda akan menerima email dengan instruksi-instruksi.';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['DETAILSSUBMITTED'] = 'Inilah perincian-perincian yang telah anda ajukan';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['INCOMPLETE'] = 'Pemesanan Tidak Selesai';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['ORDERSTEP'] = 'Status Pemesanan';
$lang['id_ID']['CheckoutPage_OrderIncomplete.ss']['PROCESS'] = 'Proses';
$lang['id_ID']['CheckoutPage_OrderSuccessful.ss']['BACKTOCHECKOUT'] = 'Klik di sini untuk kembali ke Checkout';
$lang['id_ID']['CheckoutPage_OrderSuccessful.ss']['CHECKOUT'] = 'Checkout';
$lang['id_ID']['CheckoutPage_OrderSuccessful.ss']['EMAILDETAILS'] = 'Copy dari ini telah dikirim ke alamat email anda untuk menkonfirmasi perincian-perincian pemesanan.';
$lang['id_ID']['CheckoutPage_OrderSuccessful.ss']['ORDERSTEP'] = 'Status Pemesanan';
$lang['id_ID']['CheckoutPage_OrderSuccessful.ss']['PROCESS'] = 'Proses';
$lang['id_ID']['CheckoutPage_OrderSuccessful.ss']['SUCCESSFULl'] = 'Order Sukses';
$lang['id_ID']['ChequePayment']['MESSAGE'] = 'Pembelian diterima melalui cek. Mohon dicatat: produk tidak akan dikirim sebelum pembelian sudah diterima.';
$lang['id_ID']['DataReport']['EXPORTCSV'] = 'Ekspor ke CSV';
$lang['id_ID']['FindOrderReport']['DATERANGE'] = 'Rentang Tanggal';
$lang['id_ID']['MemberForm']['DETAILSSAVED'] = 'Perincian anda telah disimpan.';
$lang['id_ID']['MemberForm']['LOGGEDIN'] = 'Saat ini Anda masuk log sebagai';
$lang['id_ID'][Order::class]['INCOMPLETE'] = 'Pemesanan Tidak Selesai';
$lang['id_ID'][Order::class]['SUCCESSFULL'] = 'Pemesanan Sukses';
$lang['id_ID']['OrderInformation.ss']['ADDRESS'] = 'Alamat';
$lang['id_ID']['OrderInformation.ss']['AMOUNT'] = 'Nominal';
$lang['id_ID']['OrderInformation.ss']['BUYERSADDRESS'] = 'Alamat Pembeli';
$lang['id_ID']['OrderInformation.ss']['CITY'] = 'Kota';
$lang['id_ID']['OrderInformation.ss']['COUNTRY'] = 'Negara';
$lang['id_ID']['OrderInformation.ss']['CUSTOMERDETAILS'] = 'Perincian Pelanggan';
$lang['id_ID']['OrderInformation.ss']['DATE'] = 'Tanggal';
$lang['id_ID']['OrderInformation.ss']['DETAILS'] = 'Keterangan';
$lang['id_ID']['OrderInformation.ss']['EMAIL'] = Email::class;
$lang['id_ID']['OrderInformation.ss']['MOBILE'] = 'Telepon Genggam';
$lang['id_ID']['OrderInformation.ss']['NAME'] = 'Nama';
$lang['id_ID']['OrderInformation.ss']['ORDERSUMMARY'] = 'Perincian Pemesanan';
$lang['id_ID']['OrderInformation.ss']['PAYMENTID'] = 'ID Pembayaran';
$lang['id_ID']['OrderInformation.ss']['PAYMENTINFORMATION'] = 'Informasi Pembayaran';
$lang['id_ID']['OrderInformation.ss']['PAYMENTMETHOD'] = 'Metode';
$lang['id_ID']['OrderInformation.ss']['PAYMENTSTATUS'] = 'Status Pembayaran';
$lang['id_ID']['OrderInformation.ss']['PHONE'] = 'Telepon';
$lang['id_ID']['OrderInformation.ss']['PRICE'] = 'Harga';
$lang['id_ID']['OrderInformation.ss']['PRODUCT'] = 'Produk';
$lang['id_ID']['OrderInformation.ss']['QUANTITY'] = 'Jumlah Barang';
$lang['id_ID']['OrderInformation.ss']['READMORE'] = 'Klik di sini untuk membaca lebih lanjut tentang &quot;%s&quot;';
$lang['id_ID']['OrderInformation.ss']['SHIPPING'] = 'Pengiriman';
$lang['id_ID']['OrderInformation.ss']['SHIPPINGDETAILS'] = 'Perincian Pengiriman';
$lang['id_ID']['OrderInformation.ss']['SHIPPINGTO'] = 'ke';
$lang['id_ID']['OrderInformation.ss']['SUBTOTAL'] = 'Sub-total';
$lang['id_ID']['OrderInformation.ss']['TABLESUMMARY'] = 'Isi keranjang anda akan ditampilkan dalam form ini dan ringkasan dari semua biaya terkait dengan pembelian dan laporan dari pilihan pembayaran.';
$lang['id_ID']['OrderInformation.ss']['TOTAL'] = 'Jumlah';
$lang['id_ID']['OrderInformation.ss']['TOTALl'] = 'Total';
$lang['id_ID']['OrderInformation.ss']['TOTALOUTSTANDING'] = 'Total Keseluruhan';
$lang['id_ID']['OrderInformation.ss']['TOTALPRICE'] = 'Jumlah Biaya';
$lang['id_ID']['OrderInformation_Editable.ss']['ADDONE'] = 'Tambahkan satu &quot;%s&quot; ke keranjang Anda';
$lang['id_ID']['OrderInformation_Editable.ss']['NOITEMS'] = '<strong>Tidak ada</strong> barang dalam keranjang Anda.';
$lang['id_ID']['OrderInformation_Editable.ss']['ORDERINFORMATION'] = 'Informasi Pemesanan';
$lang['id_ID']['OrderInformation_Editable.ss']['PRICE'] = 'Harga';
$lang['id_ID']['OrderInformation_Editable.ss']['PRODUCT'] = 'Produk';
$lang['id_ID']['OrderInformation_Editable.ss']['QUANTITY'] = 'Kuantitas';
$lang['id_ID']['OrderInformation_Editable.ss']['READMORE'] = 'Klik di sini untuk membaca lebih lanjut tentang &quot;%s&quot;';
$lang['id_ID']['OrderInformation_Editable.ss']['REMOVEONE'] = 'Buang satu &quot;%s&quot; dari keranjang Anda';
$lang['id_ID']['OrderInformation_Editable.ss']['SHIPPING'] = 'Pengiriman';
$lang['id_ID']['OrderInformation_Editable.ss']['SHIPPINGTO'] = 'ke';
$lang['id_ID']['OrderInformation_Editable.ss']['SUBTOTAL'] = 'Sub-total';
$lang['id_ID']['OrderInformation_Editable.ss']['TABLESUMMARY'] = 'Isi keranjang Anda ditampilkan pada form ini dan ringkasan dari seluruh biaya terkait dengan suatu pesanan dan catatan pilihan pembayaran.';
$lang['id_ID']['OrderInformation_Editable.ss']['TOTAL'] = 'Total';
$lang['id_ID']['OrderInformation_Editable.ss']['TOTALPRICE'] = 'Harga Total';
$lang['id_ID']['OrderInformation_NoPricing.ss']['ADDRESS'] = 'Alamat';
$lang['id_ID']['OrderInformation_NoPricing.ss']['BUYERSADDRESS'] = 'Alamat Pembeli';
$lang['id_ID']['OrderInformation_NoPricing.ss']['CITY'] = 'Kota';
$lang['id_ID']['OrderInformation_NoPricing.ss']['COUNTRY'] = 'Negara';
$lang['id_ID']['OrderInformation_NoPricing.ss']['CUSTOMERDETAILS'] = 'Perincian Pelanggan';
$lang['id_ID']['OrderInformation_NoPricing.ss']['EMAIL'] = Email::class;
$lang['id_ID']['OrderInformation_NoPricing.ss']['MOBILE'] = 'Telepon Genggam';
$lang['id_ID']['OrderInformation_NoPricing.ss']['NAME'] = 'Nama';
$lang['id_ID']['OrderInformation_NoPricing.ss']['ORDERINFO'] = 'Informasi untuk Pemesanan #';
$lang['id_ID']['OrderInformation_NoPricing.ss']['PHONE'] = 'Telepon';
$lang['id_ID']['OrderInformation_NoPricing.ss']['TABLESUMMARY'] = 'Isi keranjang Anda ditampilkan pada form ini dan ringkasan dari seluruh biaya terkait dengan suatu pesanan dan catatan pilihan pembayaran.';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['DESCRIPTION'] = 'Deskripsi';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['ITEM'] = 'Barang';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['ORDERDATE'] = 'Tanggal Pemesanan';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['ORDERNUMBER'] = 'Nomer Pemesanan';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['PAGETITLE'] = 'Cetakan Pesanan Toko';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['QUANTITY'] = 'Kuantitas';
$lang['id_ID']['OrderInformation_PackingSlip.ss']['TABLESUMMARY'] = 'Isi keranjang Anda ditampilkan pada form ini dan ringkasan dari seluruh biaya terkait dengan suatu pesanan dan catatan pilihan pembayaran.';
$lang['id_ID']['OrderInformation_Print.ss']['PAGETITLE'] = 'Cetak Pesanan';
$lang['id_ID']['OrderReport']['CHANGESTATUS'] = 'Ubah Status Pemesanan';
$lang['id_ID']['OrderReport']['NOTEEMAIL'] = 'Catatan/Email';
$lang['id_ID']['OrderReport']['PRINTEACHORDER'] = 'Cetak semua pemesanan yang terlihat';
$lang['id_ID']['OrderReport']['SENDNOTETO'] = 'Kirim catatan ini kepada %s (%s)';
$lang['id_ID']['Order_Content.ss']['PRICE'] = 'Harga';
$lang['id_ID']['Order_Content.ss']['PRODUCT'] = 'Produk';
$lang['id_ID']['Order_Content.ss']['QUANTITY'] = 'Kuantitas';
$lang['id_ID']['Order_Content.ss']['READMORE'] = 'Klik di sini untuk membaca lebih lanjut tentang &quot;%s&quot;';
$lang['id_ID']['Order_Content.ss']['TOTAL'] = 'Jumlah';
$lang['id_ID']['Order_Content.ss']['TOTALPRICE'] = 'Jumlah Harga';
$lang['id_ID']['Order_Member.ss']['ADDRESS'] = 'Alamat';
$lang['id_ID']['Order_Member.ss']['CITY'] = 'Kota';
$lang['id_ID']['Order_Member.ss']['COUNTRY'] = 'Negara';
$lang['id_ID']['Order_Member.ss']['EMAIL'] = Email::class;
$lang['id_ID']['Order_Member.ss']['MOBILE'] = 'Telepon Genggam';
$lang['id_ID']['Order_Member.ss']['NAME'] = 'Nama';
$lang['id_ID']['Order_Member.ss']['PHONE'] = 'Telepon';
$lang['id_ID']['Order_receiptEmail.ss']['HEADLINE'] = 'Bon Pemesanan dari Toko';
$lang['id_ID']['Order_receiptEmail.ss']['TITLE'] = 'Bon dari Toko';
$lang['id_ID']['Order_statusEmail.ss']['HEADLINE'] = 'Pergantian Status Pembelian';
$lang['id_ID']['Order_statusEmail.ss']['STATUSCHANGE'] = 'Status diganti menjadi "%s" untuk Pemesanan #';
$lang['id_ID']['Order_statusEmail.ss']['TITLE'] = 'Status Toko Diubah';
$lang['id_ID']['PaymentInformation.ss']['AMOUNT'] = 'Jumlah';
$lang['id_ID']['PaymentInformation.ss']['DATE'] = 'Tanggal';
$lang['id_ID']['PaymentInformation.ss']['DETAILS'] = 'Perincian';
$lang['id_ID']['PaymentInformation.ss']['PAYMENTID'] = 'ID Pembelian';
$lang['id_ID']['PaymentInformation.ss']['PAYMENTINFORMATION'] = 'Informasi Pembelian';
$lang['id_ID']['PaymentInformation.ss']['PAYMENTMETHOD'] = 'Metode';
$lang['id_ID']['PaymentInformation.ss']['PAYMENTSTATUS'] = 'Status Pembelian';
$lang['id_ID']['PaymentInformation.ss']['TABLESUMMARY'] = 'Isi keranjang Anda ditampilkan pada form ini dan ringkasan dari seluruh biaya terkait dengan suatu pesanan dan catatan pilihan pembayaran.';
$lang['id_ID']['Product.ss']['ADD'] = 'Masukkan &quot;%s&quot; ke kereta anda';
$lang['id_ID']['Product.ss']['ADDLINK'] = 'Masukkan barang ini ke kereta';
$lang['id_ID']['Product.ss']['ADDONE'] = 'Masukkan satu lagi dari &quot;%s&quot; ke kereta anda';
$lang['id_ID']['Product.ss']['AUTHOR'] = 'Penulis';
$lang['id_ID']['Product.ss']['FEATURED'] = 'Ini adalah produk yang difiturkan.';
$lang['id_ID']['Product.ss']['GOTOCHECKOUT'] = 'Menuju bagian akhiri belanja sekarang';
$lang['id_ID']['Product.ss']['GOTOCHECKOUTLINK'] = '&#187; Menuju bagian akhiri belanja';
$lang['id_ID']['Product.ss']['IMAGE'] = '%s gambar';
$lang['id_ID']['Product.ss']['ItemID'] = '# Barang';
$lang['id_ID']['Product.ss']['NOIMAGE'] = 'Maaf, tidak ada gambar produk untuk &quot;%s&quot;';
$lang['id_ID']['Product.ss']['QUANTITYCART'] = 'Kuantitas dalam kereta';
$lang['id_ID']['Product.ss']['REMOVE'] = 'Keluarkan &quot;%s&quot; dari kereta anda';
$lang['id_ID']['Product.ss']['REMOVEALL'] = 'Keluarkan satu &quot;%s&quot; dari kereta anda';
$lang['id_ID']['Product.ss']['REMOVELINK'] = '&#187; Keluarkan dari kereta';
$lang['id_ID']['Product.ss']['SIZE'] = 'Ukuran';
$lang['id_ID']['ProductGroup.ss']['FEATURED'] = 'Produk-produk yang difiturkan';
$lang['id_ID']['ProductGroup.ss']['VIEWGROUP'] = 'Lihat &quot;%s&quot; grup produk';
$lang['id_ID']['ProductGroupItem.ss']['ADD'] = 'Masukkan &quot;%s&quot; ke kereta anda';
$lang['id_ID']['ProductGroupItem.ss']['ADDLINK'] = 'Masukkan barang ini ke kereta';
$lang['id_ID']['ProductGroupItem.ss']['ADDONE'] = 'Masukkan satu lagi dari &quot;%s&quot; ke kereta anda';
$lang['id_ID']['ProductGroupItem.ss']['AUTHOR'] = 'Pemilik';
$lang['id_ID']['ProductGroupItem.ss']['IMAGE'] = '%s gambar';
$lang['id_ID']['ProductGroupItem.ss']['NOIMAGE'] = 'Maaf, tidak ada gambar produk untuk &quot;%s&quot;';
$lang['id_ID']['ProductGroupItem.ss']['QUANTITY'] = 'Kuantitas';
$lang['id_ID']['ProductGroupItem.ss']['QUANTITYCART'] = 'Jumlah dalam kereta';
$lang['id_ID']['ProductGroupItem.ss']['READMORE'] = 'Klik di sini untuk membaca lebih lanjut tentang &quot;%s&quot;';
$lang['id_ID']['ProductGroupItem.ss']['READMORECONTENT'] = 'Klik untuk membaca lebih lanjut &#187;';
$lang['id_ID']['ProductGroupItem.ss']['REMOVE'] = 'Keluarkan &quot;%s&quot; dari kereta anda';
$lang['id_ID']['ProductGroupItem.ss']['REMOVELINK'] = '&#187; Keluarkan dari kereta';
$lang['id_ID']['ProductGroupItem.ss']['REMOVEONE'] = 'Keluarkan satu &quot;%s&quot; dari kereta anda';
$lang['id_ID']['ProductMenu.ss']['GOTOPAGE'] = 'Pergi ke halaman %s';
$lang['id_ID']['SSReport']['ALLCLICKHERE'] = 'Klik di sini untuk melihat semua produk';
$lang['id_ID']['SSReport']['INVOICE'] = 'laporan tagihan';
$lang['id_ID']['SSReport']['PRINT'] = 'cetak';
$lang['id_ID']['SSReport']['VIEW'] = 'lihat';
$lang['id_ID']['ViewAllProducts.ss']['AUTHOR'] = 'Pemilik';
$lang['id_ID']['ViewAllProducts.ss']['CATEGORIES'] = 'Kategori-kategori';
$lang['id_ID']['ViewAllProducts.ss']['IMAGE'] = '%s gambar';
$lang['id_ID']['ViewAllProducts.ss']['LASTEDIT'] = 'Terakhir diedit';
$lang['id_ID']['ViewAllProducts.ss']['LINK'] = 'Tautan';
$lang['id_ID']['ViewAllProducts.ss']['NOCONTENT'] = 'Tidak ada isi yang ditentukan.';
$lang['id_ID']['ViewAllProducts.ss']['NOIMAGE'] = 'Maaf, tidak ada gambar untuk &&quot;%s&quot;';
$lang['id_ID']['ViewAllProducts.ss']['NOSUBJECTS'] = 'Tidak Ada Subyek yang Ditentukan';
$lang['id_ID']['ViewAllProducts.ss']['PRICE'] = 'Harga';
$lang['id_ID']['ViewAllProducts.ss']['PRODUCTID'] = 'ID Produk';
$lang['id_ID']['ViewAllProducts.ss']['WEIGHT'] = 'Berat';
