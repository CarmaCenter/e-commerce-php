<?php
session_start();
$noNavbar='';
$pageTitle = 'Login Admin';
if(isset($_SESSION['Username'])){
    header('Location:dashboard.php'); //Redirect To Dashboard page
    exit();
}
include "init.php";
//check If User Coming From HTTP Post Request

if($_SERVER['REQUEST_METHOD'] == 'POST'){
$username = $_POST['user'];
$password = $_POST['password'];
$hashedPass = sha1($password);

//check If The User Exist In Database
$stmt = $con->prepare("SELECT
                             Username, Password ,UserId
                       FROM 
                             users
                       WHERE
                             Username = ?
                       AND
                             Password = ?
                       AND
                             GroupId = 1
                       LIMIT 1");
$stmt ->execute (array($username, $hashedPass));
$row = $stmt->fetch();
$count = $stmt->rowCount();

//If count > 0 This Mean The Database Contain Record About This Username

if($count > 0){
    $_SESSION['Username'] = $username; //register session Name
    $_SESSION['ID'] = $row['UserId']; //Register session ID
    header('Location:dashboard.php'); //Redirect To Dashboard page
    exit();
}
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method='POST' class='login'>
 <h4 class='text-center'>Admin Login</h4>
 <input class='form-control' type="text" name='user' placeholder='Username' autocomplate='off' />
 <input class='form-control' type="password" name='password' placeholder='Password' autocomplate='new-password/'>
 <input class='btn btn-primary btn-block' type="submit" value='Login'>
</form>

<?php
include $tpl. "footer.php";
?>