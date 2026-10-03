<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/public.css">
    <link rel="stylesheet" href="../CSS/asking_form.css">
    <link rel="stylesheet" href="../CSS/review.css">
    <link rel="stylesheet" href="../CSS/review_plus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>review</title>
</head>

<body>
    <header>
        <ul class="head">
            <a href="home.php">
                <li><i class="fas fa-home"></i></li>
            </a>
            <a href="review.php">
                <li><i class="fas fa-book"></i></li>
            </a>
            <a href="chat.php">
                <li><i class="fa-solid fa-comment"></i></li>
            </a>
            <a href="profile.php">
                <li><i class="fa-solid fa-user"></i></li>
            </a>
        </ul>
    </header>
    <div style="height: 40px;"></div>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['lang_id'])
    ) {
        $role_id = $_SESSION['role_id'];
        $user_id = $_SESSION['user_id'];
        if ($role_id == 2) {
    ?>
            <main>
                <div class="title">
                    <h3>card</h3>
                    <i class="fa-solid fa-square-plus" id="add"></i>
                </div>
                <div style="height:70px;"></div>
                <?php
                $method = $_SERVER["REQUEST_METHOD"];
                if ($method == "GET") {
                    $sql = "SELECT `name` , `desc`,`id` from `folder` where `stu_id`=$user_id;";
                    $con = db_connect();
                    if ($res = $con->query($sql)) {
                        while ($row = $res->fetch_assoc()) {
                            extract($row);
                ?>
                            <div class="container">
                                <a href="../BACK/drop_f.php?folder_id=<?= $id ?>&why=d">
                                    <i class="fas fa-trash" id="drop"></i>
                                </a>
                                <a href="../BACK/drop_f.php?folder_id=<?= $id ?>&why=e">
                                    <i class="fas fa-edit" id="edit"></i>
                                </a>
                                <h4>
                                    <a href="folder.php?folder_id=<?= $id ?>">
                                        <?= $name ?>
                                    </a>
                                </h4>
                                <p><?= $desc ?></p>
                            </div>
                    <?php
                        }
                    } else {
                        die($con->error);
                    }
                } elseif ($method == "POST") {
                    if (
                        isset($_POST['title']) && !empty($_POST['title'])
                        && isset($_POST['desc']) && !empty($_POST['desc'])
                    ) {
                        $title = valid_input($_POST['title']);
                        $desc = valid_input($_POST['desc']);
                        $sql = "INSERT INTO `folder`(`name`,`desc`,`stu_id`) values('$title','$desc',$user_id);";
                        if (db_connect()->query($sql)) {
                            header("location:review.php");
                        } else {
                            die("insert error");
                        }
                    } else {
                        header("location:review.php");
                    }
                }
            } elseif ($role_id == 1) {
                # allowed the admin to go back to admin dashbord
                if (isset($_SESSION['transition']) && $_SESSION['transition'] == 2) {
                    ?>
                    <a href="../BACK/back_as_admin.php" class="back_a" style="text-decoration: none;">Back</a>
                <?php
                }
                // teachers
                if (!is_supper_teacher($user_id)) {
                    // teacher but not admin
                ?>
                    <div class="center_child">
                        <h1 id="not_admins">this page is for admins :D </h1>
                        <a href="contact.php?from=teacher_id_togetEmail" id="contact_admin">contact admin</a>
                    </div>
        <?php
                } else {
                    // hirarchi teachers also
                }
            }
        } else {
            header('location:index.php');
        }
        ?>
            </main>
            <!--  for student to add a folder -->
            <form action="review.php" method="post">
                <div class="main_folders">
                    <div class="unit">
                        <i class="fas fa-times"></i>
                        <label for="title">folder title:</label>
                        <input type="text" name="title" id="title" required>
                        <label for="desc">description:</label>
                        <input type="text" name="desc" id="desc" maxlength="255" required>
                        <input type="submit" value="submit">
                    </div>
                </div>
            </form>
            <script>
                const x = document.querySelector('.fa-times');
                const main = document.querySelector('.main_folders');
                x.addEventListener('click', function() {
                    main.style.top = '100vh';
                });
                document.addEventListener('DOMContentLoaded', (event) => {
                    const add = document.getElementById('add');
                    if (add) {
                        add.addEventListener('click', function() {
                            // alert('yes');
                            main.style.top = '0vh';
                        });
                    }
                });
            </script>
            <script src="../JS/units.js"></script>
</body>

</html>