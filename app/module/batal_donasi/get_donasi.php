<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    if($kode_petugas=='' AND $kode_daerah==''){

        $sql	= "SELECT * FROM view_222 WHERE status='Aktif'";        

    }elseif(!empty($kode_daerah)) {
        
        $sql	= "SELECT * FROM view_222 WHERE status='Aktif' AND kode_daerah='$kode_daerah'";

    }else{

        $sql	= "SELECT * FROM view_222 WHERE status='Aktif' AND createdby='$kode_petugas' AND kode_daerah='$kode_daerah'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_donasi'] = $row['no_donasi'];

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['norek_bank'] = $row['norek_bank'];

        $r['program'] = $row['program'];

        $r['jml_donasi'] = number_format($row['jml_donasi'], 0, ',', '.');

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}