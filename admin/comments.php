<?php

ob_start();

session_start();

$pageName = "Comments";


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
            $query = "WHERE status = 0";
        }
        
        $stmt = $db->prepare("SELECT 
                                        comments.* , users.name AS username , products.name AS product_name, products.avatar AS product_avatar
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
                                        cID  DESC   ");
        
        
        $stmt->execute();
        $allComments = $stmt->fetchAll();
        $count = $stmt->rowCount();
        
        if($count > 0){

?>


   <div class="container">
       <h2 class="text-center">Manage Comments</h2>
       <div class="text-center table-data">
           <table class="main-table table table-bordered">
               <tr>
                   <td>#ID</td>
                   <td>Comment</td>
                   <td>Registered Date</td>
                   <td>Name</td>
                   <td>Product Name</td>
                   <td>Avatar</td>
                   <td>Control</td>
               </tr>
               <?php
               foreach ($allComments as $comments){
               ?>
        
               <tr>
                   <td><?php echo $comments["cID"] ;?></td>
                   <td><?php echo $comments["comment"] ;?></td>
                   <td><?php echo $comments["date"] ;?></td>
                   <td><?php echo $comments["username"] ;?></td>
                   <td><?php echo $comments["product_name"] ;?></td>
                   <td>
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
                   </td>
                   <td>
                       <a href="comments.php?do=Delete&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-danger confirm"><i class="fas fa-times"></i> Delete</a>
                       <?php
                         if($comments["status"] == 0){
                             ?>
                       <a href="comments.php?do=Approve&cid=<?php echo $comments['cID'] ; ?>" class="btn btn-info"><i class="fa fa-check"></i> Activate</a>
                       <?php
                         }
                       ?>
                   </td>
               </tr>
               <?php
               }
               ?>
           </table>
       </div>
   
        
        
    <?php
        }else{
            
             echo "<div class = 'container'>
                           <div class = 'alert alert-danger'> There is no data to show</div>
                      </div>";
            
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
            getMessage("This comment is not exist" , "class = 'alert alert-danger'" ,"back");
        }


       
        ?>
             
         </div> 
<?php

    }else if($do == "Approve"){// delete page    ?>

         <div class="container">
            <h2 class="text-center">Activate Comments</h2>
        
    <?php
             
        if(isset($_GET["cid"]) && is_numeric($_GET["cid"]) ){
            $cid = intval ($_GET["cid"]);
        }else{
            $cid= 0 ;
        }
        
        $check = checkCount("*" , "comments" ,"cID" , $cid);
        if($check > 0){
            
            $stmt = $db->prepare("UPDATE comments SET status = 1 WHERE cID = ? ");

            $stmt->execute(array($cid));
            
            
            getMessage($stmt->rowCount()."Record Activated" , "class = 'alert alert-success'" ,"back");


        }else{
            getMessage("This comment is not exist" , "class = 'alert alert-danger'" ,"back");
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