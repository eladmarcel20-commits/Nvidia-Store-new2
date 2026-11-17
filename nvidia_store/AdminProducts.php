
<?php
session_start();
require_once "database.php";

$adminEmail = "eladmarcel13@gmail.com";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: Login.php");
    exit();
}

// Check admin account
$userSql = "SELECT email, first_name FROM users WHERE id = ?";
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

// Create CSRF token for delete forms
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Fetch all products
$sql = "SELECT id, name, brand, category, price, old_price, image, stock
        FROM products
        ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products | NVIDIA Store Admin</title>

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
                <h1>Products</h1>
                <p>Manage your NVIDIA Store products.</p>
            </div>

            <div class="admin-profile">
                <span class="profile-icon">♟</span>
                <span>Admin</span>
            </div>

        </header>

        <section class="products-section">

            <div class="section-header">

                <h2>All Products</h2>

                <a href="AddProduct.php" class="add-product-btn">
                    ＋ Add Product
                </a>

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

                    <?php if ($result && $result->num_rows > 0): ?>

                        <?php while ($product = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <img
                                        class="product-thumb"
                                        src="<?php echo htmlspecialchars($product["image"]); ?>"
                                        alt="<?php echo htmlspecialchars($product["name"]); ?>"
                                    >
                                </td>

                                <td class="product-name">

                                    <?php echo htmlspecialchars($product["name"]); ?>

                                    <span class="brand">
                                        <?php echo htmlspecialchars($product["brand"]); ?>
                                    </span>

                                </td>

                                <td>
                                    <span class="category-pill">
                                        <?php echo htmlspecialchars($product["category"]); ?>
                                    </span>
                                </td>

                                <td>
                                    $<?php echo number_format((float)$product["price"], 2); ?>
                                </td>

                                <td>
                                    <span class="<?php echo ((int)$product["stock"] <= 5) ? "stock-low" : "stock-normal"; ?>">
                                        <?php echo (int)$product["stock"]; ?>
                                    </span>
                                </td>

                                <td class="actions">

                                    <!-- Edit Product -->
                                    <a
                                        class="action-btn edit"
                                        href="EditProducts.php?id=<?php echo (int)$product["id"]; ?>"
                                        title="Edit product"
                                    >
                                        ✎
                                    </a>

                                    <!-- Delete Product -->
                                    <form
                                        action="DeleteProduct.php"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int)$product["id"]; ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>"
                                        >

                                        <button type="submit"class="action-btn delete"title="Delete product"> ⌫</button>
  </form>
</td>

               </tr>

              <?php endwhile; ?>

       <?php else: ?>

               <tr>
                 <td colspan="6" class="empty-row">
                    No products found in your database.
              </td>
             </tr>

       <?php endif; ?>

       </tbody>

     </table>
</div>
 </section>
 </main>

</div>

</body>
</html>
