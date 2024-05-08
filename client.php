<?php
/*  
  ini_set('display_errors', true);
  error_reporting(E_ALL); 
 */

require_once('lib/nusoap.php');
$error  = '';
$result = array();
$response = '';
$wsdl = "http://localhost/uts_ws/service.php?wsdl"; // Sesuaikan URL WSDL dengan lokasi file service.php di server Anda

if (isset($_POST['sub'])) {
    $item = trim($_POST['item']);
    if (!$item) {
        $error = 'Item cannot be left blank.';
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
            $result = $client->call('getLoundrybyItems', array($item));
            $result = json_decode($result);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laundry System Web Service Client</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body style=" background-color: teal;">

    <div class="container">
        <h2 style="color: white;">Laundry System SOAP Web Service Client</h2>
        <p>Enter the <strong>item</strong> and click <strong>Search Laundry Item by Item</strong>.</p>
        <br />
        <div class='row'>
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
                <button type="submit" name="sub" class="btn btn-default">Search Laundry Item</button>
            </form>
        </div>
        <br />
        <h2>Laundry Item Information</h2>
        <table class="table" style="background-color:white; border: 3px solid;">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result) { ?>

                    <tr>
                        <td><?php echo $result->item; ?></td>
                        <td><?php echo $result->price; ?></td>
                    </tr>
                <?php
                } else { ?>
                    <tr>
                        <td colspan="2">Enter Item and click on Search Laundry Item button</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class='row'>
            <h2>Add New Laundry Item</h2>
            <a href="client_services/tambahdata.php" class="btn btn-default">Add Laundry Item</a>
            <a href="client_services/allitem.php" class="btn btn-default">get all Item</a>

        </div>
    </div>

</body>

</html>