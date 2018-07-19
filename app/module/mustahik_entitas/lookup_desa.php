<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $id_kec = $_POST['kec'];

    $sql	= "SELECT id_desa, desa FROM tm_desa WHERE id_kec='$id_kec'";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    $cek_data = mysqli_num_rows($hasil);

    if($cek_data > 0){

        while($row 	= $hasil->fetch_assoc()){

            $r['id_desa'] = $row['id_desa'];
    
            $r['desa'] = $row['desa'];
    
            array_push($response["data"], $r);
    
        }
    
        echo json_encode($response);

    }else{

        $r['id_desa'] = '';
    
        $r['desa'] ='';

        array_push($response["data"], $r);

        echo json_encode($response);

    }

   

}