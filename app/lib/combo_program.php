<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../bin/koneksi.php";

    $kode_daerah=$_SESSION['kode_daerah'];

    if ($kode_daerah=='') {

        $sql	= "SELECT kode_prg_pnr, program FROM view_411 WHERE status='Aktif'";

    } else {
        
        $sql	= "SELECT kode_prg_pnr, program FROM view_411 WHERE status='Aktif' AND kode_daerah='$kode_daerah'";

    }

    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}