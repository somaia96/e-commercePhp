<?php


// set var to page title

function getTitle()
{
    global $pageTitle;
    if(isset($pageTitle))
    {
        echo $pageTitle;
    } else {
        echo 'default';
    }
}

/*
** v2.0
** Redirect Function [ This Function Accept Parameters ]
** $theMsg = echo the message [error | success |warning]
** $seconds = seconds before redirecting
$url
*/

function redirectHome($theMsg, $url=null, $seconds = 3) {
    if($url === null) {
        $url = 'index.php';
        $link = 'Home page';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
            $url =$_SERVER['HTTP_REFERER'];
            $link = 'previous page';
        // $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] :'index.php'; // back
        } else {
            $url = 'index.php';
            $link = 'Home page';
        }
    }

    echo $theMsg;
    echo "<div class='alert m-auto w-50 alert-info'>you will be redirected to $link after $seconds seconds</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

/*
** check items function
** function to check item in database [ function accept paramaters ]
** $select = the item to select [ ex: user, item, category]
** $from = the tabe to select from [ ex: users, items, categories]
** $value = the value of select [ ex: somaia, box, electronics]
*/

function checkItem($select, $from, $value) {
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}

/*
** count number of items function v1.0
** function to count number of items rows
** $item = the item to count
** $table = the table to choose from
*/

function countItems($item,$table) {

    global $con; // it won't work without this
    
    $stmt2 =$con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();

}

/*
** Get Latest Records Function v1.0
** Function To Get Latest Items From DDatabase [ Users , Items, Comments]
** $select = Field To Select
** $table = The Table To Choose From
** $order = the desc ordering // from Z to A
** $limit = Number Of Records To Get
*/

function getLatest($select,$table, $order,$limit =5) {

    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows =$getStmt->fetchAll();
    return $rows;
}