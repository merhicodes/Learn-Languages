<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <title>delete lang</title>
    <style>
        body {
            background-color: #08121e;
            height: 100vh;
        }

        .main {
            top: 0vh;
        }

        label {
            text-align: center;
            color: #eee;
        }

        .unit {
            background-color: #111111;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        button[type="submit"] {
            width: 70px;
            height: 30px;
            font-size: large;
            font-weight: 700;
        }

        button[type="submit"]:hover {
            color: #eee;
        }
    </style>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_GET['teach_id']) && !empty($_GET['teach_id'])
    ) {
        $con=db_connect();
        $teach_id=$_GET['teach_id'];
        # get admin id
        $sql="SELECT `teach_id` from `teacher` where `admin_id` is null and `teach_id`=$teach_id;;";
        $res=$con->query($sql);
        if(!$res){
            die($con->error);
        }
        # check that it is not the admin trying to delete himself
        if($res->num_rows){
            header('location:../FRONT/admin.php');
            exit;
        }

        if(isset($_POST['confirm'])&& ! empty($_POST['confirm'])){
            if($_POST['confirm']=='yes'){
                $why="i don't know";
                if(!delete_teach($teach_id,$why)){
                    die("error: $why");
                }
            }
            header("location:../FRONT/admin.php");
            exit;
        }
        $sql="SELECT `name` from `users` where `id`=$teach_id";
        $res=$con->query($sql);
        if(!$res || ! $res->num_rows){
            die("error!");
        }
        if($row=$res->fetch_assoc()){
            $name=$row['name'];
        }
    ?>
        <form action="drop_lang.php?teach_id=<?= $teach_id ?>" method="post">
            <div class="main">
                <div class="unit">
                    <label for="title">are you shour you want to delete <span style="color:#ca3d3d;"><?= $name ?></span> <br></label>
                    <button type="submit" name="confirm" style="background-color: green;" value="yes">Yes</button>
                    <button type="submit" name="confirm" style="background-color: red;" value="no">No</button>
                </div>
            </div>
        </form>
    <?php
    }else{
        print_r($_GET);
    }
    ?>
</body>

</html>