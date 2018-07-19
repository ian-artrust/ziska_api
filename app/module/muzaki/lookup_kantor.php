<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if($kode_daerah==''){

        $sql	= "SELECT kode_daerah, no_kantor, nama_kantor, pimpinan FROM tm_kantor_layanan";

    } else {

        $sql	= "SELECT kode_daerah, no_kantor, nama_kantor, pimpinan FROM tm_kantor_layanan WHERE kode_daerah='$kode_daerah'";       

    }

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_daerah'] = $row['kode_daerah'];

        $r['no_kantor'] = $row['no_kantor'];

        $r['nama_kantor'] = $row['nama_kantor'];

        $r['pimpinan'] = $row['pimpinan'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}