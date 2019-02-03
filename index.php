<?php
ob_start();
session_start();

$pageName = "Index";

 include_once "init.php";
 
?>
<header class="text-center">
    <div class="header-text">
        <div class="overlay"></div>
        <h1>Tasty Restaurant</h1>
        <p class="typed"></p>
    </div>
</header>

<!--------------- start features ------------------>

<div class="features text-center">
    <div class="container">
        <h2>Features</h2>
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <i class="fas fa-check fa-3x"></i>
                <h4>Quality</h4>
                <p>We always work hard to make our products with high quality. all products have good shape and wonderful tast. We use natural and healthy food and fresh drinks </p>
            </div>
            <div class="col-sm-12 col-md-4">
                <i class="fas fa-dollar-sign fa-3x"></i>
                <h4>Cost</h4>
                <p>In Tasty resturant the cost is not high , We make our products with heigh quality and its cost is not expensive ,Each product has cost according to its components.</p>
            </div>
            <div class="col-sm-12 col-md-4">
                <i class="fas fa-shipping-fast fa-3x"></i>
                <h4>Speed</h4>
                <p>When our clients send order to get our products we send our clerk with this order to give it to our client , We try to send order to client very fast as possible. </p>
            </div>
        </div>
    </div>
</div>
 
<!----------------- end features ------------------->
<!--------------- start products ------------------>

<div class="products text-center">
    <div class="container">
        <h2>Products</h2>
        <ul id="product">
            <a href="index.php?type=all#product"><li class="choosed">All Products</li></a>
            <a href="index.php?type=1#product"><li>Meals</li></a>
            <a href="index.php?type=2#product"><li>Sandwiches</li></a>
            <a href="index.php?type=3#product"><li>Drinks</li></a>
        </ul>
        
        <!-------------------- start products image ---------------------->
        <div>
            
            
            <?php 
            
            if(isset($_GET["type"]) && is_numeric($_GET["type"]) ){
                $type = intval ($_GET["type"]);
            }else{
                $type= 0 ;
            }
    
                $stmt = $db->prepare("SELECT * FROM products ORDER BY pID DESC LIMIT 8 ");
                $stmt->execute();
                $allProducts = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if($count > 0){
                    
            ?>
            
            <div class="row">
                <?php
                foreach($allProducts as $products){
                    ?>
                <div class="col-sm-3">
                    
                    <div class="product-info">
                        <div 
                             <?php if($type != $products["type"] && $type != 0){
                                   echo "class='overlay'";
                        
                                   } 
                             
                             ?> 
                        >
                        
                        </div>
                            <div>

                            <?php 
                            if(empty($products["avatar"])){ ?>
                           <img src="layout/images/m1.jpg" alt="image" />

                            <?php

                            }else{
                            ?>
                            <img src="admin/uploads/products/<?php echo $products["avatar"] ; ?>" alt="image" />
                           <?php
                            }

                            ?>
                            </div>
                            <span><strong><?php echo $products['price'] ?></strong></span>
                            <h5><?php echo $products['name'] ?></h5>
                            
                            <?php 
                                if(isset($_SESSION['user'])){
                            ?>
                                
                                <div class="text-center">
                                    <a href="requests.php?do=Add&proid=<?php echo $products['pID'] ; ?>" class="btn btn-success"> Send Request</a>
                                </div>
                           <?php
                            } 
                            ?>
                    </div>
                </div> 
            <?php
                    }
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }
            ?>
             </div>
        </div>
        <!--------------------- end products image ----------------------->
        
    </div>
</div>
 
<!----------------- end products ------------------->
<!--------------- start statistics ------------------>

