<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $dari_tgl       = $_POST['dari_tgl'];

    $sampai_tgl     = $_POST['sampai_tgl'];

    $sql	= "SELECT 
        metode,
        
        no_donasi,
        
        nama_donatur,
        
        tgl_donasi,
        
        program,

        createdby,

        status,

        jml_donasi

    FROM view_221
    
    WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' 
    
    AND kode_daerah='$kode_daerah' AND status !='REJECT'";

    $sqlTotal = "SELECT 
                                
                    SUM(jml_donasi) AS total_donasi

                FROM view_221

                WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' 

                AND kode_daerah='$kode_daerah' AND status !='REJECT'";

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";
    
    $hasil	        = $konek->query($sql);

    $hasilTotal = $konek->query($sqlTotal);

    $row_total = $hasilTotal->fetch_assoc();

    $total_dns = $row_total['total_donasi'];

    $data   = "

                <h4><b>Laporan Penerimaan Donasi</b></h4>

                <h5><b>Periode : ".$dari_tgl." s/d ".$sampai_tgl."</b></h5>

                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                    
                        <td><b>Jenis</b></td>

                        <td><b>No Donasi</b></td>

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
                
                <td>". $row['metode'] ."</td>

                <td>". $row['no_donasi'] ."</td>

                <td>". $row['nama_donatur'] ."</td>

                <td>". $row['tgl_donasi'] ."</td>

                <td>". $row['program'] ."</td>

                <td  align='right' id='jml_donasi'>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "<tr>
            
                <td colspan='5' align='right'><b>Jumlah</b></td>

                <td align='right'><b>".number_format($total_dns, 0, ',', '.')."</b></td>

            </tr>";

    $data .= "</table>";

    echo $data;

}