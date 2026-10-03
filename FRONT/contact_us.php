<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../CSS/contact_us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php
require_once "../BACK/function.php";
if (
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
) {
    // if the admin try to logout a teach_acount we stop him
    if(isset($_SESSION['transition']) && $_SESSION['transition']=2){
        header("location:../FRONT/home.php?c=1");
        exit;
    }
    ?>
    <div class="contact-container">
        <h2>Contact Us</h2>
        <form class="contact-form" action="../BACK/send_mail.php" method="POST">
            <!-- <div class="input-group">
                <label for="name"><i class="fas fa-user"></i> Name</label>
                <input type="text" id="name" name="name" required>
            </div> -->
            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="message"><i class="fas fa-comment"></i> Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php
}else{
    header("location:index.php");
    exit;
}
?>
</body>
</html>
