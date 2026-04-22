<?php
// includes/functions.php
session_start();

// Utility for redirect
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Function to check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if a user is an admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin';
}

// Ensure the user is authenticated, redirect to login if not
function requireAuth() {
    if (!isLoggedIn()) {
        redirect('/zoo_project/auth/login.php');
    }
}

// Upload file function
function uploadImage($file, $target_dir = "../uploads/animals/") {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'default.jpg';
    }

    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return 'default.jpg';
    }

    // Create unique filename to prevent overwriting
    $new_filename = uniqid('img_', true) . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    }

    return 'default.jpg';
}

// Format date securely
function formatDate($dateString) {
    if (!$dateString) return 'N/A';
    $date = new DateTime($dateString);
    return $date->format('M j, Y');
}
?>
