<?php

/*
** getTitle function v1.0
** to get page name 
*/

function getTitle() {
    
    global $pageName ;
    
    if(isset($pageName)){
        echo $pageName;
    }else{
        echo "Default";
    }
    
}


/*
** getAll function v1.0
** to get all data the table
*/

function getAll($select , $table , $where = null , $and = null , $orderfield , $order = "DESC" ) {
    
    global $db ;
    
    $stmt = $db->prepare("SELECT $select FROM $table $where $and ORDER BY $orderfield $order");
    $stmt->execute();
    $row = $stmt->fetchAll();
    
    return $row;
    
}


/*
** checkCount function v1.0
** to get count of all data in table
*/

function checkAllCount($select , $table) {
    
    global $db ;
    
    $stmt = $db->prepare("SELECT $select FROM $table");
    $stmt->execute();
    $count = $stmt->rowCount();
    
    return $count;
}


/*
** checkCount function v1.0
** to get count 
*/

function checkCount($select , $table , $field , $value , $and=null) {
    
    global $db ;
    
    $stmt = $db->prepare("SELECT $select FROM $table WHERE $field = ? $and");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();
    
    return $count;
}


/*
** getMessage function v1.0
** to get message 
*/

function getMessage($msg = null, $class , $back = null) {
    
    if($back == null){
        $page = "Home Page";
        $link = "dashboard.php";
        
    }else{
        if(isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] !== ""){
            $page = "Previous Page";
            $link = $_SERVER["HTTP_REFERER"];
        }else{
            $page = "Home Page";
            $link = "dashboard.php";
        }
        
    }
    
    if($msg != null){
        echo "<div $class >" . $msg . "</div>";
    }
    echo  "<div class= 'alert alert-info' > You will redirected to $page after 4 seconds </div>";
    header("refresh:4;url=$link");
    exit();
}
    










?>