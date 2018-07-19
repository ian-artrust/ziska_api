<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $no_kantor    = $_SESSION['no_kantor']; 

    if($kode_daerah==''){

        $sql	= "SELECT * FROM view_229 WHERE status='Aktif'";        

    }else{

        $sql	= "SELECT * FROM view_229 WHERE status='Aktif' AND no_kantor='$no_kantor' AND kode_daerah='$kode_daerah'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_donasi'] = $row['no_donasi'];

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['tgl_donasi'] = $row['tgl_donasi'];

        $r['norek_bank'] = $row['norek_bank'];

        $r['program'] = $row['program'];

        $r['jml_donasi'] = number_format($row['jml_donasi'], 0, ',', '.');

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}