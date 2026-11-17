<?php

require_once "database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    // Check that all fields are filled
    if (
        empty($firstName) ||
        empty($lastName) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {
        $error = "Please fill in all fields.";
    }

    // Check email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }

    // Check password length
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }

    // Check password confirmation
    elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    }

    else {

        // Check if the email already exists
        $checkSql = "SELECT id FROM users WHERE email = ?";

        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {

            $error = "An account with this email already exists.";

        } else {

            // Hash the password before saving it
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert the new account into the database
            $sql = "INSERT INTO users
                    (first_name, last_name, email, password)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssss",
                $firstName,
                $lastName,
                $email,
                $hashedPassword
            );
if ($stmt->execute()) {

    header("Location: Login.php");
    exit();

} else {

    $error = "Unable to create your account.";

}
            $stmt->close();
        }

        $checkStmt->close();
    }
}

?>