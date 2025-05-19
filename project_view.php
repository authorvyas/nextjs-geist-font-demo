<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Project ID is required.");
}

$project_id = intval($_GET['id']);
$project_result = $conn->query("SELECT * FROM projects WHERE id=$project_id");
if ($project_result->num_rows == 0) {
    die("Project not found.");
}

$project = $project_result->fetch_assoc();

// Read the project file content if it is an HTML file
$file_path = "uploads/" . $project['filename'];
$file_content = "";
if (file_exists($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) === 'html') {
    $file_content = file_get_contents($file_path);
} else {
    $file_content = "<p>Preview not available for this file type.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($project['title']); ?> - Project Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($project['title']); ?></h1>
    <p class="mb-6"><?php echo htmlspecialchars($project['description']); ?></p>
    <div class="bg-white p-4 rounded shadow-md mb-6 overflow-auto max-h-[70vh]">
        <?php echo $file_content; ?>
    </div>
    <a href="projects.php" class="text-blue-600 hover:underline">Back to Projects</a>
</body>
</html>
