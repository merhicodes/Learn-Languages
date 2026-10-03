<?php
require_once "../BACK/function.php";
if(
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
    && isset($_POST['folders'])
    && !empty($_POST['folders'])
    && $_POST['folders']!=-1
    && isset($_POST['sel_words'])
){
    $con=db_connect();
    $folders=$_POST['folders'];
    $words=$_POST['sel_words'];
    foreach($folders as $f_id){
        $stmt=$con->prepare("INSERT INTO `folder_word` (id,word_id,folder_id) values (null,?,$f_id);");
        foreach($words as $w_id){
            $stmt->bind_param("i",$w_id);
            $sql="SELECT id from `folder_word` where word_id=$w_id and folder_id=$f_id;";
            $exists=$con->query($sql);
            # check if the student has already add this word to this folder ?
            if(!$exists ||$exists->num_rows){
                continue;
            }
            if(!$stmt->execute()){
                die("insert error");
            }
        }
        header("location:../FRONT/vocabulary.php");
        exit;
    }
}else{
    // header("location:vocabulary.php");
    print_r($_POST);
}