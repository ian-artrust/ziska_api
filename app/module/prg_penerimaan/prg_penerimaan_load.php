<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_prg_pnr   = $_GET['kode_prg_pnr'];
       
    $sqlFind 	= "SELECT * FROM view_411 WHERE kode_prg_pnr = '$kode_prg_pnr'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}