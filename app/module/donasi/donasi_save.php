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

        $tahun = date('y');

		$sqlcount= "SELECT no_donasi FROM trs_donasi 
        
                    WHERE kode_daerah='$kode_daerah' AND LEFT(no_donasi,2)='$tahun' 
                    
                    ORDER BY no_donasi DESC";
        
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

    function autotrs($lebar=0, $awalan='', $kode_daerah){
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

    $daerah         = $_SESSION['nama_daerah'];

    $no_donasi	    = autonum(6,date('y')."40".$kode_daerah, $kode_daerah);

    $no_jurnal      = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $no_transaksi   = autotrs(6,date('y')."12".$kode_daerah, $kode_daerah); 

    $npwz 	        = strip_tags($_POST['npwz']);

    $muzaki         = strip_tags($_POST['muzaki']);
    
    $periode 		= strip_tags($_POST['periode']);
    
    $no_nota 	    = strip_tags($_POST['no_nota']);
    
    $tgl_donasi		= strip_tags($_POST['tgl_donasi']);

    $jml_donasi     = strip_tags($_POST['jml_donasi']);
    
    $metode 		= strip_tags($_POST['metode']);

    $no_rekening 	= strip_tags($_POST['no_rekening']);

    $saldo_bank 	= strip_tags($_POST['saldo_bank']);
    
    $kode_program       = $_POST['kode_program'];

    $program            = $_POST['program'];

    $akun_debit         = $_POST['akun_debit'];

    $akun_debit_bank    = $_POST['akun_debit_bank'];

    $akun_kredit        = $_POST['akun_kredit'];

    $akun_kredit_bank   = $_POST['akun_kredit_bank'];

    $sql_no_hp          = "SELECT no_hp FROM tm_donatur WHERE npwz='$npwz'";

    $exe_no_hp          = $konek->query($sql_no_hp);

    $rowNoHp            = $exe_no_hp->fetch_assoc();

    $no_hp              = $rowNoHp['no_hp'];

    $tgl_sms            = date('Y-m-d H:m:s');

    $pesan_sms = "Terima kasih Bp/Ibu ".$muzaki." telah melakukan ".$program." Dilazismu ".$daerah." Sebesar: ".number_format($jml_donasi, 0, ',', '.')." semoga dibalas oleh Alloh SWT";

    if($metode=='CASH') {
        
        $ad = $akun_debit;

        $ak = $akun_kredit;

    }else{
        
        $ad = $akun_debit_bank;

        $ak = $akun_kredit_bank;
    }
    

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $no_kantor      = $_SESSION['no_kantor'];

    $space          = " ";

    /* Validasi Kode */
    $sqlCekDonasi = "SELECT no_donasi FROM trs_donasi WHERE no_donasi='$no_donasi'";
    
    $exe_sqlDonasi = $konek->query($sqlCekDonasi);
    
    $cekDonasi	= mysqli_num_rows($exe_sqlDonasi);

    
    if($cekDonasi > 0 ){
        
            $pesan 		= "Data Sudah Terdaftar";
        
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);

    } elseif($metode=='CASH') {

        /* SQL Query Simpan */
        $sqlDonasi = "INSERT INTO 
            trs_donasi(
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
                '$tgl_donasi',
                '$program.$space.$muzaki',
                'Trial',
                'JU',
                '$kode_daerah',
                '$created',
                '$createdby',
                '$no_donasi'
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
                '$ad',
                '$jml_donasi',
                '0',
                '$periode',
                '$tgl_donasi',
                '$kode_daerah',
                '$program.$space.$muzaki',
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
                '$ak',
                '0',
                '$jml_donasi',
                '$periode',
                '$tgl_donasi',
                '$kode_daerah',
                '$program.$space.$muzaki',
                'Trial'
            )";
        
        $sqlSMS = "INSERT INTO 
                    outbox(           
                        DestinationNumber,
                        TextDecoded,
                        SendingDateTime,
                        CreatorID
                    )VALUES(
                        '$no_hp',
                        '$pesan_sms',
                        '$tgl_sms',
                        '$createdby'
            )";
        
        if($no_hp==''){

            /** Menggunakan Transaction Mysql */
            //$konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

                $insertDonasi   = $konek->query($sqlDonasi);

                $insertJuHdr    = $konek->query($sqlJuHdr);

                $insertJuDebit  = $konek->query($sqlJuDebit);

                $insertJuKredit = $konek->query($sqlJuKredit);  

            //$konek->commit();   

            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_donasi'=>$no_donasi);

            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            //$konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

                $insertDonasi   = $konek->query($sqlDonasi);

                $insertJuHdr    = $konek->query($sqlJuHdr);

                $insertJuDebit  = $konek->query($sqlJuDebit);

                $insertJuKredit = $konek->query($sqlJuKredit);  
                
                $insertSms      = $konek->query($sqlSMS);  

            //$konek->commit();   

            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_donasi'=>$no_donasi);

            echo json_encode($response);
        }

    }else{

        /* SQL Query Simpan */
        $new_saldo = intval($saldo_bank) + intval($jml_donasi);
        $sqlDonasi = "INSERT INTO 
            trs_donasi(
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
                            ref_number,
                            kode_daerah,
                            created,
                            createdby
                        )VALUES(
                            '$no_transaksi',
                            '$no_rekening',
                            '$periode',
                            '$tgl_donasi',
                            '01',
                            '$program.$space.$muzaki',
                            '$jml_donasi',
                            '0',
                            '$new_saldo',
                            'Aktif',
                            '$no_donasi',
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
                '$tgl_donasi',
                '$program.$space.$muzaki',
                'Trial',
                'JU',
                '$kode_daerah',
                '$created',
                '$createdby',
                '$no_donasi'
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
                status,
                no_rekening
            )VALUES(
                '$no_jurnal',
                '$ad',
                '$jml_donasi',
                '0',
                '$periode',
                '$tgl_donasi',
                '$kode_daerah',
                '$program.$space.$muzaki',
                'Trial',
                '$no_rekening'
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
                status,
                no_rekening
            )VALUES(
                '$no_jurnal',
                '$ak',
                '0',
                '$jml_donasi',
                '$periode',
                '$tgl_donasi',
                '$kode_daerah',
                '$program.$space.$muzaki',
                'Trial',
                '$no_rekening'
            )";

        $sqlSMS = "INSERT INTO 
                outbox(           
                    DestinationNumber,
                    TextDecoded,
                    SendingDateTime,
                    CreatorID
                )VALUES(
                    '$no_hp',
                    '$pesan_sms',
                    '$tgl_sms',
                    '$createdby'
                )";

        if($no_hp==''){

            /** Menggunakan Transaction Mysql */
            //$konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

                $insertDonasi   = $konek->query($sqlDonasi);

                // $insertBank     = $konek->query($sqlTrsBank);

                // $insertJuHdr    = $konek->query($sqlJuHdr);

                // $insertJuDebit  = $konek->query($sqlJuDebit);

                // $insertJuKredit = $konek->query($sqlJuKredit);     

            //$konek->commit(); 

            $pesan 		= "Data Berhasil Disimpan | Konfirmasi Ke Bagian Keuangan Untuk Membuat Jurnal";

            $response 	= array('pesan'=>$pesan, 'no_donasi'=>$no_donasi);

            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            //$konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

                $insertDonasi   = $konek->query($sqlDonasi);

                $insertSms   = $konek->query($sqlSMS);

                // $insertBank     = $konek->query($sqlTrsBank);

                // $insertJuHdr    = $konek->query($sqlJuHdr);

                // $insertJuDebit  = $konek->query($sqlJuDebit);

                // $insertJuKredit = $konek->query($sqlJuKredit);     

            //$konek->commit();   

            $pesan 		= "Data Berhasil Disimpan | Konfirmasi Ke Bagian Keuangan Untuk Membuat Jurnal";

            $response 	= array('pesan'=>$pesan, 'no_donasi'=>$no_donasi);

            echo json_encode($response);

        }

    }

}