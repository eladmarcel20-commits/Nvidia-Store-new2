
<?php
session_start();
require_once "database.php";

$adminEmail = "eladmarcel13@gmail.com";

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

if (!$user || strtolower($user["email"]) !== strtolower($adminEmail)) {
    header("Location: index.php");
    exit();
}

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: AdminProducts.php");
    exit();
}

// Verify CSRF token
if (
    !isset($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"] ?? "")
) {
    header("Location: AdminProducts.php?error=invalid_request");
    exit();
}

// Get product ID
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location: AdminProducts.php?error=invalid_id");
    exit();
}

// Delete product from database
$sql = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        header("Location: AdminProducts.php?success=deleted");
        exit();
    } else {
        header("Location: AdminProducts.php?error=not_found");
        exit();
    }

} else {
    header("Location: AdminProducts.php?error=delete_failed");
    exit();
}
?>
