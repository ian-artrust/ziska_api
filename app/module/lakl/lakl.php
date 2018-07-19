<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $periode        = $_POST['periode'];

    /** Aset Lancar Kelolaan */
    // Hasil Penempatan
    $sqlSAHP    = "SELECT SUM(jml_setup) AS saldo_awal_hp FROM view_321c 
                    WHERE kode_daerah='$kode_daerah' AND status='Aktif' 
                    AND kategori='Penempatan Infak' AND periode='$periode'";
    $hasilSAHP  = $konek->query($sqlSAHP);
    $row_sa_hp  = $hasilSAHP->fetch_assoc();

    $sqlPBHP    = "SELECT SUM(debit) AS penambahan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Penempatan Infak' AND periode='$periode'";
    $hasilPBHP  = $konek->query($sqlPBHP);
    $row_pb_hp  = $hasilPBHP->fetch_assoc();

    $sqlPRHP    = "SELECT SUM(kredit) AS pengurangan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Penempatan Infak' AND periode='$periode'";
    $hasilPRHP  = $konek->query($sqlPRHP);
    $row_pr_hp  = $hasilPRHP->fetch_assoc();

    $saldo_akhir_hp = (intval($row_sa_hp['saldo_awal_hp'])+intval($row_pb_hp['penambahan']))-intval($row_pr_hp['pengurangan']);

    // Piutang
    $sqlSAPT    = "SELECT SUM(jml_setup) AS saldo_awal_pt FROM view_321c 
                    WHERE kode_daerah='$kode_daerah' AND status='Aktif' 
                    AND kategori='Piutang Infak' AND periode='$periode'";
    $hasilSAPT  = $konek->query($sqlSAPT);
    $row_sa_pt  = $hasilSAPT->fetch_assoc();

    $sqlPBPT    = "SELECT SUM(debit) AS penambahan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Piutang Infak' AND periode='$periode'";
    $hasilPBPT  = $konek->query($sqlPBPT);
    $row_pb_pt  = $hasilPBPT->fetch_assoc();

    $sqlPRPT    = "SELECT SUM(kredit) AS pengurangan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Piutang Infak' AND periode='$periode'";
    $hasilPRPT  = $konek->query($sqlPRPT);
    $row_pr_pt  = $hasilPRPT->fetch_assoc();

    $saldo_akhir_pt = (intval($row_sa_pt['saldo_awal_pt'])+intval($row_pb_pt['penambahan']))-intval($row_pr_pt['pengurangan']);

    // Persediaan
    $sqlSAPD    = "SELECT SUM(jml_setup) AS saldo_awal_pd FROM view_321c 
                    WHERE kode_daerah='$kode_daerah' AND status='Aktif' 
                    AND kategori='Persediaan Infak' AND periode='$periode'";
    $hasilSAPD  = $konek->query($sqlSAPD);
    $row_sa_pd  = $hasilSAPD->fetch_assoc();

    $sqlPBPD    = "SELECT SUM(debit) AS penambahan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Persediaan Infak' AND periode='$periode'";
    $hasilPBPD  = $konek->query($sqlPBPD);
    $row_pb_pd  = $hasilPBPD->fetch_assoc();

    $sqlPRPD    = "SELECT SUM(kredit) AS pengurangan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Persediaan Infak' AND periode='$periode'";
    $hasilPRPD  = $konek->query($sqlPRPD);
    $row_pr_pd  = $hasilPRPD->fetch_assoc();

    $saldo_akhir_pd = (intval($row_sa_pd['saldo_awal_pd'])+intval($row_pb_pd['penambahan']))-intval($row_pr_pd['pengurangan']);

    // Asset Kelolaan
    $sqlSAKL    = "SELECT SUM(jml_setup) AS saldo_awal_kl FROM view_321c 
                    WHERE kode_daerah='$kode_daerah' AND status='Aktif' 
                    AND kategori='Aset Kelolaan Infak' AND periode='$periode'";
    $hasilSAKL  = $konek->query($sqlSAKL);
    $row_sa_kl  = $hasilSAKL->fetch_assoc();

    $sqlPBKL    = "SELECT SUM(debit) AS penambahan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Aset Kelolaan Infak' AND periode='$periode'";
    $hasilPBKL  = $konek->query($sqlPBKL);
    $row_pb_kl  = $hasilPBKL->fetch_assoc();

    $sqlPRKL    = "SELECT SUM(kredit) AS pengurangan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Aset Kelolaan Infak' AND periode='$periode'";
    $hasilPRKL  = $konek->query($sqlPRKL);
    $row_pr_kl  = $hasilPRKL->fetch_assoc();

    $sqlAKPY    = "SELECT SUM(debit) AS akm_penyusutan FROM view_641 
    WHERE kode_daerah='$kode_daerah' AND status!='REJECT' 
    AND kategori='Akumulasi Penyusutan Aset Kelolaan Infak' AND periode='$periode'";
    $hasilAKPY  = $konek->query($sqlAKPY);
    $row_py_akl  = $hasilAKPY->fetch_assoc();

    $saldo_akhir_kl = (intval($row_sa_kl['saldo_awal_kl'])+intval($row_pb_kl['penambahan']))-intval($row_pr_kl['pengurangan'])-intval($row_py_akl['akm_penyusutan']);

    $data   = "

                <h4><b>LAPORAN PERUBAHAN ASET KELOLAAN - PSAK-109</b></h4>

                <hr style='border:1px solid;'>

                <table cellspacing='15' cellpadding='15'>
                    
                    <tr>
                        <td width='100'><b>LAZISMU</b></td>
                        <td><b>:</b></td>
                        <td contenteditable='true'>&nbsp;".$_SESSION['nama_daerah']."</td>
                    </tr>
                    <tr>
                        <td><b>PERIODE</b></td>
                        <td><b>:</b></td>
                        <td contenteditable='true'>&nbsp;".$periode."</td>
                    </tr>

                </table>
            
                <hr style='border:1px solid;'>

            ";



    $data .= "
        <div class='row'>
            <div class='col-md-12'>
                <table class='table table-condensed'>
                    <tr>
                        <td align='left'><b>KETERANGAN</b></td>
                        <td align='right'><b>SALDO AWAL</b></td>
                        <td align='right'><b>PENAMBAHAN</b></td>
                        <td align='right'><b>PENGURANGAN</b></td>
                        <td align='right'><b>AKM. PENYUSUTAN</b></td>
                        <td align='right'><b>AKM. PENYISIHAN</b></td>
                        <td align='right'><b>SALDO AKHIR</b></td>
                    </tr>    
                    <tr>
                        <td align='left' colspan='7'><b>Aset Lancar Kelolaan</b></td>
                    </tr>
                    <tr>
                        <td align='left'>Penempatan</td>
                        <td align='right'>".number_format($row_sa_hp['saldo_awal_hp'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pb_hp['penambahan'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pr_hp['pengurangan'],0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format($saldo_akhir_hp,0,',','.')."</td>
                    </tr>
                    <tr>
                        <td align='left'>Piutang</td>
                        <td align='right'>".number_format($row_sa_pt['saldo_awal_pt'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pb_pt['penambahan'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pr_pt['pengurangan'],0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format($saldo_akhir_pt,0,',','.')."</td>
                    </tr>
                    <tr>
                        <td align='left'>Persediaan</td>
                        <td align='right'>".number_format($row_sa_pd['saldo_awal_pd'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pb_pd['penambahan'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pr_pd['pengurangan'],0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format($saldo_akhir_pd,0,',','.')."</td>
                    </tr>
                    <tr>
                        <td align='left'><b>Aset Tidak Lancar Kelolaan</b></td>
                        <td align='left'></td>
                        <td align='left'></td>
                        <td align='left'></td>
                        <td align='left'></td>
                        <td align='left'></td>
                        <td align='left'></td>
                    </tr>
                    <tr>
                        <td align='left'>Aset Kelolaan</td>
                        <td align='right'>".number_format($row_sa_kl['saldo_awal_kl'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pb_kl['penambahan'],0,',','.')."</td>
                        <td align='right'>".number_format($row_pr_kl['pengurangan'],0,',','.')."</td>
                        <td align='right'>".number_format($row_py_akl['akm_penyusutan'],0,',','.')."</td>
                        <td align='right'>".number_format(0,0,',','.')."</td>
                        <td align='right'>".number_format($saldo_akhir_kl,0,',','.')."</td>
                    </tr>
                </table>
            </div>
        </div>
    ";

    echo $data;

}