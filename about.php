<?php

ob_start();
session_start();

$pageName = "About Us";

include_once "init.php";
    

?>

<div class="container">
    <h2 class="text-center">About Us</h2>
    <div class="row about">
        <div class="col-sm-12 col-md-6">
            <h4>Tasty Resturant : </h4>
            <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </p>    
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="right-img">
                <img src="layout/images/img5.jpg" alt=image/>
            </div>
        </div>
    </div>
    <div class="row about">
        <div class="col-sm-12 col-md-6">
            <div class="left-img">
                <img src="layout/images/img2.jpg" alt=image/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <h4>Tasty Resturant : </h4>
            <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </p>
        </div>
    </div>
</div>

<?php

include_once $tmp."footer.php";

ob_end_flush();

?>