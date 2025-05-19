<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$user_result = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $user_result->fetch_assoc();

$projects_result = $conn->query("SELECT * FROM projects ORDER BY uploaded_at DESC");

// Handle like, comment, and click actions via POST or AJAX in future steps
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Projects - Project Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-black text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Projects</h1>
        <a href="logout.php" class="bg-gray-800 px-3 py-1 rounded hover:bg-gray-700 transition">Logout</a>
    </header>
    <main class="flex-grow p-6">
        <h2 class="text-2xl font-semibold mb-4">Available Projects</h2>
        <?php if ($projects_result->num_rows == 0): ?>
            <p>No projects available at the moment.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($project = $projects_result->fetch_assoc()): ?>
                    <div class="bg-white p-4 rounded shadow-md flex flex-col">
                        <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p class="mb-4 flex-grow"><?php echo htmlspecialchars($project['description']); ?></p>
                        <div class="flex space-x-4">
                            <a href="project_view.php?id=<?php echo $project['id']; ?>" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">View</a>
                            <?php if ($project['download_enabled']): ?>
                                <a href="uploads/<?php echo urlencode($project['filename']); ?>" download class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">Download</a>
                            <?php else: ?>
                                <button disabled class="bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed">Download Disabled</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>
    <footer class="bg-black text-white p-4 text-center">
        &copy; <?php echo date("Y"); ?> Project Portal
    </footer>
</body>
</html>
