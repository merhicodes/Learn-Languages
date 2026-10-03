<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/edit_teach.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>edit teacher</title>
</head>

<body>

    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_GET['teach_id']) && !empty($_GET['teach_id'])
    ) {
        $con = db_connect();
        $teach_id = $_GET['teach_id'];
        $sql = "SELECT `name`,`last_name`,`email` from `users` where `id`=$teach_id;";
        $res = $con->query($sql);
        if (!$res || !$res->num_rows) {
        }
        $res = $res->fetch_assoc();
        if (!$res) {
            die("fetch error");
        }
        $teach_name = $res['name'] . " " . $res['last_name'];
        $teach_email = $res['email'];
    ?>
        <div class="container">
            <a href="admin.php"><i class="fas fa-arrow-left"></i></a>
            <h1>Teacher Admin Assignment</h1>
            <div class="teacher-info">
                <p><span>Name: </span><span id="teacher-name"><?= $teach_name ?></span></p>
                <p><span>Email: </span><span id="teacher-email"><?= $teach_email ?></span></p>
            </div>
            <form action="../BACK/edit_teach.php?teach_id=<?= $teach_id ?>" method="post">
                <fieldset>
                    <legend>Assign Admin</legend>
                    <div class="admin-list" id="admin-list">
                        <?php
                        ## getting the owner's id
                        $sql = "SELECT `teach_id` from `teacher` where `admin_id` is null;";
                        $res = $con->query($sql);
                        if (!$res || !$res->num_rows) {
                            die("no owner !!");
                        }
                        if ($admin_id = $res->fetch_assoc()) {
                            $admin_id = $admin_id['teach_id'];
                            # getting all teachers who they are admins on < than 5 sub_teachers
                            $sql = "SELECT u.name ,u.last_name,u.email,u.id
                            FROM users AS u 
                            WHERE u.id IN ( 
                                SELECT admin_id 
                                FROM teacher 
                                WHERE admin_id IS NOT NULL 
                                GROUP BY admin_id 
                                HAVING COUNT(teach_id) < 5 ) 
                            OR u.id NOT IN ( 
                                SELECT DISTINCT admin_id 
                                FROM teacher WHERE admin_id IS NOT NULL 
                            ); ";
                            $res = $con->query($sql);
                            if (!$res || !$res->num_rows) {
                                die("no sup_teachers");
                            }
                            while ($row = $res->fetch_assoc()) {
                                extract($row);
                                if ($id == $admin_id) {
                                    $last_name .= " (you)";
                                }
                                # to block him admin himself !
                                if($id !== $teach_id){
                        ?>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="teacher" value="<?= $id ?>">
                                        <span class="custom-radio"></span>
                                        <span class="name"><?= $name . " " . $last_name ?></span>
                                        <span class="email"><?= $email ?></span>
                                    </label>
                                </div>
                            <?php
                                }
                            } // else it continue
                            ?>
                    </div>
                </fieldset>
                <button type="submit" class="see-button">Edit</button>
                <button class="see-button"><a href="../BACK/impersonation.php?teach_id=<?=$teach_id?>">See</a></button>
            </form>
        </div>
<?php
                        } else {
                            die("can't fetch admin id ?"); // fetch admin
                        }
                    } else {
                        header("location:../FRONT/admin.php");
                        exit;
                    }
?>
</body>

</html>