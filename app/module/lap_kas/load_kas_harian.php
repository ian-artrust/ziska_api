<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $kas_tgl        = $_POST['kas_tgl'];

    $dari_tgl       = $_POST['dari_tgl'];

    $sampai_tgl     = $_POST['sampai_tgl'];

    if($kode_daerah==''){

        $sql	= "SELECT 
            no_jurnal,
            
            tgl_jurnal,
            
            keterangan,
            
            debit,
            
            kredit

        FROM view_321b
        
        WHERE tgl_jurnal BETWEEN '$dari_tgl' AND '$sampai_tgl' 
        
        AND kode_akun='$kas_tgl' AND status !='REJECT'";

    } else {

        $sql	= "SELECT 
            no_jurnal,
            
            tgl_jurnal,
            
            keterangan,
            
            debit,
            
            kredit

        FROM view_321b
        
        WHERE tgl_jurnal BETWEEN '$dari_tgl' AND '$sampai_tgl' 
        
        AND kode_daerah='$kode_daerah' AND kode_akun='$kas_tgl' AND status !='REJECT'";

    }

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    
    $sqlTotal	= "SELECT 

                SUM(debit) AS debit,
            
                SUM(kredit) AS kredit,

				SUM(debit) - SUM(kredit) AS saldo

            FROM view_321b

            WHERE tgl_jurnal BETWEEN '$dari_tgl' AND '$sampai_tgl' 

            AND kode_daerah='$kode_daerah' AND kode_akun='$kas_tgl' AND status !='REJECT'";

    
    $hasil	        = $konek->query($sql);

    $hasilPetugas   = $konek->query($sqlPetugas);

    $hasilTotal     = $konek->query($sqlTotal);

    $baris          = $hasilPetugas->fetch_assoc();

    $barisTotal     = $hasilTotal->fetch_assoc();

    $data   = "

                <h4><b>Laporan Kas</b></h4>

                <hr style='border:1px solid;'>

                <table cellspacing='15' cellpadding='15'>
                    
                    <tr>
                        <td width='150'><b>KODE PETUGAS</b></td>
                        <td width=25><b>:</b></td>
                        <td>".$kode_petugas."</td>
                    </tr>
                    <tr>
                        <td><b>PETUGAS</b></td>
                        <td><b>:</b></td>
                        <td>".$_SESSION['nama_petugas']."</td>
                    </tr>

                </table>
            
                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                    
                        <td><b>No Jurnal</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Keterangan</b></td>

                        <td><b>Debit</b></td>

                        <td><b>Kredit</b></td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $class = ' class="normal"';

        $data .="
            <tr ". $class .">
                
                <td>". $row['no_jurnal'] ."</td>

                <td>". $row['tgl_jurnal'] ."</td>

                <td>". $row['keterangan'] ."</td>

                <td>". number_format($row['debit'], 0, ',', '.') ."</td>

                <td>". number_format($row['kredit'], 0, ',', '.') ."</td>

            </tr>
            
        ";

    }

    $data .= "
    
            <tr>

                <td colspan='3' align='right'>TOTAL: </td>

                <td>". number_format($barisTotal['debit']) ."</td>

                <td>". number_format($barisTotal['kredit']) ."</td>

            </tr>

            <tr>

                <td colspan='3' align='right'>SALDO: </td>

                <td>". number_format($barisTotal['saldo']) ."</td>

            </tr>
        </table>";

    echo $data;

}