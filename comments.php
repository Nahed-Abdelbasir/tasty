<?php

ob_start();
session_start();

$pageName = "Comments";

include_once "init.php";
    
if(isset($_GET["do"])){
        $do = $_GET["do"];
    }else{
        $do = "Manage";
    }


    if($do == "Manage"){ // manage page 

?>

<div class="container">
    <h2 class="text-center">Comments</h2>

    <!-------------------- start comments ---------------------->
        <div class="comment-data">
            
            <?php 
              

                $stmt = $db->prepare("SELECT 
                                                comments.* , users.name AS username , users.avatar AS user_avatar , products.name AS product_name, products.avatar AS product_avatar, products.price AS price
                                     FROM 
                                                comments 
                                     INNER JOIN
                                                users
                                     ON
                                                comments.user_id = users.userID
                                     INNER JOIN
                                                products
                                     ON
                                                comments.pro_id = products.pID 

                                     WHERE 
                                                status = 1          
                                     ORDER BY  
                                                cID  DESC 
                                                LIMIT 10");


                $stmt->execute();
                $allComments = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if($count > 0){
                    foreach($allComments as $comments){
            ?>
            
            <div class="row data-show">
                <div class="col-sm-9">
                    <div class="user-attr">
                        <div>
                            <?php 
                            if(empty($comments["user_avatar"])){ ?>
                           <img class="user-img" src="layout/images/user.jpg" alt="userimage" />

                            <?php

                            }else{
                                ?>
                                <img class="user-img" src="admin/uploads/avatars/<?php echo $comments['user_avatar'] ; ?>" alt="userimage" />
                           <?php
                            }
                           ?>
                        </div>
                        <span><?php echo $comments["username"] ;?></span>
                    </div>
                    <div class="user-comment"><?php echo $comments["comment"] ;?></div>
                </div>
                <div class="col-sm-3">
                   <div class="comm-info">
                       <a <?php if(isset($_SESSION['user'])){?> href="comments.php?do=Add&proid=<?php echo $comments["pro_id"]?>" <?php }else{?> href="login.php" <?php }?> >
                           <div>

                                <?php 
                                if(empty($comments["product_avatar"])){ ?>
                               <img src="layout/images/m1.jpg" alt="image" />

                                <?php

                                }else{
                                ?>
                                <img src="admin/uploads/products/<?php echo $comments['product_avatar'] ; ?>" alt="image" />
                               <?php
                                }
                                ?>

                            </div>
                            <span><strong><?php echo $comments['price'] ?></strong></span>
                            <h5><?php echo $comments['product_name'] ?></h5>
                       </a>
                    </div>
                </div>
                       <?php
                          if(isset($_SESSION['user'])){
                              if($comments["user_id"] == $_SESSION["id"]){
                             ?>
                                <a href="comments.php?do=Edit&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="comments.php?do=Delete&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-danger confirm"><i class="fas fa-times"></i> Delete</a>
                           <?php
                             }
                          }          
                         
                       ?>
                <div class="comm-date"><span>comment date : </span><?php echo $comments['date'] ;?></div>
            </div>
            <?php
                    }
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }
            ?>
        </div>
        <!--------------------- end comments ----------------------->

</div>

<?php
    }else if($do == "Add"){// add page   ?>

         <div class="container">
            <h2 class="text-center">Add Comments</h2>
        
    <?php
             
        if(isset($_GET["proid"]) && is_numeric($_GET["proid"]) ){
            $proid = intval ($_GET["proid"]);
        }else{
            $proid= 0 ;
        }
        
        
            
            $stmt = $db->prepare("SELECT 
                                        comments.* , users.name AS username ,users.avatar AS user_avatar , products.name AS name, products.avatar AS avatar ,products.price AS price , products.description AS description
                             FROM 
                                        comments 
                             INNER JOIN
                                        users
                             ON
                                        comments.user_id = users.userID
                             INNER JOIN
                                        products
                             ON
                                        comments.pro_id = products.pID 

                             WHERE 
                                        comments.pro_id=? 
                             AND 
                                        status = 1          
                             ORDER BY  
                                        cID  DESC ");
        
        

            $stmt->execute(array($proid));
        
        
            if($stmt->rowCount() > 0){
                $row = $stmt->fetch();
            }else{
                
                $stmt = $db->prepare("SELECT * FROM products WHERE pID = ? ");
                $stmt->execute(array($proid));
                
                $row = $stmt->fetch();
                
                $row['pro_id'] = $row['pID'];
            }
             
          
            
            ?>
             <div class="comment-edit">
                 <div class="pro-info">
                        <div>

                            <?php 
                            if(empty($row["avatar"])){ ?>
                           <img src="layout/images/m1.jpg" alt="image" />

                            <?php

                            }else{
                            ?>
                            <img src="admin/uploads/products/<?php echo $row["avatar"] ; ?>" alt="image" />
                           <?php
                            }

                            ?>

                        </div>
                        <span><strong><?php echo $row['price'] ?></strong></span>
                        <h5><?php echo $row['name'] ?></h5>
                        <hr>
                        <div class="text-center">
                            <a href="requests.php?do=Add&proid=<?php echo $row['pro_id'] ; ?>" class="btn btn-success"> Send Request</a>
                        </div>
                    </div>
                 
                 <div class="desc text-center">
                     <h4>Description</h4>
                     <?php echo $row['description'] ?>
                 </div>
                 
                 <form class="text-center" action="?do=Add&page=Insert&proid=<?php echo $row['pro_id']?>" method="post">
                     <div class="form-group">
                        <textarea minlength="5" class="form-control" name="comment"></textarea>
                     </div>
                     <input class="btn btn-primary" type="submit" value="Add Comment"/>
                 </form>
             </div>
             
             <hr class="line text-center">
             
        <?php
              
            
           // insert comments of user
                            
             if(isset($_GET['page']) && $_GET['page'] == "Insert"){
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                 if(isset($_GET["proid"]) && is_numeric($_GET["proid"]) ){
                        $proid = intval ($_GET["proid"]);
                    }else{
                        $proid= 0 ;
                    }

                $comment  = $_POST["comment"];
                $userid     = $_SESSION['id'];
              
                $formErrors = array();

                if(isset($comment)){
                    $filteredComment = filter_var($comment , FILTER_SANITIZE_STRING);
                    if(empty($filteredComment)){
                        $formErrors[] = "Comment can't be <strong>empty</strong>";
                    }
                    if(strlen($filteredComment )< 5){
                        $formErrors[] = "Comment must be more than <strong>4</strong> characters";
                    }

                }

                if(!empty($formErrors)){
                    foreach($formErrors as $errors){
                        ?>
                 <div class='alert alert-danger'><?php echo $errors ;?></div>
                 <?php
                    }
                }else{

                     $stmt = $db->prepare("INSERT INTO 
                                                        comments (comment, status, date, user_id, pro_id) 
                                                         VALUES (:zcomment, 0, now(), :zuser_id, :zpro_id)");
                        
                        $stmt->execute(array(
                          "zcomment"  => $comment ,
                          "zuser_id"  => $userid ,
                          "zpro_id"   => $proid
                        
                        ));
                    
                
                     getMessage($stmt->rowCount()."Record inserted" , "class = 'alert alert-success'" ,"back");
                    
                }
            }
        }                
                     $stmtComments = $db->prepare("SELECT 
                                                comments.* , users.name AS username , users.avatar AS user_avatar , products.name AS product_name, products.avatar AS product_avatar, products.price AS price
                                     FROM 
                                                comments 
                                     INNER JOIN
                                                users
                                     ON
                                                comments.user_id = users.userID
                                     INNER JOIN
                                                products
                                     ON
                                                comments.pro_id = products.pID 

                                     WHERE 
                                                status = 1 
                                     AND  
                                                comments.pro_id=? 
                                     ORDER BY  
                                                cID  DESC ");


                $stmtComments->execute(array($proid));
                $allComments = $stmtComments->fetchAll();
                $count = $stmtComments->rowCount();       
            
                if($count > 0){
                    foreach($allComments as $comments){
            ?>
            
            <div class="data-show all-comments">
                <div class="com-data">
                    <div class="user-attr">
                        <div>
                            <?php 
                            if(empty($comments["user_avatar"])){ ?>
                           <img class="user-img" src="layout/images/user.jpg" alt="userimage" />

                            <?php

                            }else{
                                ?>
                                <img class="user-img" src="admin/uploads/avatars/<?php echo $comments['user_avatar'] ; ?>" alt="userimage" />
                           <?php
                            }
                           ?>
                        </div>
                        <span><?php echo $comments["username"] ;?></span>
                    </div>
                    <div class="user-comment"><?php echo $comments["comment"] ;?></div>
                
                       <?php
                        if(isset($_SESSION['user'])){
                         if($comments["user_id"] == $_SESSION["id"]){
                             ?>
                        <a href="comments.php?do=Delete&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-danger float-right confirm"><i class="fa fa-close"></i> Delete</a>
                        <a  href="comments.php?do=Edit&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-success float-right"><i class="fa fa-edit"></i> Edit</a>
                       <?php
                         }
                       }
                       ?>
                </div>
                    
                <div class="comm-date"><span>comment date : </span><?php echo $comments['date'] ;?></div>
            </div>
            <?php
                    }
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }
                       
                            
        
        ?>
             
             
             
         </div> 
<?php
 
    
   } else if($do == "Edit"){// edit page    ?>

         <div class="container">
            <h2 class="text-center">Edit Comments</h2>
        
    <?php
             
        if(isset($_GET["cid"]) && is_numeric($_GET["cid"]) ){
            $cid = intval ($_GET["cid"]);
        }else{
            $cid= 0 ;
        }
        
        $check = checkCount("*" , "comments" ,"cID" , $cid);
        if($check > 0){
            
            $stmt = $db->prepare("SELECT 
                                        comments.* , users.name AS username , products.name AS product_name, products.avatar AS avatar ,products.price AS price
                             FROM 
                                        comments 
                             INNER JOIN
                                        users
                             ON
                                        comments.user_id = users.userID
                             INNER JOIN
                                        products
                             ON
                                        comments.pro_id = products.pID 

                             WHERE 
                                        comments.cID=? ");
        
    

            $stmt->execute(array($cid));
            
            $row = $stmt->fetch();
            
            ?>
             <div class="comment-edit">
                 <div class="pro-info">
                        <div>

                        <?php 
                        if(empty($row["avatar"])){ ?>
                       <img src="layout/images/m1.jpg" alt="image" />

                        <?php

                        }else{
                        ?>
                        <img src="admin/uploads/products/<?php echo $row["avatar"] ; ?>" alt="image" />
                       <?php
                        }
                    
                        ?>
                        </div>
                        <span><strong><?php echo $row['price'] ?></strong></span>
                        <h5><?php echo $row['product_name'] ?></h5>
                        <hr>
                        <div class="text-center">
                            <a href="requests.php?do=Add&proid=<?php echo $row['pro_id'] ; ?>" class="btn btn-success"> Send Request</a>
                        </div>
                    </div>
                 <form class="text-center" action="?do=Edit&page=Update&cid=<?php echo $row['cID']?>" method="post">
                     <div class="form-group">
                        <textarea minlength="5" class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
                     </div>
                     <input class="btn btn-primary" type="submit" value="Save"/>
                 </form>
             </div>
        <?php
        }else{
            getMessage("This comment is not exist" , "class = 'alert alert-danger'" ,"back");
        }


       // update comment of user
        
        
        if(isset($_GET['page']) && $_GET['page'] == "Update"){
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                 if(isset($_GET["cid"]) && is_numeric($_GET["cid"]) ){
                        $cid = intval ($_GET["cid"]);
                    }else{
                        $cid= 0 ;
                    }

                $comment  = $_POST["comment"];
              
                $formErrors = array();

                if(isset($comment)){
                    $filteredComment = filter_var($comment , FILTER_SANITIZE_STRING);
                    if(empty($filteredComment)){
                        $formErrors[] = "Comment can't be <strong>empty</strong>";
                    }
                    if(strlen($filteredComment )< 5){
                        $formErrors[] = "Comment must be more than <strong>4</strong> characters";
                    }

                }

                if(!empty($formErrors)){
                    foreach($formErrors as $errors){
                        ?>
                 <div class='alert alert-danger'><?php echo $errors ;?></div>
                 <?php
                    }
                }else{

                     $stmt = $db->prepare("UPDATE  comments SET comment = ? WHERE cID = ? ");

                     $stmt->execute(array($comment , $cid));
                
                     ?>

                    <div class="alert alert-success"><?php echo $stmt->rowCount()."Record Updated" ?></div>

                 <?php
                    
                }
            }
        }
        ?>
             
             
             
         </div> 
<?php
        

    }else if($do == "Delete"){ // delete page    ?>

         <div class="container">
            <h2 class="text-center">Delete Comments</h2>
        
    <?php
             
        if(isset($_GET["cid"]) && is_numeric($_GET["cid"]) ){
            $cid = intval ($_GET["cid"]);
        }else{
            $cid= 0 ;
        }
        
        $check = checkCount("*" , "comments" ,"cID" , $cid);
        if($check > 0){
            
            $stmt = $db->prepare("DELETE FROM comments WHERE cID = :zid ");

           $stmt->bindParam(":zid" , $cid);

           $stmt->execute();
            
            
            getMessage($stmt->rowCount()."Record Deleted" , "class = 'alert alert-success'" ,"back");


        }else{
            getMessage("This user is not exist" , "class = 'alert alert-danger'" ,"back");
        }


       
        ?>
             
         </div> 
<?php

    }


include_once $tmp."footer.php";

ob_end_flush();

?>