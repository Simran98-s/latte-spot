<?php
$mysqli = new mysqli('localhost','root','password','coffee_shop');
if ($mysqli->connect_errno) { echo "DB Connect error: ".$mysqli->connect_error; exit; }

$name = $mysqli->real_escape_string($_POST['item_name'] ?? '');
$category = $mysqli->real_escape_string($_POST['category'] ?? '');
$price = (float)($_POST['price'] ?? 0);

if ($name && $price > 0) {
    $mysqli->query("INSERT INTO menu (item_name, category, price) VALUES ('$name','$category',$price)");
}
header("Location: admin.php");
exit;
?>
