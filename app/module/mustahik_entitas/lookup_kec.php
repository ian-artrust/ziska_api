<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $id_kab = $_POST['kab_kota'];

    $sql	= "SELECT id_kec, kecamatan FROM tm_kec WHERE id_kab='$id_kab'";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    $cek_data = mysqli_num_rows($hasil);

    if($cek_data > 0){

        while($row 	= $hasil->fetch_assoc()){

            $r['id_kec'] = $row['id_kec'];
    
            $r['kecamatan'] = $row['kecamatan'];
    
            array_push($response["data"], $r);
    
        }
    
        echo json_encode($response);

    } else{

        $r['id_kec'] = '';
    
        $r['kecamatan'] ='';

        array_push($response["data"], $r);

        echo json_encode($response);
    
    }

}