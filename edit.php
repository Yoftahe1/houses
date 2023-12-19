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
    <?php
    include "db.php";
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM houses WHERE id= {$id}";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $title = $row["title"];
            $price =  $row["price"];
            $type =  $row["type"];
            $area =  $row["area"];
            $location =  $row["location"];
            $bed =  $row["bed"];
            $room =  $row["room"];
            $toilet =  $row["toilet"];
            $image = "./images/{$row["image"]}";
        }
    }
    if (isset($_POST["save"])) {
        $title = $_POST["title"];
        $price =  $_POST["price"];
        $type =  $_POST["type"];
        $area =  $_POST["area"];
        $location =  $_POST["location"];
        $bed =  $_POST["bed"];
        $room =  $_POST["room"];
        $toilet =  $_POST["toilet"];
        if ($_FILES['image-input']['error'] === 0) {
            $imageUpload = $_FILES['image-input']['tmp_name'];
            $imageName = uniqid('', true) . "." . $_FILES['image-input']['name'];
            $destination = 'images/' . $imageName;
            $uploaded = move_uploaded_file($imageUpload, $destination);
            $image = "./images/{$imageName}";
        }
    }
    mysqli_close($conn);
    ?>
    <div class="createContainer">
        <form class='create' method='post' action="<?php echo "edit.php?id={$_GET["id"]}"; ?>" enctype='multipart/form-data'>
            <div class='imageContainer' style="min-height: auto;">
                <input type='file' id='image-input' accept='image/*' name='image-input'>
                <div class="image">
                    <img id='image-preview' alt='house' src=<?php echo $image; ?> height="100%" width="100%" style='object-fit: cover;'>
                </div>
            </div>
            <div class="formContainer">
                <h2>Edit house correctly</h2>
                <div class='form'>
                    <div class='desc'>
                        <p>Title:</p>
                        <input placeholder='enter title' name='title' type='text' value=<?php echo $title; ?>>
                    </div>
                    <div class='desc'>
                        <p>Price:</p>
                        <input placeholder='enter Price' name='price' type='number' value=<?php echo $price; ?>>
                    </div>
                    <div class='desc'>
                        <p>Type:</p>
                        <select name='type'>
                            <option value='Vila' <?php if ($type === "Vila") echo "selected"; ?>>Vila</option>
                            <option value='Apartment' <?php if ($type === "Apartment") echo "selected"; ?>>Apartment</option>
                            <option value='Condominium' <?php if ($type === "Condominium") echo "selected"; ?>>Condominium</option>
                        </select>
                    </div>
                    <div class='desc'>
                        <p>Area:</p>
                        <input placeholder='enter area' name='area' type='number' value=<?php echo $area; ?>>
                    </div>
                    <div class='desc'>
                        <p>Location:</p>
                        <input placeholder='enter location' name='location' type='text' value=<?php echo $location; ?>>
                    </div>
                    <div class='desc'>
                        <p>Number of bed:</p>
                        <input placeholder='enter number of bed' name='bed' type='number' value=<?php echo $bed; ?>>
                    </div>
                    <div class='desc'>
                        <p>Number of class:</p>
                        <input placeholder='enter number of class' name='room' type='number' value=<?php echo $room; ?>>
                    </div>
                    <div class='desc'>
                        <p>Number of toilet:</p>
                        <input placeholder='enter number of toilet' name='toilet' type='number' value=<?php echo $toilet; ?>>
                    </div>
                </div>
                <?php
                include "db.php";

                if (isset($_POST["save"])) {

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
                        $id = $_GET['id'];
                        if (empty($_FILES['image-input']['name'])) {
                            $sql = "UPDATE houses SET title='{$_POST["title"]}', price={$_POST["price"]}, type='{$_POST["type"]}', area={$_POST["area"]}, location='{$_POST["location"]}', bed={$_POST["bed"]}, room={$_POST["room"]}, toilet={$_POST["toilet"]} 
                                Where id = {$id}";
                            try {
                                mysqli_query($conn, $sql);
                                echo "<span class='success'>house edited successfully</span>";
                            } catch (mysqli_sql_exception) {
                                echo "<span class='error'>something went wrong</span>";
                            }
                        } else {
                            if ($uploaded) {
                                unlink("./images/{$row["image"]}");
                                $sql = "UPDATE houses SET title='{$_POST["title"]}', price={$_POST["price"]}, type='{$_POST["type"]}', area={$_POST["area"]}, location='{$_POST["location"]}', bed={$_POST["bed"]}, room={$_POST["room"]}, toilet={$_POST["toilet"]}, image='{$imageName}' 
                                        Where id = {$id}";
                                try {
                                    mysqli_query($conn, $sql);
                                    echo "<span class='success'>house edited successfully</span>";
                                } catch (mysqli_sql_exception) {
                                    echo "<span class='error'>something went wrong</span>";
                                }
                            } else {
                                echo 'Failed to upload image.';
                            }
                        }
                    }
                }
                mysqli_close($conn);
                ?>
                <input type='submit' value='Save' name='save' style='color:green;background:rgba(0,255,0,.2);border:0;cursor:pointer;'>
            </div>
        </form>
    </div>
    <script>
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = (readerEvent) => {
                    imagePreview.src = readerEvent.target.result;
                };
                reader.readAsDataURL(file);
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