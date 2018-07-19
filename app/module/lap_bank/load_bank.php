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

    $no_rekening    = $_POST['no_rekening'];

    if($kode_daerah==''){

        $sql	= "SELECT 
                        id_judtl,
                        kode_daerah,
                        kode_akun,
                        kode_sub_kat_akun,
                        ref_number,
                        status,
                        no_jurnal,
                        periode,
                        tgl_jurnal,
                        keterangan,
                        debit,
                        kredit,
                        @saldo_awal_bank:=@saldo_awal_bank+debit-kredit as saldo 
                    FROM view_323a x, (SELECT @saldo_awal_bank:=0) y 
                    WHERE x.tgl_jurnal BETWEEN '$dari_tgl' AND '$sampai_tgl'
                    AND x.kode_sub_kat_akun='102' 
                    AND x.no_rekening='$no_rekening'
                    AND x.status!='REJECT'";

    } else {

        $sql	= "SELECT 
                    id_judtl,
                    kode_daerah,
                    kode_akun,
                    kode_sub_kat_akun,
                    ref_number,
                    status,
                    no_jurnal,
                    periode,
                    tgl_jurnal,
                    keterangan,
                    debit,
                    kredit,
                    @saldo_awal_bank:=@saldo_awal_bank+debit-kredit as saldo 
                FROM view_323a x, (SELECT @saldo_awal_bank:=0) y 
                WHERE x.tgl_jurnal BETWEEN '$dari_tgl' AND '$sampai_tgl'
                AND x.kode_sub_kat_akun='102' 
                AND x.no_rekening='$no_rekening'
                AND x.kode_daerah='$kode_daerah'
                AND x.status!='REJECT'";

    }

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    $sqlBank    = "SELECT nama_bank FROM tm_bank WHERE no_rekening='$no_rekening'";
    
    $hasil	        = $konek->query($sql);

    $hasilPetugas   = $konek->query($sqlPetugas);

    $hasilBank      = $konek->query($sqlBank);

    $baris          = $hasilPetugas->fetch_assoc();

    $barisBank      = $hasilBank->fetch_assoc();

    $data   = "

                <h4><b>Rekening Koran</b></h4>

                <hr style='border:1px solid;'>

                <table cellspacing='15' cellpadding='15'>
                    
                    <tr>
                        <td width='150'><b>KODE PETUGAS</b></td>
                        <td width=25><b>:</b></td>
                        <td>".$kode_petugas."</td>
                        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        <td width='100'><b>NO REK</b></td>
                        <td width='25'><b>:</b></td>
                        <td>".$no_rekening."</td>
                    </tr>
                    <tr>
                        <td><b>PETUGAS</b></td>
                        <td><b>:</b></td>
                        <td>".$_SESSION['nama_petugas']."</td>
                        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        <td><b>BANK</b></td>
                        <td><b>:</b></td>
                        <td>".$barisBank['nama_bank']."</td>
                    </tr>

                </table>
            
                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                    
                        <td><b>No Transaksi</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Keterangan</b></td>

                        <td><b>Debit</b></td>

                        <td><b>Kredit</b></td>

                        <td><b>Saldo</b></td>

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

                <td>". number_format($row['saldo'], 0, ',', '.') ."</td>

            </tr>
            
        ";

    }

    $data .= "</table>";

    echo $data;

}