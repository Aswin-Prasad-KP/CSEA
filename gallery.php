<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch images from the gallery
$sql = "SELECT * FROM gallery ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">Gallery</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <!-- Updated image path to use the correct column from the database -->
                        <img src="uploads/<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <!-- <p class="card-text"><?php //echo htmlspecialchars($row['description']); ?></p> -->
                            <small>Uploaded At: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No images found in the gallery.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
