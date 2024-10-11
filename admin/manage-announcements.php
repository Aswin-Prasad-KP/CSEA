<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle announcement creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $created_by = $_SESSION['username'];

    $sql = "INSERT INTO announcements (title, description, event_date, created_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $event_date, $created_by);
    $stmt->execute();
}

// Fetch announcements
$sql = "SELECT * FROM announcements ORDER BY event_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - CSEA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Announcements</h2>

    <form method="POST">
        <div class="form-group">
            <label for="title">Announcement Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Announcement Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Announcement</button>
    </form>

    <h3 class="mt-5">Current Announcements</h3>
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
