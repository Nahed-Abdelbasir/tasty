<?php
ob_start();
session_start();

$pageName = "Products";
    
   include_once "init.php" ;
    
    ?>

<div class="container">
    <h2 class="text-center">Products</h2>

    <!-------------------- start products ---------------------->
        <div >
            
            <?php 
    
                $query = "";
        
                if(isset($_GET["type"]) && $_GET["type"] == "meals"){
                    $query = "WHERE type = 1";
                }else if(isset($_GET["type"]) && $_GET["type"] == "sandwiches"){
                    $query = "WHERE type = 2";
                }else if(isset($_GET["type"]) && $_GET["type"] == "drinks"){
                    $query = "WHERE type = 3";
                }

    
              
                $stmt = $db->prepare("SELECT * FROM products $query ORDER BY pID DESC");
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
                    <div class="pro-info">
                        <a href="comments.php?do=Add&proid=<?php echo $products["pID"]?>">
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
                            <hr>
                        </a>
                        <div class="text-center">
                            <a <?php if(isset($_SESSION['user'])){?>
                               href="requests.php?do=Add&proid=<?php echo $products['pID'] ; ?>"
                             <?php }else{?> href="login.php" <?php }?> class="btn btn-success"> Send Request</a>
                        </div>
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
        <!--------------------- end products ----------------------->




</div>
    
    
    
   <?php 
    
    include_once $tmp."footer.php";

ob_end_flush();
?>