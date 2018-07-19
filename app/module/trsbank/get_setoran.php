<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_kantor   = $_SESSION['no_kantor'];
        
    $sql	= "SELECT * FROM view_228 WHERE status='Aktif' AND kode_daerah='$kode_daerah'";


    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_setoran'] = $row['no_setoran'];

        $r['tgl_setoran'] = $row['tgl_setoran'];

        $r['penyetor'] = $row['penyetor'];

        $r['jml_setoran'] = number_format($row['jml_setoran'], 0, ',', '.');

        $r['nama_bank'] = $row['nama_bank'];

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}