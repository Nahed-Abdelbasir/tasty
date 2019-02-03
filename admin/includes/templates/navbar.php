
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
   <div class="container">
      <a class="navbar-brand" href="dashboard.php">Tasty</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="dashboard.php"><?php echo lang("HOME_ADMIN") ;?></a>
          </li>
            <li class="nav-item">
            <a class="nav-link" href="members.php"><?php echo lang("MEMBERS") ;?></a>
          </li>
            <li class="nav-item">
            <a class="nav-link" href="products.php"><?php echo lang("PRODUCTS") ;?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="comments.php"><?php echo lang("COMMENTS") ;?></a>
          </li>
            <li class="nav-item">
            <a class="nav-link" href="requests.php"><?php echo lang("REQUESTS") ;?></a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              More...
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../index.php">Visit Shop</a>
              <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION["id"] ;?>">Edit Profile</a>
              <a class="dropdown-item" href="#">Setting</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
          </li>
         </ul>
      </div>
   </div>
</nav>
