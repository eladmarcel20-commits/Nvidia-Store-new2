


<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

$message = "";

// Update quantity or remove a product
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productId = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);
    $action = $_POST["action"] ?? "";

    if ($productId && isset($_SESSION["cart"][$productId])) {

        if ($action === "remove") {

            unset($_SESSION["cart"][$productId]);

        } elseif ($action === "update") {

            $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);

            $sql = "SELECT stock FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();

            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            $stmt->close();

            if (!$product || $product["stock"] <= 0) {

                unset($_SESSION["cart"][$productId]);

            } elseif ($quantity !== false && $quantity !== null && $quantity > 0) {

                $_SESSION["cart"][$productId] = min(
                    $quantity,
                    (int)$product["stock"]
                );
            }
        }
    }

    header("Location: cart.php");
    exit();
}

// Get cart products
$cartItems = [];
$total = 0;

foreach ($_SESSION["cart"] as $productId => $quantity) {

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();

    if (!$product || $product["stock"] <= 0) {
        unset($_SESSION["cart"][$productId]);
        continue;
    }

    // Keep quantity within available stock
    $quantity = min((int)$quantity, (int)$product["stock"]);
    $_SESSION["cart"][$productId] = $quantity;

    $product["quantity"] = $quantity;
    $product["subtotal"] = $product["price"] * $quantity;

    $total += $product["subtotal"];

    $cartItems[] = $product;
}

$cartCount = array_sum($_SESSION["cart"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart | NVIDIA Store</title>

    <link   href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="addtocart.css" rel="stylesheet" >

    

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark navbar-translucent xs-6 md-6 xs-6">

    <div class="container">

        <img src="A-Logo.png" alt="Logo" width="35" height="25">

        <a class="navbar-brand fw-bold" href="index.php">
            NVIDIA STORE
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link fw-bold text-white" href="index.php">
                        Store
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-bold text-white" href="Contact.php">
                        Contact
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-bold text-white" href="About.php">
                        About Us
                    </a>
                </li>

                <li class="nav-item ms-5">
                <a class="nav-link text-success fw-bold" href="cart.php">
    <img src="DigitaI.png" alt="Cart" width="35" height="25">
    <span id="cartCount">
        <?= $cartCount ?>
    </span>
</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-success fw-bold" href="login.php">
                        <button class="btn btn-success fw-bold" id="button-v">
                            Login
                        </button>
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- CART CONTENT -->

<div class="cart-container ">

    <h2 class="cart-title">My Shopping Cart</h2>

    <?php if (empty($cartItems)) { ?>

        <div class="empty-cart">

            <h3>Your cart is empty</h3>

            <p class="text-secondary mt-3">
                You haven't added any products to your cart yet.
            </p>

            <a href="index.php" class="btn btn-green mt-3">
                Continue Shopping
            </a>

        </div>

    <?php } else { ?>

        <div class="row">

            <div class="col-lg-8">

                <?php foreach ($cartItems as $item) { ?>

                    <div class="cart-item">

                        <div class="row align-items-center g-3">

                            <div class="col-4 col-md-3">

                                <img
                                    src="<?= htmlspecialchars($item["image"]) ?>"
                                    alt="<?= htmlspecialchars($item["name"]) ?>"
                                    class="cart-image img-fluid"
                                >

                            </div>

                            <div class="col-8 col-md-9">

                                <p class="product-brand mb-1">
                                    <?= htmlspecialchars($item["brand"]) ?>
                                </p>

                                <h5 class="product-name">
                                    <?= htmlspecialchars($item["name"]) ?>
                                </h5>

                                <p class="product-price mb-2">
                                    $<?= number_format($item["price"], 2) ?>
                                </p>

                                <div class="d-flex flex-wrap align-items-center gap-2">

                                    <form method="POST" action="cart.php"
                                          class="d-flex align-items-center gap-2">

                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="<?= (int)$item["id"] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="update"
                                        >

                                        <input
                                            type="number"
                                            name="quantity"
                                            class="quantity-input"
                                            value="<?= (int)$item["quantity"] ?>"
                                            min="1"
                                            max="<?= (int)$item["stock"] ?>"
                                        >

                                        <button type="submit" class="btn btn-sm btn-green">
                                            Update
                                        </button>

                                    </form>

                                    <form method="POST" action="cart.php">

                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="<?= (int)$item["id"] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="remove"
                                        >

                                        <button type="submit" class="remove-btn">
                                            Remove
                                        </button>

                                    </form>

                                </div>

                                <p class="mt-2 mb-0">
                                    Subtotal:
                                    <strong>
                                        $<?= number_format($item["subtotal"], 2) ?>
                                    </strong>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>


            <!-- ORDER SUMMARY -->

            <div class="col-lg-4">

                <div class="cart-summary">

                    <h4 class="mb-4">Order Summary</h4>

                    <div class="d-flex justify-content-between mb-3">

                        <span>Products</span>

                        <span><?= $cartCount ?></span>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">

                        <strong>Total</strong>

                        <span class="total-price">
                            $<?= number_format($total, 2) ?>
                        </span>

                    </div>

                    <button
                        type="button"
                        class="btn btn-green w-100 mt-4"
                        disabled
                    >
                        Checkout Coming Soon
                    </button>

                    <a href="index.php" class="btn btn-outline-light w-100 mt-2">
                        Continue Shopping
                    </a>

                </div>

            </div>

        </div>

    <?php } ?>

</div>

</body>
</html>
