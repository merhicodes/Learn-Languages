<?php
 require_once "../BACK/function.php";
 if (
     isset($_SESSION['user_id'])
     && isset($_SESSION['role_id'])
     && isset($_GET['less_id'])
     && !empty($_GET['less_id'])
     && isset($_GET['word_id'])
     && !empty($_GET['word_id'])
 ){
    $word_id=$_GET['word_id'];
    $less_id=$_GET['less_id'];
    $why="i don't know :)";
    if(!delete_word($word_id,$less_id,$why)){
        die("reason:[ $why ]");
    }
    // if(!delete_word($word_id,$less_id)){
    //     die("delete error");
    // }
    header("location:edit_less.php?less_id=$less_id");
    exit;
 }
 else{
    die("can't access to this page :(");
 }