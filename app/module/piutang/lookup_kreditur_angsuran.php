<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if($kode_daerah==''){

        $sql	= "SELECT 

                    no_piutang, 

                    nama_kreditur,

                    jml_piutang,

                    sumber_dana 

                FROM view_325 WHERE status!='REJECT'";

    } else {

        $sql	= "SELECT 

                    no_piutang, 

                    nama_kreditur,

                    jml_piutang,

                    sumber_dana

                FROM view_325 

                WHERE 

                    kode_daerah = '$kode_daerah' AND status!='REJECT'";

    }    

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_piutang'] = $row['no_piutang'];

        $r['nama_kreditur'] = $row['nama_kreditur'];

        $r['jml_piutang'] = $row['jml_piutang'];

        $r['sumber_dana'] = $row['sumber_dana'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}