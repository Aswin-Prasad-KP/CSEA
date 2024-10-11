<?php
include 'config/db.php';
include 'includes/header.php';

$sql = "SELECT * FROM announcements ORDER BY event_date DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">Announcements</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h4><?php echo $row['title']; ?></h4>
                    <p><?php echo $row['description']; ?></p>
                    <small>Event Date: <?php echo $row['event_date']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No announcements at the moment.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
