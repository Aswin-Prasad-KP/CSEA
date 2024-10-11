<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $image_url = $_POST['image_url'];
    $created_by = $_SESSION['username'];

    $sql = "INSERT INTO gallery (title, image_url, created_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $image_url, $created_by);
    $stmt->execute();
}

// Fetch gallery items
$sql = "SELECT * FROM gallery";
$gallery = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - CSEA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Gallery</h2>

    <form method="POST">
        <div class="form-group">
            <label for="title">Image Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="text" class="form-control" id="image_url" name="image_url" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Image</button>
    </form>

    <h3 class="mt-5">Current Gallery</h3>
    <div class="row">
        <?php while($row = $gallery->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <small>Uploaded by: <?php echo $row['created_by']; ?></small>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
