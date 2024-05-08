<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Item</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body style="background-color: teal;">

    <div class="container">
        <h2>Delete Item</h2>
        <div class="alert alert-warning">
            Are you sure you want to delete this item?
        </div>
        <form method="post">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="allItem.php" class="btn btn-default">Cancel</a>
        </form>
    </div>

</body>

</html>

<?php
require_once('../lib/nusoap.php');

// Periksa apakah ID item telah diterima melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Item ID is missing.";
    exit();
}

$id = $_GET['id'];

// URL WSDL dari layanan web
$wsdl = "http://localhost/uts_ws/service.php?wsdl"; // Sesuaikan dengan lokasi file service.php di server Anda

// Buat klien SOAP
$client = new nusoap_client($wsdl, true);

// Panggil metode deleteItem dari layanan dengan parameter ID item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = array('id' => $id);
    $result = $client->call('deleteItem', $params);

    // Periksa apakah pemanggilan berhasil atau tidak
    if ($client->fault) {
        echo 'Error calling SOAP method: ' . $client->fault;
    } else {
        $err = $client->getError();
        if ($err) {
            echo 'Error calling SOAP method: ' . $err;
        } else {
            header("Location: allItem.php");
            // Periksa hasil pemanggilan
        }
    }
}
?>