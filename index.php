<?php
// index.php
$mysqli = new mysqli('localhost','root','password','coffee_shop');
if ($mysqli->connect_errno) { echo "DB Connect error: ".$mysqli->connect_error; exit; }

$result = $mysqli->query("SELECT * FROM menu");
$menu = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Coffee Shop - Menu</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="header" ><h1 style="margin:0">Latte Spot</h1></header>
  <div class="container">
    <h2>Menu</h2>
    <form method="post" action="place_order.php">
      <table class="menu-table">
        <thead>
          <tr><th>Item</th><th>Category</th><th>Price (₹)</th><th>Qty</th></tr>
        </thead>
        <tbody>
          <?php foreach($menu as $m): ?>
          <tr>
            <td><?=htmlspecialchars($m['item_name'])?></td>
            <td><?=htmlspecialchars($m['category'])?></td>
            <td><?=number_format($m['price'],2)?></td>
            <td>
              <input class="input" type="number" name="qty[<?=$m['item_id']?>]" min="0" value="0" style="width:70px">
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h3>Your details</h3>
      <div class="form-row"><input class="input" name="name" placeholder="Your name" required></div>
      <div class="form-row"><input class="input" name="phone" placeholder="Phone"></div>
      <div class="form-row"><input class="input" name="email" placeholder="Email"></div>
      <div class="form-row"><input class="input" name="address" placeholder="Address"></div>

      <div style="margin-top:14px;">
        <button class="button" type="submit">Place Order</button>
	    
      </div>
      <p class="note">Note: quantities left as 0 are ignored.</p>
    </form>
  </div>
</body>
</html>
