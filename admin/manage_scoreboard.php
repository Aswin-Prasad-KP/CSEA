<?php
session_start();
include '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

// Handle form submission for adding scores
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $participant_name = $_POST['participant_name'];
    $score = $_POST['score'];

    // Insert score into the scoreboard table
    $stmt = $conn->prepare("INSERT INTO scoreboard (event_id, participant_name, score) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $event_id, $participant_name, $score);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Score added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding score.</div>";
    }

    $stmt->close();
}

// Fetch events for the dropdown based on the user's role
if ($role === 'General Secretary') {
    $sql = "SELECT * FROM events"; // General Secretary can see all events
} else {
    $sql = "SELECT * FROM events WHERE created_by = ?"; // OB and Joint Secretary can see only their events
}

$stmt = $conn->prepare($sql);
if ($role !== 'General Secretary') {
    $stmt->bind_param("s", $_SESSION['username']);
}
$stmt->execute();
$events = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scoreboard - CSEA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Scoreboard</h2>

    <form method="POST">
        <div class="form-group">
            <label for="event_id">Select Event:</label>
            <select class="form-control" id="event_id" name="event_id" required>
                <?php while ($row = $events->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option> <!-- Updated to use 'title' -->
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="participant_name">Participant Name:</label>
            <input type="text" class="form-control" id="participant_name" name="participant_name" required>
        </div>
        <div class="form-group">
            <label for="score">Score:</label>
            <input type="number" class="form-control" id="score" name="score" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Score</button>
    </form>

    <h3 class="mt-5">Current Scores</h3>
    <ul class="list-group">
        <?php
        // Fetch scores to display
        $score_sql = "SELECT scoreboard.*, events.title 
                      FROM scoreboard 
                      JOIN events ON scoreboard.event_id = events.id 
                      ORDER BY scoreboard.created_at DESC";
        $score_result = $conn->query($score_sql);
        
        if ($score_result->num_rows > 0) {
            while($score_row = $score_result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?php echo $score_row['participant_name']; ?></h5>
                    <p>Event: <?php echo $score_row['title']; ?></p>
                    <p>Score: <?php echo $score_row['score']; ?></p>
                    <small>Created At: <?php echo date('F j, Y', strtotime($score_row['created_at'])); ?></small>
                </li>
            <?php endwhile;
        } else {
            echo "<div class='alert alert-warning'>No scores available.</div>";
        }
        ?>
    </ul>
</div>
</body>
</html>

<?php
$conn->close();
?>
