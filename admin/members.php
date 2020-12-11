<?php
session_start();
$pageTitle = 'Members';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page
    if ($do == 'Manage') { //Manage Members Page 
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupId != 1");
        //execute The Statement
        $stmt->execute();
        //Assign To Variable
        $rows = $stmt->fetchAll();
?>
<h1 class="text-center">Manage Members</h1>
<div class="container mb-5">
 <div class="table-resposive">
  <table class="main-table text-center table table-bordered">
   <tr>
    <td>#ID</td>
    <td>Username</td>
    <td>@Email</td>
    <td>Full Name</td>
    <td>Registerd Date</td>
    <td>Control</td>
   </tr>
   <?php
                    foreach ($rows as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['UserId'] . '</td>';
                        echo '<td>' . $row['Username'] . '</td>';
                        echo '<td>' . $row['Email'] . '</td>';
                        echo '<td>' . $row['Fullname'] . '</td>';
                        echo '<td>' . ' ' . '</td>';
                        echo "
                        <td>
                        <a href='members.php?do=Edit&UserId=" . $row['UserId'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='members.php?do=Delete&UserId=" . $row['UserId'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>
                       </td>
                        ";
                        echo '</tr>';
                    }
                    ?>
  </table>
 </div>
 <a href='members.php?do=add' class="btn btn-primary"> <i class="fa fa-plus"></i> New Members</a></br>
</div>
<?php } elseif ($do == 'add') { ?>
<h1 class="text-center">Add New Member</h1>
<div class="container">
 <form class="form-horizontal" action="?do=Insert" method="POST">
  <!-- Start User Name Field  -->
  <div class="form-group row">
   <label class="col-sm-2 control-label">Username</label>
   <div class="col-sm-10 col-lg-8">
    <input type="text" class="form-control form-control-lg" name='username' autocomplate="off" required='required'
     placeholder="Username To Login Into Shop" />
   </div>
  </div>
  <!-- End User Name Field  -->
  <!-- Start Password Field  -->
  <div class="form-group row">
   <label class="col-sm-2 control-label">Password</label>
   <div class="col-sm-10 col-lg-8">
    <input type="password" class="password form-control form-control-lg" name='password' autocomplate='new-password'
     placeholder="Password Most Be Hard & Complex" required="required" />
    <i class="show-pass fa fa-eye fa-2x"></i>

   </div>
  </div>
  <!-- End Password Field  -->
  <!-- Start Email Field  -->
  <div class=" form-group row">
   <label class="col-sm-2 control-label">Email</label>
   <div class="col-sm-10 col-lg-8">
    <input type="email" class="form-control form-control-lg" name=' email' required='required'
     placeholder="Email Most Be Valid" />
   </div>
  </div>
  <!-- End Email Field  -->
  <!-- Start FullName Field  -->
  <div class="form-group row">
   <label class="col-sm-2 control-label">Full Name</label>
   <div class="col-sm-10 col-lg-8">
    <input type="text" class="form-control form-control-lg" name='full' required='required'
     placeholder="Full Name Apeer In Your Profile Page" />
   </div>
  </div>
  <!-- End FullName Field  -->
  <!-- Start Submit Field  -->
  <div class="form-group row">
   <div class="col-sm-offset-2 col-sm-10">
    <input type="submit" class="btn btn-primary btn-lg" value='Add Member' />
   </div>
  </div>
  <!-- End Submit Field  -->
 </form>
</div>
<?php } elseif ($do == "Insert") {
        //Insert Member Page
        // echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['full'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Insert Member</h1>';
            echo '<div class="container">';
            //Get Variable From The Form
            $user        = $_POST['username'];
            $pass        = $_POST['password'];
            $email       = $_POST['email'];
            $name        = $_POST['full'];
            $hashpass        = sha1($_POST['password']);
            //Validate The Form
            $formErrors = array();
            if (strlen($user) < 4 && strlen($user) > 0) {
                $formErrors[] = ' Username cant be <strong> less than 4 characters</strong>';
            }
            if (strlen($user) > 20) {
                $formErrors[] = ' Username cant be <strong> More than 20 characters</strong> ';
            }
            if (empty($user)) {
                $formErrors[] = ' Username Cant Be <strong> Empaty</strong> ';
            }
            if (empty($pass)) {
                $formErrors[] = ' password Cant Be <strong> Empaty</strong> ';
            }
            if (empty($name)) {
                $formErrors[] = ' FullName Cant Be <strong> Empaty</strong> ';
            }
            if (empty($email)) {
                $formErrors[] = ' Email Cant Be <strong> Empaty</strong>';
            }
            //Loop Into error Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
                redirectHome($error);
            }
            //check If Theres No Error Proceed The Update Operation
            if (empty($formErrors)) {
                //Check If username Exist In DataBase
                $Check = checkItems("Username", "users", $user);
                if ($Check == 1) {
                    echo "Sory This User Is Exist";
                } else {
                    //insert user Info In Database
                    $stmt = $con->prepare("INSERT INTO users (Username , Password , Email , Fullname)VALUES (:user,:pass,:email,:name)");
                    $stmt->execute(array(
                        'user' => $user,
                        'pass' => $hashpass,
                        'email' => $email,
                        'name' => $name,
                    ));
                    //Echo Success Message
                    echo '<div class="alert alert-success text-center">' . $stmt->rowCount() . 'Record Inserted</div>';
                }
            }
            echo '</div>';
        } else {
            redirectHome('Sory You Cant Browse This Page Directly');
        }
    } elseif ($do == 'Edit') {
        // Edit Page 

        //check If get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['UserId']) && is_numeric($_GET['UserId']) ? intval($_GET['UserId']) : 0;

        //Select All Data Depend on This Id
        $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");

        //execute query
        $stmt->execute(array($userid));

        //Fech The Data

        $row = $stmt->fetch();

        //The row Count
        $count = $stmt->rowCount();

        //If Thers such Id show The Form
        if ($stmt->rowCount() > 0) { ?>
<h1 class="text-center">Edit Member</h1>
<div class="container">
 <form class="form-horizontal" action="?do=Update" method="POST">
  <input type="hidden" name='userid' value='<?php echo $userid ?>'>
  <!-- Start User Name Field  -->
  <div class="form-group row">
   <label class="col-sm-2 control-label">Username</label>
   <div class="col-sm-10 col-lg-8">
    <input type="text" class="form-control form-control-lg" name='username' autocomplate="off" required='required'
     value='<?php echo $row["Username"] ?>' />
   </div>
  </div>
  <!-- End User Name Field  -->
  <!-- Start Password Field  -->
  <div class="form-group row">
   <label class="col-sm-2 control-label">Password</label>
   <div class="col-sm-10 col-lg-8">
    <input type="hidden" name='oldpassword' value='<?php echo $row["Password"] ?>'>
    <input type="password" class="form-control form-control-lg" name='newpassword' autocomplate='new-password'
     placeholder="Leave Blank If You Dont Want To Change " />

   </div>
  </div>
  <!-- End Password Field  -->
  <!-- Start Email Field  -->
  <div class=" form-group row">
   <label class="col-sm-2 control-label">Email</label>
   <div class="col-sm-10 col-lg-8">
    <input type="email" class="form-control form-control-lg" name=' email' value='<?php echo $row["Email"] ?>'
     required='required' />
   </div>
  </div>
  <!-- End Email Field  -->
  <!-- Start FullName Field  -->
  <div class="form-group row">
   <label class="col-sm-2 control-label">FullName</label>
   <div class="col-sm-10 col-lg-8">
    <input type="text" class="form-control form-control-lg" name='full' value='<?php echo $row["Fullname"] ?>'
     required='required' />
   </div>
  </div>
  <!-- End FullName Field  -->
  <!-- Start Submit Field  -->
  <div class="form-group row">
   <div class="col-sm-offset-2 col-sm-10">
    <input type="submit" class="btn btn-primary btn-lg" value='UPDATE' />
   </div>
  </div>
  <!-- End Submit Field  -->
 </form>
</div>
<?php } else {
            redirectHome('Theres No Such ID');
        }
    } elseif ($do == 'Update') { //Update Page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">';
            //Get Variable From The Form
            $id          = $_POST['userid'];
            $user        = $_POST['username'];
            $email       = $_POST['email'];
            $name        = $_POST['full'];

            //Password Trick
            $pass = '';
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            //Validate The Form
            $formErrors = array();
            if (strlen($user) < 4 && strlen($user) > 0) {
                $formErrors[] = ' Username cant be <strong> less than 4 characters</strong>';
            }
            if (strlen($user) > 20) {
                $formErrors[] = ' Username cant be <strong> More than 20 characters</strong> ';
            }
            if (empty($user)) {
                $formErrors[] = ' Username Cant Be <strong> Empaty</strong> ';
            }
            if (empty($name)) {
                $formErrors[] = ' FullName Cant Be <strong> Empaty</strong> ';
            }
            if (empty($email)) {
                $formErrors[] = ' Email Cant Be <strong> Empaty</strong>';
            }
            //Loop Into error Array And Echo It
            foreach ($formErrors as $error) {
                echo $error;
                redirectHome($error);
            }
            //check If Theres No Error Proceed The Update Operation
            if (empty($formErrors)) {
                //Update the Database With This Info
                $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , Fullname = ? , Password = ? WHERE UserId = ?");
                $stmt->execute(array($user, $email, $name, $pass, $id));
                //Echo Success Message
                redirectHome($stmt->rowCount() . 'Record Updated');
            }
        } else {
            echo 'Sory';
        }
        echo '</div>';
    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Member</h1>';
        echo '<div class="container">';
        //Delete Member Page
        //check If get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['UserId']) && is_numeric($_GET['UserId']) ? intval($_GET['UserId']) : 0;

        //Select All Data Depend on This Id
        $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");

        //execute query
        $stmt->execute(array($userid));


        //The row Count
        $count = $stmt->rowCount();

        //If Thers such Id show The Form
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("DELETE FROM users WHERE userId = :userid");
            $stmt->bindParam(":userid", $userid);
            $stmt->execute();
            redirectHome('Delete One Record', 5);
        } else {
            redirectHome('ID Not Exist');
        }
        echo '</div>';
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}