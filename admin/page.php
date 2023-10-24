<?php

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// $do = '';

// if(isset($_GET['do'])) {
//     $do = $_GET['do'];
// } else {
//     $do = 'Manage';
// }

// if the page is main page

if ($do == 'Manage') {
    echo 'you are in manage page';
    echo '<a href="page.php?do=Add">Add New Category +</a>';
    // echo '<a href="?do=Add">Add New Category +</a>';
}elseif ($do == 'Add') {
    echo 'you are in Add category page';
}elseif ($do == 'Insert') {
    echo 'you are in insert page';
}else {
    echo 'there is no page with this name';
}