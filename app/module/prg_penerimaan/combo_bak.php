<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT kode_akun, akun FROM tm_akun 
    
    WHERE kategori='Penerimaan Zakat Via Bank' AND status='Aktif'
    
    OR kategori='Penerimaan Infak Terikat Via Bank' AND status='Aktif'
    
    OR kategori='Penerimaan Infak Tidak Terikat Via Bank' AND status='Aktif'";

    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}