<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_rekening = $_GET['no_rekening'];

    $sqlFind 	= "SELECT  

    SUM(debit) AS debit,

    SUM(kredit) AS kredit,
    
    SUM(debit) - SUM(kredit) AS saldo 
    
    FROM view_323a WHERE ref_number = '$no_rekening' AND kode_sub_kat_akun='102' AND status!='REJECT'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}