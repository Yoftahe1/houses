<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: signin.php");
}
?>

<style>
    .navbar {
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        height: 60px;
        padding: 0 70px;
    }

    .link {
        padding: 10px 20px;
        text-decoration: none
    }

    .dropbtn {
        width: 160px;
        background-color: #3876BF;
        color: white;
        border: 0;
        padding: 0 20px;
        height: 100%;
        cursor: pointer;
    }

    .dropbtn:hover,
    .dropbtn:focus {
        background-color: #2980B9;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #f1f1f1;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .show {
        display: block;
    }

    .edit {
        width: 100%;
        padding: 12px 16px;
        border: 0;
        color: green;
        display: block;
        text-align: center;
        text-decoration: none;
    }

    .edit:hover {
        background-color: rgba(0, 255, 0, .2);
    }

    .logout {
        width: 100%;
        padding: 12px 16px;
        border: 0;
        color: blue;
    }

    .logout:hover {
        background-color: rgba(0, 0, 255, .2);
    }

    .delete {
        width: 100%;
        padding: 12px 16px;
        border: 0;
        color: red;
    }

    .delete:hover {
        background-color: rgba(255, 0, 0, .2);
    }

    .error {
        text-align: center;
        color: red;
    }
</style>

<div class="navbar">
    <p style="font-size: 30px;">Houses</p>
    <div style="display: flex;gap:10px">
        <a href="home.php" class="link">Home</a>
        <a href="create.php" class="link">Add-House</a>
        <a href="myHouses.php" class="link">My-Houses</a>
        <div class="dropdown" onmouseenter="dropdown()" onmouseleave="dropdown()">
            <button class="dropbtn"><?php echo $_SESSION["name"]; ?></button>

            <div id="myDropdown" class="dropdown-content">
                <a href="editUser.php" class="edit">Edit</a>
                <form method="post" action="nav.php">
                    <input type="submit" value="Logout" name="logout" class="logout">
                </form>
                <form method="post" action="nav.php">
                    <input type="submit" value="Delete" name="deleteUser" class="delete">
                </form>
            </div>
            
        </div>
    </div>
</div>
<script>
    function dropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
</script>

<?php
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