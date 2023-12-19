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
        <h1>JOIN-US</h1>
    </div>

    <div class="formContainer">
        <form action="signup.php" method="post">
            <label class="title">Sign-up</label>
            <label>Name</label>
            <input type="text" placeholder="enter your name" name="name" min="5" max="25" required>
            <label>Password</label>
            <input type="password" placeholder="enter password" name="password" min="8" required>

            <?php
            include "db.php";

            if (isset($_POST["signup"])) {
                if (!empty($_POST["name"]) && !empty($_POST["password"])) {
                    if (strlen(trim($_POST["name"])) < 5 || strlen(trim($_POST["name"])) > 25) {
                        echo "<span class='error'>username should minimum of 5 and maximum of 25 characters</span>";
                    } else if (strlen(trim($_POST["password"])) < 8) {
                        echo "<span class='error'>password should minimum of 8 characters</span>";
                    } else {
                        $name = $_POST["name"];
                        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                        $sql = "INSERT INTO users (name,password) VALUES ('$name','$password')";

                        try {
                            mysqli_query($conn, $sql);

                            $id = mysqli_insert_id($conn);

                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $_POST["name"];

                            header("Location: home.php");
                        } catch (mysqli_sql_exception) {
                            echo "<span class='error'>username is taken</span>";
                        }
                    }
                } else {
                    foreach ($_POST as $key => $value) {
                        if (empty($value)) {
                            echo "<span class='error'>please fill form {$key} correctly</span>";
                            break;
                        }
                    }
                }
            }
            mysqli_close($conn);
            ?>

            <input class="button" type="submit" value="Sign-Up" name="signup">
            <p>Already have account: <a href="./signin.php">sign-in</a></p>
        </form>
    </div>
</body>

</html>