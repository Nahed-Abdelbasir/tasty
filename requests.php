<?php
ob_start();
session_start();

$pageName = "Requests";

if(isset($_SESSION['user'])){
    
   include_once "init.php" ;
    
    if(isset($_GET["do"])){
        $do = $_GET["do"];
    }else{
        $do = "Add";
    }


    if($do == "Add"){ 
    
    ?>

<div class="container">
    <h2 class="text-center">Send Request</h2>

    <!-------------------- start add requests ---------------------->
    
    
            
            <?php 
    
               if(isset($_GET["proid"]) && is_numeric($_GET["proid"]) ){
                    $proid = intval ($_GET["proid"]);
                }else{
                    $proid= 0 ;
                }

                $stmt = $db->prepare("SELECT * FROM products WHERE pID = ?");
                $stmt->execute(array($proid));
                $product = $stmt->fetch();
                $check = $stmt->rowCount();
        
                if($check > 0){
                    
            ?>
                 <div class="send-request">
                    <div class="pro-info"> 
                        <div>

                        <?php 
                        if(empty($product["avatar"])){ ?>
                            <img src="layout/images/m1.jpg" alt="image" />

                        <?php

                        }else{
                        ?>
                            <img src="admin/uploads/products/<?php echo $product["avatar"] ; ?>" alt="image" />
                       <?php
                        }

                        ?>
                        </div>
                        <span><strong><?php echo $product['price'] ?></strong></span>
                        <h5><?php echo $product['name'] ?></h5>
                        
                    </div>
    
    
                    <!-- start sign up form -->
    
                    <div class="request-form">
                        <form action="?do=Add&process=Insert&proid=<?php echo $product['pID']?>" method="post">
                            <div class="form-group">
                                <input pattern=".{5,}" title="address must be more than 5 characters" class="form-control" type="text" name="address" placeholder="enter your address" autocomplete="off" required="required" />
                            </div>
                            <div class="form-group">
                                <input maxlength="15" class="form-control" type="tel" name="phone" placeholder="enter your phone" autocomplete="off" required="required"/>
                            </div>
                            <div class="form-group">
                                <input maxlength="5" class="form-control" type="number" name="number" placeholder="how many requests that you want ?" required="required" />
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary btn-block" type="submit" value="Send"/>
                            </div>
                        </form>
                    </div>

                    <!-- end sign up form -->
                     
                     
                   <?php  
                     
                     
                     // insert requests data
                     
                     if(isset($_GET["process"])){
                        $process = $_GET["process"];
                    }else{
                        $process = "";
                    }
                    

                    if($process == "Insert"){ // insert page

                        if($_SERVER["REQUEST_METHOD"] == "POST"){ 
                            
                            
                            $userid      = $_SESSION['id'];
                            $address     = $_POST["address"];
                            $phone       = $_POST["phone"];
                            $number      = $_POST["number"];
                            $price       = $product["price"];
                            
    
                            
                            
                            $formErrors = array();
        
                            if(isset($address)){
                                if(empty($address)){
                                     $formErrors[] = "Address can't be <strong> empty </strong>";
                                }
                                if(strlen($address) < 5){
                                    $formErrors[] = "Address must be more than <strong> 5 </strong> characters";
                                }
                            }
                             
                            
                             if(isset($phone)){
                                
                                if(empty($phone)){
                                     $formErrors[] = "Phone can't be <strong> empty </strong>";
                                }
                                if(strlen($phone) > 12){
                                    $formErrors[] = "Phone must be less than <strong> 12 </strong> numbers";
                                }
                                
                            }
                           
                            
                            
                            if(isset($number)){
                                if(empty($number)){
                                     $formErrors[] = "Count of request can't be <strong> empty </strong>";
                                }
                                if(strlen($number) > 5){
                                    $formErrors[] = "Count of request must be less than <strong> 5 </strong> numbers";
                                }
                                
                            }
                            
                            if(isset($price)){
                                $filteredPrice = filter_var($price , FILTER_SANITIZE_NUMBER_INT);
                            }

                            $totalPrice = $number * $filteredPrice ;
                            
                            $total_price = "$".$totalPrice;
                           
                                   
                            // print errors if exist
              
                            foreach($formErrors as $error){
                            echo "<div class='alert alert-danger'>".$error."</div>";   
                           }
                            
                            
                            
                            if(empty($formErrors)) {
                    
                    
                               $stmt = $db->prepare("INSERT INTO 
                                                        requests (req_num , total_price , date , user_id , pro_id)
                                                        VALUES   ( :znum , :zprice , now() , :zuserid , :zproid)");
                        
                                $stmt->execute(array(

                                "znum"      => $number ,
                                "zprice"    => $total_price ,
                                "zuserid"   => $userid,
                                "zproid"    => $proid 
                                

                                ));

                               
                                
                                $stmt = $db->prepare("UPDATE users SET address =? , phone =?  WHERE userID= ? ");

                                $stmt->execute(array($address , $phone , $userid));

                                
                                
                                 getMessage($stmt->rowCount()."Record Inserted" , "class = 'alert alert-success'" ,"back");


                        }


                        }
                     
                     }
                

                     
                     
                     ?>
                     
                     <?php
                        
                    }else{
                         getMessage("This product is not exist" , "class = 'alert alert-danger'" ,"back");
                    }
                ?>


            </div>
    
    
     
    
    <!--------------------- end add requests ----------------------->

</div>
    
    
    
   <?php 
        
    }else if($do == "Edit"){ 
    
    ?>

<div class="container">
    <h2 class="text-center">Edit Request</h2>

    <!-------------------- start edit requests ---------------------->
    
    
            
            <?php 
    
        
                if(isset($_GET["reqid"]) && is_numeric($_GET["reqid"]) ){
                    $reqid = intval ($_GET["reqid"]);
                }else{
                    $reqid= 0 ;
                }
                
               

                 $stmt = $db->prepare("SELECT 
                                        requests.* , users.address AS address, users.phone AS phone , products.name AS name, products.avatar AS avatar ,products.price AS price 
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
                                        reqID = ?
                                       ");
                $stmt->execute(array($reqid));
                $product = $stmt->fetch();

                $check = $stmt->rowCount();
                
               
                if($check > 0){
                    
            ?>
                 <div class="send-request">
                    <div class="pro-info"> 
                        <div>

                        <?php 
                        if(empty($product["avatar"])){ ?>
                            <img src="layout/images/m1.jpg" alt="image" />

                        <?php

                        }else{
                        ?>
                            <img src="admin/uploads/products/<?php echo $product["avatar"] ; ?>" alt="image" />
                       <?php
                        }

                        ?>
                        </div>
                        <span><strong><?php echo $product['price'] ?></strong></span>
                        <h5><?php echo $product['name'] ?></h5>
                        
                    </div>
    
    
                    <!-- start sign up form -->
    
                    <div class="request-form">
                        <form action="?do=Edit&process=Update&reqid=<?php echo $product['reqID']?>" method="post">
                            <div class="form-group">
                                <input pattern=".{5,}" title="address must be more than 5 characters" class="form-control" type="text" name="address"
                                       value="<?php echo $product["address"] ?>" placeholder="enter your address" autocomplete="off" required="required" />
                            </div>
                            <div class="form-group">
                                <input maxlength="15" class="form-control" type="tel" name="phone" value="<?php echo $product["phone"] ?>" placeholder="enter your phone" autocomplete="off" required="required"/>
                            </div>
                            <div class="form-group">
                                <input maxlength="5" class="form-control" type="number" name="number" value="<?php echo $product["req_num"] ?>" placeholder="how many requests that you want ?" required="required" />
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary btn-block" type="submit" value="Send"/>
                            </div>
                        </form>
                    </div>

                    <!-- end sign up form -->
                     
                     
                   <?php  
                     
                     
                     // insert requests data
                     
                     if(isset($_GET["process"])){
                        $process = $_GET["process"];
                    }else{
                        $process = "";
                    }
                    

                    if($process == "Update"){ // update page

                        if($_SERVER["REQUEST_METHOD"] == "POST"){ 
                            
                            
                            $userid      = $_SESSION['id'];
                            $address     = $_POST["address"];
                            $phone       = $_POST["phone"];
                            $number      = $_POST["number"];
                            $price       = $product["price"];
                            
    
                            
                            
                            $formErrors = array();
        
                            if(isset($address)){
                                if(empty($address)){
                                     $formErrors[] = "Address can't be <strong> empty </strong>";
                                }
                                if(strlen($address) < 5){
                                    $formErrors[] = "Address must be more than <strong> 5 </strong> characters";
                                }
                            }
                             
                            
                             if(isset($phone)){
                                
                                if(empty($phone)){
                                     $formErrors[] = "Phone can't be <strong> empty </strong>";
                                }
                                if(strlen($phone) > 12){
                                    $formErrors[] = "Phone must be less than <strong> 12 </strong> numbers";
                                }
                                
                            }
                           
                            
                            
                            if(isset($number)){
                                if(empty($number)){
                                     $formErrors[] = "Count of request can't be <strong> empty </strong>";
                                }
                                if(strlen($number) > 5){
                                    $formErrors[] = "Count of request must be less than <strong> 5 </strong> numbers";
                                }
                                
                            }
                            
                            if(isset($price)){
                                $filteredPrice = filter_var($price , FILTER_SANITIZE_NUMBER_INT);
                            }

                            $totalPrice = $number * $filteredPrice ;
                            
                            $total_price = "$".$totalPrice;
                           
                                   
                            // print errors if exist
              
                            foreach($formErrors as $error){
                            echo "<div class='alert alert-danger'>".$error."</div>";   
                           }
                            
                            
                            
                            if(empty($formErrors)) {
                    
                    
                
                                 // update request
                                
                                $stmt = $db->prepare("UPDATE requests SET req_num =? , total_price =?   WHERE reqID= ? ");

                                $stmt->execute(array($number , $total_price , $reqid));


                               // update user address and phone
                                
                                $stmt = $db->prepare("UPDATE users SET address =? , phone =?  WHERE userID= ? ");

                                $stmt->execute(array($address , $phone , $userid));

                                
                                
                                 getMessage($stmt->rowCount()."Record Updated" , "class = 'alert alert-success'" ,"back");


                        }


                        }
                     
                     }
                

                     
                     
                     ?>
                     
                     <?php
                        
                    }else{
                         getMessage("This product is not exist" , "class = 'alert alert-danger'" ,"back");
                    }
                ?>


            </div>
    
    
     
    
    <!--------------------- end edit requests ----------------------->

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