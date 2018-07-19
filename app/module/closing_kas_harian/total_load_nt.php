<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $tgl_donasi = $_GET['tgl_donasi'];

    $sqlFind 	= "SELECT SUM(jml_donasi) AS total 
    
                    FROM view_221 
                    
                    WHERE tgl_donasi = '$tgl_donasi' AND metode='NON TUNAI' AND kode_daerah='$kode_daerah' AND status='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}