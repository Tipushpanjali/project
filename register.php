<?php
session_start();
include 'db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $organizer_id = $_SESSION['user_id']; // Assuming you store user ID in session

    $sql = "INSERT INTO events (title, date, time, location, description, organizer_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $date, $time, $location, $description, $organizer_id);

    if ($stmt->execute()) {
        echo "Event created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
</head>
<body>
    <h1>Create an Event</h1>
    <form method="POST" action="">
        <label for="title">Event Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="date">Event Date:</label>
        <input type="date" id="date" name="date" required><br>

        <label for="time">Event Time:</label>
        <input type="time" id="time" name="time" required><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <button type="submit">Create Event</button>
    </form>
</body>
</html>
