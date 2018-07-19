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

		$sqlcount= "SELECT no_pengajuan FROM trs_pengajuan WHERE kode_daerah='$kode_daerah' ORDER BY no_pengajuan DESC";
        
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
    $kode_daerah        = $_SESSION['kode_daerah'];

    $no_kantor          = strip_tags($_POST['no_kantor']);

    $no_pengajuan	    = autonum(6,"PJN-".date('y').$kode_daerah, $kode_daerah);

    $no_registrasi 	    = strip_tags($_POST['no_registrasi']);

    $no_disposisi 	    = strip_tags($_POST['no_disposisi']);
    
    $no_master 	        = strip_tags($_POST['no_master']);

    $keterangan 	    = strip_tags($_POST['keterangan']);

    $periode 			= strip_tags($_POST['periode']);

    $tgl_pengajuan 	    = strip_tags($_POST['tgl_pengajuan']);
    
    $jml_pengajuan 		= strip_tags($_POST['jml_pengajuan']);
     
    /* Validasi Kode */
    $sqlCekNoPengajuan     = "SELECT no_pengajuan FROM trs_pengajuan WHERE no_pengajuan='$no_pengajuan'";
    
    $exe_sqlCekNoPengajuan = $konek->query($sqlCekNoPengajuan);

    $cekNoPengajuan	    = mysqli_num_rows($exe_sqlCekNoPengajuan);

    /* SQL Query Simpan */
    $sqlPengajuan = "INSERT INTO 
        trs_pengajuan(
            no_pengajuan,
            no_registrasi,
            no_master,
            keterangan,
            periode,
            tgl_pengajuan,
            jml_pengajuan,
            kode_daerah,
            no_kantor,
            no_disposisi,
            status
        )VALUES(
            '$no_pengajuan',
            '$no_registrasi',
            '$no_master',
            '$keterangan',
            '$periode',
            '$tgl_pengajuan',
            '$jml_pengajuan',
            '$kode_daerah',
            '$no_kantor',
            '$no_disposisi',
            'PENGAJUAN'
        )";
    
    if($cekNoPengajuan > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertNoPengajuan = $konek->query($sqlPengajuan);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}