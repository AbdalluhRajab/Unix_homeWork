<?php
$host = "db";
$user = "sumuser";
$password = "12345";
$dbname = "sum_app";

$conn = null;
$maxRetries = 10;
$retryDelay = 3;

for ($i = 1; $i <= $maxRetries; $i++) {
    $conn = @new mysqli($host, $user, $password, $dbname);

    if (!$conn->connect_error) {
        break;
    }

    sleep($retryDelay);
}

if ($conn->connect_error) {
    die("Database connection failed. Please refresh the page after a few seconds.");
}

$result = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number1 = $_POST["number1"];
    $number2 = $_POST["number2"];

    if (is_numeric($number1) && is_numeric($number2)) {
        $sum = $number1 + $number2;
        $result = "Result: " . $sum;

        $stmt = $conn->prepare("INSERT INTO calculations (number1, number2, result) VALUES (?, ?, ?)");
        $stmt->bind_param("ddd", $number1, $number2, $sum);
        $stmt->execute();
        $stmt->close();
    } else {
        $result = "Please enter valid numbers.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Two Numbers Sum Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Two Numbers Sum Calculator</h1>
    <p>Please enter two numbers to calculate and save the result.</p>

    <form method="POST">
        <input type="number" step="any" name="number1" placeholder="Enter first number" required>
        <input type="number" step="any" name="number2" placeholder="Enter second number" required>

        <button type="submit">Calculate Sum</button>
    </form>

    <h2><?php echo $result; ?></h2>
</div>

</body>
</html>