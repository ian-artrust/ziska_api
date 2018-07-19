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

                    kode_petugas, 

                    nama_petugas, 

                    nama_kantor,

                    no_hp 

                FROM view_112 

                WHERE 

                    status='Aktif'";

    } else {

        $sql	= "SELECT 

                    kode_petugas, 

                    nama_petugas, 

                    nama_kantor,

                    no_hp 

                FROM view_112 

                WHERE 

                    kode_daerah = '$kode_daerah' 

                AND status='Aktif'";

    }    

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_petugas'] = $row['kode_petugas'];

        $r['nama_petugas'] = $row['nama_petugas'];

        $r['nama_kantor'] = $row['nama_kantor'];

        $r['no_hp'] = $row['no_hp'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}