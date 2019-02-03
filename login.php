<?php

ob_start();

session_start();

$pageName  = "Login";

$nosection="";

if(isset($_SESSION['user'])){
    header("location: index.php");
   
}

include_once "init.php";

if(isset($_POST["login"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $name     = $_POST["username"];
        $password = $_POST["password"];
        $pass     = sha1($password);
        
        $stmt = $db->prepare("SELECT 
                                    userID , name , password
                              FROM
                                     users
                              WHERE 
                                     name = ?
                               AND 
                                     password = ? ");
        
        $stmt->execute(array($name , $pass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if ($count > 0){
            
            $_SESSION["user"] = $name;
            $_SESSION["id"] = $row['userID'];
            
            header("location: index.php");
            exit();
        }

    }
}else if(isset($_POST["sign"])){
    
    
        $name      = $_POST["username"];
        $password  = $_POST["password"];
        $password2 = $_POST["password2"];
        $nickname  = $_POST["nickname"];
        $email     = $_POST["email"];
        
        $formErrors = array();
        
        if(isset($name)){
            $filteredUser = filter_var($name , FILTER_SANITIZE_STRING);
            if(strlen($filteredUser) < 5){
                $formErrors[] = "Username must be more than <strong> 4 </strong> characters";
            }
        }
        
        if(isset($password) && isset($password2)){
            if(empty($password)){
                 $formErrors[] = "Password can't be <strong> empty </strong>";
            }
            if(strlen($password) < 5){
                $formErrors[] = "Password must be more than <strong> 4 </strong>";
            }
            if(sha1($password) != sha1($password2)){
                 $formErrors[] = "Sorry password is not match";
            }
            
        }
        
        if(isset($email)){
            $filteredEmail = filter_var($email , FILTER_SANITIZE_EMAIL);
            if(filter_var($filteredEmail , FILTER_VALIDATE_EMAIL) != true){
                $formErrors[] = "This email is not valid";
            }
        }
        
        // check if there is error or not
    
        if(empty($formErrors)){
            
            $check = checkCount("*" , "users" , "name" , $name);
            
            if($check > 0){
                 
                $formErrors[] = "Sorry this user is exist";
            }else{
                
                // insert user info in database 

                $stm = $db -> prepare('INSERT INTO 
                                              users (name, password, nickname, email, approval, date) 
                                              VALUES (:zuser, :zpass,:znick , :zmail, 0, now())');
                $stm -> execute(array(

                    "zuser" => $name,
                    "zpass" => sha1($password), 
                    "znick" => $nickname,
                    "zmail" => $email 
                ));

                // echo success message

            $successMsg = "Congrats you are now registered user";
                
            }
            
        }
        
        
   
}

?>


<div class="container user-form">
    <h2 class="text-center"><span class="select" data-class=".login">Login</span> | <span data-class=".sign-up">Sign Up</span></h2>
    
    <!-- start login form -->
    
    <div class="login active">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input class="form-control" type="text" name="username" placeholder="enter username" autocomplete="off" required="required" />
            <input class="form-control" type="password" name="password" placeholder="enter password" autocomplete="new-password" required="required" />
            <input class="btn btn-primary btn-block" type="submit" name="login" value="Login"/>
        </form>
    </div>
    
    <!-- end login form -->
    <!-- start sign up form -->
    
    <div class="sign-up">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input pattern=".{5,}" title="username must be more than 4 characters" class="form-control" type="text" name="username" placeholder="enter username" autocomplete="off" required="required" />
            <input minlength="5" class="form-control" type="password" name="password" placeholder="enter password" autocomplete="new-password" required="required" />
            <input minlength="5" class="form-control" type="password" name="password2" placeholder="enter password again" autocomplete="new-password" required="required" />
            <input class="form-control" type="text" name="nickname" placeholder="enter nickname" autocomplete="off" />
            <input class="form-control" type="email" name="email" placeholder="enter your email" required="required" />
            <input class="btn btn-success btn-block" type="submit" name="sign" value="Sign Up"/>
        </form>
    </div>
    
    <!-- end sign up form -->
    
    <div class="text-center">
        
        <?php 
    
        if(!empty($formErrors)){
            foreach($formErrors as $error){
                echo "<div class= 'alert alert-danger'>" . $error . "</div>";
            }
          }
          
          if(isset($successMsg)){
               echo "<div class= 'alert alert-success'>" . $successMsg . "</div>";
          }
        ?>
       
    </div>
    
</div>








<?php

include_once $tmp."footer.php";
ob_end_flush();          

?>