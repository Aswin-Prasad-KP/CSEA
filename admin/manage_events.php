<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

// Handle event creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $created_by = $_SESSION['username'];

    $sql = "INSERT INTO events (title, description, event_date, created_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $event_date, $created_by);
    $stmt->execute();
}

// Fetch events created by the user or all events based on their role
if ($role === 'General Secretary') {
    $sql = "SELECT * FROM events"; // General Secretary can see all events
} else {
    $sql = "SELECT * FROM events WHERE created_by = ?"; // Office Bearer and Joint Secretary can see only their events
}
$stmt = $conn->prepare($sql);
if ($role !== 'General Secretary') {
    $stmt->bind_param("s", $_SESSION['username']);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - CSEA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Events</h2>

    <form method="POST">
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Event Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>

    <h3 class="mt-5">Current Events</h3>
    <ul class="list-group">
        <?php while($row = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <h4><?php echo $row['title']; ?></h4>
                <p><?php echo $row['description']; ?></p>
                <small>Event Date: <?php echo $row['event_date']; ?> | Created by: <?php echo $row['created_by']; ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>

<?php
$conn->close();
?>
