<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

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
    $kode_daerah        = $_SESSION['kode_daerah'];

    $no_jurnal          = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $kode_akun_debit 	= strip_tags($_POST['kode_akun_debit']);

    $akun_debit         = strip_tags($_POST['akun_debit']);
    
    $periode 		    = strip_tags($_POST['periode']);
    
    $kode_akun_kredit 	= strip_tags($_POST['kode_akun_kredit']);
    
    $akun_kredit		= strip_tags($_POST['akun_kredit']);

    $tgl_setup          = strip_tags($_POST['tgl_setup']);
    
    $jumlah 		    = strip_tags($_POST['jml_setup']);
    
    $jml_setup          = str_replace(".","",$jumlah);

    $created            = date('Y-m-d h:m:s');

    $createdby          = $_SESSION['kode_petugas'];

    $no_kantor          = $_SESSION['no_kantor'];

    $space              = " ";


    /* SQL Query Simpan */
    $sqlSetupSaldo = "INSERT INTO 
                    trs_setup_saldo(
                        kode_daerah,
                        kode_akun,
                        periode,
                        tgl_setup,
                        jml_setup,
                        status,
                        ref_number
                    )VALUES(
                        '$kode_daerah',
                        '$kode_akun_debit',
                        '$periode',
                        '$tgl_setup',
                        '$jml_setup',
                        'Aktif',
                        '$no_jurnal'
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
            created,
            createdby
        )VALUES(
            '$no_jurnal',
            '$periode',
            '$tgl_setup',
            'Setup Saldo Awal Periode',
            'Trial',
            'JU',
            '$kode_daerah',
            '$created',
            '$createdby'
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
            '$kode_akun_debit',
            '$jml_setup',
            '0',
            '$periode',
            '$tgl_setup',
            '$kode_daerah',
            'Setup Saldo Awal Periode',
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
            '$kode_akun_kredit',
            '0',
            '$jml_setup',
            '$periode',
            '$tgl_setup',
            '$kode_daerah',
            'Setup Saldo Awal Periode',
            'Trial'
        )";
    
    if ($jml_setup=="" || $kode_akun_debit=="" || $kode_akun_kredit=="") {
       
        $pesan 		= "Saldo Gagal Disetup";

        $response 	= array('pesan'=>$pesan, 'kode_akun_debit'=>$kode_akun_debit);

        echo json_encode($response);

   } else {

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $insertSetup    = $konek->query($sqlSetupSaldo);
            
            $insertJuHdr    = $konek->query($sqlJuHdr);

            $insertJuDebit  = $konek->query($sqlJuDebit);

            $insertJuKredit = $konek->query($sqlJuKredit);     

        $konek->commit();     

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'kode_akun_debit'=>$kode_akun_debit);

        echo json_encode($response);
        
   }

}