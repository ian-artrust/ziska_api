<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../bin/koneksi.php";

    $kode_daerah=$_SESSION['kode_daerah'];
   
    $sql	= "SELECT kode_akun, akun FROM view_321 WHERE jenis='Kas dan Setara Kas' OR jenis='Bank'";

    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}