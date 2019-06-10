<?php 
include("config.php");
session_start();
$user=$_SESSION['user'];






?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


   
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <title>Hello, world!</title>

  </head>
  <body>           
        <nav class="navbar navbar-expand-lg navbar-info bg-info">
            <img src="images/logo.png" class="img-fluid rounded-circle">
           <a class="navbar-brand text-white" href="#">&nbsp;ABC Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
              <div class="navbar-nav">
                <a class="nav-item nav-link text-white active" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link text-white" href="#">Product</a>
                <a class="nav-item nav-link text-white" href="#">FAQ</a>
                <a class="nav-item nav-link text-white" href="#">Contact</a>   
                          
              </div>     

                <form class="form-inline" action="product_list.php" method="post">
                  <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-primary my-2 my-sm-0 " type="submit">Search</button>
                </form>

                <?php
                    $user="";
                    if(isset($_GET['u'])){
                        if($_GET['u']=='logout'){
                        session_destroy(); //clear $user value
                        echo "<script>window.location.assign('index.html');</script>";
                        }
                    }

                    if(isset($_SESSION['user'])){ 
                        echo "<a class='nav-link text-white' href='#'>".$_SESSION['user']."</a>";
                        $user=$_SESSION['user'];
                    }

    $countitem="SELECT count(*) as countitem FROM cart WHERE userID='$user' and orderID=''";
    $cart = $conn->query($countitem);
        if ($cart->num_rows > 0) {                    
            while($row = $cart->fetch_assoc()) {
                $count=$row['countitem'];
                    echo "<h5><a class='nav-link text-white' href='myCart.php'>Cart<span class='badge badge-danger'>$count</span></a> </h5>";
            }
        }                 

                    if($user==""){            
                        echo '<a class="nav-link text-white" href="index.html">Login</a>';
                    }
                    else{
                        echo '<a class="nav-link text-white" href="product_detail.php?u=logout">Logout</a>';
                    }

                ?>
              
            </div>
        </nav>
        <div class="container-fluid">
        <form method="post" action="product_detail.php?ID=<?php echo $_GET['ID'] ?>">
            <div class="row" style="padding-top:20px">
                <div class="col-md-2">
                    <ul class="list-group">
                        <li class="list-group-item active">Brands</li>
                        <li class="list-group-item"><a href="product_list.php?category=Samsung">Samsung</a></li>
                        <li class="list-group-item"><a href="product_list.php?category=Asus">Asus</a></li>
                        <li class="list-group-item"><a href="product_list.php?category=Oppo">Oppo</a></li>
                    </ul>                
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-8">
    <?php
        $sql="select b.ID,b.title,b.description,b.image,b.price,a.pQuantity,b.available from cart as a left join product_detail as b on a.productID=b.ID where a.userID='$user'";
        $result=$conn->query($sql); //run SQL
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $ID=$row['ID'];
              $title=$row['title'];
              $description=$row['description'];
              $image=$row['image'];
              $price=$row['price'];
              $quantity=$row['pQuantity'];
              $available=$row['available'];
           
    ?>
            
    <tr>
        <td>
            <div class="row">
                <div class="col-md-1">
                    <?php
                        if($available==1){
                            echo '<input type="checkbox" name="item[]" value="" />';
                        }
                        else{
                            echo 'N/A <input type="checkbox" name="item[]" value="" disabled />';
                        }
                    ?>	
                </div>
                <div class="col-md-2">
                <img src="images/<?php echo $image; ?>" class="img-fluid" />
                </div>
                <div class="col-md-5">
                    <h6><?php echo $title; ?></h6>
					<div><?php echo $description; ?></div></div>
                <div class="col-md-4">RM  <?php echo $price; ?> 
                    <input type="text" value="<?php echo $quantity; ?>" name="quantity[]" size="1" id="quantity[]" onchange="cal(this.value)" />
                    <input type="text" value="<?php echo $quantity*$price; ?>" name="subamount[]" size="6" id="subamount[]"/></div>
            </div>
        </td>
    </tr>
    <?php
        }
    } 
    ?>
                </div>
                <div class="col-md-1"></div>
            </div>
            </form>
        </div>
    </body>

</html>