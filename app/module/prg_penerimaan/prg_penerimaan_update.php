<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_prg_pnr	= $_POST['kode_prg_pnr'];

    $program 	    = strip_tags($_POST['program']);
    
    $kode_kategori 	= strip_tags($_POST['kode_kategori']);
    
    $status 		= strip_tags($_POST['status']);
    
    $akun_debit 	= strip_tags($_POST['akun_debit']);

    $akun_kredit 	= strip_tags($_POST['akun_kredit']);
    
    $akun_debit_bank = strip_tags($_POST['akun_debit_bank']);
    
    $akun_kredit_bank = strip_tags($_POST['akun_kredit_bank']);

    /* SQL Query Update */
    $sqlProgram = "UPDATE tm_prg_pnr SET

                    program='$program',
                        
                    kode_kategori='$kode_kategori',
                        
                    status='$status',
                        
                    akun_debit='$akun_debit',
                        
                    akun_kredit='$akun_kredit',
                        
                    akun_debit_bank='$akun_debit_bank',

                    akun_kredit_bank='$akun_kredit_bank'

                WHERE kode_prg_pnr='$kode_prg_pnr' ";

    if($kode_prg_pnr!=""){

        $updateProgram = $konek->query($sqlProgram);
     
        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}