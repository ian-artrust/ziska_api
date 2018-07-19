<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_akun      = $_POST['kode_akun'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    $periode        = $_POST['periode'];

    $sql	= "SELECT 
                
                * 
                
            FROM trs_judtl 
            
            WHERE kode_akun='$kode_akun' 
            
            AND kode_daerah='$kode_daerah' 
            
            AND periode='$periode'
            
            AND status='Trial'";
    
    $result	= $konek->query($sql);

    $data   = '';

    while($row = $result->fetch_assoc()){

        $data .="
            <tr>
                <td>". $row['no_jurnal'] ."</td>

                <td>". $row['tgl_jurnal'] ."</td>

                <td>". $row['keterangan'] ."</td>

                <td>". number_format($row['debit'], 0, ',', '.') ."</td>

                <td>". number_format($row['kredit'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    echo $data;
}