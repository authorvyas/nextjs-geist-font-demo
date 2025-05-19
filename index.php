<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome - Project Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-black text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Welcome, <?php echo htmlspecialchars($username); ?></h1>
        <a href="logout.php" class="bg-gray-800 px-3 py-1 rounded hover:bg-gray-700 transition">Logout</a>
    </header>
    <main class="flex-grow p-6">
        <h2 class="text-2xl font-semibold mb-4">Available Projects</h2>
        <p class="mb-6">Projects will be listed here. Admin will upload projects for you to download or access.</p>
        <!-- Project listing will be implemented here -->
    </main>
    <footer class="bg-black text-white p-4 text-center">
        &copy; <?php echo date("Y"); ?> Project Portal
    </footer>
</body>
</html>
