<?php

$host         = "localhost";
$username     = "root";
$password     = "";
$dbname       = "dbloundry";

try {
    $dbconn = new PDO('mysql:host=localhost;dbname=dbloundry', $username, $password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
