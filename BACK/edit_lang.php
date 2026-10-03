<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/lang_admin.css">
    <title>edit lang</title>
    <style>
        .form-container {
            top: 0vh;
        }
        #log_out{
            color: white;
        }
    </style>
</head>

<body>
<a href="logout.php" id="log_out">log out</a>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_GET['lang_id']) && !empty($_GET['lang_id'])
    ) {
        $con = db_connect();
        $lang_id = $_GET['lang_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['user_selection']) && !empty($_POST['user_selection'])) {
                // update
                $teach_id = $_POST['user_selection'];
                $sql = "UPDATE `language` set `teach_id`=$teach_id where `id`=$lang_id;";
                if (!$con->query($sql)) {
                    die("$con->error");
                }
                // echo "teach_id`=$teach_id where `id`=$lang_id;";
                header('location:../FRONT/admin.php');
                exit;
            } else {
                print_r($_POST);
            }
        } else {
            // echo "lang_id";
            // exit;
            $lang_name = "default";
            $sql = "SELECT `name`,`teach_id` from `language` where `id`=$lang_id;";
            $res = $con->query($sql);
            if (!$res || !$res->num_rows) {
                die("an error");
            }
            $lang_name = $res->fetch_assoc();
            $main_id = $lang_name['teach_id']?? 0;
            $lang_name = $lang_name['name'];
            if (!$lang_name) { // is null
                die("another error :)");
            }
    ?>
            <div class="form-container " id="lang_form">
                <form action="edit_lang.php?lang_id=<?=$lang_id?>" method="POST">
                    <table class="user-selection-table">
                        <tbody>
                            <tr>
                                <td>
                                    <label for="lang_name" class="selection-label">Lang Name</label>
                                </td>
                                <td class="name_td"><input type="text" id="name" name="lang_name" value="<?= $lang_name ?>" class="lang_name" readonly /></td>
                            </tr>
                            <?php
                            $sql = "  SELECT t.teach_id as id, u.name,u.email
                                          FROM teacher AS t
                                          JOIN users AS u ON t.teach_id = u.id
                                          LEFT JOIN language AS l ON t.teach_id = l.teach_id
                                          WHERE l.teach_id IS NULL;
                                        ";
                            $no_lang = $con->query($sql);
                            if (!$no_lang) {
                                go_back();
                            }
                            $checked = "";
                            $check_admin = "";
                            while ($row = $no_lang->fetch_assoc()) {
                                $teach_id = $row['id'];
                                $teach_name = $row['name'];
                                $teach_email = $row['email'];
                                if ($teach_id == $main_id) {
                                    $checked = "checked";
                                }
                            ?>
                                <tr>
                                    <td>
                                        <input type="radio" id="option1" name="user_selection" value="<?= $teach_id ?>" class="hidden-radio" <?php echo $checked ?> />
                                        <label for="option1" class="selection-label"><?= $teach_name ?? "me" ?></label>
                                    </td>
                                    <td class="email-cell"><?= $teach_email ?></td>
                                </tr>
                            <?php
                            }
                            $sql = "SELECT u.name,u.email,u.id from users as u , teacher as t
                                      where u.id = t.teach_id
                                      and   t.admin_id is null;";
                            $admin = $con->query($sql);
                            if (!$admin) {
                                // go_back();
                                die("not admin");
                            }
                            if ($admin = $admin->fetch_assoc()) {
                                $admin_name = $admin['name'];
                                $admin_email = $admin['email'];
                                $admin_id = $admin['id'];
                                if (!$checked) {
                                    $check_admin = "checked";
                                }
                            ?>
                                <tr>
                                    <td>
                                        <input type="radio" id="option5" name="user_selection" value="<?= $admin_id ?>" class="hidden-radio" <?php echo $check_admin ?> />
                                        <label for="option5" class="selection-label"><?= $admin_name ?></label>
                                    </td>
                                    <td class="email-cell"><?= $admin_email ?> (You)</td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="2">
                                    <button type="submit" class="submit-button">Submit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
    <?php
        }
    }else{
        die("<h1 style='color:#fff;'>not log in<h1>");
    }
    ?>
</body>

</html>