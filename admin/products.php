<?php

ob_start();

session_start();

$pageName = "Products";


if(isset($_SESSION['user'])){
  
    include_once "init.php";
    
    if(isset($_GET["do"])){
        $do = $_GET["do"];
    }else{
        $do = "Manage";
    }


    if($do == "Manage"){ // manage page 
        
        
        $stmt = $db->prepare("SELECT * FROM products ORDER BY pID DESC");
        $stmt->execute();
        $allProducts = $stmt->fetchAll();
        $count = $stmt->rowCount();
        
        if($count > 0){

?>


   <div class="container">
       <h2 class="text-center">Manage Products</h2>
       <div class="text-center table-data">
           <table class="table table-bordered">
               <tr>
                   <td>#ID</td>
                   <td>Avatar</td>
                   <td>Name</td>
                   <td>Description</td>
                   <td>Type</td>
                   <td>Price</td>
                   <td>Control</td>
               </tr>
               <?php
               foreach ($allProducts as $products){
               ?>
        
               <tr>
                   <td><?php echo $products["pID"] ;?></td>
                   <td>
                       <?php 
                        if(empty($products["avatar"])){ ?>
                       <img src="layout/images/m1.jpg" alt="image" />
                            
                        <?php
                                                      
                        }else{
                            ?>
                            <img src="uploads/products/<?php echo $products['avatar'] ; ?>" alt="image" />
                       <?php
                        }
                       ?>
                   </td>
                   <td><?php echo $products["name"] ;?></td>
                   <td><?php echo $products["description"] ;?></td>
                   <td><?php echo $products["type"] ;?></td>
                   <td><?php echo $products["price"] ;?></td>
                   <td>
                       <a href="products.php?do=Edit&pid=<?php echo $products['pID'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                       <a href="products.php?do=Delete&pid=<?php echo $products['pID'] ; ?>" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>
                   </td>
               </tr>
               <?php
               }
               ?>
           </table>
           <a href="products.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Products </a>
       </div>
   
        
        
    <?php
        }else{
              echo "<div class = 'container'>
                           <div class = 'alert alert-danger'> There is no data to show</div>";
            ?>
           <a href="products.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New products </a>
      <?php
            echo "</div>";
        }
        ?>
       
       </div>
<?php
        
    }else if($do == "Add"){ // add page ?>

        <div class="container">
            <h2 class="text-center">Add Products</h2>
            <div class="form-data">
                <form class="form-horizontal" action="products.php?do=Insert" method="post" enctype="multipart/form-data">
                    <!------------------ start product name --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" required />
                        </div>
                    </div>
                    <!------------------ end product name --------------->
                    <!------------------ start description --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Description</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="desc" required/>
                        </div>
                    </div>
                    <!------------------ end description --------------->
                    <!------------------ start type --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Type</label>
                        </div>
                        <div class="col-sm-10">
                            <select name="type">
                                <option value="0">---</option>
                                <option value="1">Meal</option>
                                <option value="2">Sandwiches</option>
                                <option value="3">Drinks</option>
                            </select>
                        </div>
                    </div>
                    <!------------------ end type --------------->
                     <!------------------ start price --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Price</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="price" required/>
                        </div>
                    </div>
                    <!------------------ end price --------------->
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
                            <input class="btn btn-primary" type="submit" value = "Add Product" />
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
                <h2 class="text-center">Insert Products</h2>
                <?php
                
                $name     = $_POST["name"];
                $desc     = $_POST["desc"];
                $type     = $_POST["type"];
                $price    = $_POST["price"];
                $avat     = $_FILES["avatar"];
            
                
            
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
                  $formErrors[] = "name must be more than <strong>3</strong> characters";
               }
               if(strlen($name) > 30){
                   $formErrors[] = "name can't be more than <strong>30</strong> characters";
               }
               if(strlen($desc) < 5){
                   $formErrors[] = "description must be more than <strong>5</strong> characters";
               }
               if(empty($name)){
                   $formErrors[] = "Name mustn't be <strong>empty</strong>";
               }
               if(empty($desc)){
                   $formErrors[] = "description mustn't be <strong>empty</strong>";
               }
               if($type == 0){
                   $formErrors[] = "type mustn't be <strong>empty</strong>";
               }
               if(empty($price)){
                   $formErrors[] = "price mustn't be <strong>empty</strong>";
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
                    move_uploaded_file($avatarTmp , "uploads\products\\".$avatar);
                    
                
                   $check = checkCount("*" , "products" ,"name" , $name);
                    if($check == 1){
                        getMessage("This product is exist" , "class = 'alert alert-danger'" ,"back");
                    }else{
                        
                        $stmt = $db->prepare("INSERT INTO 
                                                        products (name , description , type , price , avatar)
                                                        VALUES (:zname ,:zdesc , :ztype ,:zprice , :zavatar)");
                        
                        $stmt->execute(array(
                        
                        "zname"    => $name ,
                        "zdesc"    => $desc ,
                        "ztype"    => $type,
                        "zprice"   => $price ,
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
if(isset($_GET["pid"]) && is_numeric($_GET["pid"]) ){
        $pid = intval ($_GET["pid"]);
    }else{
        $pid= 0 ;
    }


   $stmt = $db->prepare("SELECT * FROM products WHERE pID = ? LIMIT 1");

   $stmt->execute(array($pid));
   $row = $stmt->fetch(); 
                            
   $count = $stmt->rowCount(); 

   if($count > 0){
   

?>

        <div class="container">
            <h2 class="text-center">Edit Products</h2>
            <div class="form-data">
                <form class="form-horizontal" action="products.php?do=Update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="productid" value ="<?php echo $pid ;?>" />
                    <!------------------ start name --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" value ="<?php echo $row['name'] ;?>" required autocomplete="off"/>
                        </div>
                    </div>
                    <!------------------ end name --------------->
                    <!------------------ start description --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Description</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="desc" value ="<?php echo $row['description'] ;?>" required autocomplete="off"/>
                        </div>
                    </div>
                    <!------------------ end description --------------->
                    <!------------------ start type --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Type</label>
                        </div>
                        <div class="col-sm-10">
                            <select name="type">
                                <option value="0">---</option>
                                <option value="1"  <?php if($row['type'] == 1){ echo 'selected' ; } ?> >Meal</option>
                                <option value="2"  <?php if($row['type'] == 2){ echo 'selected' ; } ?> >Sandwiches</option>
                                <option value="3"  <?php if($row['type'] == 3){ echo 'selected' ; } ?> >Drinks</option>
                            </select>
                        </div>
                    </div>
                    <!------------------ end type --------------->
                     <!------------------ start price --------------->
                    <div class="row form-group">
                        <div class="col-sm-2">
                            <label>Price</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="price" value ="<?php echo $row['price'] ;?>" required autocomplete="off" />
                        </div>
                    </div>
                    <!------------------ end price --------------->
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
        
                                                                            
    }else if($do == "Update"){// insert page
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){  ?>
            
            <div class="container">
                <h2 class="text-center">Update Products</h2>
                <?php
                
                $id       = $_POST["productid"];
                $name     = $_POST["name"];
                $desc     = $_POST["desc"];
                $type     = $_POST["type"];
                $price    = $_POST["price"];
                $avat     = $_FILES["avatar"];
                $oldavat  = $_POST["oldavatar"];
                
                
                $avatarName = $avat['name'];
                $avatarType = $avat['type'];
                $avatarTmp  = $avat['tmp_name'];
                $avatarSize = $avat['size'];
            
                
                
            
                $extension = array("jpg" , "jpeg" , "png" , "gif");
                 
                $avatarExtension = strtolower(end(explode(".",$avatarName)));
            
               $formErrors = array();
            
               if(strlen($name) < 3){
                  $formErrors[] = "Username must be more than <strong>3</strong> characters";
               }
               if(strlen($name) > 30){
                   $formErrors[] = "Username can't be more than <strong>30</strong> characters";
               }
               if(strlen($desc) < 5){
                   $formErrors[] = "description must be more than <strong>5</strong> characters";
               }
               if(empty($name)){
                   $formErrors[] = "Name mustn't be <strong>empty</strong>";
               }
               if(empty($desc)){
                   $formErrors[] = "description mustn't be <strong>empty</strong>";
               }
               if($type == 0){
                   $formErrors[] = "type mustn't be <strong>empty</strong>";
               }
               if(empty($price)){
                   $formErrors[] = "price mustn't be <strong>empty</strong>";
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
                       move_uploaded_file($avatarTmp , "uploads\products\\".$avatar);   
                    }
            
                    
                    
                    $stmt = $db->prepare("SELECT * FROM products WHERE pID != ? AND name = ?");

                   $stmt->execute(array($id , $name));
                   
                   $count = $stmt->rowCount(); 

                   if($count > 0){
                       
                       getMessage("This user is exist" , "class = 'alert alert-danger'" ,"back");
                       
                   }else{
                       
                        
                        $stmt = $db->prepare("UPDATE products SET name =? , description =? , type =? , price=? , avatar =? WHERE pID= ? ");
                        
                        $stmt->execute(array($name ,$desc ,$type ,$price , $avatar ,$id));
                        
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
            <h2 class="text-center">Delete Products</h2>
        
    <?php
             
        if(isset($_GET["pid"]) && is_numeric($_GET["pid"]) ){
            $pid = intval ($_GET["pid"]);
        }else{
            $pid= 0 ;
        }
        
        $check = checkCount("*" , "products" ,"pID" , $pid);
        if($check > 0){
            
            $stmt = $db->prepare("DELETE FROM products WHERE pID = :zid ");

           $stmt->bindParam(":zid" , $pid);

           $stmt->execute();
            
            
            getMessage($stmt->rowCount()."Record Deleted" , "class = 'alert alert-success'" ,"back");


        }else{
            getMessage("This product is not exist" , "class = 'alert alert-danger'" ,"back");
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