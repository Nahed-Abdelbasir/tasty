<?php

ob_start();
session_start();

$pageName = "Contact Us";
$nosection="";

include_once "init.php";


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $name      = $_POST["name"];
        $email     = $_POST["email"];
        $msg  = $_POST["message"];
       
        
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
        
        
        // if there is no errors send email [ mail(to , subject , message , headers , parameters) ]
        
        $myEmail = "nahed771993@gmail.com";
        $subject = "Website Tasty Message";
        $headers = "From:".$filteredEmail."\r\n";
        
        if(empty($formErrors)){
            
            $send_mail = mail($myEmail ,$subject , $filteredMsg, $headers );
            
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

<!--------------- start contact us ------------------>

<div class="contact text-center">
    <div class="container">
        <h2>Contact Us</h2>
        <div class="form-msg">
            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ;?>" method="post">
            
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
                <textarea minlength="10" class="form-control" name="message" placeholder="Write your message" required></textarea>
                <!------------------ end message --------------->
                <!------------------ start submit --------------->
                <input class="btn btn-primary btn-block" type="submit" value = "Send" />
                <!------------------ end submit --------------->
            </form>
        </div>
        
        <?php
         
        if(!empty($formErrors)){
             foreach($formErrors as $error){
                    echo "<div class='alert alert-danger'>".$error."</div>";   
             }
        }
        ?>
        
    </div>
</div>
 
<!----------------- end contact us ------------------->


<?php

include_once $tmp."footer.php";

ob_end_flush();

?>