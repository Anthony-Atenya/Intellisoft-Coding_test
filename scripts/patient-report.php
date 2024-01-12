<?php
// Include the MongoDB connection configuration
require 'scripts/config.php';

// Function to classify BMI status
function classifyBMI($bmi)
{
    if ($bmi < 18.5) {
        return 'Underweight';
    } elseif ($bmi >= 18.5 && $bmi < 25) {
        return 'Normal';
    } else {
        return 'Overweight';
    }
}

// Check if date filter is set
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Query MongoDB based on date filter
$filter = [];
if ($dateFilter !== '') {
    $filter['registration_date'] = ['$gte' => new MongoDB\BSON\UTCDateTime(strtotime($dateFilter) * 1000)];
}

// Retrieve data from MongoDB "patients" collection
$collection = $db->patients;
$patients = $collection->find($filter);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Report</title>
    <!-- Link to your CSS file if needed -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Patient Report</h1>
    
    <form action="" method="get">
        <label for="date">Filter by Date:</label>
        <input type="date" id="date" name="date" value="<?= $dateFilter ?>">
        <button type="submit">Apply Filter</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Registration Date</th>
                <th>Height</th>
                <th>Weight</th>
                <th>BMI</th>
                <th>BMI Status</th>
                <th>General Good Health</th>
                <th>Diet/Lose Weight</th>
                <th>Comments A</th>
                <th>Chronic Illness</th>
                <th>Taking Drugs</th>
                <th>Comments B</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $patient) : ?>
                <?php
                // Calculate BMI
                $height = $patient['height'];
                $weight = $patient['weight'];
                $bmi = $weight / (($height / 100) * ($height / 100));
                
                // Classify BMI status
                $bmiStatus = classifyBMI($bmi);

                // Retrieve Section A and Section B data
                $sectionA = $patient['section_a'] ?? [];
                $sectionB = $patient['section_b'] ?? [];
                ?>
                <tr>
                    <td><?= $patient['first_name'] ?></td>
                    <td><?= $patient['last_name'] ?></td>
                    <td><?= $patient['dob']->toDateTime()->format('Y-m-d') ?></td>
                    <td><?= $patient['gender'] ?></td>
                    <td><?= $patient['registration_date']->toDateTime()->format('Y-m-d H:i:s') ?></td>
                    <td><?= $patient['height'] ?></td>
                    <td><?= $patient['weight'] ?></td>
                    <td><?= number_format($bmi, 2) ?></td>
                    <td><?= $bmiStatus ?></td>
                    <td><?= $sectionA ? $sectionA['good_health'] : '' ?></td>
                    <td><?= $sectionA ? $sectionA['diet'] : '' ?></td>
                    <td><?= $sectionA ? $sectionA['comments'] : '' ?></td>
                    <td><?= $sectionB ? $sectionB['chronic_illness'] : '' ?></td>
                    <td><?= $sectionB ? $sectionB['taking_drugs'] : '' ?></td>
                    <td><?= $sectionB ? $sectionB['comments'] : '' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
