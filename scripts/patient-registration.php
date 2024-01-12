<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collection = $db->patients;

    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'dob' => $_POST['dob'],
        'gender' => $_POST['gender'],
        'registration_date' => new MongoDB\BSON\UTCDateTime()
    ];

    $result = $collection->insertOne($data);

    if ($result->getInsertedCount() > 0) {
        echo "Patient registered successfully!";
        header("Location: ../visits.html");
        exit();
    } else {
        echo "Error: Unable to register patient.";
    }
}
?>
