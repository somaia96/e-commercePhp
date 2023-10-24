<?php
    session_start();
    $pageTitle = 'Members';
    if (isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') { // manage members page 
            // $value = "semsem";
            // $check = checkItem("Username","users",$value);
            // if($check == 1) {
            //     echo "helllllooooooooo";
            // }
                $query = '';
                if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
                    $query = 'AND RegStatus = 0';
                }
            // select all users except admin
            $stmt =$con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
            <tr>
                <td>#ID</td>
                <td>Username</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registred Date</td>
                <td>Control</td>
            </tr>
            <?php
                foreach($rows as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['UserID'] . "</td>";
                    echo "<td>" . $row['Username'] . "</td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td>" . $row['FullName'] . "</td>";
                    echo "<td>" . $row['Date'] . "</td>";
                    echo "<td>
                    <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn grebtn btn-success'><i class='fa fa-edit mr-1'></i>Edit</a>
                    <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn redbtn confirm btn-danger'><i class='fa fa-close mr-1'></i>Delete</a>";
                    if($row['RegStatus'] == 0) {
                        echo "<a 
                        href='members.php?do=Activate&userid=" . $row['UserID'] . "' 
                        class='btn ml-1 blubtn btn-info'>
                        <i class='fa fa-check'></i>Active</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            ?>
                </table>
        </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>
       <?php }elseif ($do == 'Add') { //add members page ?>
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form action="?do=Insert" method="POST">
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" autocomplete="off" class="form-control" required="required" placeholder="username to login into shop">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="password" name="password" required="required" autocomplete="new-password" class="form-control" placeholder="password must be hard & complex">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" required="required" class="form-control" placeholder="Email must be valid">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" required="required" name="full" class="form-control" placeholder="full name apper in your profile page">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" value="Add Member" class="btn btn-lg btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        <?php  
        }elseif ($do == 'Insert') { // insert member page
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Update Member</h1>";
                echo "<div class='container'>";
                // get vars from form
                $user   = $_POST['username'];
                $pass   = $_POST['password'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];
                $hashPass = sha1($_POST['password']);
                // validate the form
                $formErrors = array();
                if(strlen($user) < 4) {
                    $formErrors[]= 'username can not be less than <strong>4 character</strong>';
                }
                if(strlen($user) > 20) {
                    $formErrors[]= 'username can not be more than <strong>20 character</strong>';
                }
                if(empty($user)) {
                    $formErrors[]= 'username can not be <strong>empty</strong>';
                }
                if(empty($pass)) {
                    $formErrors[]= 'password can not be <strong>empty</strong>';
                }
                if(empty($email)) {
                    $formErrors[]= 'Email can not be <strong>empty</strong>';
                }
                if(empty($name)) {
                    $formErrors[]= 'Full Name can not be <strong>empty</strong>';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                    // echo $id . $user . $name . $email;
                    // update database with this info
                    // check if there is no errors update the data
                    if(empty($formErrors)) {
                        // check if user exist in database
                            $check = checkItem("Username","users",$user);
                            if($check == 1) {
                                $theMsg = '<div class="alert w-50 mx-auto my-3 alert-danger">Sorry This user is exist</div>';
                                redirectHome($theMsg, 'back');
                            }else {
                        // insert userinfo in database
                        $stmt = $con->prepare("INSERT INTO users(
                                                                Username, Password, Email, FullName, RegStatus, Date)
                                                VALUES (:zuser, :zpass, :zmail, :zname, 1, now())
                            "); // number 1 mean this user accepted already because the admin add him
                        $stmt->execute(array(
                            'zuser' => $user,
                            'zpass' => $hashPass,
                            'zmail' => $email,
                            'zname' => $name
                        ));
                    // is success
                    $theMsg = '<div class="alert w-50 mx-auto my-3 alert-success">' . $stmt->rowCount() . ' record Inserted</div>';
                    redirectHome($theMsg,'back');
                }
                }
            } else {
                $theMsg= "<div class='alert mx-auto w-50 my-3 alert-danger'>you can't browse this page directly</div>";
                redirectHome($theMsg);
            }
            echo '</div>';
            // echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['full']; 
    
    }elseif ($do == 'Edit') { // Edit Profile
            // echo 'edit for member ' . $_GET['userid']; 
            
            // if(isset($_GET['userid']) && is_numeric($_GET['userid'])) {
            //     echo intval($_GET['userid']);
            // } else {
            //     echo 0;
            // }
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            // echo $userid;
                    $stmt = $con->prepare("SELECT 
                                                *
                                        FROM 
                                                users 
                                        WHERE
                                                UserID = ? 
                                        LIMIT 1 ");
                $stmt->execute(array($userid));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();
                if ($count > 0) {
                //     echo 'good';
                // } else {
                //     echo 'no';
                // }
            ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" value="<?php echo $row['Username']; ?>" autocomplete="off" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                            <input type="password" name="newpassword" autocomplete="new-password" class="form-control" placeholder="let it empty if u do not want to change">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" required="required" value="<?php echo $row['Email']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" required="required" name="full" value="<?php echo $row['FullName']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <input type="submit" value="Save" class="btn btn-lg btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        <?php } else {
            $theMsg = '<div class="alert alert-danger w-50 my-3 mx-auto">there is no such id</div>';
            redirectHome($theMsg);
        }
        } elseif ($do == 'Update') { // update page
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // get vars from form
                $id     = $_POST['userid'];
                $user   = $_POST['username'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];
                // password trick
                $pass = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] :$pass = sha1($_POST['newpassword']);
                // validate the form
                $formErrors = array();
                if(strlen($user) < 4) {
                    $formErrors[]= 'username can not be less than <strong>4 character</strong>';
                }
                if(strlen($user) > 20) {
                    $formErrors[]= 'username can not be more than <strong>20 character</strong>';
                }
                if(empty($user)) {
                    $formErrors[]= 'username can not be <strong>empty</strong>';
                }
                if(empty($email)) {
                    $formErrors[]= 'Email can not be <strong>empty</strong>';
                }
                if(empty($name)) {
                    $formErrors[]= 'Full Name can not be <strong>empty</strong>';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                    // echo $id . $user . $name . $email;
                    // update database with this info
                    // check if there is no errors update the data
                    if(empty($formErrors)) {
                    $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));
                    // is success
                    $theMsg = '<div class="alert w-50 mx-auto my-3 alert-success">' . $stmt->rowCount() . ' record updated</div>';
                    redirectHome($theMsg , 'back');
                    }
            } else {
                $theMsg= "<div class='w-50 mx-auto my-3 alert alert-danger'>you can't browse this page directly</div>";
                redirectHome($theMsg);
            }
            echo '</div>';
        } elseif ($do =='Delete') { // delete member page
            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            // echo $userid;
                    // $stmt = $con->prepare("SELECT 
                    //                             *
                    //                     FROM 
                    //                             users 
                    //                     WHERE
                    //                             UserID = ? 
                    //                     LIMIT 1 ");
                $check = checkItem('userid', 'users', $userid);
                // echo $check;
                // $stmt->execute(array($userid));
                // $count = $stmt->rowCount();
                if ($check > 0) {
                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuserid");
                    $stmt->bindParam(":zuserid", $userid);
                    $stmt->execute();
                $theMsg = '<div class="alert alert-success mx-auto w-50 my-3">' . $stmt->rowCount() . ' record Deleted</div>';
                redirectHome($theMsg);
                } else {
                    $theMsg = '<div class="alert alert-danger mx-auto w-50">This ID Is Not Exist</div>';
                    redirectHome($theMsg);
                }
                echo "</div>";
        } elseif ($do == 'Activate') {
            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            // echo $userid;
                    // $stmt = $con->prepare("SELECT 
                    //                             *
                    //                     FROM 
                    //                             users 
                    //                     WHERE
                    //                             UserID = ? 
                    //                     LIMIT 1 ");
                $check = checkItem('userid', 'users', $userid);
                // echo $check;
                // $stmt->execute(array($userid));
                // $count = $stmt->rowCount();
                if ($check > 0) {
                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                    $stmt->execute(array($userid));
                $theMsg = '<div class="alert alert-success mx-auto w-50 my-3">' . $stmt->rowCount() . ' record Updated</div>';
                redirectHome($theMsg);
                } else {
                    $theMsg = '<div class="alert alert-danger mx-auto w-50">This ID Is Not Exist</div>';
                    redirectHome($theMsg);
                }
                echo "</div>";
        }
        include $tpl . 'footer.php';
    } else {
        // echo 'You are not allowed to view this page';
        header('Location: index.php');
        exit();
    }