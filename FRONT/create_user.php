<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../CSS/sing_up.css">
</head>

<body>
    <div class="container">
        <div class="signup-form">
            <h2>Sign Up</h2>
            <form action="create_user.php" method="POST">
                <div class="input-group">
                    <label for="name">First Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="phoneNumber">Phone Number:</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required>
                </div>
                <div class="input-group">
                    <label for="lang">select language:</label>
                        <?php
                        require_once "../BACK/function.php";
                        // Check if form data has been sent
                        $conn = db_connect();
                        if (!$conn) {
                            die("connection error");
                        }
                        if (
                            isset($_POST['name'])
                            && isset($_POST['lang'])
                            && isset($_POST['lastname'])
                            && isset($_POST['email'])
                            && isset($_POST['password'])
                            && isset($_POST['phoneNumber'])
                        ) {

                            // Retrieve POST data
                            $name = $_POST['name'];
                            $lastname = $_POST['lastname'];
                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            $phoneNumber = $_POST['phoneNumber'];
                            $lang = $_POST['lang'];

                            // Check if user already exists
                            $sql = "SELECT * FROM users WHERE email = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $email);
                            if(!$stmt->execute()) header('location:index.php');
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                header('location:index.php');
                            } else {
                                // Insert new user
                                $sql = "INSERT INTO `users` (`name`, `last_name`, `email`, `pass`, `phone`) VALUES ('$name', '$lastname','$email','$password','$phoneNumber');";
                                $stmt = $conn->query($sql);
                                $ID = $conn->query("SELECT `ID` FROM `USERS` WHERE `EMAIL`like '$email';")->fetch_assoc()['ID'];
                                $sql = "INSERT INTO `STUDenT`(`stu_id`) VALUES(?);";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $ID);
                                if(!$stmt->execute()) header('location:index.php');
                                $currentDate = date('Y-m-d');
                                echo "<option>$currentDate</option>";
                                $lang_info=db_connect()->query("SELECT min(`id`)as mid from `units`where `lang_id` = $lang;")->fetch_assoc();
                                if(!$lang_info)   $lang_info=0; //die("lang_info error");//header('location:index.php');
                                $unit_id=$lang_info['mid'];
                                if(!$unit_id)  $unit_id=0;//die("unit_id null"); //header('location:index.php');
                                $less_id=db_connect()->query("SELECT MIN(`ID`) as mid FROM `LESSONS`WHERE `UNIT_ID`=$unit_id and `level`=1;")->fetch_assoc()['mid'];
                                if(!$less_id) $values="VALUES($ID, $lang,null,'$currentDate')";
                                else $values="VALUES($ID, $lang,$less_id,'$currentDate')";
                                db_connect()->query("INSERT INTO `user_lang` (`STU_ID`,`lang_id`,`less_id`,`date`) $values;");
                                header('location:index.php');
                            }

                            // Close connection
                            $stmt->close();
                            $conn->close();
                        } else {
                            echo "<select name='lang' id='lang'>";
                            $sql = "SELECT `id`,`name`FROM `LANGUAGE`;";
                            $lang = $conn->query($sql);
                            if ($lang->num_rows) {
                                while ($row = $lang->fetch_assoc()) {
                                    $lang_name = $row['name'];
                                    $lang_id = $row['id'];
                                    echo "<option value='$lang_id'>$lang_name</option>";
                                }
                            } else {
                                header('location:index.php');
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>
</body>

</html>