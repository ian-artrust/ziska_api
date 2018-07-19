<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $sql	= "SELECT id_prov, provinsi FROM tm_prov";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['id_prov'] = $row['id_prov'];

        $r['provinsi'] = $row['provinsi'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}