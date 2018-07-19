<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_registrasi  = $_POST['no_registrasi'];

    $nama_mustahik  = $_POST['nama_mustahik'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    $sql	= "SELECT 
                
                * 
                
            FROM view_224 
            
            WHERE no_registrasi='$no_registrasi' AND status='REALISASI'
            
            OR no_registrasi='$no_registrasi' AND status='COMPLETE'";
    
    $result	= $konek->query($sql);

    $data   = "

                <h4><b>LAPORAN PENDISTRIBUSIAN MUSTAHIK</b></h4>

                    <hr style='border:1px solid;'>

                    <table border='0'>

                        <tr>
                            
                            <td><b>Kode Mustahik</b></td>

                            <td>:</td>

                            <td><b>".$no_registrasi."</b></td>

                        </tr>

                        <tr>

                            <td><b>Mustahik</b></td>

                            <td>:</td>

                            <td><b>".$nama_mustahik."</b></td>

                        </tr>

                    </table>

                <hr style='border:1px solid;'>

            ";
    
    $data .= "</table>";

    $data .= "
            <table class='table'>

                <tr>
                    
                        <td><b>Perihal</b></td>

                        <td><b>Keterangan</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Jumlah</b></td>

                </tr>";


    while($row = $result->fetch_assoc()){

        $data .="
            <tr>
                <td>". $row['nama_master'] ."</td>    

                <td>". $row['keterangan'] ."</td>

                <td>". $row['tgl_realisasi'] ."</td>

                <td>". number_format($row['jml_realisasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "</table>";

    echo $data;
}