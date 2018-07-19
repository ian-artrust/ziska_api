<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    /** PETUGAS */
    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $data   = "

                <h4><b>DAFTAR MUSTAHIK</b></h4>

                <hr style='border:1px solid;'>

            ";
            
        $sql	= "SELECT 

                        no_registrasi, 

                        nama_mustahik,

                        nilai_bantuan

                    FROM view_433 

                    WHERE kode_daerah = '$kode_daerah'";

        $hasil	        = $konek->query($sql);

        $data .= "
                <table class='table'>

                    <tr>
                        
                            <td><b>Kode Mustahik</b></td>

                            <td><b>Mustahik</b></td>

                            <td><b>Nilai Bantuan</b></td>

                    </tr>";

        while($row = $hasil->fetch_assoc()){
 
            $data .="
                
                <tr>
                    
                    <td>". $row['no_registrasi'] ."</td>
    
                    <td>". $row['nama_mustahik'] ."</td>

                    <td>". number_format($row['nilai_bantuan'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }

    $data .= "</table>";

    echo $data;

}