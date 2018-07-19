<?php  
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else { 

    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $npwz = $_POST['npwz'];

    $sql	= "SELECT 
    
                tgl_donasi, 
                
                jml_donasi

                FROM view_221

                WHERE npwz='$npwz' AND kode_kategori!='101' AND status !='REJECT'

                ORDER BY tgl_donasi ASC";
    
    $hasil	= $konek->query($sql);

    while($row = $hasil->fetch_assoc()){

        $data ="
                <tr>

                    <td>". $row['tgl_donasi'] ."</td>

                    <td id='jumlah' align='right'>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>

                </tr>
            ";

        echo $data;
    }

}