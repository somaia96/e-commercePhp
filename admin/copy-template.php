<?php 

    ob_start();
    session_start();
    $pageTitle = '';
    if (isset($_SESSION['Username'])) {
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        
        

        
        if ($do == 'Manage') { // manage members page 

        }elseif ($do == 'Add') { //add members page
            
        }elseif ($do == 'Insert') { // insert member page

        }elseif ($do == 'Edit') {
             // Edit Profile
        } elseif ($do == 'Update') {
             // update page
        }elseif ($do == 'Delete') {

        } elseif ($do == 'Activate') {

        }
        include $tpl . 'footer.php';

        } else {
            header('Location: index.php');
            exit();
        }

    ob_end_flush();
    ?>