<?php 

    ob_start();
    session_start();
    $pageTitle = 'Categories';
    if (isset($_SESSION['Username'])) {
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        

        
        if ($do == 'Manage') { // manage members page 
            $sort = 'ASC';
            $sort_array = array('ASC','DESC');
            if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)) {
                $sort = $_GET['sort'];
            }
            $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll(); ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="card">
                    <div class="card-header">
                    <i class='fa fa-edit'></i> Manage Categories
                        <div class="option pull-right">
                        <i class='fa fa-sort'></i> Ordering: [ 
                            <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a> | 
                            <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a> ] 
                            <i class='fa fa-eye'></i> View: [
                            <span class="active" data-view="full">Full</span> | 
                            <span>Classic</span> ]
                        </div>
                    </div>
                    <div class="py-2 px-0 card-body">
                        <?php
                            foreach($cats as $cat) {
                                echo "<div class='h-auto cat px-4 overflow-hidden position-relative mb-2'>";
                                echo "<div class='hidden-buttons position-absolute'>";
                                    echo "<a href='?do=Edit&catid=$cat[ID]' class='btn mr-1 btn-primary btn-sm'><i class='fa fa-edit'></i>Edit</a>";
                                    echo "<a href='?do=Delete&catid=$cat[ID]' class='confirm btn btn-danger btn-sm'><i class='fa fa-close'></i>Delete</a>";
                                    echo "</div>";
                                echo "<h3 role='button'>" . $cat['Name'] . "</h3>";
                                echo "<div class='full-view mt-2'>";
                                echo "<p>"; if($cat['Description'] == '') {echo 'This Category Has No Description';} else {echo $cat['Description'];} ; echo "<p>";
                                if($cat['Visibility'] == 1){echo "<span class='global visibility'><i class='fa fa-eye'></i> Hidden</span>";}
                                if($cat['Allow_Comment'] == 1){echo "<span class='global commenting'><i class='fa fa-close'></i> Comment Disable</span>";}
                                if($cat['Allow_Ads'] == 1){echo "<span class='global advertises'><i class='fa fa-close'></i> Ads Disable</span>";}
                                echo "</div>";
                                echo "</div>";
                                echo "<hr class='my-1'>";
                            }
                        ?>
                    </div>
                </div>
                <a class="my-3 btn btn-primary" href="?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
            </div>



