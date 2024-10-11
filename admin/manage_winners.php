<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle winner addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $winner_name = $_POST['winner_name'];
    $position = $_POST['position'];

    $sql = "INSERT INTO winners (event_id, winner_name, position) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $event_id, $winner_name, $position);
    $stmt->execute();
}

// Fetch events for dropdown
$sql = "SELECT id, title FROM events";
$events = $conn->query($sql);

// Fetch winners
$sql = "SELECT w.*, e.title AS event_title FROM winners w JOIN events e ON w.event_id = e.id";
$winners = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Winners - CSEA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Winners</h2>

    <form method="POST">
        <div class="form-group">
            <label for="event_id">Select Event</label>
            <select class="form-control" id="event_id" name="event_id" required>
                <?php while($event = $events->fetch_assoc()): ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo $event['title']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="winner_name">Winner Name</label>
            <input type="text" class="form-control" id="winner_name" name="winner_name" required>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <select class="form-control" id="position" name="position" required>
                <option value="1st">1st</option>
                <option value="2nd">2nd</option>
                <option value="3rd">3rd</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Winner</button>
    </form>

    <h3 class="mt-5">Current Winners</h3>
    <ul class="list-group">
        <?php while($winner = $winners->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo $winner['winner_name']; ?></strong> - <?php echo $winner['position']; ?> (Event: <?php echo $winner['event_title']; ?>)
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>

<?php
$conn->close();
?>
