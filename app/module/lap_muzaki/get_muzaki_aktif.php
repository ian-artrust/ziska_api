<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $no_kantor = $_SESSION['no_kantor'];

    if($kode_daerah == ''){

        $sql	= "SELECT * FROM view_231 WHERE ORDER BY npwz ASC";

    }else{

        $sql	= "SELECT * FROM view_231 WHERE kode_daerah='$kode_daerah' ORDER BY npwz ASC";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['npwz'] = $row['npwz'];

        $r['muzaki'] = $row['muzaki'];

        $r['kategori'] = $row['kategori'];

        $r['total_donasi'] = number_format($row['total_donasi']);

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}