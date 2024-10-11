<?php
include 'config/db.php'; // Database connection

// Query to fetch announcements ordered by event date (newest first)
$sql = "SELECT * FROM announcements ORDER BY event_date DESC";
$result = $conn->query($sql);

include 'includes/header.php'; // Include header
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Latest Announcements</h2>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="text-muted">Event Date: <?php echo date('F d, Y', strtotime($row['event_date'])); ?></p>
                            <a href="#" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="alert alert-info text-center">No announcements at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include 'includes/footer.php'; // Include footer
$conn->close(); // Close database connection
?>
