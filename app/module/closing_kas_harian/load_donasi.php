<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $npwz           = $_POST['npwz'];

    $sql	= "SELECT 
        
        no_donasi,
        
        no_bukti,

        nama_donatur,
        
        tgl_donasi,
        
        program,

        jml_donasi

    FROM view_221
    
    WHERE kode_daerah='$kode_daerah' AND npwz='$npwz'";
    
    $hasil	        = $konek->query($sql);

    $data   = "

                <h4><b>Laporan Donasi Muzaki</b></h4>

                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>

                        <td><b>No Donasi</b></td>

                        <td><b>No Bukti</b></td>

                        <td><b>Muzaki</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Program</b></td>

                        <td  align='right'><b>Jumlah</td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $class = '';

        $class = ' class="normal"';

        $data .="
            <tr ". $class .">
                
                <td>". $row['no_donasi'] ."</td>

                <td>". $row['no_bukti'] ."</td>

                <td>". $row['nama_donatur'] ."</td>

                <td>". $row['tgl_donasi'] ."</td>

                <td>". $row['program'] ."</td>

                <td  align='right' id='jml_donasi'>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "</table>";

    echo $data;

}