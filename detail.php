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
    <link href="./detail.css" rel="stylesheet">
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
    <div class="detailContainer">
        <?php
        include "db.php";
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT *,users.name FROM houses JOIN users ON houses.userId=users.id WHERE houses.id= {$id}";;
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
            }
        }
        if (isset($_POST["buy"])) {
            $id = $_GET['id'];
            $sql = "UPDATE houses SET userId={$_SESSION["id"]} WHERE id = {$id}";
            try {
                mysqli_query($conn, $sql);
                header("Location: myHouses.php");
            } catch (mysqli_sql_exception) {
                $error = "something went wrong";
                echo "<span style='color:red;text-align: center;'>something went wrong</span>";
            }
        }
        mysqli_close($conn);
        ?>
        <div class="detail">
            <div class='img'>
                <img src=<?php echo "./images/{$row["image"]}"; ?> alt='card' height='100%' width='100%' style='object-fit: cover;'>
            </div>
            <div class="descContainer">
                <h2>House detail</h2>
                <div class='desc'>
                    <p><b>Title: </b><?php echo $row["title"]; ?></p>
                    <p><b>Price: </b><?php echo $row["price"]; ?></p>
                    <p><b>Type: </b><?php echo $row["type"]; ?></p>
                    <p><b>Area: </b><?php echo $row["area"]; ?> m2</p>
                    <p><b>Location: </b><?php echo $row["location"]; ?></p>
                    <p><b>Number of bed: </b><?php echo $row["bed"]; ?></p>
                    <p><b>Number of class: </b><?php echo $row["room"]; ?></p>
                    <p><b>Number of toilet: </b><?php echo $row["toilet"]; ?></p>
                    <p><b>Owner: </b><?php echo $row["name"]; ?></p>
                </div>
                <span style='color:red;text-align: center;'><?php if (isset($error)) {
                                                                echo $error;
                                                            } ?></span>
                <form action=<?php echo "detail.php?id={$_GET['id']}"; ?> method='post'>
                    <input type='submit' value='Buy' name='buy' class='button'>
                </form>
            </div>
        </div>
    </div>
    <script>
        function pcDropdown() {
            document.getElementById("pcContent").classList.toggle("show");
        }

        function mobileDropdown() {
            document.getElementById("mobileContent").classList.toggle("showMobile");
        }
    </script>
</body>

</html>