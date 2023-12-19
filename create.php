<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: signin.php");
}
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: signin.php");
}
if (isset($_POST["deleteUser"])) {
    include "db.php";
    $id = $_SESSION["id"];

    $sql = "SELECT * FROM houses WHERE userId = {$id}";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while (
            $row = mysqli_fetch_assoc($result)
        ) {
            $file_to_delete = "./images/{$row["image"]}";
            unlink($file_to_delete);
        }
    }

    $sql = "delete from users where id = {$id}";
    try {
        mysqli_query($conn, $sql);
        session_destroy();
        header("Location: signup.php");
    } catch (mysqli_sql_exception) {
        echo "<span class='error'>something went wrong</span>";
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./create.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="nav">
        <p class="logo">Houses</p>
        <div class="pc">
            <a href="home.php" class="link">Home</a>
            <a href="create.php" class="link">Add-House</a>
            <a href="myHouses.php" class="link">My-Houses</a>
            <div class="pcDropdown" onmouseenter="pcDropdown()" onmouseleave="pcDropdown()">
                <button class="username"><?php echo $_SESSION['name']; ?></button>
                <div id="pcContent" class="pcDropdownContent">
                    <a href="editUser.php" class="edit">Edit</a>
                    <form method="post" action="home.php">
                        <input type="submit" value="Logout" name="logout" class="logout">
                    </form>
                    <form method="post" action="home.php">
                        <input type="submit" value="Delete" name="deleteUser" class="delete">
                    </form>
                </div>
            </div>
        </div>
        <div class="mobile" onmouseenter="mobileDropdown()" onmouseleave="mobileDropdown()">
            <div class="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div id="mobileContent" class="mobileDropdownContent">
                <a href="home.php" class="mobileLink">Home</a>
                <a href="create.php" class="mobileLink">Add-House</a>
                <a href="myHouses.php" class="mobileLink">My-Houses</a>
                <a href="editUser.php" class="mobileLink">Edit</a>
                <form method="post" action="home.php">
                    <input type="submit" value="Logout" name="logout" class="logout">
                </form>
                <form method="post" action="home.php">
                    <input type="submit" value="Delete" name="deleteUser" class="delete">
                </form>
            </div>
        </div>
    </div>

    <div class="createContainer">
        <form class="create" method="post" action="create.php" enctype="multipart/form-data">
            <div class="imageContainer" id="image-container">
                <input type="file" id="image-input" accept="image/*" name="image-input">
                <div class="image">
                    <img id="image-preview" src="#" alt="choose an image" height="100%" width="100%" style="object-fit: cover;">
                </div>
            </div>
            <div class="formContainer">
                <h2>Fill form correctly</h2>
                <div class="form">
                    <div class="desc">
                        <p>Title:</p>
                        <input placeholder="enter title" name="title" type="text">
                    </div>
                    <div class="desc">
                        <p>Price:</p>
                        <input placeholder="enter Price" name="price" type="number">
                    </div>
                    <div class="desc">
                        <p>Type:</p>
                        <select name="type">
                            <option>Vila</option>
                            <option>Apartment</option>
                            <option>Condominium</option>
                        </select>
                    </div>
                    <div class="desc">
                        <p>Area:</p>
                        <input placeholder="enter area" name="area" type="number">
                    </div>
                    <div class="desc">
                        <p>Location:</p>
                        <input placeholder="enter location" name="location" type="text">
                    </div>
                    <div class="desc">
                        <p>Number of bed:</p>
                        <input placeholder="enter number of bed" name="bed" type="number">
                    </div>
                    <div class="desc">
                        <p>Number of class:</p>
                        <input placeholder="enter number of class" name="room" type="number">
                    </div>
                    <div class="desc">
                        <p>Number of toilet:</p>
                        <input placeholder="enter number of toilet" name="toilet" type="number">
                    </div>


                </div>
                <?php

                include "db.php";

                if (isset($_POST["submit"])) {
                    if (
                        !empty($_POST["title"]) &&
                        !empty($_POST["price"]) &&
                        !empty($_POST["type"]) &&
                        !empty($_POST["area"]) &&
                        !empty($_POST["location"]) &&
                        !empty($_POST["bed"]) &&
                        !empty($_POST["room"]) &&
                        !empty($_POST["toilet"]) &&
                        !empty($_FILES['image-input']['name'])
                    ) {
                        if (
                            strlen(trim($_POST["title"])) < 1 ||
                            $_POST["price"] < 1 ||
                            strlen(trim($_POST["type"])) < 1 ||
                            $_POST["area"] < 1 ||
                            strlen(trim($_POST["location"])) < 1 ||
                            $_POST["bed"] < 1 ||
                            $_POST["room"] < 1 ||
                            $_POST["toilet"] < 1
                        ) {
                            echo "<span class='error'>input minimum size is 1</span>";
                        } else {

                            if ($_FILES['image-input']['error'] === 0) {
                                $image = $_FILES['image-input']['tmp_name'];
                                $imageName = uniqid('', true) . "." . $_FILES['image-input']['name'];
                                $destination = 'images/' . $imageName;

                                if (move_uploaded_file($image, $destination)) {
                                    $sql = "INSERT INTO houses (title,price,type,area,location,bed,room,toilet,image,userId) 
        VALUES 
        ('{$_POST["title"]}',{$_POST["price"]},'{$_POST["type"]}',{$_POST["area"]},'{$_POST["location"]}',{$_POST["bed"]},{$_POST["room"]},{$_POST["toilet"]},'{$imageName}',{$_SESSION["id"]})";

                                    try {
                                        mysqli_query($conn, $sql);
                                        echo "<span style='color:green;text-align: center;'>house added successfully</span>";
                                    } catch (mysqli_sql_exception) {
                                        echo "<span class='error'>something went wrong</span>";
                                    }
                                } else {
                                    echo 'Failed to upload image.';
                                }
                            }
                        }
                    } else {
                        if (empty($_FILES['image-input']['name'])) {
                            echo "<span class='error'>Please insert image correctly</span>";
                        } else {
                            foreach ($_POST as $key => $value) {
                                if (empty($value)) {
                                    echo "<span class='error'>Please insert {$key} correctly</span>";
                                    break;
                                }
                            }
                        }
                    }
                }
                mysqli_close($conn);

                ?>
                <input type="submit" value="Create" name="submit" class="button">
            </div>
        </form>
    </div>
    <script>
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        const imageContainer = document.getElementById('image-container');
        imageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = (readerEvent) => {
                    imagePreview.src = readerEvent.target.result;
                };
                reader.readAsDataURL(file);
                imageContainer.style.minHeight = "auto";
            }
        });
        function pcDropdown() {
            document.getElementById("pcContent").classList.toggle("show");
        }
        function mobileDropdown() {
            document.getElementById("mobileContent").classList.toggle("showMobile");
        }
    </script>
</body>

</html>