<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/congratulation.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>Congratulation</title>
</head>

<body onload="win()">
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_SESSION['less_id'])
        && isset($_GET['win']) && $_GET['win']==1
    ){
        $less_id=$_SESSION['less_id'];
    ?>
    <div class="video-container" onclick="win()">
        <video  muted id="bg-video">
            <source src="sources/cong.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <!-- <a href="Quiz.php"><i class="fas fa-arrow-left"></i></a> -->
        <h1>
            <span style="--i:1;">C</span>
            <span style="--i:2;">o</span>
            <span style="--i:3;">n</span>
            <span style="--i:4;">g</span>
            <span style="--i:5;">r</span>
            <span style="--i:6;">a</span>
            <span style="--i:7;">t</span>
            <span style="--i:8;">u</span>
            <span style="--i:9;">l</span>
            <span style="--i:10;">a</span>
            <span style="--i:11;">t</span>
            <span style="--i:12;">i</span>
            <span style="--i:13;">o</span>
            <span style="--i:14;">n</span>
        </h1>
        <h5>You have completed this lesson</h5>
        <div class="photo"></div>
        <h5 id="down">You have completed this lesson <h5>GOOD JOB!</h5>
        </h5>
        <a href="lesson.php?less_id=<?=$less_id?>" id="finish">Finish</a>
    </div>
    <?php
    }else{
        header('location:index.php');
    }
    ?>
    <script>
        function win(){
            // alert('i am winnig');
            const win = new Audio('../sounds/win.wav');
            setTimeout(function () {
                win.play();
            }, 50);
        }
    </script>
</body>

</html>