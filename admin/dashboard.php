<?php

ob_start();

session_start();

$pageName = "Dashboard";



if(isset($_SESSION['user'])){
   
    include_once "init.php";
    
?>
    <div class="container">
        <div class="row items">
            <div class="col-sm-2">
                <div class="text-center info pend-members">
                    <h4><a href="dashboard.php">Pending Members</a></h4>
                    <span>
                        <a href="members.php?do=Manage&page=pending"><?php echo checkCount("*" , "users" , "approval" , 0 , "AND groupID != 1") ;?></a>
                    </span>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="text-center info all-products">
                    <h4><a href="dashboard.php?content=products">Products</a></h4>
                    <span>
                        <a href="products.php?do=Manage"><?php echo checkAllCount("*" , "products") ;?></a>
                    </span>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="text-center info all-comments">
                    <h4><a href="dashboard.php?content=comments">Comments</a></h4>
                    <span>
                        <a href="comments.php?do=Manage"><?php echo checkAllCount("*" , "comments") ;?></a>
                    </span>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="text-center info pend-comments">
                    <h4><a href="dashboard.php?content=comments&page=pending">Pending Comments</a></h4>
                    <span>
                        <a href="comments.php?do=Manage&page=pending"><?php echo checkCount("*" , "comments" , "status" , 0) ;?></a>
                    </span>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="text-center info  all-requests">
                    <h4><a href="dashboard.php?content=requests">Requests</a></h4>
                    <span>
                        <a href="requests.php?do=Manage"><?php echo checkAllCount("*" , "requests") ;?></a>
                    </span>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="text-center info pend-requests">
                    <h4><a href="dashboard.php?content=requests&page=unaccept">Pending Requests</a></h4>
                    <span>
                        <a href="requests.php?do=Manage&page=unaccept"><?php echo checkCount("*" , "requests" , "approval" , 0 ) ;?></a>
                    </span>
                </div>  
            </div>
        </div>
        
        <?php
            if(isset($_GET['content'])){
                $content = $_GET['content'] ;
            }else{
                $content = "members";
            }

            if($content == "members"){
    ?>
        
        <!-------------------- start members ---------------------->
        <div class="member-data">
            
            <?php 
              
                $stmt = $db->prepare("SELECT * FROM users WHERE groupID != 1 AND approval = 0 ORDER BY userID DESC LIMIT 5");
                $stmt->execute();
                $allMembers = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if($count > 0){
                    foreach($allMembers as $members){
            ?>
            
            <div class="row data-show">
                <div class="col-sm-6">
                    <span>
                        <?php 
                        if(empty($members["avatar"])){ ?>
                       <img class="user-img" src="layout/images/user.jpg" alt="userimage" />
                            
                        <?php
                                                      
                        }else{
                            ?>
                            <img class="user-img" src="uploads/avatars/<?php echo $members['avatar'] ; ?>" alt="userimage" />
                       <?php
                        }
                       ?>
                    </span>
                    <span><?php echo $members["name"] ;?></span>
                </div>
                <div class="col-sm-6">
                   <a href="members.php?do=Edit&userid=<?php echo $members['userID'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                   <a href="members.php?do=Delete&userid=<?php echo $members['userID'] ; ?>" class="btn btn-danger confirm"><i class="fa fa-times"></i> Delete</a>
                   <a href="members.php?do=Approve&userid=<?php echo $members['userID'] ; ?>" class="btn btn-info"><i class="fa fa-check"></i> Activate</a>
                </div>
            </div>
            <?php
                    }
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }
            ?>
        </div>
        <!--------------------- end members ----------------------->
        <?php 
            }else if($content == "products"){ 
         ?>
        <!-------------------- start products ---------------------->
        <div class="product-data">
            
            <?php 
              
                $stmt = $db->prepare("SELECT * FROM products ORDER BY pID DESC LIMIT 8");
                $stmt->execute();
                $allProducts = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if($count > 0){
                    
            ?>
            
            <div class="row data-show">
                <?php
                foreach($allProducts as $products){
                    ?>
                <div class="col-sm-3">
                    <div class="pro-info">
                        <div>

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

                        </div>
                        <span><strong><?php echo $products['price'] ?></strong></span>
                        <h5><?php echo $products['name'] ?></h5>
                        <hr>
                        <div class="text-center">
                            <a href="products.php?do=Edit&pid=<?php echo $products['pID'] ; ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="products.php?do=Delete&pid=<?php echo $products['pID'] ; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
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
        <?php 
            }else if($content == "comments"){ 
         ?>
        <!-------------------- start comments ---------------------->
        <div class="comment-data">
            
            <?php 
              
                $query = "";
        
                if(isset($_GET["page"]) && $_GET["page"] == "pending"){
                    $query = "WHERE status = 0";
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

                                                $query
                                     ORDER BY  
                                                cID  DESC 
                                                LIMIT 5");


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
                                <img class="user-img" src="uploads/avatars/<?php echo $comments['user_avatar'] ; ?>" alt="userimage" />
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
                       <div>

                            <?php 
                            if(empty($comments["product_avatar"])){ ?>
                           <img src="layout/images/m1.jpg" alt="image" />

                            <?php

                            }else{
                            ?>
                            <img src="uploads/products/<?php echo $comments['product_avatar'] ; ?>" alt="image" />
                           <?php
                            }
                            ?>

                        </div>
                        <span><strong><?php echo $comments['price'] ?></strong></span>
                        <h5><?php echo $comments['product_name'] ?></h5>
                    </div>
                </div>
                 <a href="comments.php?do=Delete&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                       <?php
                         if($comments["status"] == 0){
                             ?>
                       <a href="comments.php?do=Approve&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-info"><i class="fa fa-check"></i> Activate</a>
                       <?php
                         }
                       ?>
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
            }else if($content == "requests"){ 
         ?>
        <!-------------------- start requests ---------------------->
        <div class="request-data">
            
            <?php 
              
                $query = "";
            if(isset($_GET['page']) && $_GET['page'] == "unaccept"){
               $query = "WHERE requests.approval = 0" ;
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
                                        $query
                                 ORDER BY 
                                        reqID DESC
                                       LIMIT 5");
            $stmt->execute();
            $allRequests = $stmt->fetchAll();
          
            $count = $stmt->rowCount();
     

                if($count > 0){
                    foreach($allRequests as $request){ 
            ?>
            
            <div class="row data-show">
                <div class="col-sm-9">
                            <div class="user-attr">
                            
                                <div>

                                   <?php 
                                    if(empty($request["user_avatar"])){ ?>
                                   <img class="user-img" src="layout/images/user.jpg" alt="image" />

                                    <?php

                                    }else{
                                    ?>
                                    <img class="user-img" src="uploads/avatars/<?php echo $request["user_avatar"] ;?>"/>
                                   <?php
                                    }
                                    ?>


                                </div>
                                <span><?php echo $request['username'] ?></span>
                                
                            </div>
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
                            <div class="pro-info">
                                <div>

                                <?php 
                                if(empty($request["pro_avatar"])){ ?>
                               <img src="layout/images/m1.jpg" alt="image" />

                                <?php

                                }else{
                                ?>
                                <img src="uploads/products/<?php echo $request['pro_avatar'] ; ?>" alt="image" />
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
                        <a class="btn btn-info" href="requests.php?do=Accept&req_id=<?php echo $request['reqID'] ;?>"><i class="fa fa-check"></i> Accept</a>
                        <?php } ?>
                        <a class="btn btn-danger" href="requests.php?do=Delete&req_id=<?php echo $request['reqID'] ;?>"><i class="fa fa-close"></i> Delete</a>
            </div>
            <?php
                    }
                }else{
                    echo "<div class = 'alert alert-danger'> There is no data to show</div>";
                }
            ?>
        </div>
        <!-------------------- start requests ---------------------->
        <?php 
            }
    ?>
    </div>




<?php

include_once $tmp."footer.php";

}else{
    header("location: index.php");
    exit();
}

ob_end_flush();

?>