<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./signup.css">
    <title>Document</title>
</head>

<body>
    <div class="circle">
        <h1>WELCOME</h1>
    </div>

    <div class="formContainer">
        <form method="post" action="signin.php">
            <label class="title">Sign-in</label>
            <label>Name</label>
            <input type="text" placeholder="enter your name" name="name" required>
            <label>Password</label>
            <input type="password" placeholder="enter password" name="password" required>
            <?php
            include "db.php";

            if (isset($_POST["signin"])) {
                if (!empty($_POST["name"]) && !empty($_POST["password"])) {

                    $inputName = $_POST["name"];
                    $sql = "SELECT * FROM users Where name = '$inputName' ";

                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        if (password_verify($_POST["password"], $row["password"])) {

                            $_SESSION["id"] = $row["id"];
                            $_SESSION["name"] = $row["name"];
                            header("Location: home.php");
                        } else {
                            echo "<span class='error'>username and password don't match</span>";
                        }
                    } else {
                        echo "<span class='error'>no user found by this username</span>";
                    }
                } else {
                    foreach ($_POST as $key => $value) {
                        if (empty($value)) {
                            echo "<span class='error'>please insert {$key} correctly</span>";
                            break;
                        }
                    }
                }
            }
            mysqli_close($conn);
            ?>
            <input class="button" type="submit" value="Sign-In" name="signin">
            <p>Don't have account: <a href="./signup.php">sign up</a></p>
        </form>
    </div>
</body>

</html>