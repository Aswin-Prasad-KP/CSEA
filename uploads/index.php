<?php
if(isset($_POST['upload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Save image path to the database and display in gallery
        echo "Image uploaded successfully!";
    } else {
        echo "Error uploading image.";
    }
}
?>
<form action="gallery.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" name="upload" value="Upload Image">
</form>
