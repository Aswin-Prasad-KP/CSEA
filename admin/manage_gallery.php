<?php
session_start();
include '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $created_by = $_SESSION['username'];

    // Handle file upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "<div class='alert alert-danger'>File is not an image.</div>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<div class='alert alert-danger'>Sorry, file already exists.</div>";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
    } else {
        // Attempt to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Save file name in database
            $sql = "INSERT INTO gallery (title, image_url, created_by) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $title, basename($_FILES["image"]["name"]), $created_by);
            $stmt->execute();
            echo "<div class='alert alert-success'>The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.</div>";
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    }
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

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Image Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="image">Select Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Image</button>
    </form>

    <h3 class="mt-5">Current Gallery</h3>
    <div class="row">
        <?php while($row = $gallery->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="../uploads/<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>">
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
