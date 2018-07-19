<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $sql	= "SELECT kode_daerah, nama_daerah FROM tm_daerah";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_daerah'] = $row['kode_daerah'];

        $r['nama_daerah'] = $row['nama_daerah'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}