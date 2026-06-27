<?php
/**
 * One-time script to add audio_image column to audio_books table
 * Run this once, then delete the file
 */
session_start();
include "../config/db.php";

// Check authentication
if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized. Please log in as admin.");
}

echo "<h2>Adding audio_image Column</h2>";
echo "<pre>";

// Add audio_image column
$sql = "ALTER TABLE audio_books ADD COLUMN audio_image VARCHAR(255) DEFAULT 'default_audio.jpg' AFTER audio_file";

if ($conn->query($sql)) {
    echo "✓ Successfully added audio_image column to audio_books table\n";
    
    // Update existing records to have default image
    $updateSql = "UPDATE audio_books SET audio_image = 'default_audio.jpg' WHERE audio_image IS NULL OR audio_image = ''";
    if ($conn->query($updateSql)) {
        echo "✓ Updated existing records with default image\n";
    } else {
        echo "⚠ Warning: " . $conn->error . "\n";
    }
} else {
    echo "✗ Error: " . $conn->error . "\n";
    echo "\nIf column already exists, you can ignore this error.\n";
}

echo "\nDone! You can now delete this file.\n";
echo "</pre>";
?>

