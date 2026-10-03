<!DOCTYPE html>
<html lang="en">
<!-- add setting for admin -->
<!-- drop teach  -->
<!-- edit teach -->
<!-- Gmail -->
<!-- see function -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/lang_admin.css">
    <link rel="stylesheet" href="../CSS/teach_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>Admin</title>
</head>

<body>
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && $_SESSION['role_id'] == 0
    ) {
        $role_id = $_SESSION['role_id'];
        $user_id = $_SESSION['user_id'];
        $con = db_connect();
    ?>
        <div class="admin_container">
            <h3 class="switch" id="switch"><span>teachers</span></h3>
            <div class="rel" id="add"><i class='fa-solid fa-square-plus' data-for='lang' id="add"></i></div>
            <!-- dispaly languages for the admin -->
            <div class="lang" id="lang">
                <table class="dispaly_lang">
                    <thead>
                        <th>lang_name</th>
                        <th>teacher</th>
                        <th>student count</th>
                        <th>delete</th>
                        <th>edit</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT `id` ,`name`,`teach_id`
                            from `language`;";
                        $res1 = $con->query($sql);
                        if (!$res1 || !$res1->num_rows) {
                            go_back();
                        }
                        while ($row1 = $res1->fetch_assoc()) {
                            $teach_id = $row1['teach_id'] ?? "no";
                            if ($teach_id == "no") { // if the lang teach is null
                                $teach_name = "no body";
                            } else {
                                $sql2 = "SELECT `name`, `last_name` from `users` where `id`=$teach_id;";
                                $res2 = $con->query($sql2);
                                if (!$res2 || !$res2->num_rows) {
                                    die($con->error);
                                }
                                $row2 = $res2->fetch_assoc();
                                $teach_name = $row2['name'] . " " . $row2['last_name'];
                            }
                            $lang_id = $row1['id'];
                            $lang_name = $row1['name'];
                            $sql = "SELECT COUNT(*) as c FROM `USER_LANG` WHERE `LANG_ID`=$lang_id;";
                            $res_c = $con->query($sql);
                            if (!$res_c) {
                                go_back();
                            }
                            $stu_count = $res_c->fetch_assoc()['c'] ?? 0;
                            // $langues=
                        ?>
                            <tr>
                                <td><?= $lang_name ?></td>
                                <td><?= $teach_name ?></td>
                                <td><?= $stu_count ?></td>
                                <td><a href="../BACK/drop_lang.php?lang_id=<?= $lang_id ?>" class="btn delete">del</a></td>
                                <td><a href="../BACK/edit_lang.php?lang_id=<?= $lang_id ?>" class="btn edit">edi</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <!--  create a language by admin -->
                <div class="form-container " id="lang_form">
                    <form action="../BACK/add_lang.php" method="GET">
                        <table class="user-selection-table">
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="lang_name" class="selection-label">Lang Name</label>
                                    </td>
                                    <td class="name_td"><input type="text" id="name" name="lang_name" value="<?= $lang_name ?>" class="lang_name" /></td>
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
                                while ($row = $no_lang->fetch_assoc()) {
                                    $teach_id = $row['id'];
                                    $teach_name = $row['name'];
                                    $teach_email = $row['email'];
                                ?>
                                    <tr>
                                        <td>
                                            <input type="radio" id="option1" name="user_selection" value="<?= $teach_id ?>" class="hidden-radio">
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
                                    go_back();
                                }
                                if ($admin = $admin->fetch_assoc()) {
                                    $admin_name = $admin['name'];
                                    $admin_email = $admin['email'];
                                    $admin_id = $admin['id'];
                                ?>
                                    <tr>
                                        <td>
                                            <input type="radio" id="option5" name="user_selection" value="<?= $admin_id ?>" class="hidden-radio">
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
            </div>
            <!-- display teachers for the admin -->
            <div class="teach" id="teach" style="display:none">
                <table class="dispaly_lang">
                    <thead>
                        <th>Teacher_name</th>
                        <th>Language</th>
                        <th>Admin</th>
                        <th>Delete</th>
                        <th>Edit</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT t.id as id,l.name as l_name,t.name as t_name,t.last_name as last_name
                        from language as l , users as t
                        where l.teach_id=t.id ;";
                        $res = $con->query($sql);
                        if (!$res || !$res->num_rows) {
                            go_back();
                        }
                        while ($row = $res->fetch_assoc()) {
                            $teach_id = $row['id'];
                            $lang_name = $row['l_name'];
                            $teach_name = $row['t_name'] . " " . $row['last_name'];
                            $sql = "SELECT COUNT(*) as c FROM `USER_LANG` WHERE `LANG_ID`=$lang_id;";
                            $res_c = $con->query($sql);
                            if (!$res_c) {
                                go_back();
                            }
                            $sql = "SELECT admin_u.name AS `admin_name`
                                    FROM teacher AS t
                                    LEFT JOIN users AS u ON t.teach_id = u.id
                                    LEFT JOIN users AS admin_u ON t.admin_id = admin_u.id
                                    where t.teach_id = $teach_id;
                                  ";
                            $res_a = $con->query($sql);
                            if (!$res_a) {
                                go_back();
                            }
                            if ($row_a = $res_a->fetch_assoc()) {
                                $admin = $row_a['admin_name'] ?? 'you';
                            } else {
                                go_back();
                            }
                            $del_link = "drop_teach.php?teach_id=$teach_id";
                            $edi_link = "edit_teach.php?teach_id=$teach_id";
                            $delete = "del";
                            $edit = "edit";
                            // to set the del to log out 
                            //  and the edit to edit prof
                            if ($admin == 'you') {
                                $del_link = "logout.php";
                                $edi_link = "editprofile.php?teach_id=$teach_id";
                                $delete = "log out";
                                $edit = "edit";
                            }

                        ?>
                            <tr>
                                <td><?= $teach_name ?></td>
                                <td><?= $lang_name ?></td>
                                <td><?= $admin ?></td>
                                <td><a href="../BACK/<?= $del_link ?>" class="btn delete"><?= $delete ?></a></td>
                                <td><a href="<?= $edi_link ?>" class="btn edit"><?= $edit ?></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- manage a sub teacher  by an admin or sup teacher-->
        <div class="container">
            <div class="hierarchy-section">
                <h3>Teacher Hierarchy</h3>

            </div>
        </div>
        <script>
            const bh = document.getElementById('switch');
            const add = document.getElementById('add');
            const rot = add.firstElementChild.style;
            const lang = document.getElementById('lang');
            const teach = document.getElementById('teach');
            const lang_form = document.getElementById('lang_form');
            // const teach_form = document.getElementById('teach_form');

            bh.addEventListener('click', function() {
                const bhs = bh.firstElementChild;
                const txt = bhs.textContent;
                if (txt == 'teachers') {
                    bhs.textContent = 'languages';
                    lang.style.display = 'none';
                    teach.style.display = 'flex';
                    add.onclick = function() {
                        window.location.href = "show_stu_to.php";
                    } //top_teach; // Assign function reference
                } else {
                    bhs.textContent = 'teachers';
                    lang.style.display = 'flex';
                    teach.style.display = 'none';
                    add.onclick = top_lang; // Assign function reference
                }
            });

            function top_lang() {
                const top_l = lang_form.style;
                if (!top_l.top) {
                    top_l.top = '100vh'; // Initialize to a default value
                }

                if (top_l.top === '0vh') {
                    // console.log('100vh');
                    lang_form.style.top = '100vh';
                } else {
                    // console.log('0vh');
                    lang_form.style.top = '0vh';
                }
            }

            // function top_teach() {
            //     console.log("we top teach");
            // }
        </script>
    <?php
    } else {
        go_back();
    }
    function go_back()
    {
        // header("location:index.php");
        // exit;
        print_r($_SESSION);
    }
    ?>
</body>

</html>