<?php

require_once('dbconn.php');
require_once('lib/nusoap.php');

$server = new nusoap_server();

/* Method untuk menambahkan item laundry */
function insertLoundrys($item, $price)
{
    global $dbconn;
    $sql_insert = "INSERT INTO laundry (item, price) VALUES (:item, :price)";
    $stmt = $dbconn->prepare($sql_insert);
    // Insert sebuah baris
    $result = $stmt->execute(array(':item' => $item, ':price' => $price));
    if ($result) {
        return json_encode(array('status' => 200, 'msg' => 'success'));
    } else {
        return json_encode(array('status' => 400, 'msg' => 'fail'));
    }

    $dbconn = null;
}

/* Method untuk mencari item laundry berdasarkan item */
function getLoundrybyItems($item)
{
    global $dbconn;
    $sql = "SELECT id, item, price FROM laundry WHERE item = :item";
    // Persiapkan SQL dan bind parameter
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':item', $item);
    // Insert sebuah baris
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}

/* Method untuk mengambil semua item laundry */
function getAllItems()
{
    global $dbconn;
    $sql = "SELECT id, item, price FROM laundry";
    // Persiapkan SQL
    $stmt = $dbconn->prepare($sql);
    // Jalankan query
    $stmt->execute();
    // Ambil semua data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}

/* Method untuk update item laundry */
function updateItem($id, $newItem, $newPrice)
{
    global $dbconn;
    $sql_update = "UPDATE laundry SET item = :newItem, price = :newPrice WHERE id = :id";
    $stmt = $dbconn->prepare($sql_update);
    // Bind parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':newItem', $newItem);
    $stmt->bindParam(':newPrice', $newPrice);
    // Execute the update statement
    $result = $stmt->execute();
    if ($result) {
        return json_encode(array('status' => 200, 'msg' => 'success'));
    } else {
        return json_encode(array('status' => 400, 'msg' => 'fail'));
    }
    // $dbconn = null; // Hapus baris ini
}
/* Method untuk mengambil item laundry berdasarkan ID */
function getItemById($id)
{
    global $dbconn;
    $sql = "SELECT id, item, price FROM laundry WHERE id = :id";
    // Persiapkan SQL dan bind parameter
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':id', $id);
    // Jalankan query
    $stmt->execute();
    // Ambil data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
}

/* Method untuk menghapus item laundry berdasarkan ID */
function deleteItem($id)
{
    global $dbconn;
    $sql = "DELETE FROM laundry WHERE id = :id";
    // Persiapkan SQL dan bind parameter
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':id', $id);
    // Execute the delete statement
    $result = $stmt->execute();
    if ($result) {
        return json_encode(array('status' => 200, 'msg' => 'success'));
    } else {
        return json_encode(array('status' => 400, 'msg' => 'fail'));
    }
}


//register method
$server->configureWSDL('laundryServer', 'urn:laundry');
$server->register(
    'getLoundrybyItems',
    array('item' => 'xsd:string'),  // Parameter
    array('data' => 'xsd:string'),  // Output
    'urn:laundry',   // Namespace
    'urn:laundry#getLoundrybyItems' // SOAP Action
);

$server->register(
    'insertLoundrys',
    array('item' => 'xsd:string', 'price' => 'xsd:string'),  // Parameter
    array('data' => 'xsd:string'),  // Output
    'urn:laundry',   // Namespace
    'urn:laundry#insertLoundrys' // SOAP Action
);

$server->register(
    'getAllItems',
    array(),  // Parameter
    array('data' => 'xsd:string'),  // Output
    'urn:laundry',   // Namespace
    'urn:laundry#getAllItems' // SOAP Action
);

$server->register(
    'updateItem',
    array('id' => 'xsd:int', 'newItem' => 'xsd:string', 'newPrice' => 'xsd:string'),  // Parameter
    array('data' => 'xsd:string'),  // Output
    'urn:laundry',   // Namespace
    'urn:laundry#updateItem' // SOAP Action
);

$server->register(
    'getItemById',
    array('id' => 'xsd:int'),  // Parameter
    array('data' => 'xsd:string'),  // Output
    'urn:laundry',   // Namespace
    'urn:laundry#getItemById' // SOAP Action
);

$server->register(
    'deleteItem',
    array('id' => 'xsd:int'),  // Parameter
    array('data' => 'xsd:string'),  // Output
    'urn:laundry',   // Namespace
    'urn:laundry#deleteItem' // SOAP Action
);



$server->service(file_get_contents("php://input"));
