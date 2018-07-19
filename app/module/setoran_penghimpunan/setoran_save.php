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

		$sqlcount= "SELECT no_setoran FROM trs_setoran_kl WHERE kode_daerah='$kode_daerah' ORDER BY no_setoran DESC";
        
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

    function autoJurnal($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_jurnal FROM trs_juhdr WHERE kode_daerah='$kode_daerah' ORDER BY no_jurnal DESC";
        
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

    $no_setoran	    = autonum(6,date('y')."42".$kode_daerah, $kode_daerah);

    $no_jurnal      = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $no_bukti       = strip_tags($_POST['no_bukti']);

    $penyetor       = strip_tags($_POST['penyetor']);
    
    $periode 		= strip_tags($_POST['periode']);
    
    $tgl_setoran    = strip_tags($_POST['tgl_setoran']);

    $jml_setoran    = strip_tags($_POST['jml_setoran']);

    $no_rekening 	= strip_tags($_POST['no_rekening']);
    
    $jenis          = strip_tags($_POST['jenis']);

    $kode_akun      = strip_tags($_POST['kode_akun']);

    $no_kantor      = $_SESSION['no_kantor'];

    $keterangan     = $jenis." ".$no_kantor;

    if ($jenis=='Setoran Zakat') {
        
        $akun_debit     = $kode_akun;

        $akun_kredit    = '401018';

    }elseif ($jenis=='Setoran Infak Sedekah') {
        
        $akun_debit     = $kode_akun;

        $akun_kredit    = '402029';

    } else {
        
        $akun_debit     = $kode_akun;

        $akun_kredit    = '403025';

    }    

    $created        = date('Y-m-d hh:mm:ss');

    $createdby      = $_SESSION['kode_petugas'];

    $no_kantor      = $_SESSION['no_kantor'];

    $space          = " ";

    /* Validasi Kode */
    $sqlCekSetoran = "SELECT no_setoran FROM trs_setoran_kl WHERE no_setoran='$no_setoran'";
    
    $exe_sqlSetoran = $konek->query($sqlCekSetoran);
    
    $cekSetoran	= mysqli_num_rows($exe_sqlSetoran);
    
    if($cekSetoran > 0 ){
        
            $pesan 		= "Data Sudah Terdaftar";
        
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);

    }elseif($kode_daerah=='') {
        
        $pesan 		= "Hanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        /* SQL Query Simpan */
        $sqlSetoran = "INSERT INTO 
            trs_setoran_kl(
                no_setoran,
                no_bukti,
                penyetor,
                no_kantor,
                periode,
                tgl_setoran,
                norek_setoran,
                kode_daerah,
                jml_setoran,
                jenis,
                akun_debit,
                akun_kredit,
                status
            )VALUES(
                '$no_setoran',
                '$no_bukti',
                '$penyetor',
                '$no_kantor',
                '$periode',
                '$tgl_setoran',
                '$no_rekening',
                '$kode_daerah',
                '$jml_setoran',
                '$jenis',
                '$akun_debit',
                '$akun_kredit',
                'Aktif'
            )";

        $sqlJuHdr = "INSERT INTO 
            trs_juhdr(
                no_jurnal,
                periode,
                tgl_jurnal,
                keterangan,
                status,
                jenis,
                kode_daerah,
                ref_number
            )VALUES(
                '$no_jurnal',
                '$periode',
                '$tgl_setoran',
                '$keterangan',
                'Trial',
                'JU',
                '$kode_daerah',
                '$no_setoran'
            )";
        
        $sqlJuDebit = "INSERT INTO 
            trs_judtl(
                no_jurnal,
                kode_akun,
                debit,
                kredit,
                periode,
                tgl_jurnal,
                kode_daerah,
                keterangan,
                status
            )VALUES(
                '$no_jurnal',
                '$akun_debit',
                '$jml_setoran',
                '0',
                '$periode',
                '$tgl_setoran',
                '$kode_daerah',
                '$keterangan',
                'Trial'
            )";
        
        $sqlJuKredit = "INSERT INTO 
            trs_judtl(
                no_jurnal,
                kode_akun,
                debit,
                kredit,
                periode,
                tgl_jurnal,
                kode_daerah,
                keterangan,
                status
            )VALUES(
                '$no_jurnal',
                '$akun_kredit',
                '0',
                '$jml_setoran',
                '$periode',
                '$tgl_setoran',
                '$kode_daerah',
                '$keterangan',
                'Trial'
            )";

         /** Menggunakan Transaction Mysql */
         $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $insertSetoran   = $konek->query($sqlSetoran);

            // $insertJuHdr    = $konek->query($sqlJuHdr);

            // $insertJuDebit  = $konek->query($sqlJuDebit);

            // $insertJuKredit = $konek->query($sqlJuKredit);     

        $konek->commit();     

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'no_setoran'=>$no_setoran);

        echo json_encode($response);
    }

}