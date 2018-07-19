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

		$sqlcount= "SELECT no_donasi FROM trs_donasi_kl WHERE kode_daerah='$kode_daerah' ORDER BY no_donasi DESC";
        
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
    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_donasi	    = autonum(6,date('y')."41".$kode_daerah, $kode_daerah);

    $npwz 	        = strip_tags($_POST['npwz']);

    $muzaki         = strip_tags($_POST['muzaki']);
    
    $periode 		= strip_tags($_POST['periode']);
    
    $no_nota 	    = strip_tags($_POST['no_nota']);
    
    $tgl_donasi		= strip_tags($_POST['tgl_donasi']);

    $jml_donasi     = strip_tags($_POST['jml_donasi']);
    
    $metode 		= strip_tags($_POST['metode']);

    $no_rekening 	= strip_tags($_POST['no_rekening']);
    
    $kode_program   = $_POST['kode_program'];

    $program        = $_POST['program'];

    $akun_debit     = $_POST['akun_debit'];

    $akun_kredit    = $_POST['akun_kredit'];

    $total_donasi    = $_POST['jml_donasi'];

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $no_kantor      = $_SESSION['no_kantor'];

    $space          = " ";

    /* Validasi Kode */
    $sqlCekDonasi = "SELECT no_donasi FROM trs_donasi_kl WHERE no_donasi='$no_donasi'";
    
    $exe_sqlDonasi = $konek->query($sqlCekDonasi);
    
    $cekDonasi	= mysqli_num_rows($exe_sqlDonasi);
    
    if($cekDonasi > 0 ){
        
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($kode_daerah=='' || $no_kantor==''){
        
        $pesan 		= "Hanya Account Yang Memiliki Entitas Kantor Daerah / KL Yang Berhak Melakukan Transaksi..!";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        /* SQL Query Simpan */
        $sqlDonasi = "INSERT INTO 
            trs_donasi_kl(
                no_donasi,
                no_kantor,
                npwz,
                no_bukti,
                periode,
                tgl_donasi,
                kode_prg_pnr,
                jml_donasi,
                metode,
                norek_bank,
                kode_daerah,
                status,
                created,
                createdby
            )VALUES(
                '$no_donasi',
                '$no_kantor',
                '$npwz',
                '$no_nota',
                '$periode',
                '$tgl_donasi',
                '$kode_program',
                '$jml_donasi',
                '$metode',
                '$no_rekening',
                '$kode_daerah',
                'Aktif',
                '$created',
                '$createdby'
            )";

        $insertDonasi = $konek->query($sqlDonasi);

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'no_donasi'=>$no_donasi);

        echo json_encode($response);
    }

}