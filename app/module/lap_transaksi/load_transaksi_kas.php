<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $dari        = $_POST['dari'];

    $sampai      = $_POST['sampai'];

    $sqlTotal	= "SELECT 

                        kode_akun,

                        akun,

                        SUM(debit) AS debit,

                        SUM(kredit) AS kredit,

                        IF(SUM(debit) > SUM(kredit), SUM(debit)-SUM(kredit), SUM(kredit)- SUM(debit)) AS saldo

                    FROM view_631

                    WHERE 
                    
                    tgl_jurnal BETWEEN '$dari' AND '$sampai' AND kategori='Kas dan Setara Kas'

                    AND kode_daerah='$kode_daerah' AND status !='REJECT' 

                    GROUP BY kode_akun";

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    $hasilTotal     = $konek->query($sqlTotal);

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    // $barisTotal     = $hasilTotal->fetch_assoc();

    $data   = "

                <h4><b>LAPORAN TRANSAKSI</b></h4>

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
                        <td>".$baris['nama_petugas']."</td>
                    </tr>

                </table>
                <hr style='border:1px solid;'>

            ";
    
    while ($row_total = $hasilTotal->fetch_assoc()) {

        $data .= "
                    <table>

                        <tr>
                            <td>".$row_total['kode_akun']." : ".$row_total['akun']."</td>
                        </tr>

                        <tr>

                            <td width='250'>DEBIT: ".number_format($row_total['debit'])." </td>

                            <td>&nbsp;</td>

                            <td  width='250'>KREDIT: ".number_format($row_total['kredit'])." </td>

                            <td>&nbsp;</td>

                            <td  width='250'>SALDO: ".number_format($row_total['saldo'])." </td>

                        </tr>
                    </table>";

            $kode_akun_dtl = $row_total['kode_akun'];
            
            if($kode_daerah==''){

                $sql	= "SELECT 
                
                            kode_akun,
                            
                            akun,
        
                            keterangan,
        
                            debit,
        
                            kredit
            
                        FROM view_631 
        
                        WHERE 
                        
                        tgl_jurnal BETWEEN '$dari' AND '$sampai' 
                        
                        AND kategori='Kas dan Setara Kas' AND kode_akun='$kode_akun_dtl' 
                        
                        AND status !='REJECT'";
        
            } else {
        
                $sql	= "SELECT 
                
                            kode_akun,
                            
                            akun,
        
                            keterangan,
        
                            debit,
        
                            kredit
        
                        FROM view_631 
                
                        WHERE 
                        
                        tgl_jurnal BETWEEN '$dari' AND '$sampai' AND kode_daerah='$kode_daerah'
                        
                        AND kategori='Kas dan Setara Kas' AND kode_akun='$kode_akun_dtl' 
                        
                        AND status !='REJECT'";
        
            }

            $hasil	        = $konek->query($sql);

            $data .= "
                    <table class='table'>

                        <tr>
                            
                                <td><b>Kode Akun</b></td>

                                <td><b>Akun</b></td>

                                <td><b>Keterangan</b></td>

                                <td><b>Debit</b></td>

                                <td><b>Kredit</b></td>

                        </tr>";

            while($row = $hasil->fetch_assoc()){

                $class = ' class="normal"';
        
                $data .="
                    
                    <tr ". $class .">
                        
                        <td>". $row['kode_akun'] ."</td>
        
                        <td>". $row['akun'] ."</td>
        
                        <td>". $row['keterangan'] ."</td>
        
                        <td>". number_format($row['debit'], 0, ',', '.') ."</td>
        
                        <td>". number_format($row['kredit'], 0, ',', '.') ."</td>
        
                    </tr>";
        
            }

    }

    $data .= "</table>";

    echo $data;

}