<?php
session_start();
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$result = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $result->fetch_assoc();
if (!$user || !$user['is_admin']) {
    die("Access denied. Admins only.");
}

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
            header("Location: admin.php?success=1");
            exit();
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "Failed to upload file.";
    }
} else {
    $error = "Invalid request.";
}

if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
    echo "<p><a href='admin.php'>Back to Admin Panel</a></p>";
}
?>
