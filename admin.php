<?php
session_start();
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$username = $_SESSION['user'];
$result = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $result->fetch_assoc();
if (!$user || !$user['is_admin']) {
    die("Access denied. Admins only.");
}

$error = "";
$success = "";

// Handle project upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['project_file'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $download_enabled = isset($_POST['download_enabled']) ? 1 : 0;

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $filename = basename($_FILES["project_file"]["name"]);
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES["project_file"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO projects (title, description, filename, download_enabled) VALUES ('$title', '$description', '$filename', $download_enabled)";
        if ($conn->query($sql) === TRUE) {
            $success = "Project uploaded successfully.";
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "Failed to upload file.";
    }
}

// Fetch all projects
$projects_result = $conn->query("SELECT * FROM projects ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - Upload Projects</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <h1 class="text-3xl font-bold mb-6">Admin Panel - Upload Projects</h1>
    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $success; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="mb-8 bg-white p-6 rounded shadow-md max-w-lg">
        <div class="mb-4">
            <label class="block mb-1 font-semibold" for="title">Project Title</label>
            <input type="text" name="title" id="title" required class="w-full p-2 border rounded" />
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold" for="description">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full p-2 border rounded"></textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold" for="project_file">Project File (HTML, ZIP, etc.)</label>
            <input type="file" name="project_file" id="project_file" required class="w-full" />
        </div>
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="download_enabled" id="download_enabled" checked class="mr-2" />
            <label for="download_enabled" class="font-semibold">Enable Download Button</label>
        </div>
        <button type="submit" class="bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition">Upload Project</button>
    </form>

    <h2 class="text-2xl font-semibold mb-4">Uploaded Projects</h2>
    <table class="w-full bg-white rounded shadow-md">
        <thead>
            <tr class="border-b">
                <th class="p-3 text-left">Title</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-left">Filename</th>
                <th class="p-3 text-left">Download Enabled</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($project = $projects_result->fetch_assoc()): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3"><?php echo htmlspecialchars($project['title']); ?></td>
                <td class="p-3"><?php echo htmlspecialchars($project['description']); ?></td>
                <td class="p-3"><?php echo htmlspecialchars($project['filename']); ?></td>
                <td class="p-3"><?php echo $project['download_enabled'] ? 'Yes' : 'No'; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
