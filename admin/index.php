<?php
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';
    if (isset($_SESSION['Username'])) {
        header('Location: dashboard.php'); // Redirect to dashboard page
    }
    include 'init.php';

// check if user coming from HTTP post request


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        $stmt = $con->prepare("SELECT 
                                        UserID, Username, Password
                                FROM
                                        users 
                                WHERE
                                        Username = ? 
                                AND 
                                        Password = ? 
                                AND 
                                        GroupID = 1
                                LIMIT 1 ");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        //  if count > 0 this mean the database contain record about this username

        if ($count > 0) {
            // print_r($row);
                $_SESSION['Username'] = $username; // Register session name
                $_SESSION['ID'] = $row['UserID'];
                header('Location: dashboard.php'); // Redirect to dashboard page
                exit();
        }
    }

?>


    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder="username" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    </form>


<?php include $tpl . 'footer.php'; ?>