<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /** Variabel From Post */
    $kode_daerah = $_SESSION['kode_daerah'];

    $no_batal_piutang = $_POST['no_batal_piutang'];

    $sqljurnal = "SELECT no_jurnal FROM trs_juhdr WHERE ref_number='$no_batal_piutang'";

    $datajurnal = $konek->query($sqljurnal);

    $rowjurnal  = $datajurnal->fetch_assoc();

    $no_jurnal = $rowjurnal['no_jurnal'];

    /* SQL Query Update */
    $sqlBatal = "UPDATE trs_juhdr SET status='REJECT' WHERE no_jurnal='$no_jurnal' ";

    $sqlBatalSetup = "UPDATE trs_piutang SET status='REJECT' WHERE no_piutang='$no_batal_piutang' ";

    $sqlcek_angsuran = "SELECT * FROM trs_ang_piutang WHERE no_piutang='$no_batal_piutang' AND status='Aktif'";

    $hasilcek = $konek->query($sqlcek_angsuran);

    $ketemu = mysqli_num_rows($hasilcek);

    if($kode_daerah==''){

        $pesan 		= "Hanya Untuk Account Yang Memiliki Entitas Daerah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } elseif($no_batal_piutang==""){

        $pesan 		= "Pilih Piutang Yang Akan Dibatalkan";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        

    } elseif ($ketemu > 0) {
        
        $pesan 		= "Sudah Ada Angsuran Piutang, Hapus angsurand dahulu bila ingin membatalkan piutang";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } elseif($no_jurnal!=""){

        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updateBatal = $konek->query($sqlBatal); 
            
            $updateBatalSetup = $konek->query($sqlBatalSetup); 
        
            $konek->commit(); 

        $pesan 		= "Transaksi Telah Dibatalkan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}