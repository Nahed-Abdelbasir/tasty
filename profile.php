<?php
ob_start();
session_start();

$pageName = "Profile";

if(isset($_SESSION['user'])){
    
   include_once "init.php" ;
    
    if(isset($_GET["do"])){
        $do = $_GET["do"];
    }else{
        $do = "Manage";
    }
    
    if($do == "Manage"){
    
    $id = $_SESSION['id'];
    
    $stmt = $db->prepare("SELECT * FROM users WHERE userID = ? ");
    $stmt->execute(array($id));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    
    
    
    ?>

<div class="container">
    <h2 class="text-center">My Profile</h2>
    
    <?php
    
        if($count > 0){
        
    ?>
    
    <!--------------- start user information ------------------->

            <div class="user-profile data-show"> 
                    <div class="row">
                        <div class="col-sm-9">
                            <h3 class="profile-heading">My information</h3>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Name : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $row['name'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Nick Name : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php
        
                                        if(empty($row['nickname'])){
                                            echo "unknown" ;
                                        }else{
                                            echo $row['nickname'] ;
                                        }

                                      ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Email : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $row['email'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Registered Date : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $row['date'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="pro-info">
                                <div>

                                <?php 
                                if(empty($row["avatar"])){ ?>
                               <img src="layout/images/user.jpg" alt="image" />

                                <?php

                                }else{
                                ?>
                                <img src="admin/uploads/avatars/<?php echo $row['avatar'] ; ?>" alt="image" />
                               <?php
                                }
                                ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <a href="profile.php?do=Edit&userid=<?php echo $_SESSION['id'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
               </div>

               <hr class="line">
    
    <!----------------- end user information ------------------->
    <!-------------------- start requests ---------------------->
    
         <?php
            
            $query = "";
            $limit = "LIMIT 5" ;
            
            if(isset($_GET['page']) && $_GET['page'] == "unaccept"){
               $query = "AND requests.approval = 0" ;
               $limit = "" ;
            }
            
            if(isset($_GET['page']) && $_GET['page'] == "all-requests"){
               $limit = "" ;
            }
            
            $stmt = $db->prepare("SELECT 
                                        requests.* , users.name AS username , users.address AS address, users.phone AS phone , users.avatar AS user_avatar, products.name AS pro_name, products.avatar AS pro_avatar ,products.price AS price 
                                 FROM 
                                        requests
                                 INNER JOIN      
                                        users
                                 ON 
                                        requests.user_id = users.userID
                                 
                                 INNER JOIN      
                                        products
                                 ON 
                                        requests.pro_id = products.pID        
                                 WHERE
                                        requests.user_id = ?
                                        $query
                                 ORDER BY 
                                        reqID DESC
                                        
                                  $limit ");
            
            $stmt->execute(array($id));
            $allRequests = $stmt->fetchAll();
          
            $count = $stmt->rowCount();
     
            if($count > 0){
        ?>
              <div id="my-req" class="my-requests">
                <a class="btn btn-info float-right" href="profile.php?do=Manage&page=unaccept#my-req"> Not Approval Requests </a>
                <a class="btn btn-info float-right" href="profile.php?do=Manage&page=all-requests#my-req"> All Requests </a>
                <a class="btn btn-info float-right" href="profile.php?do=Manage#my-req"> Recent Requests </a>
                
                <h2 class="profile-heading">My Requests</h2>
                    <?php
                    foreach($allRequests as $request){ ?>
                    <div class="row data-show">
                        <div class="col-sm-9 req-data">
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Deliver Place  : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $request['address'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Phone  : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $request['phone'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Request Count  : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $request['req_num'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Total Price  : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $request['total_price'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <strong>Request Date/Time  : </strong> 
                                </div>
                                <div class="col-sm-9">
                                    <?php echo $request['date'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="req-info">
                                <div>

                                <?php 
                                if(empty($request["pro_avatar"])){ ?>
                               <img src="layout/images/m1.jpg" alt="image" />

                                <?php

                                }else{
                                ?>
                                <img src="admin/uploads/products/<?php echo $request['pro_avatar'] ; ?>" alt="image" />
                               <?php
                                }
                                ?>

                                </div>
                                <span><strong><?php echo $request['price'] ?></strong></span>
                                <h5><?php echo $request['pro_name'] ?></h5>
                            </div>
                        </div>
                        <?php
                           if($request['approval'] == 0){
                        ?>
                        <a href="requests.php?do=Edit&reqid=<?php echo $request['reqID'] ;?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit Request </a>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
        <?php
            }else{
                echo "<div class = 'container'>
                           <div class = 'alert alert-danger'> There is no data to show</div>
                      </div>";
            }
            ?>
    
           <hr class="line">
    
    <!--------------------- end requests ----------------------->
    <!-------------------- start comments ---------------------->
    
        <div id="my-com" class="comment-data">
            
            <?php
            
            $limit ="LIMIT 10";
            
            if(isset($_GET['page']) && $_GET['page'] == "all-comments"){
               $limit = "" ;
            }
              

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
                                                comments.user_id = ?          
                                     ORDER BY  
                                                cID  DESC 
                                                $limit");


                $stmt->execute(array($id));
                $allComments = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if($count > 0){    ?>
            
                    <div>
                      <a class="btn btn-info float-right" href="profile.php?do=Manage&page=all-requests#my-com"> All Comments </a>
                      <a class="btn btn-info float-right" href="profile.php?do=Manage#my-com"> Recent Comments </a>
                
                      <h2 class="profile-heading">My Comments</h2>
            <?php
                    
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
                       <a href="comments.php?do=Add&proid=<?php echo $comments["pro_id"]?>">
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
                         if($comments["user_id"] == $_SESSION["id"]){
                             ?>
                        <a href="comments.php?do=Edit&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                        <a href="comments.php?do=Delete&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>
                       <?php
                         }
                       ?>
                <div class="comm-date"><span>comment date : </span><?php echo $comments['date'] ;?></div>
            </div>
        </div>
            <?php
                    }
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }
            ?>
        </div>
    
        <!--------------------- end comments ----------------------->

         <?php
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }

          ?>
</div>
     
    
   <?php 
    }else if($do == "Edit"){// edit page 

    
        $userid = $_SESSION["id"];
    

   $stmt = $db->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");

   $stmt->execute(array($userid));
   $row = $stmt->fetch(); 
                            
   $count = $stmt->rowCount(); 

   if($count > 0){


?>

        <div class="container">
            <h2 class="text-center">Edit Profile</h2>
            <div class="form-data">
                <form class="form-horizontal" action="profile.php?do=Edit&process=Update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value ="<?php echo $userid ;?>" />
                    <!------------------ start user name --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>User Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" value ="<?php echo $row['name'] ;?>" required autocomplete="off"/>
                        </div>
                    </div>
                    <!------------------ end user name --------------->
                    <!------------------ start password --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Password</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="password" placeholder="enter new password if you want"/>
                            <input type="hidden" name="oldpassword" value ="<?php echo $row['password'] ;?>" />
                        </div>
                    </div>
                    <!------------------ end password --------------->
                    <!------------------ start nick name --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Nick Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nickname" value ="<?php echo $row['nickname'] ;?>"/>
                        </div>
                    </div>
                    <!------------------ end nick name --------------->
                    <!------------------ start email --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Email</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" value ="<?php echo $row['email'] ;?>" required />
                        </div>
                    </div>
                    <!------------------ end email --------------->
                    <!------------------ start avatar --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>avatar</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="file" name="avatar" />
                            <input type="hidden" name="oldavatar" value ="<?php echo $row['avatar'] ;?>" />
                        </div>
                    </div>
                    <!------------------ end avatar --------------->
                    <!------------------ start submit --------------->
                    
                        <div>
                            <input class="btn btn-primary" type="submit" value = "Save" />
                        </div>
                    
                    <!------------------ end submit --------------->
                </form>
            </div>
        
     <?php   
        }else{
       
         getMessage("There is no such id " , "class = 'alert alert-danger'" ,"back");
       
   }
      
        
     ?>
     </div>

<?php
        
        
        
        // update user information
        
        
        if(isset($_GET["process"])){
            $process = $_GET["process"];
        }else{
            $process = "";
        }
        
        if($process == "Update"){ // update page
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){  ?>
            <div class="container">
                <?php
                
                $id       = $_POST["userid"];
                $name     = $_POST["name"];
                $pass     = $_POST["password"];
                $nickname = $_POST["nickname"];
                $email    = $_POST["email"];
                $avat     = $_FILES["avatar"];
                $oldavat  = $_POST["oldavatar"];
            
            
                if(empty($pass)){
                    $hashpass = $_POST["oldpassword"];
               }else{
                    $hashpass = sha1($pass);
                }
                
               
             // avatar info
            
                $avatarName = $avat['name'];
                $avatarType = $avat['type'];
                $avatarTmp  = $avat['tmp_name'];
                $avatarSize = $avat['size'];
            
                
            
                $extension = array("jpg" , "jpeg" , "png" , "gif");
                 
                $avatarExtension = strtolower(end(explode(".",$avatarName)));
            
            
            
               // check if there is error or not 
                
               $formErrors = array();
            
               if(strlen($name) < 3){
                  $formErrors[] = "Username must be more than <strong>3</strong> characters";
               }
               if(strlen($name) > 20){
                   $formErrors[] = "Username can't be more than <strong>20</strong> characters";
               }
               if(!empty($pass) && strlen($pass) < 5){
                  $formErrors[] = "password must be more than <strong>5</strong> characters";
               }
               if(empty($name)){
                  $formErrors[] = "Name mustn't be <strong>empty</strong>";
               }
               if(empty($email)){
                  $formErrors[] = "Email mustn't be <strong>empty</strong>";
               } 
               if(!empty($avatarName) && !in_array($avatarExtension , $extension)){
                    $formErrors[] = "This extension is not <strong>allawed</strong>";
               }
               if($avatarSize > 4194304){
                    $formErrors[] = "Avatar can't be larger than <strong>4MB</strong>";
               }
               
                
               
            // print errors if exist
              
                foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>".$error."</div>";   
               }
            
            
                if(empty($formErrors)) {
                    
                    
                    if(empty($avat['name'])){
                        $avatar = $oldavat;
                    }else{  

                       $avatar = rand(0 , 100000000)."_".$avatarName;
                       move_uploaded_file($avatarTmp , "admin\uploads\avatars\\".$avatar);   
                    }
                    
                    
                    $stmt = $db->prepare("SELECT * FROM users WHERE userID != ? AND name = ?");

                   $stmt->execute(array($userid , $name));
                   
                   $count = $stmt->rowCount(); 

                   if($count > 0){
                       
                       getMessage("This user is exist" , "class = 'alert alert-danger'" ,"back");
                       
                   }else{
                       
                        
                        $stmt = $db->prepare("UPDATE users SET name =? , password =? , nickname =? , email=? , avatar =? WHERE userID= ? ");
                        
                        $stmt->execute(array($name ,$hashpass ,$nickname ,$email , $avatar ,$id));
                        
                        header("location: profile.php");
                        
                   }
                    
                    
                }else{
                    
                    getMessage("" , "class = 'alert alert-success'" ,"back");
                }                                   
            
                
              ?>  
            </div>

         <?php
                                                 
        }
         
        
    }
        
        
        
        
        
        
        
        
        
    }
        
        
    include_once $tmp."footer.php";
    
}else{
    header("location: index.php");
    exit();
}

ob_end_flush();
?>