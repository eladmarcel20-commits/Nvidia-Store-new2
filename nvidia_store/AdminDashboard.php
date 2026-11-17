
        <?php
        
session_start();
require_once "database.php";



// Replace this with the exact email address you want to use as the admin.
$adminEmail = "eladmarcel13@gmail.com";

// Only logged-in users can access this page.
if (!isset($_SESSION["user_id"])) {
    header("Location: Login.php");
    exit();
}

// Fetch the logged-in user's account details.
$userSql = "SELECT email, first_name FROM users WHERE id = ?";
$userStmt = $conn->prepare($userSql);
$userStmt->bind_param("i", $_SESSION["user_id"]);
$userStmt->execute();

$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

// Only the designated admin email is allowed.
if (!$user || strtolower($user["email"]) !== strtolower($adminEmail)) {
    header("Location: index.php");
    exit();
}

// Dashboard statistics from the existing products table.
$productCountResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = (int) $productCountResult->fetch_assoc()["total"];

$lowStockResult = $conn->query("SELECT COUNT(*) AS total FROM products WHERE stock <= 5");
$lowStockItems = (int) $lowStockResult->fetch_assoc()["total"];

// Latest five products for the Recent Products table.
$recentProductsResult = $conn->query(
    "SELECT id, name, brand, category, price, image, stock
     FROM products
     ORDER BY created_at DESC, id DESC
     LIMIT 5"
);

$totalOrders = "N/A";
$pendingOrders = "N/A";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Admindashboard.css">
</head>
<body>

    <div class="dashboard-layout">

        <aside class="sidebar">

            <!-- Logo -->
            <div class="sidebar-logo">
                <img src="A-Logo.png" alt="NVIDIA Logo">
                <h2>NVIDIA <span>STORE</span></h2>
            </div>

            <!-- Vertical navigation -->
            <nav class="sidebar-nav">
                <a href="AdminDashboard.php" class="active">
                    <span class="nav-icon">⌂</span> Dashboard
                </a>

                <a href="AdminProducts.php">
                    <span class="nav-icon">◇</span> Products
                </a>

                <a href="AdminOrders.php">
                    <span class="nav-icon">🛒</span> Orders
                </a>

                <a href="AdminSettings.php">
                    <span class="nav-icon">⚙</span> Settings
                </a>
            </nav>

            <div class="sidebar-divider"></div>

            <a href="Logout.php" class="logout">
                <span class="nav-icon">⇥</span> Logout
            </a>

        </aside>


    <main class="dashboard">
        <header class="dashboard-header">
            <div>
                <span class="green-line"></span>
                <h1>Admin Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($user["first_name"]); ?>!</p>
            </div>

            <div class="admin-profile">
                <span class="profile-icon">♟</span>
                <span>Admin</span>
            </div>
        </header>

        <section class="stats-container">
            <article class="stat-card green">
                <div class="stat-icon">◇</div>
                <div>
                    <h3>Total Products</h3>
                    <p class="stat-number"><?php echo $totalProducts; ?></p>
                </div>
            </article>

            <article class="stat-card green">
                <div class="stat-icon">🛒</div>
                <div>
                    <h3>Total Orders</h3>
                    <p class="stat-number small-number"><?php echo htmlspecialchars((string) $totalOrders); ?></p>
                </div>
            </article>

            <article class="stat-card yellow">
                <div class="stat-icon">◷</div>
                <div>
                    <h3>Pending Orders</h3>
                    <p class="stat-number small-number"><?php echo htmlspecialchars((string) $pendingOrders); ?></p>
                </div>
            </article>

            <article class="stat-card red">
                <div class="stat-icon">⚠</div>
                <div>
                    <h3>Low Stock Items</h3>
                    <p class="stat-number"><?php echo $lowStockItems; ?></p>
                </div>
            </article>
        </section>

        <section class="products-section">
            <div class="section-header">
                <h2>Recent Products</h2>
                <a href="AddProduct.php" class="add-product-btn">＋ Add Product</a>
            </div>

            <div class="table-wrap">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($recentProductsResult && $recentProductsResult->num_rows > 0): ?>
                        <?php while ($product = $recentProductsResult->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img class="product-thumb"
                                         src="<?php echo htmlspecialchars($product["image"]); ?>"
                                         alt="<?php echo htmlspecialchars($product["name"]); ?>">
                                </td>
                                <td class="product-name">
                                    <?php echo htmlspecialchars($product["name"]); ?>
                                    <span class="brand"><?php echo htmlspecialchars($product["brand"]); ?></span>
                                </td>
                                <td>
                                    <span class="category-pill">
                                        <?php echo htmlspecialchars($product["category"]); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format((float) $product["price"], 2); ?></td>
                                <td>
                                    <span class="<?php echo ((int) $product["stock"] <= 5) ? "stock-low" : "stock-normal"; ?>">
                                        <?php echo (int) $product["stock"]; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a class="action-btn edit" href="EditProducts.php?id=<?php echo (int) $product["id"]; ?>" title="Edit product">✎</a>
                                    <a class="action-btn delete" href="DeleteProduct.php?id=<?php echo (int) $product["id"]; ?>" title="Delete product">⌫</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-row">No products were found in your database.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="table-note">Showing the 5 most recently added products. Product management pages still need to be created.</p>
        </section>
    </main>


</body>
</html>
