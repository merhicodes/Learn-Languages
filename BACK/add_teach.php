<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add techer</title>
    <link rel="stylesheet" href="../CSS/units.css">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <style>
        body {
            background: linear-gradient(145deg, rgba(10, 182, 255), rgb(7, 1, 33));
            height: 100vh;
        }

        .main {
            top: 0vh;
        }

        label {
            text-align: center;
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
        && $_SESSION['role_id'] == 0
        && isset($_GET['id']) && !empty($_GET['id'])
    ) {
        $id=$_GET['id'];
        $admin_id=$_SESSION['user_id'];
        $con=db_connect();
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if(isset($_POST['confirm'])){
                $ans=$_POST['confirm'];
                if($ans=='yes'){
                    $sql="INSERT INTO `teacher` (`teach_id`,`admin_id`) values ($id,$admin_id);";
                    $res=$con->query($sql);
                    if(!$res){
                        die($con->error);
                    }
                }
                header('location:../FRONT/show_stu_to.php');
                exit;
            }else{
                die("unexpected erorr");
            }
        }else{
            $sql="SELECT `name` from `users` where `id`= $id;";
            $res=$con->query($sql);
            if(!$res || !$res->num_rows){
                die("no name !!");
            }
            $name=$res->fetch_assoc()['name']??"unknowed";
    ?>
        <form action="add_teach.php?id=<?= $id ?>" method="post">
            <div class="main">
                <div class="unit">
                    <label for="title">ARE YOU SHURE YOU WANT TO<br> ADD { <span style="color: red;"><?= $name ?></span> } as teacher</label>
                    <button type="submit" name="confirm" style="background-color: green;" value="yes">Yes</button>
                    <button type="submit" name="confirm" style="background-color: red;" value="no">No</button>
                </div>
            </div>
        </form>
    <?php
        }// else post
    }
    ?>
</body>

</html>