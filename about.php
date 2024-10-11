<?php
include 'config/db.php';
include 'includes/header.php';

// Fetch office bearers
$sql = "SELECT * FROM office_bearers";
$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">About CSEA</h2>
    <p>CSEA is dedicated to fostering a vibrant community of computer science students.</p>
    <h3>Office Bearers</h3>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?php echo $row['name']; ?> - <?php echo $row['role']; ?></h5>
                    <p>Email: <?php echo $row['email']; ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No office bearers listed.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
