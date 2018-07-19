<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $dari_detail        = $_POST['dari_detail'];

    $sampai_detail      = $_POST['sampai_detail'];

    /** PETUGAS */
    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    /** TOTAL PENERIMAAN */
    $sqlTotalKredit = "SELECT 
    
                            SUM(kredit) AS kredit

                        FROM view_631

                        WHERE 

                        tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' AND kode_daerah='$kode_daerah'

                        AND kelompok='Penerimaan' AND status !='REJECT'";

    $hasilTotalKredit = $konek->query($sqlTotalKredit);

    $barisTotalKredit = $hasilTotalKredit->fetch_assoc();

    /** TOTAL PENGELUARAN */
    $sqlTotalDebit = "SELECT 
    
                            SUM(debit) AS debit

                        FROM view_631

                        WHERE 

                        tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' AND kode_daerah='$kode_daerah'
                    
                        AND kelompok='Penyaluran' AND status !='REJECT'

                        OR 

                        tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' AND kode_daerah='$kode_daerah'

                        AND kelompok='Biaya' AND status !='REJECT'";

    $hasilTotalDebit = $konek->query($sqlTotalDebit);

    $barisTotalDebit = $hasilTotalDebit->fetch_assoc();

    $data   = "

                <h4><b>LAPORAN DETAIL TRANSAKSI</b></h4>

                <h5><b>Periode Tanggal: ".$dari_detail." s/d ".$sampai_detail."</b></h5>

                <hr style='border:1px solid;'>

            ";
            
        if($kode_daerah==''){

            $sql	= "SELECT 
            
                        kode_akun,
                        
                        akun,

                        keterangan,
    
                        debit,
    
                        kredit
        
                    FROM view_631 
    
                    WHERE 
                    
                    tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' 
                    
                    AND kelompok='Penerimaan' AND status !='REJECT'
                    
                    OR
                    
                    tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' 
                    
                    AND kelompok='Penyaluran' AND status !='REJECT'

                    OR 

                    tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail'

                    AND kelompok='Biaya' AND status !='REJECT' ORDER BY kode_akun

                    ";
    
        } else {
    
            $sql	= "SELECT 
            
                        kode_akun,
                        
                        akun,

                        keterangan,
    
                        debit,
    
                        kredit
    
                    FROM view_631 
            
                    WHERE 
                    
                    tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' AND kode_daerah='$kode_daerah'
                    
                    AND kelompok='Penerimaan' AND status !='REJECT'
                    
                    OR
                    
                    tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' AND kode_daerah='$kode_daerah'
                    
                    AND kelompok='Penyaluran' AND status !='REJECT'

                    OR 

                    tgl_jurnal BETWEEN '$dari_detail' AND '$sampai_detail' AND kode_daerah='$kode_daerah'
                    
                    AND kelompok='Biaya' AND status !='REJECT'

                    ORDER BY kode_akun

                    ";
    
        }

        $hasil	        = $konek->query($sql);

        $data .= "
                <table class='table'>

                    <tr>
                        
                            <td><b>Kode Akun</b></td>

                            <td><b>Akun</b></td>

                            <td><b>Keterangan</b></td>

                            <td><b>Penerimaan</b></td>

                            <td><b>Pengeluaran</b></td>

                    </tr>";

        while($row = $hasil->fetch_assoc()){

            $class = ' class="normal"';
    
            $data .="
                
                <tr ". $class .">
                    
                    <td>". $row['kode_akun'] ."</td>
    
                    <td>". $row['akun'] ."</td>

                    <td>". $row['keterangan'] ."</td>

                    <td align='right'>". number_format($row['kredit'], 0, ',', '.') ."</td>
    
                    <td align='right'>". number_format($row['debit'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }
    
    $data .= "
                <tr>

                    <td colspan='3' align='right'><b>JUMLAH: </b></td>

                    <td align='right'><b>". number_format($barisTotalKredit['kredit'], 0, ',', '.')."</b></td>

                    <td align='right'><b>". number_format($barisTotalDebit['debit'], 0, ',', '.')."</b></td>

                </tr>";

    $data .= "</table>";

    $data .= "
    
                <br>

                <table border='0'>

                    <tr>
                        <td>Keuangan</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Direktur</td>
                    </tr>
                    <tr>
                        <td width='50'>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td contenteditable='true'>---</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td contenteditable='true'>---</td>
                    </tr>

                </table>
        
    ";

    echo $data;

}