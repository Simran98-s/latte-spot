<?php
// place_order.php
$mysqli = new mysqli('localhost','root','password','coffee_shop');
if ($mysqli->connect_errno) { echo "DB Connect error: ".$mysqli->connect_error; exit; }

$name = $mysqli->real_escape_string($_POST['name'] ?? '');
$phone = $mysqli->real_escape_string($_POST['phone'] ?? '');
$email = $mysqli->real_escape_string($_POST['email'] ?? '');
$address = $mysqli->real_escape_string($_POST['address'] ?? '');
$qtys = $_POST['qty'] ?? [];

if (!$name) { echo "Name required"; exit; }

// Insert or reuse customer (simple approach: insert always)
$mysqli->query("INSERT INTO customers (name,phone,email,address) VALUES ('$name','$phone','$email','$address')");
$customer_id = $mysqli->insert_id;

// Prepare to calculate total
$total = 0.0;
$items_to_insert = [];

foreach($qtys as $item_id => $q){
    $q = (int)$q;
    if ($q <= 0) continue;
    // get price
    $res = $mysqli->query("SELECT price FROM menu WHERE item_id=" . (int)$item_id);
    if($res && $row = $res->fetch_assoc()){
        $price = (float)$row['price'];
        $subtotal = $price * $q;
        $total += $subtotal;
        $items_to_insert[] = ['item_id'=> (int)$item_id, 'quantity'=>$q, 'subtotal'=>$subtotal];
    }
}

if (count($items_to_insert) == 0) {
    echo "No items selected. Go back and choose an item.";
    exit;
}

// Insert order
$mysqli->query("INSERT INTO orders (customer_id, total_amount) VALUES ($customer_id, $total)");
$order_id = $mysqli->insert_id;

// Insert order_items
$stmt = $mysqli->prepare("INSERT INTO order_items (order_id,item_id,quantity,subtotal) VALUES (?,?,?,?)");
foreach($items_to_insert as $it){
    $stmt->bind_param("iiii", $order_id, $it['item_id'], $it['quantity'], $it['subtotal']);
    // Note: cast subtotal to int for binding; better to use appropriate types or use string and DECIMAL properly.
    $stmt->execute();
}
$stmt->close();

// Redirect to order confirmation (simple)
header("Location: view_order.php?order_id=".$order_id);
exit;
?>
