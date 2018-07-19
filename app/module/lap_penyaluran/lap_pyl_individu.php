<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $periode_pyi    = $_POST['periode_pyi'];

    /** PETUGAS */
    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    /** TOTAL SOSIAL */
    $sqlTotalSosialIndividu = "SELECT 
    
                            SUM(jml_realisasi) AS jml_realisasi

                        FROM view_224

                        WHERE jenis='Sosial dan Dakwah' AND nama_mustahik!='' 
                       
                        AND status ='COMPLETE' AND periode='$periode_pyi' 
                        
                        AND kode_daerah='$kode_daerah'";

    $hasilTotalSosialInd = $konek->query($sqlTotalSosialIndividu);
    $barisTotalSosialInd = $hasilTotalSosialInd->fetch_assoc();

    $sqlTotalSosialLbg = "SELECT 
    
                            SUM(jml_realisasi) AS jml_realisasi

                        FROM view_224b

                        WHERE jenis='Sosial dan Dakwah' AND nama_lembaga!='' 

                        AND status ='COMPLETE' AND periode='$periode_pyi' 

                        AND kode_daerah='$kode_daerah'";

    $hasilTotalSosialLbg = $konek->query($sqlTotalSosialLbg);
    $barisTotalSosialILbg = $hasilTotalSosialLbg->fetch_assoc();

    /** TOTAL EKONOMI */
    $sqlTotalEkonomiIndividu = "SELECT 
    
                            SUM(jml_realisasi) AS jml_realisasi

                        FROM view_224

                        WHERE jenis='Ekonomi' AND nama_mustahik!='' 
                       
                        AND status ='COMPLETE' AND periode='$periode_pyi' 
                        
                        AND kode_daerah='$kode_daerah'";

    $hasilTotalEkonomiInd = $konek->query($sqlTotalEkonomiIndividu);
    $barisTotalEkonomiInd = $hasilTotalEkonomiInd->fetch_assoc();

    $sqlTotalEkonomiLbg = "SELECT 
    
                            SUM(jml_realisasi) AS jml_realisasi

                        FROM view_224b

                        WHERE jenis='Ekonomi' AND nama_lembaga!='' 

                        AND status ='COMPLETE' AND periode='$periode_pyi' 

                        AND kode_daerah='$kode_daerah'";

    $hasilTotalEkonomiLbg = $konek->query($sqlTotalEkonomiLbg);
    $barisTotalEkonomiLbg = $hasilTotalEkonomiLbg->fetch_assoc();

    /** TOTAL PENDIDIKAN */
    $sqlTotalPendidikanIndividu = "SELECT 

                                    SUM(jml_realisasi) AS jml_realisasi

                                FROM view_224

                                WHERE jenis='Pendidikan' AND nama_mustahik!='' 

                                AND status ='COMPLETE' AND periode='$periode_pyi' 

                                AND kode_daerah='$kode_daerah'";

    $hasilTotalPendidikanInd = $konek->query($sqlTotalPendidikanIndividu);
    $barisTotalPendidikanInd = $hasilTotalPendidikanInd->fetch_assoc();

    $sqlTotalPendidikanLbg = "SELECT 

            SUM(jml_realisasi) AS jml_realisasi

        FROM view_224b

        WHERE jenis='Pendidikan' AND nama_lembaga!='' 

        AND status ='COMPLETE' AND periode='$periode_pyi' 

        AND kode_daerah='$kode_daerah'";

    $hasilTotalPendidikanLbg = $konek->query($sqlTotalPendidikanLbg);
    $barisTotalPendidikanLbg = $hasilTotalPendidikanLbg->fetch_assoc();

    /** TOTAL KESEHATAN */
    $sqlTotalKesehatanIndividu = "SELECT 

                                        SUM(jml_realisasi) AS jml_realisasi

                                    FROM view_224

                                    WHERE jenis='Kesehatan' AND nama_mustahik!='' 

                                    AND status ='COMPLETE' AND periode='$periode_pyi' 

                                    AND kode_daerah='$kode_daerah'";

    $hasilTotalKesehatanInd = $konek->query($sqlTotalKesehatanIndividu);
    $barisTotalKesehatanInd = $hasilTotalKesehatanInd->fetch_assoc();

    $sqlTotalKesehatanLbg = "SELECT 

                                SUM(jml_realisasi) AS jml_realisasi

                            FROM view_224b

                            WHERE jenis='Kesehatan' AND nama_lembaga!='' 

                            AND status ='COMPLETE' AND periode='$periode_pyi' 

                            AND kode_daerah='$kode_daerah'";

    $hasilTotalKesehatanLbg = $konek->query($sqlTotalKesehatanLbg);
    $barisTotalKesehatanLbg = $hasilTotalKesehatanLbg->fetch_assoc();

    $data   = "

                <h4><b>LAPORAN PENYALURAN</b></h4>

                <h5><b>PERIODE: ".$periode_pyi."</b></h5>

                <hr style='border:1px solid;'>

            ";

    /** SOSIAL */
    
    $sqlSosialInd = "SELECT
    
                        nama_mustahik,

                        tgl_pengajuan,

                        nama_master,

                        keterangan,

                        jml_realisasi

                    FROM view_224 

                    WHERE jenis='Sosial dan Dakwah' AND nama_mustahik!='' 
                       
                    AND status ='COMPLETE' AND periode='$periode_pyi' 
                    
                    AND kode_daerah='$kode_daerah'";

    $hasil = $konek->query($sqlSosialInd);

    $sqlSosialLbg = "SELECT
    
                        nama_lembaga,

                        tgl_pengajuan,

                        nama_master,

                        keterangan,

                        jml_realisasi

                    FROM view_224b 

                    WHERE jenis='Sosial dan Dakwah' AND nama_lembaga!='' 
                    
                    AND status ='COMPLETE' AND periode='$periode_pyi' 

                    AND kode_daerah='$kode_daerah'";

    $hasilLbg = $konek->query($sqlSosialLbg);

    /** Sosial Individu */
    $data .= "
            <h4><b>SOSIAL DAN DAKWAH</b></h4>

            
            <table class='table'>

                <tr>

                    <td colspan='5'><b>INDIVIDU</b></td>

                </tr>

                <tr>
                    
                        <td><b>Nama Mustahik</b></td>

                        <td><b>Tgl Pengajuan</b></td>

                        <td><b>Pengajuan</b></td>

                        <td><b>Keterangan</b></td>

                        <td align='right'><b>Jml Realisasi</b></td>

                </tr>";

        while($row = $hasil->fetch_assoc()){
    
            $data .="
                
                <tr>
                    
                    <td>". $row['nama_mustahik'] ."</td>
    
                    <td>". $row['tgl_pengajuan'] ."</td>

                    <td>". $row['nama_master'] ."</td>

                    <td>". $row['keterangan'] ."</td>
    
                    <td align='right'>". number_format($row['jml_realisasi'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }
    
    $data .= "
                <tr>

                    <td colspan='4' align='right'><b>JUMLAH: </b></td>

                    <td align='right'><b>". number_format($barisTotalSosialInd['jml_realisasi'], 0, ',', '.')."</b></td>

                </tr>";

    /** Sosial Lembaga */
    $data .= "
            <table class='table'>

                <tr>

                    <td colspan='5'><b>LEMBAGA</b></td>

                </tr>

                <tr>
                    
                        <td><b>Nama Lmebaga</b></td>

                        <td><b>Tgl Pengajuan</b></td>

                        <td><b>Pengajuan</b></td>

                        <td><b>Keterangan</b></td>

                        <td align='right'><b>Jml Realisasi</b></td>

                </tr>";

        while($row_lbg = $hasilLbg->fetch_assoc()){
    
            $data .="
                
                <tr>
                    
                    <td>". $row_lbg['nama_lembaga'] ."</td>
    
                    <td>". $row_lbg['tgl_pengajuan'] ."</td>

                    <td>". $row_lbg['nama_master'] ."</td>

                    <td>". $row_lbg['keterangan'] ."</td>
    
                    <td align='right'>". number_format($row_lbg['jml_realisasi'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }
    
    $data .= "
                <tr>

                    <td colspan='4' align='right'><b>JUMLAH: </b></td>

                    <td align='right'><b>". number_format($barisTotalSosialILbg['jml_realisasi'], 0, ',', '.')."</b></td>

                </tr></table>";    
    

    /** EKONOMI */ 
    $sqlEkonomiInd = "SELECT
        
            nama_mustahik,

            tgl_pengajuan,

            nama_master,

            keterangan,

            jml_realisasi

        FROM view_224 

        WHERE jenis='Ekonomi' AND nama_mustahik!='' 
        
        AND status ='COMPLETE' AND periode='$periode_pyi' 
        
        AND kode_daerah='$kode_daerah'";

    $hasilEknInd = $konek->query($sqlEkonomiInd);

    $sqlEkonomiLbg = "SELECT

            nama_lembaga,

            tgl_pengajuan,

            nama_master,

            keterangan,

            jml_realisasi

        FROM view_224b 

        WHERE jenis='Ekonomi' AND nama_lembaga!='' 
        
        AND status ='COMPLETE' AND periode='$periode_pyi' 

        AND kode_daerah='$kode_daerah'";

    $hasilEknLbg = $konek->query($sqlEkonomiLbg);

    /** Ekonomi Individu */
    $data .= "
            <h4><b>EKONOMI</b></h4>

            
            <table class='table'>

                <tr>

                    <td colspan='5'><b>INDIVIDU</b></td>

                </tr>

                <tr>
                    
                        <td><b>Nama Mustahik</b></td>

                        <td><b>Tgl Pengajuan</b></td>

                        <td><b>Pengajuan</b></td>

                        <td><b>Keterangan</b></td>

                        <td align='right'><b>Jml Realisasi</b></td>

                </tr>";

        while($row_ekn_ind = $hasilEknInd->fetch_assoc()){
    
            $data .="
                
                <tr>
                    
                    <td>". $row_ekn_ind['nama_mustahik'] ."</td>
    
                    <td>". $row_ekn_ind['tgl_pengajuan'] ."</td>

                    <td>". $row_ekn_ind['nama_master'] ."</td>

                    <td>". $row_ekn_ind['keterangan'] ."</td>
    
                    <td align='right'>". number_format($row_ekn_ind['jml_realisasi'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }
    
    $data .= "
                <tr>

                    <td colspan='4' align='right'><b>JUMLAH: </b></td>

                    <td align='right'><b>". number_format($barisTotalEkonomiInd['jml_realisasi'], 0, ',', '.')."</b></td>

                </tr>";

    /** Ekonomi Lembaga */
    $data .= "
            <table class='table'>

                <tr>

                    <td colspan='5'><b>LEMBAGA</b></td>

                </tr>

                <tr>
                    
                        <td><b>Nama Lmebaga</b></td>

                        <td><b>Tgl Pengajuan</b></td>

                        <td><b>Pengajuan</b></td>

                        <td><b>Keterangan</b></td>

                        <td align='right'><b>Jml Realisasi</b></td>

                </tr>";

        while($row_ekn_lbg = $hasilEknLbg->fetch_assoc()){
    
            $data .="
                
                <tr>
                    
                    <td>". $row_ekn_lbg['nama_lembaga'] ."</td>
    
                    <td>". $row_ekn_lbg['tgl_pengajuan'] ."</td>

                    <td>". $row_ekn_lbg['nama_master'] ."</td>

                    <td>". $row_ekn_lbg['keterangan'] ."</td>
    
                    <td align='right'>". number_format($row_ekn_lbg['jml_realisasi'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }
    
    $data .= "
                <tr>

                    <td colspan='4' align='right'><b>JUMLAH: </b></td>

                    <td align='right'><b>". number_format($barisTotalEkonomiLbg['jml_realisasi'], 0, ',', '.')."</b></td>

                </tr>

            </table>";

    /** PENDIDIKAN */ 
    $sqlPendidikanInd = "SELECT
        
                            nama_mustahik,

                            tgl_pengajuan,

                            nama_master,

                            keterangan,

                            jml_realisasi

                        FROM view_224 

                        WHERE jenis='Pendidikan' AND nama_mustahik!='' 

                        AND status ='COMPLETE' AND periode='$periode_pyi' 

                        AND kode_daerah='$kode_daerah'";

    $hasilPndInd = $konek->query($sqlPendidikanInd);

    $sqlPendidikanLbg = "SELECT

                        nama_lembaga,

                        tgl_pengajuan,

                        nama_master,

                        keterangan,

                        jml_realisasi

                    FROM view_224b 

                    WHERE jenis='Pendidikan' AND nama_lembaga!='' 

                    AND status ='COMPLETE' AND periode='$periode_pyi' 

                    AND kode_daerah='$kode_daerah'";

    $hasilPndLbg = $konek->query($sqlPendidikanLbg);

    /** Pendidikan Individu */
    $data .= "
        <h4><b>PENDIDIKAN</b></h4>

        
        <table class='table'>

            <tr>

                <td colspan='5'><b>INDIVIDU</b></td>

            </tr>

            <tr>
                
                    <td><b>Nama Mustahik</b></td>

                    <td><b>Tgl Pengajuan</b></td>

                    <td><b>Pengajuan</b></td>

                    <td><b>Keterangan</b></td>

                    <td align='right'><b>Jml Realisasi</b></td>

            </tr>";

        while($row_pnd_ind = $hasilPndInd->fetch_assoc()){

            $data .="
                
                <tr>
                    
                    <td>". $row_pnd_ind['nama_mustahik'] ."</td>

                    <td>". $row_pnd_ind['tgl_pengajuan'] ."</td>

                    <td>". $row_pnd_ind['nama_master'] ."</td>

                    <td>". $row_pnd_ind['keterangan'] ."</td>

                    <td align='right'>". number_format($row_pnd_ind['jml_realisasi'], 0, ',', '.') ."</td>

                </tr>";

        }

    $data .= "
        <tr>

            <td colspan='4' align='right'><b>JUMLAH: </b></td>

            <td align='right'><b>". number_format($barisTotalPendidikanInd['jml_realisasi'], 0, ',', '.')."</b></td>

        </tr>";

    /** Pendidikan Lembaga */
    $data .= "
        <table class='table'>

            <tr>

                <td colspan='5'><b>LEMBAGA</b></td>

            </tr>

            <tr>
                
                    <td><b>Nama Lmebaga</b></td>

                    <td><b>Tgl Pengajuan</b></td>

                    <td><b>Pengajuan</b></td>

                    <td><b>Keterangan</b></td>

                    <td align='right'><b>Jml Realisasi</b></td>

            </tr>";

        while($row_pnd_lbg = $hasilPndLbg->fetch_assoc()){

            $data .="
                
                <tr>
                    
                    <td>". $row_pnd_lbg['nama_lembaga'] ."</td>

                    <td>". $row_pnd_lbg['tgl_pengajuan'] ."</td>

                    <td>". $row_pnd_lbg['nama_master'] ."</td>

                    <td>". $row_pnd_lbg['keterangan'] ."</td>

                    <td align='right'>". number_format($row_pnd_lbg['jml_realisasi'], 0, ',', '.') ."</td>

                </tr>";

        }

    $data .= "
            <tr>

                <td colspan='4' align='right'><b>JUMLAH: </b></td>

                <td align='right'><b>". number_format($barisTotalPendidikanLbg['jml_realisasi'], 0, ',', '.')."</b></td>

            </tr>

        </table>";
    
    /** KESEHATAN */ 
    $sqlKesehatanInd = "SELECT
        
                            nama_mustahik,

                            tgl_pengajuan,

                            nama_master,

                            keterangan,

                            jml_realisasi

                        FROM view_224 

                        WHERE jenis='Kesehatan' AND nama_mustahik!='' 

                        AND status ='COMPLETE' AND periode='$periode_pyi' 

                        AND kode_daerah='$kode_daerah'";

    $hasilKstInd = $konek->query($sqlKesehatanInd);

    $sqlKesehatanLbg = "SELECT

                        nama_lembaga,

                        tgl_pengajuan,

                        nama_master,

                        keterangan,

                        jml_realisasi

                    FROM view_224b 

                    WHERE jenis='Kesehatan' AND nama_lembaga!='' 

                    AND status ='COMPLETE' AND periode='$periode_pyi' 

                    AND kode_daerah='$kode_daerah'";

    $hasilKstLbg = $konek->query($sqlKesehatanLbg);

    /** Kesehatan Individu */
    $data .= "
        <h4><b>KESEHATAN</b></h4>

        
        <table class='table'>

            <tr>

                <td colspan='5'><b>INDIVIDU</b></td>

            </tr>

            <tr>
                
                    <td><b>Nama Mustahik</b></td>

                    <td><b>Tgl Pengajuan</b></td>

                    <td><b>Pengajuan</b></td>

                    <td><b>Keterangan</b></td>

                    <td align='right'><b>Jml Realisasi</b></td>

            </tr>";

        while($row_kst_ind = $hasilKstInd->fetch_assoc()){

            $data .="
                
                <tr>
                    
                    <td>". $row_kst_ind['nama_mustahik'] ."</td>

                    <td>". $row_kst_ind['tgl_pengajuan'] ."</td>

                    <td>". $row_kst_ind['nama_master'] ."</td>

                    <td>". $row_kst_ind['keterangan'] ."</td>

                    <td align='right'>". number_format($row_kst_ind['jml_realisasi'], 0, ',', '.') ."</td>

                </tr>";

        }

    $data .= "
        <tr>

            <td colspan='4' align='right'><b>JUMLAH: </b></td>

            <td align='right'><b>". number_format($barisTotalKesehatanInd['jml_realisasi'], 0, ',', '.')."</b></td>

        </tr>";

    /** Kesehatan Lembaga */
    $data .= "
        <table class='table'>

            <tr>

                <td colspan='5'><b>LEMBAGA</b></td>

            </tr>

            <tr>
                
                    <td><b>Nama Lmebaga</b></td>

                    <td><b>Tgl Pengajuan</b></td>

                    <td><b>Pengajuan</b></td>

                    <td><b>Keterangan</b></td>

                    <td align='right'><b>Jml Realisasi</b></td>

            </tr>";

        while($row_kst_lbg = $hasilKstLbg->fetch_assoc()){

            $data .="
                
                <tr>
                    
                    <td>". $row_kst_lbg['nama_lembaga'] ."</td>

                    <td>". $row_kst_lbg['tgl_pengajuan'] ."</td>

                    <td>". $row_kst_lbg['nama_master'] ."</td>

                    <td>". $row_kst_lbg['keterangan'] ."</td>

                    <td align='right'>". number_format($row_kst_lbg['jml_realisasi'], 0, ',', '.') ."</td>

                </tr>";

        }

    $data .= "
            <tr>

                <td colspan='4' align='right'><b>JUMLAH: </b></td>

                <td align='right'><b>". number_format($barisTotalKesehatanLbg['jml_realisasi'], 0, ',', '.')."</b></td>

            </tr>

        </table>";

echo $data;

}