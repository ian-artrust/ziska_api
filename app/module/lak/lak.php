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

    /** SQL Aktivitas Penerimaan Operasi */
    $sqlPenerimaanZakat = "SELECT

                    SUM(kredit) AS jml_pnr_zakat
                
                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Zakat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Zakat Via Bank' AND status !='REJECT'";
    
    $sqlPenerimaanInfak = "SELECT

                    SUM(kredit) AS jml_pnr_infak
                
                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat Via Bank' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat Via Bank' AND status !='REJECT'";
    
    $sqlPenerimaanCSR = "SELECT

                    SUM(kredit) AS jml_pnr_csr

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan CSR' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan CSR Via Bank' AND status !='REJECT'";
    
    $sqlPenerimaanAmil = "SELECT

                    SUM(kredit) AS jml_pnr_amil

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Amil' AND status !='REJECT'";
    
    $hasilPnrZakat = $konek->query($sqlPenerimaanZakat);

    $row_pnr_zakat =  $hasilPnrZakat->fetch_assoc();

    $hasilPnrInfak = $konek->query($sqlPenerimaanInfak);

    $row_pnr_infak =  $hasilPnrInfak->fetch_assoc();

    $hasilPnrCSR = $konek->query($sqlPenerimaanCSR);

    $row_pnr_csr =  $hasilPnrCSR->fetch_assoc();

    $hasilPnrAmil = $konek->query($sqlPenerimaanAmil);

    $row_pnr_amil =  $hasilPnrAmil->fetch_assoc();

    $jml_penerimaan = intval($row_pnr_zakat['jml_pnr_zakat']) + intval($row_pnr_infak['jml_pnr_infak']) + intval($row_pnr_csr['jml_pnr_csr']) + intval($row_pnr_amil['jml_pnr_amil']); 

    /** SQL Aktivitas Penyaluran Operasi */
    $sqlPenyaluranZakat = "SELECT

                            SUM(debit) AS jml_pyl_zakat

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Zakat' AND status !='REJECT'

                        GROUP BY kategori";
    
    $hasilPylZakat = $konek->query($sqlPenyaluranZakat);

    $row_pyl_zakat =  $hasilPylZakat->fetch_assoc();

    $sqlPenyaluranIT = "SELECT

                            SUM(debit) AS jml_pyl_it

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Terikat' AND status !='REJECT'

                        GROUP BY kategori";

    $hasilPylIT = $konek->query($sqlPenyaluranIT);

    $row_pyl_it =  $hasilPylIT->fetch_assoc();

    $sqlPenyaluranITT = "SELECT

                            SUM(debit) AS jml_pyl_itt

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Tidak Terikat' AND status !='REJECT'

                        GROUP BY kategori";

    $hasilPylITT = $konek->query($sqlPenyaluranITT);

    $row_pyl_itt =  $hasilPylITT->fetch_assoc();

    $sqlPenyaluranCSR = "SELECT

                            SUM(debit) AS jml_pyl_csr

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran CSR' AND status !='REJECT'

                        GROUP BY kategori";

    $hasilPylCSR = $konek->query($sqlPenyaluranCSR);

    $row_pyl_csr =  $hasilPylCSR->fetch_assoc();

    $sqlPenyaluranAmil = "SELECT

                            SUM(debit) AS jml_pyl_amil

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Beban Amil' AND status !='REJECT'

                        GROUP BY kategori";

    $hasilPylAmil = $konek->query($sqlPenyaluranAmil);

    $row_pyl_amil =  $hasilPylAmil->fetch_assoc();

    $jml_pengeluaran_operasi = intval($row_pyl_zakat['jml_pyl_zakat'])+intval($row_pyl_it['jml_pyl_it'])+intval($row_pyl_itt['jml_pyl_itt'])+intval($row_pyl_csr['jml_pyl_csr'])+intval($row_pyl_amil['jml_pyl_amil']);
    
    $surplus_defisit = intval($jml_penerimaan)-intval($jml_pengeluaran_operasi);

    /** SQL Aktivitas Penerimaan Investasi */
    $sqlPenerimaanAT = "SELECT

                            SUM(kredit) AS jml_pnr_at

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND laporan='LAK' AND kategori='Penerimaan Amil' AND status !='REJECT'

                        GROUP BY kategori";

    $sqlPenerimaanAKL = "SELECT

                            SUM(kredit) AS jml_pnr_akl

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND laporan='LAK' AND kategori='Penerimaan Zakat' AND status !='REJECT' 
                        
                        OR kode_daerah='$kode_daerah' AND periode='$periode' AND laporan='LAK' AND kategori='Penerimaan Infak Tidak Terikat' AND status !='REJECT'

                        GROUP BY kategori";
    
    $hasilPnrAT     = $konek->query($sqlPenerimaanAT);

    $row_pnr_at     =  $hasilPnrAT->fetch_assoc();

    $hasilPnrKL     = $konek->query($sqlPenerimaanAKL);

    $row_pnr_akl    =  $hasilPnrKL->fetch_assoc();

    $jml_pnr_investasi = intval($row_pnr_at['jml_pnr_at'])+intval($row_pnr_akl['jml_pnr_akl']);

    /** SQL Aktivitas Pengeluaran Investasi */

    $sqlPembelianAT = "SELECT

                            SUM(nilai_asset) AS jml_pbl_at

                        FROM view_326 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND sumber_dana='Dana Amil' AND status !='REJECT'

                        GROUP BY sumber_dana";

    $sqlPembelianAKL = "SELECT

                            SUM(nilai_asset) AS jml_pbl_akl

                        FROM view_326 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND sumber_dana='Dana Zakat' AND status !='REJECT'

                        OR kode_daerah='$kode_daerah' AND periode='$periode' AND sumber_dana='Dana Infak Sedekah' AND status !='REJECT'

                        GROUP BY sumber_dana";

    $hasilPblAT     = $konek->query($sqlPembelianAT);

    $row_pbl_at     = $hasilPblAT->fetch_assoc();

    $hasilPblAKL     = $konek->query($sqlPembelianAKL);

    $row_pbl_akl     = $hasilPblAKL->fetch_assoc();

    $jml_pyl_investasi = intval($row_pbl_at['jml_pbl_at'])+intval($row_pbl_akl['jml_pbl_akl']);

    $sd_investasi = intval($jml_pnr_investasi) - intval($jml_pyl_investasi);

    /** SQL Aktivitas Penerimaan Pendanaan */

    $sqlPenerimaanHJPJG = "SELECT

                            SUM(kredit) AS jml_pnr_hjpjg

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Liabilitas Jangka Panjang' AND status !='REJECT'

                        GROUP BY kategori";

    $hasilPnrHJPJG  = $konek->query($sqlPenerimaanHJPJG);

    $row_pnr_hjpjg  =  $hasilPnrHJPJG->fetch_assoc();

    /** SQL Aktivitas Penyaluran Pendanaan */

    $sqlPenyaluranHJPJG = "SELECT

                            SUM(debit) AS jml_pyl_hjpjg

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Liabilitas Jangka Panjang' AND status !='REJECT'

                        GROUP BY kategori";

    $hasilPylHJPJG  = $konek->query($sqlPenyaluranHJPJG);

    $row_pyl_hjpjg  = $hasilPylHJPJG->fetch_assoc();

    $jml_pnr_pendanaan = intval($row_pnr_hjpjg['jml_pnr_hjpjg']);

    $jml_pyl_pendanaan = intval($row_pyl_hjpjg['jml_pyl_hjpjg']);

    $sd_pendanaan   = intval($jml_pnr_pendanaan) - intval($jml_pyl_pendanaan);

    /** Rekap Kenaikan (Penurunan) Kas */

    $kpk = intval($surplus_defisit) + intval($sd_investasi)+intval($sd_pendanaan); 

    $sqlSaldoAwalKas = "SELECT 

                            SUM(jml_setup) AS saldo_awal 

                        FROM view_321c

                        WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                        AND jenis='Kas dan Setara Kas' AND status !='REJECT'

                        GROUP BY jenis";

    $hasilSaldoAwal  = $konek->query($sqlSaldoAwalKas);

    $row_saldo_awal  = $hasilSaldoAwal->fetch_assoc();

    $saldo_awal_kas  = intval($row_saldo_awal['saldo_awal']);

    $saldo_akhir_kas = intval($saldo_awal_kas) + intval($kpk);

    $data   = "

                <h4><b>LAPORAN ARUS KAS - PSAK-109</b></h4>

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
            
                <hr style='border:1px solid;'>";
                $data .= "
                <div class='row'>
                    <div class='col-md-6'>
                        <!-- AKTIVITAS OPERASI -->
                        <table class='table table-condensed'>
                            <tr>
                                <td colspan='5'><b>AKTIVITAS OPERASI</b></td>
                            </tr>  
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='4'><b>Penerimaan</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penerimaan Zakat</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_zakat['jml_pnr_zakat'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penerimaan Infak</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_infak['jml_pnr_infak'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penerimaan CSR</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_csr['jml_pnr_csr'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penerimaan Amil</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_amil['jml_pnr_amil'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Jumlah Penerimaan</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($jml_penerimaan, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='4'><b>Pengeluaran</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penyaluran Zakat</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pyl_zakat['jml_pyl_zakat'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penyaluran Infak Terikat</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pyl_it['jml_pyl_it'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penyaluran Infak Tidak Terikat</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pyl_itt['jml_pyl_itt'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penyaluran CSR</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pyl_csr['jml_pyl_csr'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penyaluran Amil</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pyl_amil['jml_pyl_amil'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Jumlah Pengeluaran</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($jml_pengeluaran_operasi, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Surplus (Defisit) Aktivitas Operasi</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($surplus_defisit, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td colspan='5'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan='5'><b>AKTIVITAS INVESTASI</b></td>
                            </tr>  
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='4'><b>Penerimaan</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penjualan Aset Tetap</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_at['jml_pnr_at'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penjualan Aset Kelolaan</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_akl['jml_pnr_akl'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Jumlah Penerimaan</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($jml_pnr_investasi, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='4'><b>Pengeluaran</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Pembelian Aset Tetap</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pbl_at['jml_pbl_at'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Pembelian Aset Kelolaan</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pbl_akl['jml_pbl_akl'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Jumlah Pengeluaran</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($jml_pyl_investasi, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Surplus (Defisit) Aktivitas Investasi</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($sd_investasi, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td colspan='5'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan='5'><b>AKTIVITAS PENDANAAN</b></td>
                            </tr>  
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='4'><b>Penerimaan</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Penerimaan Hutang</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pnr_hjpjg['jml_pnr_hjpjg'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Jumlah Penerimaan</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($jml_pnr_pendanaan, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='4'><b>Penyaluran</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td width='55%'>Pengembalian Hutang</td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'>".number_format($row_pyl_hjpjg['jml_pyl_hjpjg'], 0, ',', '.')."</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Jumlah Penyaluran</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($jml_pyl_pendanaan, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan='2'><b>Surplus (Defisit) Aktivitas Pendanaan</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($sd_pendanaan, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td colspan='5'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan='3'><b>KENAIKAN ( PENURUNAN ) KAS</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($kpk, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td colspan='3'><b>SALDO AWAL KAS</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($saldo_awal_kas, 0, ',', '.')."</b></td>
                            </tr>
                            <tr>
                                <td colspan='3'><b>SALDO AKHIR KAS</b></td>
                                <td width='2%'>:</td>
                                <td align='right' width='48%'><b>".number_format($saldo_akhir_kas, 0, ',', '.')."</b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            ";
    
    echo $data;

}