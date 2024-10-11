<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CSEA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Welcome to CSEA Dashboard</h2>
    <h4>Hello, <?php echo $_SESSION['username']; ?>!</h4>
    <h5>Your Role: <?php echo $role; ?></h5>

    <h3 class="mt-4">Manage Content</h3>
    <ul class="list-group">
        <li class="list-group-item"><a href="manage_events.php">Manage Events</a></li>
        <li class="list-group-item"><a href="manage_announcements.php">Manage Announcements</a></li>
        <li class="list-group-item"><a href="manage_winners.php">Manage Winners</a></li>
        <li class="list-group-item"><a href="manage_gallery.php">Manage Gallery</a></li>
        <li class="list-group-item"><a href="logout.php">Logout</a></li>
    </ul>
</div>
</body>
</html>