<?php

        }elseif ($do == 'Add') { //add members page 
            ?>

                <h1 class="text-center">Add New Category</h1>

                <div class="container">
                    <form action="?do=Insert" method="POST">
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" autocomplete="off" class="form-control" required="required" placeholder="Name Of Category">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" placeholder="Descripe The Category">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                                <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                <input type="radio" id="vis-no" name="visibility" value="1">
                                <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked>
                                <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                <input type="radio" id="com-no" name="commenting" value="1">
                                <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked>
                                <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                <input type="radio" id="ads-no" name="ads" value="1">
                                <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <input type="submit" value="Add Category" class="btn btn-lg btn-primary">
                            </div>
                        </div>

                    </form>
                </div>

            <?php
        }elseif ($do == 'Insert') { // insert category page
            
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";
    
                // get vars from form

                $name      = $_POST['name'];
                $desc      = $_POST['description'];
                $order     = $_POST['ordering'];
                $visible   = $_POST['visibility'];
                $comment   = $_POST['commenting'];
                $ads       = $_POST['ads'];


                            $check = checkItem("Name","categories",$name);
                            if($check == 1) {
                                $theMsg = '<div class="alert w-50 mx-auto my-3 alert-danger">Sorry This Category is exist</div>';
                                redirectHome($theMsg, 'back');
                            }else {



                        // insert category info in database
                        $stmt = $con->prepare("INSERT INTO categories(
                                                                Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                                VALUES (:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)
                            "); // number 1 mean this user accepted already because the admin add him
                        $stmt->execute(array(
                            'zname'    => $name,
                            'zdesc'    => $desc,
                            'zorder'   => $order,
                            'zvisible' => $visible,
                            'zcomment' => $comment,
                            'zads'     => $ads
                        ));

                    // is success

                    $theMsg = '<div class="alert w-50 mx-auto my-3 alert-success">' . $stmt->rowCount() . ' record Inserted</div>';
                    redirectHome($theMsg,'back');
                }
                
            } else {
                $theMsg= "<div class='alert mx-auto w-50 my-3 alert-danger'>you can't browse this page directly</div>";
                redirectHome($theMsg, 'back');
            }

            echo '</div>';

        }elseif ($do == 'Edit') { // Edit Profile





            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // echo $userid;
            
                    $stmt = $con->prepare("SELECT 
                                                *
                                        FROM 
                                                categories 
                                        WHERE
                                                ID = ? 
                                        ");
                $stmt->execute(array($catid));
                $cat = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0) { ?>
                    
                <h1 class="text-center">Edit Category</h1>

                <div class="container">
                    <form action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid ?>">
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" value="<?php echo $cat['Name']; ?>" required="required" placeholder="Name Of Category">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" value="<?php echo $cat['Description']; ?>" placeholder="Descripe The Category">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="ordering" value="<?php echo $cat['Ordering']; ?>" class="form-control" placeholder="Number To Arrange The Categories">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){ echo 'checked';}?>>
                                <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                <input type="radio" id="vis-no" name="visibility" value="1" <?php if($cat['Visibility'] == 1){ echo 'checked';}?>>
                                <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){ echo 'checked';}?>>
                                <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                <input type="radio" id="com-no" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){ echo 'checked';}?>>
                                <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="" class="col-sm-2 col-form-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){ echo 'checked';}?>>
                                <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                <input type="radio" id="ads-no" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){ echo 'checked';}?>>
                                <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <input type="submit" value="Update Category" class="btn btn-lg btn-primary">
                            </div>
                        </div>

                    </form>
                </div>


        <?php } else {
            $theMsg = '<div class="alert alert-danger w-50 my-3 mx-auto">there is no such id</div>';
            redirectHome($theMsg);
        }
        } elseif ($do == 'Update') { // update page
            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // get vars from form

                $id       = $_POST['catid'];
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $order    = $_POST['ordering'];
                $visible  = $_POST['visibility'];
                $comment  = $_POST['commenting'];
                $ads      = $_POST['ads'];



                    $stmt = $con->prepare("UPDATE 
                                            categories 
                                           SET 
                                            Name = ? , 
                                            Description = ? , 
                                            Ordering = ? , 
                                            Visibility = ?  ,
                                            Allow_Comment = ? , 
                                            Allow_Ads = ? 
                                           WHERE 
                                            ID = ?
                                            ");
                    $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

                    // is success

                    $theMsg = '<div class="alert w-50 mx-auto my-3 alert-success">' . $stmt->rowCount() . ' record updated</div>';
                    redirectHome($theMsg , 'back');
                    

            } else {
                $theMsg= "<div class='w-50 mx-auto my-3 alert alert-danger'>you can't browse this page directly</div>";
                redirectHome($theMsg);
            }

            echo '</div>';
        }elseif ($do == 'Delete') {
            
            echo "<h1 class='text-center'>Delete Category</h1>";
            echo "<div class='container'>";

            
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;


                $check = checkItem('ID', 'categories', $catid);
            
                if ($check > 0) {
                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                    $stmt->bindParam(":zid", $catid);
                    $stmt->execute();

                $theMsg = '<div class="alert alert-success mx-auto w-50 my-3">' . $stmt->rowCount() . ' record Deleted</div>';
                redirectHome($theMsg, 'back');
                } else {
                    $theMsg = '<div class="alert alert-danger mx-auto w-50">This ID Is Not Exist</div>';
                    redirectHome($theMsg, 'back');
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