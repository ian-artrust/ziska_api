<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $id_prov = $_POST['prov'];

    $sql	= "SELECT id_kab, kab_kota FROM tm_kab WHERE id_prov='$id_prov'";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    $cek_data = mysqli_num_rows($hasil);

    if($cek_data > 0){

        while($row 	= $hasil->fetch_assoc()){

            $r['id_kab'] = $row['id_kab'];
    
            $r['kab_kota'] = $row['kab_kota'];
    
            array_push($response["data"], $r);
    
        }
    
        echo json_encode($response);

    } else{

        $r['id_kab'] = '';
    
        $r['kab_kota'] ='';

        array_push($response["data"], $r);

        echo json_encode($response);

    }

}