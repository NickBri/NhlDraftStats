
<!DOCTYPE html>
<?php
   ob_start();
   session_start();
   if (isset($_POST['return'])) {
    session_destroy();
    $return = urldecode('login.php'.$_POST['return']);
        header("Location: $return");
        exit;   
}
?>
<html>
<head>
  <title>Hockey Parts</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 12px;
    }

    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
      border-radius: 12px;
      margin: 15px;
      background-color: lavender;
    }
   

    /* add margin to top of table*/
    .panel-primary{
      margin-top: 100px;
    }

   /* Add nice background*/
   body {
      background-image:  url("/hockeyParts/img/hero.png");
      background-repeat: repeat;
      background-color: #cccccc;
      border-radius: 12px;
    }
    input[type=button], input[type=submit]{
      background-color: #4CAF50;
      border: none;
      color: lavender;
      padding: 10px 32px;
      text-decoration: none;
      cursor: pointer;
      margin: 15px;
    }

    .orderButtonWrap {
      text-align: center;
    } 
  </style>
</head>
<body>
<div class="jumbotron">
  <div class="container text-center">
    <h1>Products</h1><br>
    <?php
echo  $_SESSION["username"];
?><br>
<div class = "orderButtonWrap">
<form action="login.php" method="post">
    <input name="return" type="hidden" value="<?php echo urlencode($_SERVER["PHP_SELF"]);?>" />
    <input type="submit" value="logout" />
</form>  
</div>    
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="/hockeyParts/display_parts.php">Products</a></li>
        <li><a href="/hockeyParts/display_customers.php">Account Info</a></li>
        <li><a href="/hockeyParts/display_invoices.php">Invoices</a></li>
        <li><a href="/hockeyParts/display_orders.php">Orders</a></li>
        
      </ul>
      <ul class="nav navbar-nav navbar-right">
      </ul>
    </div>
  </div>
</nav>
  
<?php
require 'rb.php';
//make connection 
$username = "root"; 
$password = "soccerball007"; 
$database = "hockey_shop"; 
$mysqli = new mysqli("localhost", $username, $password, $database); 


//retrieve and display all values from hockeyProduct206 table
$query = "SELECT * FROM hockeyProduct206"; 
echo '
<form method="post">
<div class="container">    
  <div class="row">
      <div class="panel panel-primary">
        <div class="panel-heading">Hockey Products</div>
        <div class="panel-body">
        <table border="2" cellspacing="20" cellpadding="5" width = 100%> 
      <tr> 
          <td> <font face="Arial">Product Id: </font> </td> 
          <td> <font face="Arial">Product Name: </font> </td> 
          <td> <font face="Arial">Product Quantity: </font> </td> 
          <td> <font face="Arial">Product Manufacturer Price: </font> </td> 
          <td> <font face="Arial">Store Id Supplied: </font> </td> 
          <td> <font face="Arial">SALE (% off): </font> </td> 
          <td> <font face="Arial">Purchase Amount:</font> </td> 
       
      </tr>';
