<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {

        $sql	= "SELECT no_rekening, nama_bank, kode_akun FROM view_311";

    } else {
        
        $sql	= "SELECT no_rekening, nama_bank, kode_akun FROM view_311 WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_rekening'] = $row['no_rekening'];

        $r['nama_bank'] = $row['nama_bank'];

        $r['kode_akun'] = $row['kode_akun'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}