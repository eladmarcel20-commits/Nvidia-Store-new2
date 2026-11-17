
<?php
session_start();

require_once "database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Find the account using its email
    $sql = "SELECT id, first_name, email, password FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Verify the entered password
        if (password_verify($password, $user["password"])) {

            // Create a secure login session
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["first_name"] = $user["first_name"];

            // Admin email
            $adminEmail = "eladmarcel13@gmail.com";

            // Redirect based on account type
            if (strtolower(trim($user["email"])) === strtolower(trim($adminEmail))) {

                header("Location: AdminDashboard.php");
                exit();

            } else {

                header("Location: index.php");
                exit();

            }

        } else {

            $error = "Incorrect email or password.";

        }

    } else {

        $error = "Incorrect email or password.";

    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | NVIDIA Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
       <link href="login.css" rel="stylesheet">
    

</head>

<body>

<div class="container login-container">

    <div class="login-card">
         
    <div class="logo">
         <img src="A-Logo.png" alt="Logo" width="65" height="50">
        <h1 class="mt-2">NVIDIA STORE</h1>
</div>

        <p>Welcome back, Login to your account.</p>

       

       <?php if (!empty($error)): ?>

    <p id="result" class="login-error">
        <?php echo htmlspecialchars($error); ?>
    </p>

<?php endif; ?>

        <form id="form-field" method="POST" action="Login.php">

            <div class="mb-3 mt-3">

                <label class="form-label">Email Address</label>

                <input type="email"
                       name="email"
                       id="userEmail"
                       class="form-control"
                       placeholder="Enter your email"
                       required>

            </div>

            <div class="mb-4">

                <label class="form-label">Password</label>
<div class="password-container">

    <input type="password"
           id="userPassword"
           name="password"
           class="form-control"
           placeholder="Enter your password"
           required>

    <button type="button"
            id="togglePassword"
            class="show-password">
      <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
fill="currentColor" viewBox="0 0 24 24" >
<!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
<path d="M12 19c7.63 0 9.93-6.62 9.95-6.68.07-.21.07-.43 0-.63-.02-.07-2.32-6.68-9.95-6.68s-9.93 6.61-9.95 6.67c-.07.21-.07.43 0 .63.02.07 2.32 6.68 9.95 6.68Zm0-10c1.64 0 3 1.36 3 3s-1.36 3-3 3-3-1.36-3-3 1.36-3 3-3"></path>
</svg>
    </button>

</div>
                    
            </div>

               <div class="contain">
              <label>
             remember me <input Type="checkbox"></label>   <a class="forgot">Frogot Password  </a>
                 

</div>

            <button type="submit" class="btn login-btn">
                LOG IN
            </button>

        </form>

        <div class="text-center mt-5">

            <span style="color: #b8c0c8;">
                Don't have an account?
            </span>

            <a href="signUp.php" class="register-link">
                Create Account
            </a>

        </div>

    </div>

</div>


<script>

    
 



// Show Password

const passwordInput = document.getElementById("userPassword");
const togglePassword = document.getElementById("togglePassword");

const eyeIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>
`;

const eyeSlashIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
<path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
<path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
</svg>
`;

togglePassword.innerHTML = eyeSlashIcon;

togglePassword.addEventListener("click", function () {

    if (passwordInput.type === "password") {

        passwordInput.type = "text";
        togglePassword.innerHTML = eyeIcon;

    } else {

        passwordInput.type = "password";
        togglePassword.innerHTML = eyeSlashIcon;

    }

});





    </script>

</body>
</html>
