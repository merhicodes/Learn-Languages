<?php
require_once "../BACK/function.php";
if (
    isset($_FILES['video']) && !empty($_FILES['video'])
    && isset($_FILES['pdf']) && !empty($_FILES['pdf'])
    && isset($_POST['title']) && !empty($_POST['title'])
    && isset($_POST['description']) && !empty($_POST['description'])
    && isset($_SESSION['lang_id']) && !empty($_SESSION['lang_id'])
    && isset($_SESSION['unit_id']) && !empty($_SESSION['unit_id'])
    && isset($_SESSION['lev']) && !empty($_SESSION['lev'])
) {
    $lang_id = $_SESSION['lang_id'];
    $unit_id = $_SESSION['unit_id'];
    $video = $_FILES['video'];
    $pdf = $_FILES['pdf'];
    $unit_id = $_SESSION['unit_id'];
    $level = $_SESSION['lev'];
    $con = db_connect();
    function go_back(): void
    {
        global $unit_id;
        header("location:../FRONT/lessons.php?unit_id=$unit_id");
        exit;
    }
    function alert($msg): void
    {
        // echo "<script>alert('$msg');</script>";
        // go_back();
        die($msg);
    }
    // valid inputs
    foreach ($_POST as $p) {
        if (strtolower(gettype($p)) == 'string')
            $p = valid_input($p);
    }
    extract($_POST);
    // getting the language name
    $lang_name = $con->query("SELECT `name` from `language`where `id`=$lang_id;");
    if (!$lang_name->num_rows) {
        // go_back();
        alert("no languages");
    }
    /* check files */
    $video_ext = pathinfo($video['name'], PATHINFO_EXTENSION);
    $is_pdf = strtolower(pathinfo($pdf['name'], PATHINFO_EXTENSION)) === 'pdf' && $pdf['size'] <= 5000000 && $pdf['error'] == 0;
    // go back for this [get video.*];
    $is_video = in_array(strtolower($video_ext), ['mp4', 'mov', 'wmv']) && $video['size'] <= 20000000 && $video['error'] == 0;
    if (!($is_pdf && $is_video)) {
        alert("invalid pdf/video");
    }
    # is_unique less name?
    $sql = "SELECT `title` from `lessons`
            where `title`like '$title'
            and  `level`=$level
            and  `unit_id`=$unit_id;";
    if ($res = $con->query($sql)) {
        if ($res->num_rows) {
            alert("not a unitque title");
        }
    } else {
        alert("can't check the title is unique?");
    }
    # prepare dir
    $lang_name = $lang_name->fetch_assoc()['name'];
    $dir = "../lessons/$lang_name/$title-$unit_id-$level";
    if (!is_dir($dir)) {
        if (!mkdir($dir, 777, true)) {
            alert("coudn't create dir");
        }
    } else {
        rmdir($dir);
    }
    $full_pdf = $dir . "/less_p.pdf";
    $full_video = $dir . "/less_v.$video_ext";
    move_uploaded_file($pdf['tmp_name'], $full_pdf);
    move_uploaded_file($video['tmp_name'], $full_video);
    # getting max less_id to insert into
    $sql = "SELECT max(`id`) as id  
            from `lessons`
            ";
    $less_id = 1; // supose there is no lessons
    $res = $con->query($sql);
    if ($res->num_rows) {
        $max_less_id = $res->fetch_assoc()['id'];
        $less_id = $max_less_id + 1;
        // echo "max_less_id :$max_less_id";
    }
    #insert in db
    $sql = "INSERT INTO `lessons`(`id`,`unit_id`,`level`,`video`,`pdf`,`title`,`description`) 
            VALUES               ($less_id,$unit_id,$level,'$full_video','$full_pdf','$title','$description');";
    if (!$con->query($sql)) {
        if (is_dir($dir)) {
            if (is_file($full_pdf)) {
                unlink($full_pdf);
            }
            if (is_file($full_video)) {
                unlink($full_video);
            }
            rmdir($dir);
        }
        alert("can't insert?");
    }
    # removing null key|value
    if (
        isset($_POST['key']) && !empty($_POST['key'])
        && isset($_POST['value']) && !empty($_POST['value'])
    ) {
        $key = valid_array($key);
        $value = valid_array($value);
        if (!empty($key) && !empty($value)) {
            $stmt = $con->prepare("INSERT INTO `words` (id,`key`,`value`,`less_id`) values(null,?,?,?);");
            for ($i = 0; $i < count($key); $i++) {
                $stmt->bind_param("ssi", $key[$i], $value[$i], $less_id);
                if (!$stmt->execute()) {
                    die("can't insert '" . $key[$i] . "'=>'" . $value[$i] . "' word ?");
                }
            }
            go_back();
        } else {
            die("no key | values");
        }
    }
    header("location:../FRONT/lessons.php?unit_id=$unit_id");
    exit;
} else {
    // alert("not isset");
    // print_r($_SESSION);
    // print_r($_FILES);
    die("where is key value?");
    print_r($_POST);
}
