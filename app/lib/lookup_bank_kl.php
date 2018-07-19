<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../bin/koneksi.php";

    $no_kantor      = $_SESSION['no_kantor'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    if($no_kantor!=''){

        $sql	= "SELECT no_rekening, nama_bank, status FROM tm_bank_kl WHERE no_kantor='$no_kantor' AND status='Aktif'";

    }elseif($kode_daerah !=''){

        $sql	= "SELECT no_rekening, nama_bank, status FROM tm_bank_kl WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }else{

        $sql	= "SELECT no_rekening, nama_bank, status FROM tm_bank_kl WHERE status='Aktif'";

    }

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_rekening'] = $row['no_rekening'];

        $r['nama_bank'] = $row['nama_bank'];

        $r['status'] = $row['status'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}