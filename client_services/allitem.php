<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Laundry Items</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body style="background-color: teal;">

    <div class="container">
        <h2 style=" color: white;">All Laundry Items</h2>
        <br />
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="background-color: white;">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Action</th> <!-- New column for action -->
                        </tr>
                    </thead>
                    <tbody id="itemTableBody">
                        <?php
                        require_once('../lib/nusoap.php');
                        $result = array();
                        $wsdl = "http://localhost/uts_ws/service.php?wsdl"; // Sesuaikan URL WSDL dengan lokasi file service.php di server Anda

                        try {
                            // Create client object
                            $client = new nusoap_client($wsdl, true);
                            $err = $client->getError();
                            if ($err) {
                                echo '<tr><td colspan="3">Constructor error: ' . $err . '</td></tr>';
                            } else {
                                try {
                                    $result = $client->call('getAllItems');
                                    $result = json_decode($result);
                                    if (is_array($result)) {
                                        foreach ($result as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $row->item . '</td>';
                                            echo '<td>' . $row->price . '</td>';
                                            echo '<td><a href="updateitem.php?id=' . $row->id . '">Update</a> | <a href="deleteitem.php?id=' . $row->id . '">Delete</a></td>'; // Link to update page with item id
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="3">No data found</td></tr>';
                                    }
                                } catch (Exception $e) {
                                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                                }
                            }
                        } catch (Exception $e) {
                            echo '<tr><td colspan="3">Caught exception: ' . $e->getMessage() . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"> <!-- Kolom tengah -->
                        <a href="../client.php" class="btn btn-primary btn-block">Kembali</a> <!-- Tombol di tengah -->
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>