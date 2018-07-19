<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah        = $_SESSION['kode_daerah'];

    $kode_petugas       = $_SESSION['kode_petugas'];

    $dari_pj_lbg            = $_POST['dari_pj_lbg'];

    $sampai_pj_lbg          = $_POST['sampai_pj_lbg'];

    $status_pengajuan_lbg   = $_POST['status_pengajuan_lbg'];

    if($status_pengajuan_lbg=='SEMUA STATUS'){

        $sql	= "SELECT 

            no_disposisi,

            no_pengajuan,
            
            nama_lembaga,
            
            nama_master,
            
            tgl_pengajuan,
            
            jml_pengajuan,

            jml_realisasi,

            status

        FROM view_224b
        
        WHERE tgl_pengajuan BETWEEN '$dari_pj_lbg' AND '$sampai_pj_lbg' 
        
        AND kode_daerah='$kode_daerah' AND nama_lembaga !='' AND status!='REJECT'
        
        ORDER BY status";

    } else {

         $sql	= "SELECT 
        
            no_disposisi,

            no_pengajuan,
            
            nama_lembaga,
            
            nama_master,
            
            tgl_pengajuan,
            
            jml_pengajuan,

            jml_realisasi,

            status

        FROM view_224b
        
        WHERE tgl_pengajuan BETWEEN '$dari_pj_lbg' AND '$sampai_pj_lbg' 
        
        AND kode_daerah='$kode_daerah' AND status='$status_pengajuan_lbg' 

        AND nama_lembaga !=''
        
        ORDER BY status";

    }

    $hasil = $konek->query($sql);


    $data   = "

                <h4><b>Laporan Pengajuan Lembaga</b></h4>

                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                    
                        <td><b>No Disposisi</b></td>
                        
                        <td><b>No Pengajuan</b></td>

                        <td><b>Lembaga</b></td>

                        <td><b>Pengajuan</b></td>

                        <td><b>Tgl Pengajuan</b></td>

                        <td  align='right'><b>Pengajuan</td>

                        <td  align='right'><b>Realisasi</td>

                        <td><b>Status</b></td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $data .="
            <tr>
                
                <td>". $row['no_disposisi'] ."</td>
                
                <td>". $row['no_pengajuan'] ."</td>

                <td>". $row['nama_lembaga'] ."</td>

                <td>". $row['nama_master'] ."</td>

                <td>". $row['tgl_pengajuan'] ."</td>

                <td  align='right' id='jml_pengajuan'>". number_format($row['jml_pengajuan'], 0, ',', '.') ."</td>

                <td  align='right' id='jml_realisasi'>". number_format($row['jml_realisasi'], 0, ',', '.') ."</td>

                <td>". $row['status'] ."</td>

            </tr>
        ";

    }

    $data .= "</table>";

    echo $data;

}