<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
                
    $kode_daerah	= $_SESSION['kode_daerah'];

    $no_kantor	    = $_SESSION['no_kantor'];

    $no_registrasi 	= strip_tags($_POST['no_registrasi']);

    $nama_mustahik 	= strip_tags($_POST['nama_mustahik']);

    $no_kk 			= strip_tags($_POST['no_kk']);
    
    $nik 		= strip_tags($_POST['nik']);
    
    $tmp_lahir 		= strip_tags($_POST['tmp_lahir']);
      
    $tgl_lahir 		= strip_tags($_POST['tgl_lahir']);

    $agama 		= strip_tags($_POST['agama']);
      
    $no_hp 		= strip_tags($_POST['no_hp']);
    
    $alamat 		= strip_tags($_POST['alamat']);

    $prov 		= strip_tags($_POST['prov']);

    $kab_kota 	= strip_tags($_POST['kab_kota']);

    $kec 		= strip_tags($_POST['kec']);

    $desa 		= strip_tags($_POST['desa']);

    /* SQL Query Update */
    $sqlMustahik = "UPDATE tm_mustahik SET

            nama_mustahik='$nama_mustahik',

            no_kk='$no_kk',
    
            nik='$nik',

            tmp_lahir='$tmp_lahir',

            tgl_lahir='$tgl_lahir',
    
            agama='$agama',

            no_hp='$no_hp',
    
            alamat='$alamat',
    
            prov='$prov',

            kab_kota='$kab_kota',
    
            kec='$kec',

            desa='$desa'
    
        WHERE no_registrasi='$no_registrasi' ";

    if($kode_daerah!=''){

        $updateMustahik = $konek->query($sqlMustahik);    

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "DHanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}