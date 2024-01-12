<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collection = $db->visits;

    $data = [
        'patient_id' => new MongoDB\BSON\ObjectID($_POST['patient_id']),
        'height' => $_POST['height'],
        'weight' => $_POST['weight'],
        'bmi' => $_POST['bmi'],
        'visit_date' => new MongoDB\BSON\UTCDateTime()
    ];

    $result = $collection->insertOne($data);

    if ($result->getInsertedCount() > 0) {
        echo "Visit information saved successfully!";
    } else {
        echo "Error: Unable to save visit information.";
    }
}
?>
