<?php

function lang($phrase){
    
   static $lang = array(
       
       "HOME"       => "Home",
       "ABOUT US"   => "About Us",
       "PRODUCTS"   => "Products",
       "COMMENTS"   => "Comments",
       "LOG_IN"     => "Log In/Sign Up",
       "CONTACT_US" => "Contact Us"
       
   );
    
    return $lang[$phrase];
}

?>