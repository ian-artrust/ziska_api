<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $dari_bank       = $_POST['dari_bank'];

    $sampai_bank     = $_POST['sampai_bank'];

    $data   = "

        <h4><b>Laporan Penerimaan Donasi Via Bank</b></h4>

        <h5><b>Periode : ".$dari_bank." s/d ".$sampai_bank."</b></h5>

        <hr style='border:1px solid;'>

        <table class='table'>

            <tr>

                <td><b>No Donasi</b></td>

                <td><b>Muzaki</b></td>

                <td><b>Tanggal</b></td>

                <td><b>Program</b></td>

                <td><b>No Rekening</b></td>

                <td  align='right'><b>Jumlah</td>

            </tr>

        ";
    
    $sqlHeader = "SELECT 
    
                    kode_kategori, 
                    
                    kategori FROM view_221 
                    
                    WHERE tgl_donasi BETWEEN '$dari_bank' AND '$sampai_bank' 

                    AND kode_daerah='$kode_daerah' AND metode='MUTASI BANK' AND status !='REJECT' 
                    
                    GROUP BY kode_kategori ASC";

    $hasilHeader= $konek->query($sqlHeader);
    
    while($rowHeader = $hasilHeader->fetch_assoc()){
    
        $kode_kategori = $rowHeader['kode_kategori'];

        $sql	= "SELECT
            
            no_donasi,
            
            nama_donatur,
            
            tgl_donasi,
            
            program,

            norek_bank,

            createdby,

            status,

            jml_donasi

        FROM view_221
        
        WHERE tgl_donasi BETWEEN '$dari_bank' AND '$sampai_bank' 
        
        AND kode_daerah='$kode_daerah' AND metode='MUTASI BANK' 
        
        AND kode_kategori='$kode_kategori' AND status !='REJECT'";

        $sqlTotal = "SELECT 
                                    
                        SUM(jml_donasi) AS total_donasi

                    FROM view_221

                    WHERE tgl_donasi BETWEEN '$dari_bank' AND '$sampai_bank' 

                    AND kode_daerah='$kode_daerah' AND metode='MUTASI BANK' 
                    
                    AND kode_kategori='$kode_kategori' AND status !='REJECT'";

        $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";
        
        $hasil	        = $konek->query($sql);

        $hasilTotal = $konek->query($sqlTotal);

        $row_total = $hasilTotal->fetch_assoc();

        $total_dns = $row_total['total_donasi'];

        while($row = $hasil->fetch_assoc()){

            $class = '';

            $class = ' class="normal"';

            $data .="

                <tr ". $class .">

                    <td>". $row['no_donasi'] ."</td>

                    <td>". $row['nama_donatur'] ."</td>

                    <td>". $row['tgl_donasi'] ."</td>

                    <td>". $row['program'] ."</td>

                    <td>". $row['norek_bank'] ."</td>

                    <td  align='right' id='jml_donasi'>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>

                </tr>
            ";

        }

        $data .= "<tr>
                
                    <td colspan='5' align='right'><b>Sub Total: </b></td>

                    <td align='right'><b>".number_format($total_dns, 0, ',', '.')."</b></td>

                </tr>";
    
    }

    $sqlTotalDonasi = "SELECT 
                                    
                    SUM(jml_donasi) AS total_donasi

                FROM view_221

                WHERE tgl_donasi BETWEEN '$dari_bank' AND '$sampai_bank' 

                AND kode_daerah='$kode_daerah' AND metode='MUTASI BANK' AND status !='REJECT'";

    $hasilTotalDonasi = $konek->query($sqlTotalDonasi);

    $row_total_donasi = $hasilTotalDonasi->fetch_assoc();

    $total_end = $row_total_donasi['total_donasi'];


    $data .= "
            <tr>
                            
                <td colspan='5' align='right'><b>Total Donasi: </b></td>

                <td align='right'><b>".number_format($total_end, 0, ',', '.')."</b></td>

            </tr>

        </table>
    ";

    echo $data;

}