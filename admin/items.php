<?php 
    ob_start();
    session_start();
    $pageTitle = 'Items';
    if (isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') { // manage members page
        $stmt =$con->prepare("SELECT items.* , categories.Name AS category_name ,users.Username FROM items
        INNER JOIN categories ON categories.ID = items.Cat_ID
        INNER JOIN users ON users.UserID = items.Member_ID");
        $stmt->execute();
        $items = $stmt->fetchAll();
    ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
        <tr>
            <td>#ID</td>
            <td>Name</td>
            <td>Description</td>
            <td>Price</td>
            <td>Adding Date</td>
            <td>Category</td>
            <td>Username</td>
            <td>Control</td>
        </tr>
        <?php
            foreach($items as $item) {
                echo "<tr>";
                echo "<td>" . $item['item_ID'] . "</td>";
                echo "<td>" . $item['Name'] . "</td>";
                echo "<td>" . $item['Description'] . "</td>";
                echo "<td>" . $item['Price'] . "</td>";
                echo "<td>" . $item['Add_Date'] . "</td>";
                echo "<td>" . $item['category_name'] . "</td>";
                echo "<td>" . $item['Username'] . "</td>";
                echo "<td>
                <a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' 
                class='btn grebtn btn-success'><i class='fa fa-edit mr-1'></i>Edit</a>
                <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn redbtn confirm btn-danger'><i class='fa fa-close mr-1'></i>Delete</a>";
                if($item['Approve'] == 0) {
                    echo "<a 
                    href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' 
                    class='btn ml-1 blubtn btn-info'>
                    <i class='fa fa-check'></i>Approve</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
        ?>
            </table>
    </div>
        <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
    </div>
    <?php
        }elseif ($do == 'Add') { //add members page ?>
                <h1 class="text-center">Add New Item</h1>
                <div class="container">
                    <form action="?do=Insert" method="POST">
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name"class="form-control" 
                                required="required"
                                placeholder="Name Of Item">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description"class="form-control" 
                                placeholder="Description Of Item">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Price</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="price"class="form-control" 
                                required="required" placeholder="Price Of Item">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Country</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="country"class="form-control" 
                                required="required" placeholder="Country Of Made">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="status">
                                    <option value="0">...</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">Old</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Member</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="member">
                                    <option value="0">...</option>
                                    <?php  
                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user) {
                                            echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="category">
                                    <option value="0">...</option>
                                    <?php  
                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats=$stmt2->fetchAll();
                                        foreach($cats as $cat) {
                                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <input type="submit" value="Add Item" 
                                class="btn btn-sm btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            <?php
        }elseif ($do == 'Insert') { // insert member page
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Insert Item</h1>";
                echo "<div class='container'>";
                // get vars from form
                $name      = $_POST['name'];
                $desc      = $_POST['description'];
                $price     = $_POST['price'];
                $country   = $_POST['country'];
                $status    = $_POST['status'];
                $member    = $_POST['member'];
                $cat       = $_POST['category'];
                // validate the form
                $formErrors = array();
                if(empty($country)) {
                    $formErrors[]= 'Country can not be <strong>empty</strong>';
                }
                if(empty($price)) {
                    $formErrors[]= 'Price can not be <strong>empty</strong>';
                }
                if(empty($desc)) {
                    $formErrors[]= 'Description can not be <strong>empty</strong>';
                }
                if(empty($name)) {
                    $formErrors[]=  'Name can not be <strong>empty</strong>';
                }
                if($status == 0) {
                    $formErrors[]= 'You Must Choose The <strong>Status</strong>';
                }
                if($member == 0) {
                    $formErrors[]= 'You Must Choose The <strong>Member</strong>';
                }
                if($cat == 0) {
                    $formErrors[]= 'You Must Choose The <strong>Category</strong>';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                    // update database with this info
                    // check if there is no errors update the data
                    if(empty($formErrors)) {
                        // insert userinfo in database
                        $stmt = $con->prepare("INSERT INTO items(
                                                                Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
                                                VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)
                            "); // number 1 mean this user accepted already because the admin add him
                        $stmt->execute(array(
                            'zname'    => $name,
                            'zdesc'    => $desc,
                            'zprice'   => $price,
                            'zcountry' => $country,
                            'zstatus'  => $status,
                            'zmember'  => $member,
                            'zcat'     => $cat
                        ));
                    // is success
                    $theMsg = '<div class="alert w-50 mx-auto my-3 alert-success">' . $stmt->rowCount() . ' record Inserted</div>';
                    redirectHome($theMsg,'back');
                    }
                    } else {
                        $theMsg= "<div class='alert mx-auto w-50 my-3 alert-danger'>you can't browse this page directly</div>";
                        redirectHome($theMsg);
                    }
                    echo '</div>';
        }elseif ($do == 'Edit') { // Edit Profile
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            // echo $userid;
                    $stmt = $con->prepare("SELECT 
                                                *
                                        FROM 
                                                items 
                                        WHERE
                                                item_ID = ? 
                                        ");
                $stmt->execute(array($itemid));
                $item = $stmt->fetch();
                $count = $stmt->rowCount();
                if ($count > 0) { ?>
                    
                <h1 class="text-center">Edit Item</h1>
<div class="container">
    <form action="?do=Update" method="POST">
    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="name"class="form-control" 
                required="required"
                placeholder="Name Of Item"
                value="<?php echo $item['Name']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="description"class="form-control" 
                placeholder="Description Of Item"
                value="<?php echo $item['Description']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="price"class="form-control" 
                required="required" placeholder="Price Of Item"
                value="<?php echo $item['Price']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Country</label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="country"class="form-control" 
                required="required" placeholder="Country Of Made"
                value="<?php echo $item['Country_Made']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10 col-md-6">
                <select name="status">
                    <option value="1" <?php if($item['Status']==1){echo "selected";} ?>>New</option>
                    <option value="2" <?php if($item['Status']==2){echo "selected";} ?>>Like New</option>
                    <option value="3" <?php if($item['Status']==3){echo "selected";} ?>>Used</option>
                    <option value="4" <?php if($item['Status']==4){echo "selected";} ?>>Old</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Member</label>
            <div class="col-sm-10 col-md-6">
                <select name="member">
                    <?php  
                        $stmt = $con->prepare("SELECT * FROM users");
                        $stmt->execute();
                        $users=$stmt->fetchAll();
                        foreach($users as $user) {
                            echo "<option value='" . $user['UserID'] . "'"; 
                            if($item['Member_ID']==$user['UserID']){echo 'selected';} 
                            echo ">" . $user['Username'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10 col-md-6">
                <select name="category">
                    <?php  
                        $stmt2 = $con->prepare("SELECT * FROM categories");
                        $stmt2->execute();
                        $cats=$stmt2->fetchAll();
                        foreach($cats as $cat) {
                            echo "<option value='" . $cat['ID'] . "'";
                            if($item['Cat_ID']==$cat['ID']){echo 'selected';} 
                            echo ">" . $cat['Name'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
                <input type="submit" value="Save Item" 
                class="btn btn-sm btn-primary">
            </div>
        </div>
    </form>
</div>
        <?php } else {
            $theMsg = '<div class="alert alert-danger w-50 my-3 mx-auto">there is no such id</div>';
            redirectHome($theMsg);
        }
        } elseif ($do == 'Update') {  // update page
            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // get vars from form
                $id        = $_POST['itemid'];
                $name      = $_POST['name'];
                $desc      = $_POST['description'];
                $price     = $_POST['price'];
                $country   = $_POST['country'];
                $status    = $_POST['status'];
                $member    = $_POST['member'];
                $cat       = $_POST['category'];
                // validate the form
                $formErrors = array();
                if(empty($country)) {
                    $formErrors[]= 'Country can not be <strong>empty</strong>';
                }
                if(empty($price)) {
                    $formErrors[]= 'Price can not be <strong>empty</strong>';
                }
                if(empty($desc)) {
                    $formErrors[]= 'Description can not be <strong>empty</strong>';
                }
                if(empty($name)) {
                    $formErrors[]=  'Name can not be <strong>empty</strong>';
                }
                if($status == 0) {
                    $formErrors[]= 'You Must Choose The <strong>Status</strong>';
                }
                if($member == 0) {
                    $formErrors[]= 'You Must Choose The <strong>Member</strong>';
                }
                if($cat == 0) {
                    $formErrors[]= 'You Must Choose The <strong>Category</strong>';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                    // echo $id . $user . $name . $email;
                    // update database with this info
                    // check if there is no errors update the data
                    if(empty($formErrors)) {
                    $stmt = $con->prepare("UPDATE 
                                                items 
                                            SET 
                                                Name = ? , 
                                                Description = ? , 
                                                Price = ? , 
                                                Country_Made = ? , 
                                                Status = ? , 
                                                Cat_ID = ? , 
                                                Member_ID = ? 
                                            WHERE 
                                                item_ID = ?");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $id));
                    // is success
                    $theMsg = '<div class="alert w-50 mx-auto my-3 alert-success">' . $stmt->rowCount() . ' record updated</div>';
                    redirectHome($theMsg , 'back');
                    }
            } else {
                $theMsg= "<div class='w-50 mx-auto my-3 alert alert-danger'>you can't browse this page directly</div>";
                redirectHome($theMsg);
            }
            echo '</div>';
        }elseif ($do == 'Delete') {
            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                $check = checkItem('item_ID', 'items', $itemid);
                if ($check > 0) {
                    $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
                    $stmt->bindParam(":zid", $itemid);
                    $stmt->execute();
                $theMsg = '<div class="alert alert-success mx-auto w-50 my-3">' . $stmt->rowCount() . ' record Deleted</div>';
                redirectHome($theMsg, 'back');
                } else {
                    $theMsg = '<div class="alert alert-danger mx-auto w-50">This ID Is Not Exist</div>';
                    redirectHome($theMsg);
                }
                echo "</div>";
        } elseif ($do == 'Approve') {
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
                $check = checkItem('item_ID', 'items', $itemid);
                if ($check > 0) {
                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
                    $stmt->execute(array($itemid));
                $theMsg = '<div class="alert alert-success mx-auto w-50 my-3">' . $stmt->rowCount() . ' record Updated</div>';
                redirectHome($theMsg , 'back');
                } else {
                    $theMsg = '<div class="alert alert-danger mx-auto w-50">This ID Is Not Exist</div>';
                    redirectHome($theMsg);
                }
                echo "</div>";
        }
        include $tpl . 'footer.php';
        } else {
            header('Location: index.php');
            exit();
        }
    ob_end_flush();
    ?>