<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    
    include "../../../bin/koneksi.php";

    $kode_daerah =  $_SESSION['kode_daerah'];
        
    $sql	= "SELECT 
    
                kode_akun, 
                
                akun 
            
            FROM view_321a 
            
            WHERE status='Aktif' AND sub_kategori='Bank'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_akun'] = $row['kode_akun'];

        $r['akun'] = $row['akun'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}