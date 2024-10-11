<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch office bearers from the database
$sql = "SELECT * FROM office_bearers ORDER BY role";
$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">Office Bearers</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?php echo $row['name']; ?> - <span class="text-muted"><?php echo $row['role']; ?></span></h5>
                    <p>Email: <?php echo $row['email']; ?></p>
                    <!-- <p>Contact: <?php //echo $row['contact_number']; ?></p> -->
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No office bearers listed.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
