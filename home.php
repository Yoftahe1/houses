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
    <link rel="stylesheet" href="./home.css">

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

    <div class="home">
        <h2>Available Houses</h2>
        <div class="grid">
            <?php
            include "db.php";

            $sql = "SELECT * FROM houses WHERE userId != {$_SESSION["id"]}";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while (
                    $row = mysqli_fetch_assoc($result)
                ) {
                    echo ("<a href='detail.php?id={$row["id"]}' style='text-decoration:none;color:black'>
                                <div class='card' >
                                    <img src='./images/{$row["image"]}' alt='card'>
                                    <div class='desc'>
                                    <div>
                                    <p class='name'>{$row["title"]}</p>
                                    <p class='location'>{$row["location"]}</p>
                                    </div>
                                        <p class='price'>$ {$row["price"]}</p>
                                    </div>
                                </div>
                            </a>");
                }
            } else {
                echo "<div class='noHouse'>No house available currently</div>";
            }

            mysqli_close($conn);
            ?>
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