<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if($kode_daerah == ''){

        $sql	= "SELECT * FROM view_641 WHERE status !='REJECT' ORDER BY id_judtl DESC";

    }else{

        $sql	= "SELECT 
                    
                    * 
                    
                FROM view_641 
                
                WHERE  kode_daerah='$kode_daerah'AND status !='REJECT' ORDER BY id_judtl DESC";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_jurnal'] = $row['no_jurnal'];

        $r['akun'] = $row['akun'];

        $r['keterangan'] = $row['keterangan'];

        $r['periode'] = $row['periode'];

        $r['tgl_jurnal'] = $row['tgl_jurnal'];

        $r['debit'] = number_format($row['debit']);

        $r['kredit'] = number_format($row['kredit']);

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}