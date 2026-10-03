<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/public.css">
    <link rel="stylesheet" href="../CSS/base_chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>chat</title>
    <style>
        /* 
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            border: 2px solid #00aced;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 172, 237, 0.5);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            margin: 0;
        }

        .blue-highlight {
            color: #00aced;
        }
         */
    </style>
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
    <?php
    require_once "../BACK/function.php";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
    ) {
        # allowed the admin to go back to admin dashbord
        if (isset($_SESSION['transition']) && $_SESSION['transition'] == 2) {
    ?>
            <a href="../BACK/back_as_admin.php" class="back_a" style="text-decoration: none;">Back</a>
    <?php
        }
    } else {
        header("location:index.php");
        exit;
    }
    ?>
    <div class="container">
        <h1 class="blue-highlight">This Page is Under Development</h1>
        <p>We are working hard to bring you the best experience.</p>
    </div>


    <script src="public.js"></script>
</body>

</html>