<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch upcoming events
$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
$result = $conn->query($sql);
?>

<div class="container">
    <h1 class="mt-4">Welcome to CSEA</h1>
    <h2>Upcoming Events</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?php echo $row['title']; ?></h5>
                    <p><?php echo $row['description']; ?></p>
                    <small>Date: <?php echo $row['event_date']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No upcoming events at the moment.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
