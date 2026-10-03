<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/add_teach.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>Document</title>
</head>

<body>
    <div class="container">
    <a href="admin.php" id="back"><i class="fas fa-arrow-left">b</i></a>
        <h2>Search</h2>
        <?php
        require_once "../BACK/function.php";
        if (
            isset($_SESSION['user_id'])
            && isset($_SESSION['role_id'])
            && $_SESSION['role_id'] == 0
        ) {
            $con = db_connect();
        ?>
            <form action="show_stu_to.php" method="POST">
                <div class="search_cont">
                    <input type="text" name='search' id="search" placeholder="Search..." maxlength="50">
                    <input type="submit" value="search">
                </div>
            </form>
            <form action="add_teach.php" method="POST">
                <div class="table">
                    <?php

                    if (isset($_POST['search']) && !empty($_POST['search'])) {
                        $word = $_POST['search'];
                    $sql =   "SELECT `name`,`email`,`id`
                                from `users`
                                where `id`in (
                                    SELECT stu_id from student
                                )
                                and (name like '%$word%'
                                    or email like '%$word%'
                                    )
                                and `id` not in (
                                    SELECT `teach_id` from `teacher`
                                )
                                ;";
                        if ($res = $con->query($sql)) {
                            if (!$res->num_rows) {
                    ?>
                                <div class="row">
                                    <input type="checkbox" id="row1" class="hidden">
                                    <label for="row1">
                                        <span class="cell">NO DATA FOUND</span>
                                        <span class="cell">-----</span>
                                    </label>
                                </div>
                                <?php

                            } else {
                                while ($row = $res->fetch_assoc()) {
                                    $id=$row['id'];
                                    $name=$row['name'];
                                    $email=$row['email'];
                                ?>
                                    <div class="row">
                                        <input type="checkbox" id="row1" name="search_res" value="<?=$id?>" class="hidden">
                                        <label for="row1">
                                            <span class="cell"><?=$name?></span>
                                            <span class="cell"><?=$email?></span>
                                            <span class="cell"><a href="../BACK/add_teach.php?id=<?=$id?>">@</a></span>
                                        </label>
                                    </div>
                    <?php
                                }
                            }
                        }
                    }
                    ?>
                    <!-- Add more rows as needed -->
                </div>
            </form>
        <?php
            // }else{
            //     die("method not allwoed");
            // }
        } else {
            print_r($_SESSION);
            header("location:index.php");
            exit;
        }
        ?>
    </div>
</body>

</html>