<div class="information text-center">
    <div class="overlay"></div>
    <div class="container">
        <h2>Statistics</h2>
        <div class="row">
            <div class="col-sm-3">
                <div class="text-center info-stat">
                    <i class="fa fa-users fa-3x"></i>
                    <span>
                        <?php echo checkAllCount("*" , "users" , "WHERE groupID != 1") ;?>
                    </span>
                    <hr>
                    <h4>Members</h4>
                </div>  
            </div>
            <div class="col-sm-3">
                <div class="text-center info-stat">
                    <i class="fa fa-tags fa-3x"></i>
                    <span>
                        <?php echo checkAllCount("*" , "requests") ;?>
                    </span>
                    <hr>
                    <h4>All Requests</h4>
                </div>  
            </div>
            <div class="col-sm-3">
                <div class="text-center info-stat">
                    <i class="fa fa-tag fa-3x"></i>
                    <span>
                        <?php echo checkAllCount("*" , "requests" , "WHERE approval = 0")  ;?>
                    </span>
                    <hr>
                    <h4>Pending Requests</h4>
                </div>  
            </div>
            <div class="col-sm-3">
                <div class="text-center info-stat">
                    <i class="fas fa-wine-glass fa-3x"></i>
                    <span>
                        <?php echo checkAllCount("*" , "products")  ;?>
                    </span>
                    <hr>
                    <h4>Products</h4>
                </div>  
            </div>
        </div>
        
    </div>
</div>
 
<!----------------- end statistics ------------------->
<!--------------- start contact us ------------------>

<div class="contact text-center">
    <div class="container">
        <h2>Contact Us</h2>
        <div class="form-msg">
            <form class="form-horizontal" action="" method="post">
            
                <div class="row">
                    <!------------------ start user name --------------->
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <input pattern=".{3,}" title="username must be more than 3 characters" class="form-control" type="text" name="name" placeholder="Enter user name" autocomplete="off" required />
                        </div>
                    </div>
                    <!------------------ end user name --------------->
                    <!------------------ start email --------------->
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <input class="form-control" type="email" name="email" placeholder="Enter your email" required />
                        </div>
                    </div>
                    <!------------------ end email --------------->
                </div>
                <!------------------ start message --------------->
                <textarea minlength="10" class="form-control" name="message" placeholder="Write your message"></textarea>
                <!------------------ end message --------------->
                <!------------------ start submit --------------->
                <input class="btn btn-primary btn-block" type="submit" value = "Send" />
                <!------------------ end submit --------------->
            </form>
        </div>
        
        <?php
        
          if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $name      = $_POST["name"];
        $email     = $_POST["email"];
        $msg       = $_POST["message"];
       
        
        $formErrors = array();
        
        if(isset($name)){
            $filteredUser = filter_var($name , FILTER_SANITIZE_STRING);
            if(empty($filteredUser)){
                  $formErrors[] = "Name mustn't be <strong>empty</strong>";
            }
            if(strlen($filteredUser) < 4){
                $formErrors[] = "Username must be more than <strong> 4 </strong> characters";
            }
        }
        
        
        if(isset($email)){
            $filteredEmail = filter_var($email , FILTER_SANITIZE_EMAIL);
            if(empty($filteredEmail)){
                  $formErrors[] = "Email mustn't be <strong>empty</strong>";
            }
            if(filter_var($filteredEmail , FILTER_VALIDATE_EMAIL) != true){
                $formErrors[] = "This email is not valid";
            }
        }
        
        
        if(isset($msg)){
            $filteredMsg = filter_var($msg , FILTER_SANITIZE_STRING);
            if(empty($filteredMsg)){
                  $formErrors[] = "Message mustn't be <strong>empty</strong>";
            }
            if(strlen($filteredMsg) < 10){
                $formErrors[] = "Username must be more than <strong> 10 </strong> characters";
            }
        }
        
        foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>".$error."</div>";   
             }      
              
        
        // if there is no errors send email [ mail(to , subject , message , headers , parameters) ]
        
        $myEmail = "nahed771993@gmail.com";
        $subject = "Website Tasty Message";
        $headers = "From:".$filteredEmail."\r\n";
        
        if(empty($formErrors)){
            
            $send_mail = mail($myEmail ,$subject , $filteredMsg,$headers);
            
            if($send_mail){
                
                $name  = "";
                $email = "";
                $msg   = "";


               $successMsg = "<div class='alert alert-success'>We have recieved your message</div>";

                    echo $successMsg;
                

            }
        }
        
     
   
    }

        
        ?>
        
    </div>
</div>
 
<!----------------- end contact us ------------------->



<?php

include_once $tmp."footer.php";

?>