<?php   

    include "../koneksi.php";

    $npwz = $_GET['npwz'];

    $sqlFind 	= "SELECT nama_donatur FROM view_aktif_donatur WHERE npwz = '$npwz'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);