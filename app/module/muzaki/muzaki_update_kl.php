<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $npwz	= $_POST['npwz'];

    $nama_donatur 	= strip_tags($_POST['nama_donatur']);

    $nik 	        = strip_tags($_POST['nik']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $kategori 		= strip_tags($_POST['kategori']);
    
    $status 		= strip_tags($_POST['status']);
    
    $kode_petugas	= $_SESSION['kode_petugas'];
      
    $no_kantor 		= $_SESSION['no_kantor'];

    $nk 		    = strip_tags($_POST['no_kantor']);

    /* SQL Query Update */
    $sqlMuzaki = "UPDATE tm_donatur SET

            nama_donatur='$nama_donatur',

            nik='$nik',
    
            alamat='$alamat',
    
            no_hp='$no_hp',
    
            kategori='$kategori',

            kode_petugas='$kode_petugas',
    
            no_kantor='$no_kantor',
    
            status='$status'
    
        WHERE npwz='$npwz' ";
    
    if($nk == $no_kantor){

        $updateMuzaki = $konek->query($sqlMuzaki);    

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    }elseif($nk != $no_kantor){

        $pesan 		= "Data Muzaki Kantor Layanan Lain Tidak Boleh Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($npwz==""){

        $pesan 		= "Data Tidak Boleh Kosong";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}