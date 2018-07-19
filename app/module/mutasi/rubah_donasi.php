<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah']; 
    
    /** Variabel From Post */
    $npwz_sumber 	= strip_tags($_POST['npwz_sumber']);

    $npwz_target 	= strip_tags($_POST['npwz_target']);

    /* SQL Query Update */
    $sqlDonasi = "UPDATE trs_donasi SET

                    npwz='$npwz_sumber'
            
                WHERE npwz='$npwz_target' ";

    $sqlRubah = "UPDATE tm_donatur SET

                    status='REJECT'

                WHERE npwz='$npwz_target' ";

    if($kode_daerah == ""){

        $pesan 		= "Hanya Untuk Account Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($npwz_target == $npwz_sumber){

        $pesan 		= "NPWZ Tidak Boleh Sama";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($npwz_target=="" || $npwz_sumber==""){

        $pesan 		= "Data Tidak Boleh Kosong";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updateDonasi = $konek->query($sqlDonasi);

            $rejectMuzaki = $konek->query($sqlRubah);

        $konek->commit(); 

        $pesan 		= "Donasi Muzaki Berhasil Direvisi";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}