<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch scores with corresponding event titles
$sql = "SELECT scoreboard.*, events.title 
        FROM scoreboard 
        JOIN events ON scoreboard.event_id = events.id 
        ORDER BY scoreboard.created_at DESC";

$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">Scoreboard</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?php echo $row['participant_name']; ?></h5>
                    <p>Event: <?php echo $row['title']; ?></p> <!-- Updated to use 'title' -->
                    <p>Score: <?php echo $row['score']; ?></p>
                    <small>Created At: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No scores available.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
