<?php

ob_start();

session_start();

$pageName = "Requests";



if(isset($_SESSION['user'])){
   
    include_once "init.php";
    
    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{
        $do = "Manage";
    }

        if($do == "Manage"){
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
                                       ");
            $stmt->execute();
            $allRequests = $stmt->fetchAll();
          
            $count = $stmt->rowCount();
     
            if($count > 0){
        ?>

            <div class="container request-manage">
                <h2 class="text-center">Manage Request</h2>
                <a class="btn btn-info" href="requests.php?page=unaccept"> unaccepted Requests </a>
                <a class="btn btn-info" href="requests.php"> All Requests </a>
                    <?php
                    foreach($allRequests as $request){ ?>
                    <div class="row request-info">
                        <div class="col-sm-9">
                            <div class="user-attr">
                            
                                    <div>

                               <?php 
                                if(empty($request["user_avatar"])){ ?>
                               <img class="user-img" src="layout/images/user.jpg" alt="image" />

                                <?php

                                }else{
                                ?>
                                <img class="user-img" src="uploads/avatars/<?php echo $request['user_avatar'] ;?>"/>
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
                        <a class="btn btn-danger" href="requests.php?do=Delete&req_id=<?php echo $request['reqID'] ;?>"><i class="fa fa-times"></i> Delete</a>
                    </div>
                    <?php } ?>
                
            </div>





        <?php
            }else{
                echo "<div class = 'container'>
                           <div class = 'alert alert-danger'> There is no data to show</div>
                      </div>";
            }
        }else if($do == "Accept"){ 
            
            if(isset($_GET["req_id"]) && is_numeric($_GET["req_id"])){
                    $req_id = intval ($_GET["req_id"]);
                }else{
                    $req_id = 0;
                }
              $count =checkCount("*" , "requests" , "reqID" , $req_id) ;
            if($count > 0){

       ?>
           <div class="container">
                <h2 class="text-center">Accept Request</h2>

              <?php

                $stmt = $db->prepare("UPDATE requests SET approval = 1 WHERE reqID = ?");
                $stmt->execute(array($req_id));
                
                
                getMessage($stmt->rowCount()."Record Accepted", "class='alert alert-success'" , "back");

                ?>
         </div>

      <?php
            }else{
                getMessage("This request is not exist", "class='alert alert-danger'" , "back");
            }
        }else if($do == "Delete"){
            
           if(isset($_GET["req_id"]) && is_numeric($_GET["req_id"])){
                    $req_id = intval ($_GET["req_id"]);
                }else{
                    $req_id = 0;
                }
              $count =checkCount("*" , "requests" , "reqID" , $req_id) ;
            if($count > 0){ 
                
        ?>
                

         <div class="container">
                <h2 class="text-center">Accept Request</h2>

              <?php

                $stmt = $db->prepare("DELETE FROM requests WHERE reqID = :zid");
                $stmt->bindParam(":zid" , $req_id);
                $stmt->execute();
                
                
                getMessage($stmt->rowCount()."Record Deleted", "class='alert alert-success'" , "back");

                ?>
         </div>


        <?php
                
            }else{
                getMessage("This request is not exist", "class='alert alert-danger'" , "back");
            }

        }
    

include_once $tmp."footer.php";

}else{
    header("location: index.php");
    exit();
}

ob_end_flush();

?>