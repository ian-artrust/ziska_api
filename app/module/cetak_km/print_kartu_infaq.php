<?php  

    include "../koneksi.php";

    $npwz = $_POST['npwz'];

    $sql	= "SELECT 
    
                tgl_donasi, 
                
                jml_donasi

                FROM view_donasi

                WHERE npwz='$npwz' AND kode_kategori!='KAT001' AND status_hdr='Aktif'

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