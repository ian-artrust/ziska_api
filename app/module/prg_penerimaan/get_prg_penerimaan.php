<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    
    include "../../../bin/koneksi.php";

    $kode_daerah =  $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
        
        $sql	= "SELECT * FROM view_411 WHERE status='Aktif'";

    } else {
        
        $sql	= "SELECT * FROM view_411 WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_prg_pnr'] = $row['kode_prg_pnr'];

        $r['program'] = $row['program'];

        $r['kategori'] = $row['kategori'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}