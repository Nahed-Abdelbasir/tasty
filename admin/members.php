<?php

ob_start();

session_start();

$pageName = "Members";


if(isset($_SESSION['user'])){
  
    include_once "init.php";
    
    if(isset($_GET["do"])){
        $do = $_GET["do"];
    }else{
        $do = "Manage";
    }


    if($do == "Manage"){ // manage page 
        
        $query = "";
        
        if(isset($_GET["page"]) && $_GET["page"] == "pending"){
            $query = "AND approval = 0";
        }
        
        $stmt = $db->prepare("SELECT * FROM users WHERE groupID != 1 $query");
        $stmt->execute();
        $allMembers = $stmt->fetchAll();
        $count = $stmt->rowCount();
        
        if($count > 0){

?>


   <div class="container">
       <h2 class="text-center">Manage Members</h2>
       <div class="text-center table-data">
           <table class="table table-bordered">
               <tr>
                   <td>#ID</td>
                   <td>Avatar</td>
                   <td>Name</td>
                   <td>Nickname</td>
                   <td>Email</td>
                   <td>Registered Date</td>
                   <td>Control</td>
               </tr>
               <?php
               foreach ($allMembers as $members){
               ?>
        
               <tr>
                   <td><?php echo $members["userID"] ;?></td>
                   <td>
                       <?php 
                        if(empty($members["avatar"])){ ?>
                       <img src="layout/images/user.jpg" alt="userimage" />
                            
                        <?php
                                                      
                        }else{
                            ?>
                            <img src="uploads/avatars/<?php echo $members['avatar'] ; ?>" alt="userimage" />
                       <?php
                        }
                       ?>
                   </td>
                   <td><?php echo $members["name"] ;?></td>
                   <td><?php echo $members["nickname"] ;?></td>
                   <td><?php echo $members["email"] ;?></td>
                   <td><?php echo $members["date"] ;?></td>
                   <td>
                       <a href="members.php?do=Edit&userid=<?php echo $members['userID'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                       <a href="members.php?do=Delete&userid=<?php echo $members['userID'] ; ?>" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>
                       <?php
                         if($members["approval"] == 0){
                             ?>
                       <a href="members.php?do=Approve&userid=<?php echo $members['userID'] ; ?>" class="btn btn-info"><i class="fa fa-check"></i> Activate</a>
                       <?php
                         }
                       ?>
                   </td>
               </tr>
               <?php
               }
               ?>
           </table>
           <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Members </a>
       </div>
   </div>
        
        
    <?php
        }else{
              echo "<div class = 'container'>
                           <div class = 'alert alert-danger'> There is no data to show</div>
                      </div>";
            ?>
           <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Members </a>
      <?php
            
        }
        
    }else if($do == "Add"){ // add page ?>

        <div class="container">
            <h2 class="text-center">Add Members</h2>
            <div class="form-data">
                <form class="form-horizontal" action="members.php?do=Insert" method="post" enctype="multipart/form-data">
                    <!------------------ start user name --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>User Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" required />
                        </div>
                    </div>
                    <!------------------ end user name --------------->
                    <!------------------ start password --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Password</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="password" required />
                        </div>
                    </div>
                    <!------------------ end password --------------->
                    <!------------------ start nick name --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Nick Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nickname" />
                        </div>
                    </div>
                    <!------------------ end nick name --------------->
                    <!------------------ start email --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Email</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" required />
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
                        </div>
                    </div>
                    <!------------------ end avatar --------------->
                    <!------------------ start submit --------------->
                    <div class="row form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value = "Add Member" />
                        </div>
                    </div>
                    <!------------------ end submit --------------->
                </form>
            </div>
        </div>
        
     <?php   
        
    }else if($do == "Insert"){ // insert page
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){  ?>
            
            <div class="container">
                <h2 class="text-center">Insert Members</h2>
                <?php
                
                $name     = $_POST["name"];
                $pass     = $_POST["password"];
                $nickname = $_POST["nickname"];
                $email    = $_POST["email"];
                $avat     = $_FILES["avatar"];
            
                $hashpass = sha1($pass);
                
            
                $avatarName = $avat['name'];
                $avatarSize = $avat['size'];
                $avatarTmp  = $avat['tmp_name'];
                $avatarType = $avat['type'];


                // list of allawed file typed to upload

                $extension = array("jpeg","jpg","png","gif");


                //get avatar extension

                $avatarExtension = strtolower(end(explode(".",$avatarName)));

            
               $formErrors = array();
            
               if(strlen($name) < 3){
                  $formErrors[] = "Username must be more than <strong>3</strong> characters";
               }
               if(strlen($name) > 20){
                   $formErrors[] = "Username can't be more than <strong>20</strong> characters";
               }
               if(strlen($pass) < 5){
                  $formErrors[] = "password must be more than <strong>5</strong> characters";
               }
               if(empty($name)){
                  $formErrors[] = "Name mustn't be <strong>empty</strong>";
               }
               if(empty($pass)){
                  $formErrors[] = "Password mustn't be <strong>empty</strong>";
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
                    
                    $avatar = rand(0 , 100000000)."_".$avatarName;
                    move_uploaded_file($avatarTmp , "uploads\avatars\\".$avatar);
                    
                
                   $check = checkCount("*" , "users" ,"name" , $name);
                    if($check == 1){
                        getMessage("This user is exist" , "class = 'alert alert-danger'" ,"back");
                    }else{
                        
                        $stmt = $db->prepare("INSERT INTO 
                                                        users (name , password , nickname , email , approval , date , avatar)
                                                        VALUES (:zname ,:zpass , :znick ,:zemail , 1 , now() , :zavatar)");
                        
                        $stmt->execute(array(
                        
                        "zname"    => $name ,
                        "zpass"    => $hashpass ,
                        "znick"    => $nickname,
                        "zemail"   => $email ,
                        "zavatar"  => $avatar
                        
                        
                        ));
                        
                        getMessage($stmt->rowCount()."Record Inserted" , "class = 'alert alert-success'" ,"back");
                        
                        
                    }
                    
                    
                }else{
                    
                    getMessage("" , "class = 'alert alert-success'" ,"back");
                }                                   
            
                
              ?>  
            </div>

         <?php
                                                 
        }else{   ?>
                
            <div class="container">
            <?php  getMessage("sorry you can't browse this page direct" , "class = 'alert alert-danger'"); ?>
            </div>
                
         <?php
              
           }
        
        
    }else if($do == "Edit"){// edit page 

    if(isset($_GET["userid"]) && is_numeric($_GET["userid"]) ){
        $userid = intval ($_GET["userid"]);
    }else{
        $userid= 0 ;
    }


   $stmt = $db->prepare("SELECT * FROM users WHERE userID = ? LIMIT 1");

   $stmt->execute(array($userid));
   $row = $stmt->fetch(); 
                            
   $count = $stmt->rowCount(); 

   if($count > 0){


?>

        <div class="container">
            <h2 class="text-center">Edit Members</h2>
            <div class="form-data">
                <form class="form-horizontal" action="members.php?do=Update" method="post" enctype="multipart/form-data">
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
                    <!------------------ start submit --------------->
                    <div class="row form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value = "Save" />
                        </div>
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
        
    }else if($do == "Update"){ // update page
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){  ?>
            
            <div class="container">
                <h2 class="text-center">Update Members</h2>
                <?php
                
                $id       = $_POST["userid"];
                $name     = $_POST["name"];
                $pass     = $_POST["password"];
                $nickname = $_POST["nickname"];
                $email    = $_POST["email"];
            
            
                if(empty($pass)){
                    $hashpass = $_POST["oldpassword"];
               }else{
                    $hashpass = sha1($pass);
                }
                
               
                
                
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
               
                
               
            // print errors if exist
              
                foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>".$error."</div>";   
               }
            
            
                if(empty($formErrors)) {
                    
                    
                    $stmt = $db->prepare("SELECT * FROM users WHERE userID != ? AND name = ?");

                   $stmt->execute(array($userid , $name));
                   
                   $count = $stmt->rowCount(); 

                   if($count > 0){
                       
                       getMessage("This user is exist" , "class = 'alert alert-danger'" ,"back");
                       
                   }else{
                       
                        
                        $stmt = $db->prepare("UPDATE users SET name =? , password =? , nickname =? , email=? , avatar =? WHERE userID= ? ");
                        
                        $stmt->execute(array($name ,$hashpass ,$nickname ,$email , $avatar ,$id));
                        
                        getMessage($stmt->rowCount()."Record Updated" , "class = 'alert alert-success'" ,"back");
                        
                   }
                    
                    
                }else{
                    
                    getMessage("" , "class = 'alert alert-success'" ,"back");
                }                                   
            
                
              ?>  
            </div>

         <?php
                                                 
        }else{   ?>
                
            <div class="container">
            <?php  getMessage("sorry you can't browse this page direct" , "class = 'alert alert-danger'"); ?>
            </div>
                
         <?php
              
           }
        
        
        
    }else if($do == "Delete"){ // delete page    ?>

         <div class="container">
            <h2 class="text-center">Delete Members</h2>
        
    <?php
             
        if(isset($_GET["userid"]) && is_numeric($_GET["userid"]) ){
            $userid = intval ($_GET["userid"]);
        }else{
            $userid= 0 ;
        }
        
        $check = checkCount("*" , "users" ,"userID" , $userid);
        if($check > 0){
            
            $stmt = $db->prepare("DELETE FROM users WHERE userID = :zid ");

           $stmt->bindParam(":zid" , $userid);

           $stmt->execute();
            
            
            getMessage($stmt->rowCount()."Record Deleted" , "class = 'alert alert-success'" ,"back");


        }else{
            getMessage("This user is not exist" , "class = 'alert alert-danger'" ,"back");
        }


       
        ?>
             
         </div> 
<?php

    }else if($do == "Approve"){// delete page    ?>

         <div class="container">
            <h2 class="text-center">Activate Members</h2>
        
    <?php
             
        if(isset($_GET["userid"]) && is_numeric($_GET["userid"]) ){
            $userid = intval ($_GET["userid"]);
        }else{
            $userid= 0 ;
        }
        
        $check = checkCount("*" , "users" ,"userID" , $userid);
        if($check > 0){
            
            $stmt = $db->prepare("UPDATE users SET approval = 1 WHERE userID = ? ");

            $stmt->execute(array($userid));
            
            
            getMessage($stmt->rowCount()."Record Activated" , "class = 'alert alert-success'" ,"back");


        }else{
            getMessage("This user is not exist" , "class = 'alert alert-danger'" ,"back");
        }


       
        ?>
             
         </div> 
<?php
        
        
    }




include_once $tmp."footer.php";
    
    }else{
    header("location: index.php");
    exit();
}

ob_end_flush();

?>