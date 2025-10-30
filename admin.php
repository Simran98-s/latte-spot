<?php
$mysqli = new mysqli('localhost','root','password','coffee_shop');
if ($mysqli->connect_errno) { echo "DB Connect error: ".$mysqli->connect_error; exit; }

$menu_res = $mysqli->query("SELECT * FROM menu");
$menu = $menu_res->fetch_all(MYSQLI_ASSOC);

$orders_res = $mysqli->query("SELECT o.order_id,o.order_date,o.total_amount,c.name FROM orders o JOIN customers c ON o.customer_id=c.customer_id ORDER BY o.order_date DESC LIMIT 10");
$orders = $orders_res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin - Coffee Shop</title><link rel="stylesheet" href="style.css"></head>
<body>
  <header class="header"><h1 style="margin:0">Admin Panel</h1></header>
  <div class="container">
    <h2>Add Menu Item</h2>
    <form method="post" action="add_item.php">
      <div class="form-row"><input class="input" name="item_name" placeholder="Item name" required></div>
      <div class="form-row"><input class="input" name="category" placeholder="Category"></div>
      <div class="form-row"><input class="input" name="price" placeholder="Price" required></div>
      <button class="button" type="submit">Add Item</button>
    </form>

    <h2 style="margin-top:20px">Menu Items</h2>
    <table class="menu-table">
      <thead><tr><th>Item</th><th>Category</th><th>Price</th></tr></thead>
      <tbody>
        <?php foreach($menu as $m): ?>
        <tr><td><?=htmlspecialchars($m['item_name'])?></td><td><?=htmlspecialchars($m['category'])?></td><td><?=number_format($m['price'],2)?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2 style="margin-top:20px">Recent Orders</h2>
    <table class="menu-table">
      <thead><tr><th>Order ID</th><th>Customer</th><th>Date</th><th>Total</th></tr></thead>
      <tbody>
      <?php foreach($orders as $o): ?>
        <tr>
          <td><a href="view_order.php?order_id=<?=$o['order_id']?>"><?=$o['order_id']?></a></td>
          <td><?=htmlspecialchars($o['name'])?></td>
          <td><?=htmlspecialchars($o['order_date'])?></td>
          <td><?=number_format($o['total_amount'],2)?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
