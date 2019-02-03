<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo getTitle(); ?></title>
        <link rel="stylesheet" href="<?php echo $css ;?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $css ;?>fontawesome.min.css">
        <link rel="stylesheet" href="<?php echo $css ;?>front.css">
    </head>
    <body>
        
        
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <div class="container">
          <a class="navbar-brand" href="index.php">Tasty</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse nav navbar-nav navbar-right" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="index.php"><?php echo lang("HOME") ;?></a>
              </li>
                <li class="nav-item">
                <a class="nav-link" href="about.php"><?php echo lang("ABOUT US") ;?></a>
              </li>
               
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo lang("PRODUCTS") ;?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="products.php?type=meals">Meals</a>
                  <a class="dropdown-item" href="products.php?type=sandwiches">Sandwiches</a>
                  <a class="dropdown-item" href="products.php?type=drinks">Drinks</a>
                </div>
              </li>
             
              <li class="nav-item">
                <a class="nav-link" href="comments.php"><?php echo lang("COMMENTS") ;?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact.php"><?php echo lang("CONTACT_US") ;?></a>
              </li>
              
            </ul>
            <ul class="nav navbar-nav navbar-right"> 
              <?php 
              
              if(isset($_SESSION['user'])){
                    $username = $_SESSION['user'];

                    $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
                    $stmt->execute(array($username));
                    $user = $stmt->fetch();
    
                  ?>
                    
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <?php 
                        if(empty($user["avatar"])){ ?>
                       <img class="user-img" src="layout/images/user.jpg" alt="userimage" />
                            
                        <?php
                                                      
                        }else{
                            ?>
                            <img class="user-img"  src="admin/uploads/avatars/<?php echo $user['avatar'] ; ?>" alt="userimage" />
                       <?php
                        }
                       ?>
                            <span><?php echo $_SESSION['user'] ;?></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="profile.php">My Profile</a>
                          <a class="dropdown-item" href="profile.php?do=Edit&userid=<?php echo $_SESSION["id"] ;?>">Edit Information</a>
                          <a class="dropdown-item" href="profile.php?do=Manage#my-req">My Requests</a>
                          <a class="dropdown-item" href="profile.php?do=Manage#my-com">My Comments</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                      </li>
                    
              
              <?php
              }else{
                  ?>
              
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><?php echo lang("LOG_IN") ;?></a>
                    </li>
              
              <?php
              }
              
              ?>
              
            </ul>
          </div>
       </div>
    </nav>

        