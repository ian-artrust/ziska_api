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
                    
                    FROM tm_mustahik_entitas 
                    
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

    $no_registrasi  = autonum(6,date('y')."41".$kode_daerah, $kode_daerah);

    $nama_lembaga 	= strip_tags($_POST['nama_lembaga']);
    
    $no_hp 		= strip_tags($_POST['no_hp']);
    
    $no_sk 		= strip_tags($_POST['no_sk']);

    $alamat 		= strip_tags($_POST['alamat']);
      
    $no_kantor 		= $_SESSION['no_kantor'];
    
    /* Validasi Kode */
    $sqlCekMustahik     = "SELECT no_registrasi FROM tm_mustahik_entitas WHERE no_registrasi='$no_registrasi'";
    
    $exe_sqlMustahik    = $konek->query($sqlCekMustahik);
   
    $cekMustahik	    = mysqli_num_rows($exe_sqlMustahik);

    /* SQL Query Simpan */
    $sqlMuzaki = "INSERT INTO 
        tm_mustahik_entitas(
            no_registrasi,
            nama_lembaga,
            no_hp,
            no_sk,
            alamat,
            kode_daerah,
            no_kantor,
            status
        )VALUES(
            '$no_registrasi',
            '$nama_lembaga',
            '$no_hp',
            '$no_sk',
            '$alamat',
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