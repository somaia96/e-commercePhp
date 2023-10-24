<?php
    ob_start(); // problem header already sent
    session_start();
    if (isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard';
        include 'init.php';
        $numUsers = 5; // Number Of Latest Users
        $numItems = 5; // Number Of Latest items
        // echo "<pre>";
        // print_r(getLatest("*","users","UserID", 3));
        // echo "</pre>";
        $latestUsers = getLatest("*","users","UserID", $numUsers); // array // Latest items Array
        $latestItems = getLatest("*","items","item_ID", $numItems); // array // Latest items Array
        ?>
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                    Total Members
                    <span><a href="members.php"><?php echo countItems('UserID','users') ?></a></span>
                    </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                    Pending Members
                    <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus','users', 0) ?></a></span>
                    </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                    Total Items
                    <span><a href="items.php"><?php echo countItems('item_ID','items') ?></a></span>
                    </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                    Total Comments
                    <span>210</span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pad mt-3">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-users"></i>
                            Latest <?php echo $numUsers; ?> Register Users
                            <span class="pull-right toggle-info">
                            <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                foreach ($latestUsers as $user) {
                                    echo "<li>". $user['Username'] . "
                                    <a href='members.php?do=Edit&userid=" . $user['UserID'] . "'>
                                    <span class='btn btn-success pull-right grebtn'>
                                    <i class='fa fa-edit mr-1'></i>
                                    Edit";
                                if($user['RegStatus'] == 0) {
                                    echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' 
                                    class='btn pull-right mr-1 ml-1 blubtn btn-info'><i class='fa fa-check'></i>Active</a>";
                                } 
                                echo "</span></a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-tag"></i> Latest Item
                            <span class="pull-right toggle-info">
                            <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                foreach ($latestItems as $item) {
                                    echo "<li>". $item['Name'] . "<a 
                                    href='items.php?do=Edit&itemid=" . $item['item_ID'] . "'><span class='btn btn-success pull-right grebtn'>
                                    <i class='fa fa-edit mr-1'></i>
                                    Edit";
                    if($item['Approve'] == 0) {
                        echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' 
                        class='btn pull-right mr-1 ml-1 blubtn btn-info'><i class='fa fa-check'></i>Approve</a>";
                    }
                                echo "</span></a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <?php
        // print_r($_SESSION);
        include $tpl . 'footer.php';
    } else {
        // echo 'You are not allowed to view this page';
        header('Location: index.php');
        exit();
    }
    ob_end_flush();
    ?>