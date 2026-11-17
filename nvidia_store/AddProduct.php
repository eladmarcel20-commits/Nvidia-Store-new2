
<?php
session_start();
require_once "database.php";

$adminEmail = "eladmarcel13@gmail.com";
$error = "";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: Login.php");
    exit();
}

// Verify that the user is an admin
$userSql = "SELECT email FROM users WHERE id = ?";
$userStmt = $conn->prepare($userSql);
$userStmt->bind_param("i", $_SESSION["user_id"]);
$userStmt->execute();

$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user || strtolower($user["email"]) !== strtolower($adminEmail)) {
    header("Location: index.php");
    exit();
}

// Create CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Add product when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verify CSRF token
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"] ?? "")) {
        $error = "Invalid request. Please try again.";
    } else {

        $name = trim($_POST["name"] ?? "");
        $brand = trim($_POST["brand"] ?? "");
        $category = trim($_POST["category"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $price = $_POST["price"] ?? "";
        $oldPrice = $_POST["old_price"] ?? "";
        $image = trim($_POST["image"] ?? "");
        $stock = $_POST["stock"] ?? "";
        $badge = $_POST["badge"] ?? "NONE";

        // Validate fields
        if (
            $name === "" ||
            $brand === "" ||
            $category === "" ||
            $description === "" ||
            $price === "" ||
            $image === "" ||
            $stock === ""
        ) {
            $error = "Please fill in all required fields.";

        } elseif (
            !is_numeric($price) ||
            $price < 0 ||
            !is_numeric($stock) ||
            filter_var($stock, FILTER_VALIDATE_INT) === false ||
            $stock < 0
        ) {
            $error = "Please enter a valid price and stock quantity.";

        } elseif (
            $oldPrice !== "" &&
            (!is_numeric($oldPrice) || $oldPrice < 0)
        ) {
            $error = "Please enter a valid old price.";

        } else {

            // Convert empty old price to NULL
            $oldPriceValue = ($oldPrice === "") ? null : (float)$oldPrice;

            // Insert product into database
           $sql = "INSERT INTO products
        (name, brand, category, description, price, old_price, image, stock, badge)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            $priceValue = (float)$price;
            $stockValue = (int)$stock;

          $stmt->bind_param(
    "ssssddsis",
    $name,
    $brand,
    $category,
    $description,
    $price,
    $oldPrice,
    $image,
    $stock,
    $badge
);
            if ($stmt->execute()) {
                header("Location: AdminProducts.php?success=added");
                exit();
            } else {
                $error = "Error adding product. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product | NVIDIA Store</title>

    <link rel="stylesheet" href="AdminDashboard.css">
        <link rel="stylesheet" href="AddProducts.css">
</head>

<body>

<div class="dashboard-layout">

<!-- Sidebar -->
<aside class="sidebar">

    <div class="sidebar-logo">
         <img src="A-Logo.png" alt="NVIDIA Logo">
        <h2>NVIDIA <span>STORE</span></h2>
    </div>

    <nav class="sidebar-nav">

        <a href="AdminDashboard.php">
            <span class="nav-icon">▦</span>
            Dashboard
        </a>

        <a href="AdminProducts.php">
            <span class="nav-icon">▣</span>
            Products
        </a>

        <a href="AddProduct.php" class="active">
            <span class="nav-icon">＋</span>
            Add Product
        </a>

        <div class="sidebar-divider"></div>

        <a href="index.php">
            <span class="nav-icon">⌂</span>
            View Store
        </a>

    </nav>

</aside>


    <!-- Main Content -->
    <main class="dashboard-main">

        <div class="dashboard-header">
            <h1>Add New Product</h1>
            <p>Add a new product to your NVIDIA Store.</p>
        </div>

        <?php if ($error !== ""): ?>
            <div class="form-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="AddProduct.php" class="admin-form">

            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>"
            >

            <div class="form-grid">

                <!-- Product Name -->
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                    >
                </div>

                <!-- Brand -->
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input
                        type="text"
                        id="brand"
                        name="brand"
                        required
                    >
                </div>

                <!-- Product Badge -->
<div class="form-group">
    <label for="badge">Product Badge</label>

    <select id="badge" name="badge" required>
        <option value="NONE">None</option>
        <option value="SALE">Sale</option>
        <option value="NEW">New</option>
        <option value="BEST SELLER">Best Seller</option>
        <option value="LIMITED OFFER">Limited Offer</option>
        <option value="TOP PICK">Top Pick</option>
    </select>
</div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category">Category</label>
                    <input
                        type="text"
                        id="category"
                        name="category"
                        required
                    >
                </div>
                <!-- Product Badge -->

   


                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        step="0.01"
                        min="0"
                        required
                    >
                </div>

                <!-- Old Price -->
                <div class="form-group">
                    <label for="old_price">Old Price (Optional)</label>
                    <input
                        type="number"
                        id="old_price"
                        name="old_price"
                        step="0.01"
                        min="0"
                    >
                </div>

                <!-- Stock -->
                <div class="form-group">
                    <label for="stock">Stock Quantity</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        min="0"
                        step="1"
                        required
                    >
                </div>

                <!-- Image -->
                <div class="form-group">
                    <label for="image">Product Image Path</label>
                    <input
                        type="text"
                        id="image"
                        name="image"
                        placeholder="exapmle.images/products/rtx-5090.jpg"
                        required
                    >
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        required
                    ></textarea>
                </div>

            </div>

            <div class="form-actions">

                <button type="submit" class="save-btn">
                    Add Product
                </button>

                <a href="AdminProducts.php" class="cancel-btn">
                    Cancel
                </a>

            </div>

        </form>

    </main>

</div>

</body>
</html>
