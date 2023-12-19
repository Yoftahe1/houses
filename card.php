<a href='edit.php?id=<?php echo $id; ?>' style='text-decoration:none;color:black'>
    <div class='card'>
        <img src='<?php echo $image; ?>' alt='card'>

        <div style="display: flex;justify-content: space-between;align-items: center;">
            <div>
                <p class="name"><?php echo $title; ?></p>
                <p class="location"><?php echo $location; ?></p>
            </div>
            <form method="post" action="myHouses.php">
                <?php
                if (isset($_POST["delete{$id}"])) {
                    $sql = "DELETE FROM houses WHERE id = {$id}";
                    try {
                        mysqli_query($conn, $sql);
                        unlink($image);
                        header("Location: myHouses.php");
                    } catch (mysqli_sql_exception) {
                        echo "<span class='error'>something went wrong</span>";
                    }
                }
                ?>
                <input type="submit" name="delete<?php echo $id; ?>" value="Delete" class="deleteCard">
            </form>
        </div>
    </div>
</a>