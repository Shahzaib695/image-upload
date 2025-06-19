<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
    <label for="">Upload Image:</label>
    <input type="file" name="imageupload[]" multiple required><br><br>
    <input type="submit" value="Upload" name="uploadbtn">
</form>
<?php
    include("db.php");
    if (isset($_POST['uploadbtn'])) {
        if (isset($_FILES['imageupload']) && is_array($_FILES['imageupload']['name'])) {
            for ($i = 0; $i < count($_FILES['imageupload']['name']); $i++){
                $image_name = $_FILES['imageupload']['name'][$i];
                $image_type = $_FILES['imageupload']['type'][$i];
                $image_tmp = $_FILES['imageupload']['tmp_name'][$i];
                $image_size = $_FILES['imageupload']['size'][$i];
                $folder = "images/";
    
                if (strtolower($image_type) == "image/jpg" || strtolower($image_type) == "image/jpeg" || strtolower($image_type) == "image/png") {
                    if ($image_size <= 1000000) {
                        $image_path = $folder . basename($image_name);
    
                        $query = "INSERT INTO `image`(`image_path`) VALUES ('$image_path')";
                        $result = mysqli_query($conn, $query);
    
                        if ($result) {
                            move_uploaded_file($image_tmp, $image_path);
                            echo "<script>alert('Image uploaded successfully');</script>";
                        } else {
                            echo "Failed to insert into database.";
                        }
                    } else {
                        echo "Image size is too large.";
                    }
                } else {
                    echo "Image type is not valid.";
                }
            }
        } else {
            echo "No files were uploaded.";
        }
    }
    

    $select = "SELECT * FROM `image`";
    $result = mysqli_query($conn, $select);
    $total_row = mysqli_num_rows($result);

    if ($total_row > 0) {
        echo "<h3>Images</h3>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Image</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><img src='" . $row['image_path'] . "' alt='image' width='50' height='50'></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No data found.";
    }
?>
</body>
</html>
