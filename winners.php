<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch winners from the database with corresponding event names
$sql = "SELECT winners.*, events.title 
        FROM winners 
        JOIN events ON winners.event_id = events.id 
        ORDER BY winners.created_at DESC";

$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">Winners</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?php echo $row['winner_name']; ?></h5>
                    <p>Event: <?php echo $row['title']; ?></p>
                    <p>Position: <?php echo $row['position']; ?></p>
                    <small>Created At: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No winners announced yet.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