if ($result = $mysqli->query($query)) {
  $x = 0;
  while ($row = $result->fetch_assoc()) {
      $field1name = $row["productId206"];
      $field2name = $row["pro_Name206"];
      $field3name = $row["pro_Qty206"];
      $field4name = $row["pro_Price206"];
      $field5name = $row["store_storeId206"];
      $field6name = $row["productDiscount206"];
      $x++;
    echo '<tr> 
    <td><input type="number" name="productId_'.$x.'" value="'.$field1name.'"readonly></td>
    <td><input type="text" name="productName_'.$x.'"value="'.$field2name.'"readonly></td>
    <td><input type="number" name="productLeft_'.$x.'" value="'.$field3name.'"readonly></td>
    <td><input type="number" name="price_'.$x.'" value="'.$field4name.'"readonly></td>
    <td><input type="text" name="store_'.$x.'" value="'.$field5name.'"readonly></td> 
    <td><input type="text" name="discount_'.$x.'" value="'.$field6name.'"readonly></td> 
    <td><input type="number" step="0.01"  name = "AMT_'.$x.'"></td>
    <td><input type="submit" name = "product_'.$x.'" id="buy" value = "Buy" onclick = "submitQuery()"</td> 
</tr>';
  
  }
}
   echo'   </form></table></div>
        </div>
      </div>
    
  </div>
  </div>';

  //if a purchase button was clicked and the username 
  //is nbrisson for the customer with id 10
  if($_SESSION["username"] == 'nbrisson'){
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //if the name of the button is for product 1
    if (isset($_POST["product_1"])){ 
        $qty = ($_POST["AMT_1"]); 
        $prodId =  ($_POST["productId_1"]);
        $price =  ($_POST["price_1"]);
        $prodName = ($_POST["productName_1"]);
        $discount = ($_POST["discount_1"]);
        $randomNumber1 = rand(); 
        //create new invoice
        $sqlINV = "
        INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
        VALUES (now(), 10)";
        //create new order
        $sqlORD = "
        INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
        VALUES ($randomNumber1, $prodId, $qty, $price, 20, 10)";
        //if both queries are successful, load purchased page.
        if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
          echo '<script type="text/javascript">

          { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
          header("Refresh:0");
          </script>';
        } 
        //else notify user that there was an error.
        else{
          echo '<script type="text/javascript">
          alert("ERROR: Could not able to execute $sqlINV. ");
          </script>';
        }
      }

      //if the name of the button is for product 2
    if (isset($_POST["product_2"])){ 
      $qty = ($_POST["AMT_2"]); 
      $prodId =  ($_POST["productId_2"]);
      $price =  ($_POST["price_2"]);
      $prodName = ($_POST["productName_2"]);
      $discount = ($_POST["discount_2"]);
      $randomNumber1 = rand(); 
      //create new invoice
      $sqlINV = "
      INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
      VALUES (now(), 10)";
      //create new order
      $sqlORD = "
      INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
      VALUES ($randomNumber1, $prodId, $qty, $price, 10, 10)";
      //if both queries are successful, load purchased page.
      if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
        echo '<script type="text/javascript">

        { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
        header("Refresh:0");
        </script>';
      } 
      //else notify user that there was an error.
      else{
        echo '<script type="text/javascript">
        alert("ERROR: Could not able to execute $sqlINV. ");
        </script>';
      }
    }


     //if the name of the button is for product 3
     if (isset($_POST["product_3"])){ 
      $qty = ($_POST["AMT_3"]); 
      $prodId =  ($_POST["productId_3"]);
      $price =  ($_POST["price_3"]);
      $prodName = ($_POST["productName_3"]);
      $discount = ($_POST["discount_3"]);
      $randomNumber1 = rand(); 
      //create new invoice
      $sqlINV = "
      INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
      VALUES (now(), 10)";
      //create new order
      $sqlORD = "
      INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
      VALUES ($randomNumber1, $prodId, $qty, $price, 0, 10)";
      //if both queries are successful, load purchased page.
      if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
        echo '<script type="text/javascript">

        { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
        header("Refresh:0");
        </script>';
      } 
      //else notify user that there was an error.
      else{
        echo '<script type="text/javascript">
        alert("ERROR: Could not able to execute $sqlINV. ");
        </script>';
      }
    }


    //if the name of the button is for product 4
    if (isset($_POST["product_4"])){ 
      $qty = ($_POST["AMT_4"]); 
      $prodId =  ($_POST["productId_4"]);
      $price =  ($_POST["price_4"]);
      $prodName = ($_POST["productName_4"]);
      $discount = ($_POST["discount_4"]);
      $randomNumber1 = rand(); 
      //create new invoice
      $sqlINV = "
      INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
      VALUES (now(), 10)";
      //create new order
      $sqlORD = "
      INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
      VALUES ($randomNumber1, $prodId, $qty, $price, 15, 10)";
      //if both queries are successful, load purchased page.
      if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
        echo '<script type="text/javascript">

        { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
        header("Refresh:0");
        </script>';
      } 
      //else notify user that there was an error.
      else{
        echo '<script type="text/javascript">
        alert("ERROR: Could not able to execute $sqlINV. ");
        </script>';
      }
    }
    }
  }
  /*
* This feature could be made more efficient by checking the username when 
* inserting the values directly into the database, but given the time 
* constraint it cannot be done for this project.
* This is a future implementation item for inhanced efficiency 
* and security purposes.
  */
  
  //if a purchase button was clicked and the username 
  //is cpaine for the customer with id 11
  if($_SESSION["username"] == 'cpaine'){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //if the name of the button is for product 1
      if (isset($_POST["product_1"])){ 
          $qty = ($_POST["AMT_1"]); 
          $prodId =  ($_POST["productId_1"]);
          $price =  ($_POST["price_1"]);
          $prodName = ($_POST["productName_1"]);
          $discount = ($_POST["discount_1"]);
          $randomNumber1 = rand(); 
          //create new invoice
          $sqlINV = "
          INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
          VALUES (now(), 11)";
          //create new order
          $sqlORD = "
          INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
          VALUES ($randomNumber1, $prodId, $qty, $price, 20, 11)";
          //if both queries are successful, load purchased page.
          if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
            echo '<script type="text/javascript">
  
            { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
            header("Refresh:0");
            </script>';
          } 
          //else notify user that there was an error.
          else{
            echo '<script type="text/javascript">
            alert("ERROR: Could not able to execute $sqlINV. ");
            </script>';
          }
        }
  
        //if the name of the button is for product 2
      if (isset($_POST["product_2"])){ 
        $qty = ($_POST["AMT_2"]); 
        $prodId =  ($_POST["productId_2"]);
        $price =  ($_POST["price_2"]);
        $prodName = ($_POST["productName_2"]);
        $discount = ($_POST["discount_2"]);
        $randomNumber1 = rand(); 
        //create new invoice
        $sqlINV = "
        INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
        VALUES (now(), 11)";
        //create new order
        $sqlORD = "
        INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
        VALUES ($randomNumber1, $prodId, $qty, $price, 10, 11)";
        //if both queries are successful, load purchased page.
        if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
          echo '<script type="text/javascript">
  
          { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
          header("Refresh:0");
          </script>';
        } 
        //else notify user that there was an error.
        else{
          echo '<script type="text/javascript">
          alert("ERROR: Could not able to execute $sqlINV. ");
          </script>';
        }
      }
  
  
       //if the name of the button is for product 3
       if (isset($_POST["product_3"])){ 
        $qty = ($_POST["AMT_3"]); 
        $prodId =  ($_POST["productId_3"]);
        $price =  ($_POST["price_3"]);
        $prodName = ($_POST["productName_3"]);
        $discount = ($_POST["discount_3"]);
        $randomNumber1 = rand(); 
        //create new invoice
        $sqlINV = "
        INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
        VALUES (now(), 11)";
        //create new order
        $sqlORD = "
        INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
        VALUES ($randomNumber1, $prodId, $qty, $price, 0, 11)";
        //if both queries are successful, load purchased page.
        if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
          echo '<script type="text/javascript">
  
          { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
          header("Refresh:0");
          </script>';
        } 
        //else notify user that there was an error.
        else{
          echo '<script type="text/javascript">
          alert("ERROR: Could not able to execute $sqlINV. ");
          </script>';
        }
      }
  
  
      //if the name of the button is for product 4
      if (isset($_POST["product_4"])){ 
        $qty = ($_POST["AMT_4"]); 
        $prodId =  ($_POST["productId_4"]);
        $price =  ($_POST["price_4"]);
        $prodName = ($_POST["productName_4"]);
        $discount = ($_POST["discount_4"]);
        $randomNumber1 = rand(); 
        //create new invoice
        $sqlINV = "
        INSERT INTO invoice206 (inv_Date206, customer_customerId1206) 
        VALUES (now(), 11)";
        //create new order
        $sqlORD = "
        INSERT INTO orders206 (orderId206, hockeyProduct_productId206, order_Units206, order_Price206, orderDiscount206, orders_customerId206)
        VALUES ($randomNumber1, $prodId, $qty, $price, 15, 11)";
        //if both queries are successful, load purchased page.
        if((mysqli_query($mysqli, $sqlINV)) && (mysqli_query($mysqli, $sqlORD))){
          echo '<script type="text/javascript">
  
          { alert("Order Placed. Details:  Order Id: '.$randomNumber1.', productName: '.$prodName.', Order Units: '.$qty.', Order Price: '.$price.', Store Id: 0"); }
          header("Refresh:0");
          </script>';
        } 
        //else notify user that there was an error.
        else{
          echo '<script type="text/javascript">
          alert("ERROR: Could not able to execute $sqlINV. ");
          </script>';
        }
      }
      }
    }

  
    
    $result->free();
?>


</body>
</html>