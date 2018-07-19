<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_asset_pny = $_GET['no_asset_pny'];

    $kode_daerah = $_SESSION['kode_daerah'];

    $sqlFind 	= "SELECT 

                    *

                FROM view_326

                WHERE no_asset='$no_asset_pny' 
                
                AND status='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}