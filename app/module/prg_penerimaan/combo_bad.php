<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT kode_akun, akun FROM tm_akun 
    
    WHERE kategori='Bank Zakat' AND status='Aktif'
    
    OR  kategori='Bank Infak' AND status='Aktif'
    
    OR  kategori='Bank Amil' AND status='Aktif'
    
    OR  kategori='Bank CSR' AND status='Aktif'";

    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}