
<?php
require_once "database.php";

$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // Check that all fields are filled
    if (
        $first_name == "" ||
        $last_name == "" ||
        $email == "" ||
        $subject == "" ||
        $message == ""
    ) {
        $status = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = "Please enter a valid email address.";

    } else {

        // Insert the message into the database
        $sql = "INSERT INTO contact_messages
                (first_name, last_name, email, subject, message)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $first_name,
            $last_name,
            $email,
            $subject,
            $message
        );

        if ($stmt->execute()) {
            $status = "Your message has been submitted successfully!";
        } else {
            $status = "Something went wrong. Please try again.";
        }

        $stmt->close();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
 <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
         <link href="ccg.css" rel="stylesheet">


        <title>Contact</title>
    </head>

    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
         <link href="ccg.css" rel="stylesheet">
           <link href="contact.css" rel="stylesheet">


        <title>Document</title>
    </head>
    <body>
     
<nav class="navbar  navbar-expand-lg navbar-dark  navbar-translucent  xs-6  md-6 xs-6   ">
<div class="container">
        <img src="A-Logo.png" alt="Logo" width="35" height="25">
<a class="navbar-brand fw-bold" href="index.php">NVIDIA STORE</a>
<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="menu"  >
<ul class="navbar-nav ms-auto">
<li class="nav-item">
<a class="nav-link  fw-bold text-white" href="index.php">Store</a>
</li>

<li class="nav-item">
<a class="nav-link fw-bold text-white" href="Contact.php">Contact</a>

<li class="nav-item">
<a class="nav-link  fw-bold text-white" href="About.php">About Us</a>
</li>

   <li class="nav-item ms-5 ">
                    <a class="nav-link text-success fw-bold    " href="cart.html">
                        <img src="DigitaI.png" alt="Logo" width="35" height="25"> <span id="cartCount">0</span>
                    </a>
                </li>
                

            </ul>

        </div>
    </div>
</nav>


<!--- Contact section ----->


<section class="contact-section">

<div class="contact-container">


        <!-- LEFT SIDE: CONTACT INFORMATION -->
        <div class="contact-info">

            <span class="section-label"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
  <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
</svg> === CONTACT US === <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708"/>
  <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708"/>
</svg> </span>

            <h2>GET IN TOUCH</h2>

            <p class="contact-description">
                Have a question about a product or your order?
                Contact the NVIDIA Store team and we will be
                happy to assist you.
            </p>

            <div class="contact-detail">
                <div class="contact-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
</svg></div>

                <div>
                    <h4>EMAIL SUPPORT</h4>
                    <p>support_RamHoarder.gmail.com</p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="contact-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-earbuds" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6.825 4.138c.596 2.141-.36 3.593-2.389 4.117a4.4 4.4 0 0 1-2.018.054c-.048-.01.9 2.778 1.522 4.61l.41 1.205a.52.52 0 0 1-.346.659l-.593.19a.55.55 0 0 1-.69-.34L.184 6.99c-.696-2.137.662-4.309 2.564-4.8 2.029-.523 3.402 0 4.076 1.948zm-.868 2.221c.43-.112.561-.993.292-1.969-.269-.975-.836-1.675-1.266-1.563s-.561.994-.292 1.969.836 1.675 1.266 1.563m3.218-2.221c-.596 2.141.36 3.593 2.389 4.117a4.4 4.4 0 0 0 2.018.054c.048-.01-.9 2.778-1.522 4.61l-.41 1.205a.52.52 0 0 0 .346.659l.593.19c.289.092.6-.06.69-.34l2.536-7.643c.696-2.137-.662-4.309-2.564-4.8-2.029-.523-3.402 0-4.076 1.948m.868 2.221c-.43-.112-.561-.993-.292-1.969.269-.975.836-1.675 1.266-1.563s.561.994.292 1.969-.836 1.675-1.266 1.563"/>
</svg></div>

                <div>
                    <h4>CUSTOMER SERVICE</h4>
                    <p>Product and order assistance</p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="contact-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
</svg></div>

                <div>
                    <h4>SUPPORT HOURS</h4>
                    <p>Monday - Friday</p>
                </div>
            </div>

        </div>


<!--- Right Side Form ---->


<div class="contact-form-container">

<h2>SEND US A MESSAGE</h2>



            <?php if ($status != "") { ?>

                <div class="form-status">
                    <?= htmlspecialchars($status) ?>
                </div>

            <?php } ?>

            <form method="POST" action="contact.php">

                <div class="form-row">

                    <input
                        type="text"
                        name="first_name"
                        placeholder="First Name *"
                        required>

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Last Name *"
                        required>

                </div>


                <div class="form-row">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address *"
                        required>

                    <input
                        type="text"
                        name="subject"
                        placeholder="Subject *"
                        required>

                </div>


                <textarea
                    name="message"
                    placeholder="Type Your Message *"
                    rows="6"
                    required></textarea>


                <button type="submit" class="contact-submit">
                    SUBMIT NOW
                </button>

            </form>

        </div>

    </div>
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
                <li><a href="#">Graphics Cards</a></li>
                <li><a href="#">Gaming Laptops</a></li>
                <li><a href="#">Gaming Accessories</a></li>
                <li><a href="#">Daily Offers</a></li>
            </ul>

        </div>


        <!-- CUSTOMER SUPPORT -->
        <div class="footer-column">

            <h4>SUPPORT</h4>

            <ul>
                <li><a href="contact.php">Contact Us</a></li>
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
                <li><a href="#">About Us</a></li>
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

    

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>