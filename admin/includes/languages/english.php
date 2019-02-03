<?php

function lang($phrase){
    
   static $lang = array(
       
       "HOME_ADMIN" => "Home",
       "MEMBERS"    => "Members",
       "PRODUCTS"   => "Products",
       "COMMENTS"   => "Comments",
       "REQUESTS"   => "requests"
       
   );
    
    return $lang[$phrase];
}

?>