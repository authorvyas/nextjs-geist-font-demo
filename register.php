<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user already exists
    $checkUser = $conn->query("SELECT id FROM users WHERE email='$email' OR username='$username'");
    if ($checkUser->num_rows > 0) {
        $error = "User with this email or username already exists.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php" class="space-y-4">
            <input type="text" name="username" placeholder="Username" required class="w-full p-2 border rounded" />
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded" />
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded" />
            <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">Register</button>
        </form>
        <p class="mt-4 text-center text-sm">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a></p>
    </div>
</body>
</html>
