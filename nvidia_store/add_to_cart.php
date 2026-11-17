
<?php
session_start();
require_once "database.php";

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$productId = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);

if (!$productId || $productId <= 0) {
    header("Location: index.php");
    exit();
}

// Get product stock
$sql = "SELECT id, stock FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();

if (!$product || $product["stock"] <= 0) {
    header("Location: index.php");
    exit();
}

// Create cart if it doesn't exist
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Current quantity in cart
$currentQuantity = $_SESSION["cart"][$productId] ?? 0;

// Check available stock
if ($currentQuantity < $product["stock"]) {
    $_SESSION["cart"][$productId] = $currentQuantity + 1;
}

// Open the cart page
header("Location: cart.php");
exit();
?>
