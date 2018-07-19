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

    $sqlKSK	= "SELECT 

                SUM(saldo) AS saldo 

            FROM view_641b

            WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

            AND jenis='Kas dan Setara Kas' AND status !='REJECT'
    
            GROUP BY jenis";

    $sqlPiutang	= "SELECT 

                SUM(saldo) AS saldo 

            FROM view_641b

            WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

            AND jenis='Piutang' AND status !='REJECT'

            GROUP BY jenis";

    $sqlPersediaan = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Persediaan' AND status !='REJECT'

                GROUP BY jenis";

    $sqlUangMuka = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Uang Muka' AND status !='REJECT'

                GROUP BY jenis";

    $sqlBDD = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Biaya Dibayar Dimuka' AND status !='REJECT'

                GROUP BY jenis";

    $sqlPenempatan = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Penempatan' AND status !='REJECT'

                GROUP BY jenis";

    $sqlAsetTetap = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Asset' AND status !='REJECT'

                GROUP BY jenis";

    $sqlAkmPenyusutan = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Akumulasi Asset' AND status !='REJECT'

                GROUP BY jenis";

    $sqlAsetKelolaan = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Asset Kelolaan' AND status !='REJECT'

                GROUP BY jenis";

    $sqlAkmpKelolaan = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Akumulasi Asset Kelolaan' AND status !='REJECT'

                GROUP BY jenis";

    $sqlLbjPendek = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Liabilitas Jangka Pendek' AND status !='REJECT'

                GROUP BY jenis";

    $sqlLbjPanjang = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND jenis='Liabilitas Jangka Panjang' AND status !='REJECT'

                GROUP BY jenis";

    $sqlDanaZakat = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND komponen='SDZ' AND status !='REJECT'

                GROUP BY komponen";
    
    $sqlPengurangSDZ = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND kategori='Akumulasi Penyusutan  Asset Kelolaan Zakat' AND status !='REJECT'

                GROUP BY kategori";

    $sqlDanaInfak = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND komponen='SDI' AND status !='REJECT'

                GROUP BY komponen";

    $sqlPengurangSDI = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND kategori='Akumulasi Penyusutan  Asset Kelolaan Infak' AND status !='REJECT'

                GROUP BY kategori";
    
    $sqlDanaAmil = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND komponen='SDA' AND status !='REJECT'

                GROUP BY komponen";

    $sqlDanaCSR = "SELECT 

                    SUM(saldo) AS saldo 

                FROM view_641b

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND komponen='SDC' AND status !='REJECT'

                GROUP BY komponen";

    /** Query */

    $hasilKSK	        = $konek->query($sqlKSK);

    $hasilPiutang	    = $konek->query($sqlPiutang);

    $hasilPersediaan	= $konek->query($sqlPersediaan);

    $hasilUangMuka	    = $konek->query($sqlUangMuka);

    $hasilBDD           = $konek->query($sqlBDD);

    $hasilPenempatan    = $konek->query($sqlPenempatan);

    $hasilAsetTetap     = $konek->query($sqlAsetTetap);

    $hasilAkmPenyusutan = $konek->query($sqlAkmPenyusutan);

    $hasilAsetKelolaan  = $konek->query($sqlAsetKelolaan);

    $hasilAkmpKelolaan  = $konek->query($sqlAkmpKelolaan);

    $hasilLbjPendek     = $konek->query($sqlLbjPendek);

    $hasilLbjPanjang    = $konek->query($sqlLbjPanjang);

    $hasilDanaZakat     = $konek->query($sqlDanaZakat);

    $hasilPengurangDanaZakat     = $konek->query($sqlPengurangSDZ);

    $hasilDanaInfak     = $konek->query($sqlDanaInfak);

    $hasilPengurangDanaInfak     = $konek->query($sqlPengurangSDI);

    $hasilDanaAmil      = $konek->query($sqlDanaAmil);

    $hasilDanaCSR       = $konek->query($sqlDanaCSR);

    /** Fetch Data */

    $rowKSK         = $hasilKSK->fetch_assoc();    

    $rowPiutang     = $hasilPiutang->fetch_assoc();

    $rowPersediaan  = $hasilPersediaan->fetch_assoc();

    $rowUangMuka    = $hasilUangMuka->fetch_assoc();

    $rowBDD         = $hasilBDD->fetch_assoc();

    $rowPenempatan  = $hasilPenempatan->fetch_assoc();

    $rowAsetTetap   = $hasilAsetTetap->fetch_assoc();

    $rowAkmPenyusutan  = $hasilAkmPenyusutan->fetch_assoc();

    $rowAsetKelolaan   = $hasilAsetKelolaan->fetch_assoc();

    $rowAkmpKelolaan   = $hasilAkmpKelolaan->fetch_assoc();

    $rowLbjPendek      = $hasilLbjPendek->fetch_assoc();

    $rowLbjPanjang     = $hasilLbjPanjang->fetch_assoc();

    $rowDanaZakat      = $hasilDanaZakat->fetch_assoc();

    $rowPengurangDanaZakat      = $hasilPengurangDanaZakat->fetch_assoc();

    $rowDanaInfak      = $hasilDanaInfak->fetch_assoc();

    $rowPengurangDanaInfak      = $hasilPengurangDanaInfak->fetch_assoc();

    $rowDanaAmil       = $hasilDanaAmil->fetch_assoc();

    $rowDanaCSR        = $hasilDanaCSR->fetch_assoc();

    /** Cek Data */
    $cekKSK         = mysqli_num_rows($hasilKSK); 

    $cekPiutang     = mysqli_num_rows($hasilPiutang);

    $cekPersediaan  = mysqli_num_rows($hasilPersediaan);

    $cekUangMuka    = mysqli_num_rows($hasilUangMuka);

    $cekBDD         = mysqli_num_rows($hasilBDD);

    $cekPenempatan  = mysqli_num_rows($hasilPenempatan);

    $cekAsetTetap  = mysqli_num_rows($hasilAsetTetap);

    $cekAkmPenyusutan  = mysqli_num_rows($hasilAkmPenyusutan);

    $cekAsetKelolaan   = mysqli_num_rows($hasilAsetKelolaan);

    $cekAkmpKelolaan   = mysqli_num_rows($hasilAkmpKelolaan);

    $cekLbjPendek      = mysqli_num_rows($hasilLbjPendek);

    $cekLbjPanjang     = mysqli_num_rows($hasilLbjPanjang);

    $cekDanaZakat     = mysqli_num_rows($hasilDanaZakat);

    $cekDanaInfak     = mysqli_num_rows($hasilDanaInfak);

    $cekDanaAmil     = mysqli_num_rows($hasilDanaAmil);

    $cekDanaCSR     = mysqli_num_rows($hasilDanaCSR);

    if ($cekKSK > 0) {
        
        $row_ksk = $rowKSK['saldo'];

    } else {

        $row_ksk = "0";

    }

    if ($cekPiutang > 0) {
        
        $row_piutang = $rowPiutang['saldo'];

    } else {

        $row_piutang = "0";

    }

    if ($cekPersediaan > 0) {
        
        $row_persediaan = $rowPersediaan['saldo'];

    } else {

        $row_persediaan = "0";

    }

    if ($cekUangMuka> 0) {
        
        $row_uangmuka = $rowUangMuka['saldo'];

    } else {

        $row_uangmuka = "0";

    }

    if ($cekBDD> 0) {
        
        $row_bdd = $rowBDD['saldo'];

    } else {

        $row_bdd = "0";

    }

    if ($cekPenempatan> 0) {
        
        $row_penempatan = $rowPenempatan['saldo'];

    } else {

        $row_penempatan = "0";

    }

    if ($cekAsetTetap> 0) {
        
        $row_aset_tetap = $rowAsetTetap['saldo'];

    } else {

        $row_aset_tetap = "0";

    }

    if ($cekAkmPenyusutan> 0) {
        
        $row_akm_penyusutan = $rowAkmPenyusutan['saldo'];

    } else {

        $row_akm_penyusutan = "0";

    }

    if ($cekAsetKelolaan> 0) {
        
        $row_aset_kelolaan = $rowAsetKelolaan['saldo'];

    } else {

        $row_aset_kelolaan = "0";

    }

    if ($cekAkmpKelolaan> 0) {
        
        $row_akmp_ak = $rowAkmpKelolaan['saldo'];

    } else {

        $row_akmp_ak = "0";

    }

    if ($cekLbjPendek> 0) {
        
        $row_lbj_pendek = $rowLbjPendek['saldo'];

    } else {

        $row_lbj_pendek = "0";

    }

    if ($cekLbjPanjang> 0) {
        
        $row_lbj_panjang = $rowLbjPanjang['saldo'];

    } else {

        $row_lbj_panjang = "0";

    }

    if ($cekDanaZakat> 0) {
        
        $row_dana_zakat = intval($rowDanaZakat['saldo']) - intval($rowPengurangDanaZakat['saldo']); 

    } else {

        $row_dana_zakat = 0;

    }

    if ($cekDanaInfak> 0) {
        
        $row_dana_infak = intval($rowDanaInfak['saldo']) - intval($rowPengurangDanaInfak['saldo']);

    } else {

        $row_dana_infak = 0;

    }

    if ($cekDanaAmil> 0) {
        
        $row_dana_amil = $rowDanaAmil['saldo'] - intval($rowAkmPenyusutan['saldo']);

    } else {

        $row_dana_amil = 0;

    }

    if ($cekDanaCSR> 0) {
        
        $row_dana_csr = $rowDanaCSR['saldo'];

    } else {

        $row_dana_csr = 0;

    }

    $jml_al = intval($row_ksk)+intval($row_piutang)+intval($row_persediaan)+intval($row_uangmuka)+intval($row_bdd)+intval($row_penempatan);

    $nb_at  = intval($row_aset_tetap)-intval($row_akm_penyusutan);

    $nb_ak  = intval($row_aset_kelolaan)-intval($row_akmp_ak);

    $jml_asset = intval($jml_al)+intval($nb_at)+intval($nb_ak);

    $jml_liabilitas = intval($row_lbj_pendek)+intval($row_lbj_panjang);

    $jml_saldo_dana = intval($row_dana_zakat)+intval($row_dana_infak)+intval($row_dana_amil)+intval($row_dana_csr);

    $jml_lbl_sd = intval($jml_liabilitas)+intval($jml_saldo_dana);

    $data   = "

                <h4><b>LAPORAN POSISI KEUANGAN - PSAK-109</b></h4>

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
            <div class='col-md-4'>
                <table class='table table-condensed'>
                    <tr>
                        <td colspan='5' align='left'><b>ASSET</b></td>
                    </tr>
                    <tr>
                        <td colspan='5' align='left'><b>Aset Lancar</b></td>
                    </tr>
                    <tr>
                        <td colspan='3' width='68%'>Kas dan Setara Kas</td>
                        <td width='2%'>:</td>
                        <td width='30%' align='right'>".number_format($row_ksk, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Piutang</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_piutang, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Persediaan</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_persediaan, 0, ',', '.')."</td>
                    </tr>
                        <tr>
                        <td colspan='3'>Uang Muka</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_uangmuka, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Biaya Dibayar Dimuka</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_bdd, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Penempatan</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_penempatan, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Jumlah Aset Lancar</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($jml_al, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5' align='left'><b>Aset Tetap</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'>Aset Tetap</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_aset_tetap, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Akumulasi Penyusutan</td>
                        <td>:</td>
                        <td align='right'>( ".number_format($row_akm_penyusutan, 0, ',', '.')." )</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Nilai Buku</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($nb_at, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5' align='left'><b>Aset Kelolaan</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'>Aset Kelolaan</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_aset_kelolaan, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Akumulasi Penyusutan Aset Kelolaan</td>
                        <td>:</td>
                        <td align='right'>( ".number_format($row_akmp_ak, 0, ',', '.')." )</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Nilai Buku</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($nb_ak, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>JUMLAH ASET</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($jml_asset, 0, ',', '.')."</b></td>
                    </tr>
                </table>
            </div>
            <div class='col-md-4'>
                <table class='table table-condensed'>
                    <tr>
                        <td colspan='5' align='left'><b>LIABILITAS & SALDO DANA</b></td>
                    </tr>
                    <tr>
                        <td colspan='5' align='left'><b>Liabilitas</b></td>
                    </tr>
                    <tr>
                        <td colspan='3' width='68%'>Liabilitas Jangka Pendek</td>
                        <td width='2%'>:</td>
                        <td width='30%' align='right'>".number_format($row_lbj_pendek, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3' width='68%'>Liabilitas Jangka Panjang</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_lbj_panjang, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Jumlah Liabilitas</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($jml_liabilitas, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5' align='left'><b>SALDO DANA</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'>Saldo Dana Zakat</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_dana_zakat, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Saldo Dana Infak</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_dana_infak, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Saldo Dana Amil</td>
                        <td>:</td>
                        <td width='50%' align='right'>".number_format($row_dana_amil, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'>Saldo Dana CSR</td>
                        <td>:</td>
                        <td align='right'>".number_format($row_dana_csr, 0, ',', '.')."</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Jumlah Saldo Dana</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($jml_saldo_dana, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>JUMLAH LIABILITAS + SALDO</b></td>
                        <td>:</td>
                        <td width='50%' align='right'><b>".number_format($jml_lbl_sd, 0, ',', '.')."</b></td>
                    </tr>
                </table>
            </div>
        </div>
    ";

    echo $data;

}