<?php
// view_order.php
$mysqli = new mysqli('localhost','root','password','coffee_shop');
if ($mysqli->connect_errno) { echo "DB Connect error: ".$mysqli->connect_error; exit; }
$order_id = (int)($_GET['order_id'] ?? 0);
if (!$order_id) { echo "Order not found"; exit; }

$res = $mysqli->query("SELECT o.order_id,o.order_date,o.total_amount,c.name FROM orders o JOIN customers c ON o.customer_id=c.customer_id WHERE o.order_id=$order_id");
$order = $res->fetch_assoc();
$items_res = $mysqli->query("SELECT oi.quantity,oi.subtotal,m.item_name FROM order_items oi JOIN menu m ON oi.item_id=m.item_id WHERE oi.order_id=$order_id");
$items = $items_res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Order #<?=$order['order_id']?></title><link rel="stylesheet" href="style.css"></head>
<body>
  <header class="header"><h1 style="margin:0">Order Confirmation</h1></header>
  <div class="container">
    <h2>Order #<?=htmlspecialchars($order['order_id'])?></h2>
    <p><strong>Customer:</strong> <?=htmlspecialchars($order['name'])?></p>
    <p><strong>Date:</strong> <?=htmlspecialchars($order['order_date'])?></p>

    <table class="menu-table">
      <thead><tr><th>Item</th><th>Qty</th><th>Subtotal</th></tr></thead>
      <tbody>
        <?php foreach($items as $it): ?>
        <tr>
          <td><?=htmlspecialchars($it['item_name'])?></td>
          <td><?=htmlspecialchars($it['quantity'])?></td>
          <td><?=number_format($it['subtotal'],2)?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h3>Total: ₹<?=number_format($order['total_amount'],2)?></h3>
    <p><a class="button" href="index.php">Back to Menu</a> <a class="button" href="admin.php">Go to Admin</a></p>
  </div>
</body>
</html>
