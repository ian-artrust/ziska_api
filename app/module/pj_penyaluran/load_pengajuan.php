<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $no_kantor = $_SESSION['no_kantor'];

    $dari_pj            = $_POST['dari_pj'];

    $sampai_pj          = $_POST['sampai_pj'];

    if($kode_daerah == ''){

        $sql	= "SELECT * FROM view_224 
        
                    WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj'
                    
                    AND status ='REALISASI' AND nama_mustahik !='' ORDER BY no_pengajuan DESC";

        $sqlLbg	= "SELECT * FROM view_224b 
        
                    WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj' 
                    
                    AND status ='REALISASI' AND nama_lembaga !='' ORDER BY no_pengajuan DESC";

    }elseif($no_kantor=='' AND $kode_daerah !=''){

        $sql	= "SELECT 
                    
                    * 
                    
                FROM view_224 
                
                WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj'
                
                AND kode_daerah='$kode_daerah' AND nama_mustahik !='' AND status ='REALISASI' 
                
                ORDER BY no_pengajuan DESC";

        $sqlLbg	= "SELECT 
                            
                    * 

                FROM view_224b 

                WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj' 
                
                AND kode_daerah='$kode_daerah' AND nama_lembaga !='' AND status ='REALISASI' 

                ORDER BY no_pengajuan DESC";

    }else{

        $sql	= "SELECT 
                    
                    * 
        
                FROM view_224 
                
                WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj' 
                
                AND no_kantor='$no_kantor' AND nama_mustahik !='' AND status ='REALISASI' 
                
                ORDER BY no_pengajuan DESC";

        
        $sqlLbg	= "SELECT 
                                
                    * 

                FROM view_224b 
                
                WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj'
                
                AND no_kantor='$no_kantor' AND nama_lembaga !='' AND status ='REALISASI' 
                
                ORDER BY no_pengajuan DESC";
    }

    $sqlTotal = " SELECT 
    
                    SUM(jml_realisasi) AS total_realisasi

                FROM view_224 

                WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj' 
                
                AND kode_daerah='$kode_daerah' AND nama_mustahik !='' AND status ='REALISASI'"; 

    $sqlTotalLbg = " SELECT 
        
                    SUM(jml_realisasi) AS total_realisasi

                FROM view_224b 

                WHERE tgl_realisasi BETWEEN '$dari_pj' AND '$sampai_pj' 
                
                AND kode_daerah='$kode_daerah' AND nama_lembaga !='' AND status ='REALISASI'"; 

    $hasil	        = $konek->query($sql);

    $hasilLbg       = $konek->query($sqlLbg);

    $totalMustahik  = $konek->query($sqlTotal);

    $totalLembaga   = $konek->query($sqlTotalLbg);

    $rowSaldoMustahik = $totalMustahik->fetch_assoc();

    $rowSaldoLembaga = $totalLembaga->fetch_assoc();

    $data   = "

                <h4><b>FORMULIR PENGAJUAN PENYALURAN [ C1 ]</b></h4>
           
                <hr style='border:1px solid;'>

                <h4><b>Pengajuan Individu</b></h4>

                <table class='table'>

                    <tr>
                    
                        <td><b>No Pengajuan</b></td>

                        <td><b>Mustahik</b></td>

                        <td><b>Tgl Realisasi</b></td>

                        <td><b>Keterangan</b></td>

                        <td  align='right'><b>Jumlah Realisasi</td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $data .="
            <tr>
                
                <td>". $row['no_pengajuan'] ."</td>

                <td>". $row['nama_mustahik'] ."</td>

                <td>". $row['tgl_realisasi'] ."</td>

                <td>". $row['keterangan'] ."</td>

                <td  align='right' id='jml_realisasi'>". number_format($row['jml_realisasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "
            <tr>

                <td colspan='4' align='right'><b>Total: </b></td>

                <td align='right'><b>".number_format($rowSaldoMustahik['total_realisasi'],0,',','.')."</b></td>

            </tr>
    ";

    $data .= "</table>";

    $data .= "

            <h4><b>Pengajuan Entitas</b></h4>

            <table class='table'>

                <tr>
                
                    <td><b>No Pengajuan</b></td>

                    <td><b>Lembaga</b></td>

                    <td><b>Tgl Realisasi</b></td>

                    <td><b>Keterangan</b></td>

                    <td  align='right'><b>Jumlah Realisasi</td>

                </tr>

            ";
    
    while($row = $hasilLbg->fetch_assoc()){

        $data .="
            <tr>
                
                <td>". $row['no_pengajuan'] ."</td>

                <td>". $row['nama_lembaga'] ."</td>

                <td>". $row['tgl_realisasi'] ."</td>

                <td>". $row['keterangan'] ."</td>

                <td  align='right' id='jml_realisasi'>". number_format($row['jml_realisasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "
                <tr>

                    <td colspan='4' align='right'><b>Total: </b></td>

                    <td align='right'><b>".number_format($rowSaldoLembaga['total_realisasi'],0,',','.')."</b></td>

                </tr>
    ";

    $data .= "</table>";

    $data .= "<table class='table borderless col-lg-6'>
                
                <tr class='borderless'>
                    <td contenteditable='true' colspan='7'>Purwokerto,............................. 20...</td>
                </tr>
                <tr>
                
                    <td class='col-md-1'>Mengetahui,</td>

                    <td class='col-md-1'>Diperiksa,</td>

                    <td class='col-md-1' colspan='2'>Menyetujui,</td>
                
                </tr> 
                <tr>
                
                    <td class='col-md-1'>Program</td>

                    <td class='col-md-1'>Keuangan</td>

                    <td class='col-md-1'>Direktur</td>

                    <td class='col-md-1'>Pengurus</td>
                
                </tr> 
                <tr class='borderless'><td colspan='7'></td></tr>
                <tr class='borderless'><td colspan='7'></td></tr>
                <tr class='borderless'><td colspan='7'></td></tr>
                <tr class='borderless'><td colspan='7'></td></tr>                   
                <tr>
                
                    <td contenteditable='true'>------</td>

                    <td contenteditable='true'>------</td>

                    <td contenteditable='true'>------</td>

                    <td contenteditable='true'>------</td>
                
                </tr> 
            
            </table>
            
            <style>
                .borderless td, .borderless tr {
                    border: none !important;
                }
            </style>";

    echo $data;

}