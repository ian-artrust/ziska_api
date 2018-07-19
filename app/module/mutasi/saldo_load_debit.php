<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_akun = $_GET['kode_akun'];

    $periode = $_GET['periode'];

    $kode_daerah = $_SESSION['kode_daerah'];

    $sqlFind 	= "SELECT 

                    kode_akun,

                    SUM(debit) AS debit,

                    SUM(kredit) AS kredit,

                    SUM(debit) - SUM(kredit) AS saldo_berjalan

                FROM trs_judtl 

                WHERE kode_akun='$kode_akun' 
                
                AND periode='$periode'

                AND kode_daerah='$kode_daerah'
                
                AND status='Trial'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}