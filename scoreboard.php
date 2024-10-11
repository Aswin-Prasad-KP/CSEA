<?php
include 'config/db.php';
include 'includes/header.php';

$sql = "SELECT * FROM scoreboard ORDER BY score DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h2 class="mt-4">Scoreboard</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['score']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">No scores available at the moment.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
