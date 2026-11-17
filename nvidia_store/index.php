
<?php

session_start();
require_once "database.php";

// Get cart item count
$cartCount = array_sum($_SESSION['cart'] ?? []);

// Get all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);



require_once "database.php";

// Get all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Create separate product sections
$dailyOffers = [];
$gpus = [];
$laptops = [];
$pcs = [];
$workstation = [];

// Sort products into their sections
if ($result) {
    while ($product = $result->fetch_assoc()) {

        // Daily Offers: only products with a real discount
        if (
            !empty($product['old_price']) &&
            $product['old_price'] > $product['price']
        ) {
            $dailyOffers[] = $product;
        }

        // Get the product category
        $category = strtolower(trim($product['category']));

        // Graphics Cards
        if ($category === "gpu" || $category === "graphics card") {
            $gpus[] = $product;
        }

        // Gaming Laptops
        elseif ($category === "laptop" || $category === "gaming laptop") {
            $laptops[] = $product;
        }

        // Gaming PCs
        elseif ($category === "pc" || $category === "gaming pc") {
            $pcs[] = $product;
        }

          // Workstation
       elseif ($category === "workstation") {
    $workstation[] = $product;
}
}
}



// Function to display a product card
function renderProductCard($product) {
?>

    <div class="card product-card">

        <div class="top-card">

            <div class="discount-badge">

                <?php
                $badge = $product['badge'] ?? 'NONE';

                if ($badge !== 'NONE') {
                    echo htmlspecialchars($badge);
                }
                ?>

            </div>

        </div>

        <div class="product-image">

            <img
                src="<?= htmlspecialchars($product['image']) ?>"
                class="card-img-top"
                alt="<?= htmlspecialchars($product['name']) ?>"
            >

        </div>

        <div class="card-body d-flex flex-column">

            <p class="product-brand">
                <?= htmlspecialchars($product['brand']) ?>
            </p>

            <h5 class="card-title">
                <?= htmlspecialchars($product['name']) ?>
            </h5>

            <p class="card-text">
                <?= htmlspecialchars($product['description']) ?>
            </p>

            <div class="price">

                <span class="current-price">
                    $<?= number_format($product['price'], 2) ?>
                </span>

                <?php if (
                    !empty($product['old_price']) &&
                    $product['old_price'] > $product['price']
                ) { ?>

                    <span class="old-price">
                        $<?= number_format($product['old_price'], 2) ?>
                    </span>

                <?php } ?>

            </div>

            <?php if ($product['stock'] > 0) { ?>

                <p class="stock">● In Stock</p>

            <?php } else { ?>

                <p class="stock text-danger">● Out of Stock</p>

            <?php } ?>

           <form action="add_to_cart.php" method="POST">

    <input
        type="hidden"
        name="product_id"
        value="<?= (int)$product['id'] ?>"
    >

    <button
        type="submit"
        class="btn btn-success w-100 add-to-cart"
        <?= $product['stock'] <= 0 ? 'disabled' : '' ?>
    >
        Add to Cart
    </button>

</form>
        </div>

    </div>

<?php
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
    >

    <link href="ccg.css" rel="stylesheet">

    <title>NVIDIA Store</title>

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


<!-- HERO SLIDER -->

<div
    id="heroSlider"
    class="carousel slide"
    data-bs-ride="carousel"
    data-bs-interval="6000"
>

    <div class="carousel-inner">

        <!-- Slide 1 -->

        <div class="carousel-item active">

            <img
                src="nvidia-rtx-pro-desktop-bm-l580-d.jpg"
                class="d-block w-100 hero-img"
                alt="Gaming computer"
            >

            <div class="carousel-caption">

                <h1>The Power is in your hands</h1>

                <p>Discover powerful Workstation PCs and hardware.</p>

                <a href="#" class="show">
                    Shop Now
                </a>

            </div>

        </div>


        <!-- Slide 2 -->

        <div class="carousel-item">

            <img
                src="mian.png"
                class="d-block w-100 hero-img"
                alt="High performance workstation"
            >

            <div class="carousel-caption">

                <h1>Built for Performance</h1>

                <p>Hardware designed for work and creativity.</p>

                <a href="store.html" class="show">
                    Explore Products
                </a>

            </div>

        </div>


        <!-- Slide 3 -->

        <div class="carousel-item">

            <img
                src="NVIDIA_Unterseite_Big.jpg"
                class="d-block w-100 hero-img"
                alt="Graphics card"
            >

            <div class="carousel-caption">

                <h1>Next Generation Hardware</h1>

                <p>Upgrade your setup with powerful components.</p>

                <a href="#" class="show">
                    View Hardware
                </a>

            </div>

        </div>

    </div>

</div>


<!-- PREVIOUS SLIDE BUTTON -->

<button
    class="carousel-control-prev"
    type="button"
    data-bs-target="#heroSlider"
    data-bs-slide="prev"
    id="before"
>

    <span class="carousel-control-prev-icon"></span>

</button>


<!-- NEXT SLIDE BUTTON -->

<button
    class="carousel-control-next"
    type="button"
    data-bs-target="#heroSlider"
    data-bs-slide="next"
    id="after"
>

    <span class="carousel-control-next-icon"></span>

</button>


<!-- SEARCH AND FILTER -->

<section class="container-fluid py-4" id="products">

    <div class="container">

        <div class="row g-3 mb-4">

            <div class="d-flex ms-2 fs-5 align-items-center text-white">
                Find Your Next Upgrade.
            </div>

            <div class="col-md-8">

                <input
                    type="text"
                    id="searchInput"
                    class="form-control fst-italic"
                    placeholder="Search NVIDIA products..."
                >

            </div>

            <div class="col-md-2">

                <select id="categoryFilter" class="form-select">

                    <option value="all">All Categories</option>

                    <option value="#GPU">Graphics Cards</option>

                    <option value="#Laptop">Gaming Laptops</option>

                    <option value="#PC">Gaming PCs</option>

                </select>

            </div>

        </div>

    </div>


    <!-- DAILY OFFERS HEADING -->

    <div class="d-flex align-items-center mb-4">

        <h1 class="fw-bold ms-5 mt-2 text-white">
            Daily Offers
        </h1>


    </div>

</section>


<!-- DAILY OFFERS PRODUCTS -->

<section
    class="store container-fluid gap-3 flex-wrap d-flex justify-content-center text-white"
    id="stores"
>

    <?php foreach ($dailyOffers as $product) { ?>

        <?php renderProductCard($product); ?>

    <?php } ?>

</section>


<!-- GRAPHICS CARDS BANNER -->

<div class="product-banner">

    <h2 id="GPU">
        Graphics Cards
    </h2>

</div>


<!-- GRAPHICS CARDS PRODUCTS -->

<section class="store container-fluid flex-wrap gap-3 d-flex justify-content-center text-white">

    <?php foreach ($gpus as $product) { ?>

        <?php renderProductCard($product); ?>

    <?php } ?>

</section>


<!-- GAMING LAPTOPS BANNER -->

<div class="product-banner">

    <h2 id="Laptop">
        Gaming Laptops
    </h2>

</div>


<!-- GAMING LAPTOPS PRODUCTS -->

<section class="store container-fluid flex-wrap gap-3 d-flex justify-content-center text-white">

    <?php foreach ($laptops as $product) { ?>

        <?php renderProductCard($product); ?>

    <?php } ?>

</section>


<!-- GAMING PCs BANNER -->

<div class="product-banner">

    <h2 id="PC">
        Gaming PCs
    </h2>

</div>


<!-- GAMING PCs PRODUCTS -->

<section class="store container-fluid flex-wrap gap-3 d-flex justify-content-center text-white">

    <?php foreach ($pcs as $product) { ?>

        <?php renderProductCard($product); ?>

    <?php } ?>

</section>
<!-- WORKSTATION BANNER -->

<div class="product-banner">

    <h2 id="Workstation">
        Workstations
    </h2>

</div>


<!-- WORKSTATION PRODUCTS -->

<section class="store container-fluid flex-wrap gap-3 d-flex justify-content-center text-white">

    <?php foreach ($workstation as $product) { ?>

        <?php renderProductCard($product); ?>

    <?php } ?>

</section>


<!-- NVIDIA STORE FOOTER -->

<footer class="nvidia-footer">

    <div class="footer-container">

        <!-- BRAND SECTION -->

        <div class="footer-brand">

            <a href="index.php" class="footer-logo">

                <img src="A-Logo.png" alt="Logo" width="35" height="25">

                <span>NVIDIA STORE</span>

            </a>

            <p>
                Your destination for powerful gaming hardware,
                graphics cards and NVIDIA technology.
            </p>

            <div class="footer-socials">

                <a href="#" title="Facebook">f</a>

                <a href="#" title="Instagram">◎</a>

                <a href="#" title="YouTube">▶</a>

                <a href="#" title="X">𝕏</a>

            </div>

        </div>


        <!-- MARKETPLACE LINKS -->

        <div class="footer-column">

            <h4>MARKETPLACE</h4>

            <ul>

                <li><a href="store.php">All Products</a></li>

                <li><a href="#GPU">Graphics Cards</a></li>

                <li><a href="#Laptop">Gaming Laptops</a></li>

                <li><a href="#">Gaming Accessories</a></li>

                <li><a href="#stores">Daily Offers</a></li>

            </ul>

        </div>


        <!-- CUSTOMER SUPPORT -->

        <div class="footer-column">

            <h4>SUPPORT</h4>

            <ul>

                <li><a href="Contact.php">Contact Us</a></li>

                <li><a href="#">Help Center</a></li>

                <li><a href="#">Shipping Information</a></li>

                <li><a href="#">Returns & Refunds</a></li>

                <li><a href="#">Warranty Information</a></li>

            </ul>

        </div>


        <!-- ABOUT -->

        <div class="footer-column">

            <h4>ABOUT NVIDIA STORE</h4>

            <ul>

                <li><a href="About.php">About Us</a></li>

                <li><a href="#">Our Partners</a></li>

                <li><a href="#">Privacy Policy</a></li>

                <li><a href="#">Terms & Conditions</a></li>

            </ul>

        </div>

    </div>


    <!-- FOOTER BOTTOM -->

    <div class="footer-bottom">

        <p>
            © 2026 NVIDIA STORE. All Rights Reserved.
        </p>

        <p>
            Powered by NVIDIA Technology
        </p>

    </div>

</footer>


<!-- BOOTSTRAP JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


</body>

</html>
```