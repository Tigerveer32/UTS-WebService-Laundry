<?php
require_once('../lib/nusoap.php');

$error  = '';
$response = '';
$wsdl = "http://localhost/uts_ws/service.php?wsdl"; // Sesuaikan URL WSDL dengan lokasi file service.php di server Anda

// Function to check if item exists
function isItemExists($item, $wsdl)
{
    $client = new nusoap_client($wsdl, true);
    $err = $client->getError();
    if ($err) {
        echo '<h2>Constructor error</h2>' . $err;
        // At this point, you know the call that follows will fail
        exit();
    }
    try {
        // Call method to check if item exists
        $result = $client->call('getLoundrybyItems', array($item));
        $result = json_decode($result);
        if ($result && isset($result->item)) {
            return true; // Item exists
        } else {
            return false; // Item does not exist
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        return false; // Error occurred, treat as item does not exist
    }
}

if (isset($_POST['addbtn'])) {
    $item = trim($_POST['item']);
    $price = trim($_POST['price']);

    // Perform all required validations here
    if (!$item || !$price) {
        $error = 'All fields are required.';
    } elseif (isItemExists($item, $wsdl)) {
        $error = 'Item already exists.';
    }

    if (!$error) {
        // Create client object
        $client = new nusoap_client($wsdl, true);
        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2>' . $err;
            // At this point, you know the call that follows will fail
            exit();
        }
        try {
            // Call insert laundry item method
            $response =  $client->call('insertLoundrys', array($item, $price));
            $response = json_decode($response);
            if ($response->status == 200) {
                // Redirect back to client.php after successful addition
                header("Location: ../client.php");
                exit();
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laundry System Web Service Client - Add New Laundry Item</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body style=" background-color: cyan;">

    <div class="container">
        <h2 style="background-color: green; color: white;">Add New Laundry Item</h2>
        <br />
        <?php if ($error) { ?>
            <div class="alert alert-danger fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Error!</strong>&nbsp;<?php echo $error; ?>
            </div>
        <?php } ?>
        <?php if (isset($response->status)) {

            if ($response->status == 200) { ?>
                <div class="alert alert-success fade in">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong>Success!</strong>&nbsp; Laundry Item Added successfully.
                </div>
            <?php } elseif (isset($response) && $response->status != 200) { ?>
                <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong>Error!</strong>&nbsp; Cannot Add a laundry item. Please try again.
                </div>
        <?php }
        }
        ?>
        <form class="form-inline" method="post" name="form1">
            <?php if ($error) { ?>
                <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong>Error!</strong>&nbsp;<?php echo $error; ?>
                </div>
            <?php } ?>
            <div class="form-group">
                <label for="email">Item:</label>
                <input type="text" class="form-control" name="item" id="item" placeholder="Enter Item" required>
            </div>
            <div class="form-group">
                <label for="email">Price:</label>
                <input type="text" class="form-control" name="price" id="price" placeholder="Enter Price" required>
            </div>
            <button type="submit" name="addbtn" class="btn btn-default">Add Laundry Item</button>
        </form>
    </div>

</body>

</html>