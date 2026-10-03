<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <link rel="stylesheet" href="../CSS/drop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>manag folder</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_GET['why'])
        && isset($_GET['folder_id']) && !empty($_GET['folder_id'])
    ) {
        $meth = $_SERVER['REQUEST_METHOD'];
        $user_id = $_SESSION['user_id'];
        $id=$_GET['folder_id'];
        $con = db_connect();
        if ($_GET['why'] == 'd') {
            if ($meth == "POST") {
                if (isset($_POST['confirm'])) {
                    if ($_POST['confirm'] == 'yes') {
                        $sql="DELETE FROM `folder_word` where `folder_id`=$id;";
                        if(!$con->query($sql)){
                            die("delete depending word error : ".$con->error);
                        }
                        $sql = "DELETE FROM `folder` where `id`=$id and `stu_id`=$user_id;";
                        if (!$con->query($sql)) {
                            die($con->error);
                        }
                    }
                }
                header("location:../FRONT/review.php");
            } elseif ($meth == "GET") {
                $sql="SELECT `name` FROM `FOLDER` WHERE `ID`=$id and `stu_id`=$user_id;";
                $res=$con->query($sql);
                if(!$res->num_rows){
                    die($con->error);
                }
                $row=$res->fetch_assoc();
                if(!$row){
                    die("no row");
                }
                $title=$row['name'];
    ?>
                <form action="drop_f.php?folder_id=<?= $id ?>&why=d" method="POST">
                    <div class="main">
                        <div class="unit">
                            <label for="title">ARE YOU SHURE YOU WANT TO DROP THE LESSON <span style="color: red;"><?= $title ?><span></label>
                            <button type="submit" name="confirm" style="background-color: green;" value="yes">Yes</button>
                            <button type="submit" name="confirm" style="background-color: red;" value="no">No</button>
                        </div>
                    </div>
                </form>
            <?php
            }
        } elseif ($_GET['why'] == 'e') {
            if ($meth == "POST") {
                if (
                    isset($_POST['title']) && !empty($_POST['title'])
                    && isset($_POST['desc']) && !empty($_POST['desc'])
                ) {
                    $t = valid_input($_POST['title']);
                    $d = valid_input($_POST['desc']);
                    echo "t:$t d:$d id:$id stu_id:$user_id";
                    $sql = "UPDATE `folder` set `name`='$t', `desc`='$d' where `stu_id`=$user_id and `id`=$id;";
                    if (!$con->query($sql)) {
                        die("update error");
                    }
                    header("location:../FRONT/review.php");
                } else {
                   echo "helo";
                }
            } elseif ($meth == "GET") {
                $sql = "SELECT `name`,`desc` from `folder` where `id`=$id and `stu_id`=$user_id;";
                if ($res = $con->query($sql)) {
                    if ($res = $res->fetch_assoc()) {
                        extract($res);
                    }
                } else {
                    die($con->error);
                }
            ?>
                <form action="drop_f.php?folder_id=<?= $id ?>&why=e" method="post">
                    <div class="main">
                        <div class="unit">
                            <a href="../FRONT/review.php"><i class="fas fa-times"></i></a>
                            <label for="title">folder title:</label>
                            <input type="text" name="title" id="title" required value="<?= $name ?>">
                            <label for="desc">description:</label>
                            <input type="text" name="desc" id="desc" maxlength="255" required value="<?= $desc ?>">
                            <input type="submit" value="submit">
                        </div>
                    </div>
                </form>
    <?php

            }
        }
    } else {
        print_r($_GET);
    }
    ?>
</body>

</html>