<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /*
	* Auto Number Untuk Code Buku 
	*/
	function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT 
        
                    no_registrasi 
                    
                    FROM tm_mustahik 
                    
                    WHERE kode_daerah='$kode_daerah' 
                    
                    ORDER BY no_registrasi DESC";
        
        $hasil= $konek->query($sqlcount);
        
        $jumlahrecord = mysqli_num_rows($hasil);

		if($jumlahrecord == 0)
			$nomor=1;
		else {
			$nomor = $jumlahrecord+1;
		}

		if($lebar>0)
			$angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
		else
			$angka = $awalan.$nomor;
		return $angka;
    }
    
    /** Variabel From Post */
    $kode_daerah = $_SESSION['kode_daerah'];

    $no_registrasi  = autonum(6,date('y')."40".$kode_daerah, $kode_daerah);

    $nama_mustahik 	= strip_tags($_POST['nama_mustahik']);
    
    $no_kk 		= strip_tags($_POST['no_kk']);
    
    $nik 		= strip_tags($_POST['nik']);
    
    $tmp_lahir 	= strip_tags($_POST['tmp_lahir']);
    
    $tgl_lahir 	= strip_tags($_POST['tgl_lahir']);

    $agama 		= strip_tags($_POST['agama']);

    $no_hp 		= strip_tags($_POST['no_hp']);

    $alamat 	= strip_tags($_POST['alamat']);

    $prov 		= strip_tags($_POST['prov']);

    $kab_kota 	= strip_tags($_POST['kab_kota']);

    $kec 		= strip_tags($_POST['kec']);

    $desa 		= strip_tags($_POST['desa']);
      
    $no_kantor 		= $_SESSION['no_kantor'];
    
    /* Validasi Kode */
    $sqlCekMustahik     = "SELECT no_registrasi FROM tm_mustahik WHERE no_registrasi='$no_registrasi'";
    
    $exe_sqlMustahik    = $konek->query($sqlCekMustahik);
   
    $cekMustahik	    = mysqli_num_rows($exe_sqlMustahik);

    /* SQL Query Simpan */
    $sqlMuzaki = "INSERT INTO 
        tm_mustahik(
            no_registrasi,
            nama_mustahik,
            no_kk,
            nik,
            tmp_lahir,
            tgl_lahir,
            agama,
            no_hp,
            alamat,
            prov,
            kab_kota,
            kec,
            desa,
            kode_daerah,
            no_kantor,
            status
        )VALUES(
            '$no_registrasi',
            '$nama_mustahik',
            '$no_kk',
            '$nik',
            '$tmp_lahir',
            '$tgl_lahir',
            '$agama',
            '$no_hp',
            '$alamat',
            '$prov',
            '$kab_kota',
            '$kec',
            '$desa',
            '$kode_daerah',
            '$no_kantor',
            'Aktif'
        )";
    
    if($cekMustahik > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($kode_daerah=='') {
        
        $pesan 		= "Hanya Untuk Account Yang Memiliki Entitas Daerah";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        $insertMuzaki = $konek->query($sqlMuzaki);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}