
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

// Verify admin account
$userSql = "SELECT email FROM users WHERE id = ?";
$userStmt = $conn->prepare($userSql);
$userStmt->bind_param("i", $_SESSION["user_id"]);
$userStmt->execute();

$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

if (!$user || strtolower(trim($user["email"])) !== strtolower(trim($adminEmail))) {
    header("Location: index.php");
    exit();
}

// Create CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Get product ID from URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

// If form is submitted, use the POST product ID
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
}

// Validate product ID
if (!$id || $id <= 0) {
    header("Location: AdminProducts.php");
    exit();
}

// Fetch current product
$productSql = "SELECT id, name, brand, category, description, price, old_price, image, stock, badge
               FROM products
               WHERE id = ?";

$productStmt = $conn->prepare($productSql);
$productStmt->bind_param("i", $id);
$productStmt->execute();

$productResult = $productStmt->get_result();
$product = $productResult->fetch_assoc();
$productStmt->close();

if (!$product) {
    header("Location: AdminProducts.php");
    exit();
}

// Set default badge if none exists
$product["badge"] = $product["badge"] ?? "NONE";

// Update product when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verify CSRF token
    $submittedToken = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $submittedToken)) {
        $error = "Invalid request. Please try again.";

    } else {

        // Get submitted form values
        $name = trim($_POST["name"] ?? "");
        $brand = trim($_POST["brand"] ?? "");
        $category = trim($_POST["category"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $price = $_POST["price"] ?? "";
        $oldPriceInput = trim($_POST["old_price"] ?? "");
        $image = trim($_POST["image"] ?? "");
        $stockInput = $_POST["stock"] ?? "";
        $badge = $_POST["badge"] ?? "NONE";

        // Keep entered values in the form
        $product["name"] = $name;
        $product["brand"] = $brand;
        $product["category"] = $category;
        $product["description"] = $description;
        $product["price"] = $price;
        $product["old_price"] = $oldPriceInput;
        $product["image"] = $image;
        $product["stock"] = $stockInput;
        $product["badge"] = $badge;

        // Validate fields
        if (
            $name === "" ||
            $brand === "" ||
            $category === "" ||
            $description === "" ||
            $image === "" ||
            !is_numeric($price) ||
            (float)$price < 0 ||
            filter_var($stockInput, FILTER_VALIDATE_INT) === false ||
            (int)$stockInput < 0
        ) {
            $error = "Please fill in all fields with valid values.";

        } elseif (
            $oldPriceInput !== "" &&
            (!is_numeric($oldPriceInput) || (float)$oldPriceInput < 0)
        ) {
            $error = "Please enter a valid old price.";

        } elseif (!in_array($badge, [
            "NONE",
            "SALE",
            "NEW",
            "BEST SELLER",
            "LIMITED OFFER",
            "TOP PICK"
        ], true)) {
            $error = "Please select a valid product badge.";

        } else {

            // Convert values
            $priceValue = (float)$price;
            $stockValue = (int)$stockInput;

            // Convert empty old price to NULL
            $oldPrice = ($oldPriceInput === "")
                ? null
                : (float)$oldPriceInput;

            // Update product in database
            $updateSql = "UPDATE products
                          SET name = ?,
                              brand = ?,
                              category = ?,
                              description = ?,
                              price = ?,
                              old_price = ?,
                              image = ?,
                              stock = ?,
                              badge = ?
                          WHERE id = ?";

            $updateStmt = $conn->prepare($updateSql);

            $updateStmt->bind_param(
                "ssssddsisi",
                $name,
                $brand,
                $category,
                $description,
                $priceValue,
                $oldPrice,
                $image,
                $stockValue,
                $badge,
                $id
            );

            if ($updateStmt->execute()) {
                $updateStmt->close();

                header("Location: AdminProducts.php?success=updated");
                exit();

            } else {
                $error = "Failed to update product. Please try again.";
                $updateStmt->close();
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

    <title>Edit Product | NVIDIA Store Admin</title>

    <link rel="stylesheet" href="AdminDashboard.css">
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
                <span class="nav-icon">⌂</span>
                Dashboard
            </a>

            <a href="AdminProducts.php" class="active">
                <span class="nav-icon">◇</span>
                Products
            </a>

            <a href="AdminOrders.php">
                <span class="nav-icon">🛒</span>
                Orders
            </a>

            <a href="AdminSettings.php">
                <span class="nav-icon">⚙</span>
                Settings
            </a>

        </nav>

        <div class="sidebar-divider"></div>

        <a href="Logout.php" class="logout">
            <span class="nav-icon">⇥</span>
            Logout
        </a>

    </aside>

    <!-- Main Content -->
    <main class="dashboard">

        <header class="dashboard-header">

            <div>
                <span class="green-line"></span>
                <h1>Edit Product</h1>
                <p>Update your product information.</p>
            </div>

            <div class="admin-profile">
                <span class="profile-icon">♟</span>
                <span>Admin</span>
            </div>

        </header>

        <section class="products-section">

            <div class="section-header">
                <h2>Product Information</h2>

                <a href="AdminProducts.php" class="add-product-btn">
                    ← Back to Products
                </a>
            </div>

            <?php if ($error !== ""): ?>

                <p class="form-error">
                    <?php echo htmlspecialchars($error); ?>
                </p>

            <?php endif; ?>

            <form method="POST" action="EditProducts.php" class="admin-form">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo (int)$id; ?>"
                >

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
                            value="<?php echo htmlspecialchars($product["name"]); ?>"
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
                            value="<?php echo htmlspecialchars($product["brand"]); ?>"
                            required
                        >
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category">Category</label>

                        <input
                            type="text"
                            id="category"
                            name="category"
                            value="<?php echo htmlspecialchars($product["category"]); ?>"
                            required
                        >
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">Price ($)</label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            step="0.01"
                            min="0"
                            value="<?php echo htmlspecialchars((string)$product["price"]); ?>"
                            required
                        >
                    </div>

                    <!-- Old Price -->
                    <div class="form-group">
                        <label for="old_price">Old Price ($)</label>

                        <input
                            type="number"
                            id="old_price"
                            name="old_price"
                            step="0.01"
                            min="0"
                            value="<?php echo htmlspecialchars((string)($product["old_price"] ?? "")); ?>"
                            placeholder="Optional"
                        >
                    </div>

                    <!-- Product Badge -->
                    <div class="form-group">
                        <label for="badge">Product Badge</label>

                        <select id="badge" name="badge" required>

                            <option value="NONE"
                                <?php echo ($product["badge"] === "NONE") ? "selected" : ""; ?>>
                                None
                            </option>

                            <option value="SALE"
                                <?php echo ($product["badge"] === "SALE") ? "selected" : ""; ?>>
                                Sale
                            </option>

                            <option value="NEW"
                                <?php echo ($product["badge"] === "NEW") ? "selected" : ""; ?>>
                                New
                            </option>

                            <option value="BEST SELLER"
                                <?php echo ($product["badge"] === "BEST SELLER") ? "selected" : ""; ?>>
                                Best Seller
                            </option>

                            <option value="LIMITED OFFER"
                                <?php echo ($product["badge"] === "LIMITED OFFER") ? "selected" : ""; ?>>
                                Limited Offer
                            </option>

                            <option value="TOP PICK"
                                <?php echo ($product["badge"] === "TOP PICK") ? "selected" : ""; ?>>
                                Top Pick
                            </option>

                        </select>
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
                            value="<?php echo htmlspecialchars((string)$product["stock"]); ?>"
                            required
                        >
                    </div>

                    <!-- Image -->
                    <div class="form-group full-width">
                        <label for="image">Product Image Path</label>

                        <input
                            type="text"
                            id="image"
                            name="image"
                            value="<?php echo htmlspecialchars($product["image"]); ?>"
                            required
                        >
                    </div>

                    <!-- Description -->
                    <div class="form-group full-width">
                        <label for="description">Description</label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            required
                        ><?php echo htmlspecialchars($product["description"]); ?></textarea>
                    </div>

                </div>

                <!-- Form Buttons -->
                <div class="form-buttons">

                    <a href="AdminProducts.php" class="cancel-btn">
                        Cancel
                    </a>

                    <button type="submit" class="save-btn">
                        Save Changes
                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>
