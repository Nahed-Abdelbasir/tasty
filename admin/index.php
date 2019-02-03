<?php

ob_start();

session_start();

$pageName = "Index";
$nonavbar="";


if(isset($_SESSION['user'])){
    header("location: dashboard.php"); 
}

include_once "init.php";

    // get admin name and password from the form

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $adminName = $_POST["admin"];
        $password  = $_POST["pass"];
        $pass      = sha1($password);

    

    // find if the name & password from the form is exist in database and this is admin or not

    $stmt = $db->prepare("SELECT 
                                userID , name , password
                          FROM
                               users
                          WHERE 
                                name = ?
                          AND
                               password = ?
                          AND
                               groupID = 1
                          LIMIT 1");

    $stmt->execute(array($adminName ,$pass ));
    $row   = $stmt->fetch();
    $count = $stmt->rowCount();

    if($count > 0){
        $_SESSION['user'] = $adminName;           // register session name
        $_SESSION['id'] = $row["userID"];         // register session id
        header("location: dashboard.php");        // redirect to dashboard page
        exit();
    }
      }

    ?>

 
     <form class="admin-login" action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
         <h3 class="text-center">Admin Login</h3>
         <input class="form-control" type="text" name="admin" placeholder="username" autocomplete="off"/>
         <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password"/>
         <input class="btn btn-primary btn-block" type="submit" value="login" />
     </form>


<?php

include_once $tmp."footer.php";

ob_end_flush();

?>