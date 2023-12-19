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
        <h1>EDIT</h1>
    </div>

    <div class="formContainer">
        <form action="editUser.php" method="post">
            <label class="title">Edit profile</label>
            <label>Name</label>
            <input type="text" placeholder="enter your name" name="name" min="5" max="25" required>
            <label>Password</label>
            <input type="password" placeholder="enter password" name="password" min="8" required>

            <?php
            include "db.php";

            if (isset($_POST["change"])) {
                if (!empty($_POST["name"]) && !empty($_POST["password"])) {
                    if (strlen(trim($_POST["name"])) < 5 || strlen(trim($_POST["name"])) > 25) {
                        echo "<span class='error'>username should minimum of 5 and maximum of 25 characters</span>";
                    } else if (strlen(trim($_POST["password"])) < 8) {
                        echo "<span class='error'>password should minimum of 8 characters</span>";
                    } else {
                        $id = $_SESSION["id"];
                        $name = $_POST["name"];
                        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                        $sql = "UPDATE users SET name='{$name}', password='{$password}' Where id = {$id}";
                        try {
                            mysqli_query($conn, $sql);

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
            <input class="button" type="submit" value="Change" name="change">
            <p>Changed your mind: <a href="./home.php">cancel</a></p>
        </form>
    </div>
</body>

</html>