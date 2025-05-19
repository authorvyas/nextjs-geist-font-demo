<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" class="space-y-4">
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded" />
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded" />
            <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">Login</button>
        </form>
        <p class="mt-4 text-center text-sm">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a></p>
    </div>
</body>
</html>
