<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $alokasi = $_GET['alokasi'];

    $jenis = $_GET['jenis'];

    $periode = $_GET['periode'];

    $tgl_alokasi = $_GET['tgl_alokasi'];

    $kode_daerah = $_SESSION['kode_daerah'];

    $sqlFind 	= "SELECT SUM(kredit) AS kredit

                FROM view_641 

                WHERE kategori='$jenis' 
                
                AND periode='$periode'

                AND kode_daerah='$kode_daerah'
                
                AND status!='REJECT'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}