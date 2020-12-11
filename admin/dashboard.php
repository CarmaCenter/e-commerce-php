<?php
session_start();
if(isset($_SESSION['Username'])){
    include 'init.php';
$pageTitle = 'Dashboard';
echo '<div class="text-center">Welcome To'. $pageTitle;
    include $tpl . 'footer.php';
}else{
    header('Location: index.php');
    exit();
}