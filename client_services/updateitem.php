<?php

require_once('../lib/nusoap.php');

// URL WSDL dari service
$wsdl = "http://localhost/uts_ws/service.php?wsdl";

// Buat klien SOAP
$client = new nusoap_client($wsdl, true);

// Pesan kesalahan default
$error = '';

// Periksa apakah ada id item yang dikirimkan melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $error = 'Item ID is missing.';
} else {
    $id = $_GET['id'];

    // Jika form disubmit dengan method POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil data yang di-submit
        $item_name = $_POST['item'];
        $item_price = $_POST['price'];

        // Panggil method updateItem dari service dengan parameter yang sesuai
        $params = array('id' => $id, 'newItem' => $item_name, 'newPrice' => $item_price);
        $result = $client->call('updateItem', $params);
        header("Location: allItem.php");
    }

    // Ambil data item berdasarkan ID untuk menampilkan di form
    $params = array('id' => $id);
    $result = $client->call('getItemById', $params);

    // Periksa apakah pemanggilan berhasil atau tidak
    if ($client->fault) {
        $error = 'Error calling SOAP method: ' . $client->fault;
    } else {
        // Periksa apakah data ditemukan
        if (!empty($result)) {
            $item = $result;
        } else {
            $error = 'Item not found.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Item</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body style="background-color: teal;">

<div class="container">
    <h2>Update Item</h2>
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } else { ?>
        <form method="post">
            <div class="form-group">
                <label for="item">Item:</label>
                <input type="text" class="form-control" id="item" name="item" value="<?php echo isset($item['item']) ? $item['item'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo isset($item['price']) ? $item['price'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="allItems.php" class="btn btn-default">Cancel</a>
        </form>
    <?php } ?>
</div>

</body>
</html>
