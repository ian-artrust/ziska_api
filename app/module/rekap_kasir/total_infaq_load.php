<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {   

    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $npwz = $_GET['npwz'];

    $sqlFind 	= "SELECT 
    
                        SUM(jml_donasi) AS total 
    
                    FROM view_donasi 
                    
                    WHERE npwz = '$npwz' AND kode_kategori!='101' AND status!='REJECT'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}