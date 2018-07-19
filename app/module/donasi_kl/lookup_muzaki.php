<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $no_kantor      = $_SESSION['no_kantor'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {

        $sql	= "SELECT npwz, nama_donatur, nama_petugas, nama_kantor, no_kantor FROM view_211 WHERE status='Aktif'";
        
    } else {

        $sql	= "SELECT npwz, nama_donatur, nama_petugas, nama_kantor, no_kantor FROM view_211 WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['nama_petugas'] = $row['nama_petugas'];

        $r['nama_kantor'] = $row['nama_kantor'];

        $r['no_kantor'] = $row['no_kantor'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}