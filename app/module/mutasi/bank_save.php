<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /*
	* Auto Number No Bank
	*/
	function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_transaksi FROM trs_bank WHERE kode_daerah='$kode_daerah' ORDER BY no_transaksi DESC";
        
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

    /*
	* Auto Number Untuk No Jurnal
	*/
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

    $no_transaksi   = autonum(6,date('y')."12".$kode_daerah, $kode_daerah);

    $no_jurnal	    = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $kode_akun_bank_debit = $_POST['kode_akun_bank_debit'];

    $no_rekening_debit  = $_POST['no_rekening_debit'];

    $nama_bank_debit 	= strip_tags($_POST['nama_bank_debit']);

    $saldo_bank_debit 	= strip_tags($_POST['saldo_bank_debit']);

    $no_rekening_kredit = $_POST['no_rekening_kredit'];
    
    $nama_bank_kredit 	= strip_tags($_POST['nama_bank_kredit']);

    $saldo_bank_kredit 	= strip_tags($_POST['saldo_bank_kredit']);

    $kode_akun_bank_kredit     = $_POST['kode_akun_bank_kredit'];
    
    $jml_mutasi_bank	= strip_tags($_POST['jml_mutasi_bank']);
    
    $keterangan_bank    = $_POST['keterangan_bank'];
    
    $periode_bank 	    = strip_tags($_POST['periode_bank']);
    
    $tgl_transaksi_bank	= strip_tags($_POST['tgl_transaksi_bank']);
    
    $created            = date('Y-m-d');

    $createdby          = $_SESSION['kode_petugas'];

    /* Validasi Kode */
    $sqlCekBank = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
    $exe_sqlBank = $konek->query($sqlCekBank);
    
    $cekBank	= mysqli_num_rows($exe_sqlBank);
    
    if($cekBank > 0 ){
        
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        
    }else{

        $new_saldo_debit    = intval($saldo_bank_debit) + intval($jml_mutasi_bank);

        $new_saldo_kredit   = intval($saldo_bank_kredit) - intval($jml_mutasi_bank);
        
        /* SQL Query Simpan */
        $sqlTrsBank = "INSERT INTO 
         trs_bank(
             no_transaksi,
             no_rekening,
             periode,
             tgl_transaksi,
             kode_transaksi,
             keterangan,
             debit,
             kredit,
             saldo,
             status,
             kode_daerah,
             created,
             createdby
         )VALUES(
             '$no_transaksi',
             '$no_rekening',
             '$periode',
             '$tgl_transaksi',
             '$kode_transaksi',
             '$keterangan',
             '$jml_transaksi',
             '0',
             '$new_saldo',
             'Aktif',
             '$kode_daerah',
             '$created',
             '$createdby'
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
                 createdby,
                 ref_number
             )VALUES(
                 '$no_jurnal',
                 '$periode',
                 '$tgl_transaksi',
                 '$keterangan',
                 'Trial',
                 'JU',
                 '$kode_daerah',
                 '$created',
                 '$createdby',
                 '$no_transaksi'
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
                 '$kode_akun',
                 '$jml_transaksi',
                 '0',
                 '$periode',
                 '$tgl_transaksi',
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
                 '$kode_akun_counter',
                 '0',
                 '$jml_transaksi',
                 '$periode',
                 '$tgl_transaksi',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial'
             )";
        
        if($no_rekening_debit=='' OR $no_rekening_kredit==''){
            
            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);
    
            echo json_encode($response);
        
        }elseif($new_saldo_kredit < 0){

            $pesan 		= "Saldo Tidak Boleh Minus";
            
            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);
    
            echo json_encode($response);

        }elseif($no_rekening_debit==$no_rekening_kredit){

            $pesan 		= "Bank Yang Dipilih Tidak Boleh Sama";
            
            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);
    
            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                
                $insertJuHeader    = $konek->query($sqlJuHeader);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
                $insertBankSaldoDebit  = $konek->query($sqlSaldoDebit);

                $insertBankSaldoKredit = $konek->query($sqlSaldoKredit);
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);

            echo json_encode($response);

        }

    }

}