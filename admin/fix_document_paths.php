<?php
/**
 * One-time script to fix document file_path values in database
 * Strips any path prefixes and keeps only the filename
 * Run this once, then delete the file
 */
session_start();
include "../config/db.php";
include "../config/config.php";

// Check authentication
if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized. Please log in as admin.");
}

echo "<h2>Fixing Document Paths</h2>";
echo "<pre>";

// Get all documents
$result = $conn->query("SELECT id, file_path FROM documents");

if (!$result) {
    die("Error: " . $conn->error);
}

$updated = 0;
$skipped = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $oldPath = $row['file_path'];
    
    // Extract just the filename
    $newPath = basename($oldPath);
    
    // Only update if path changed
    if ($oldPath !== $newPath) {
        // Verify file exists with new path
        $filePath = $DOCS_PATH . $newPath;
        
        if (file_exists($filePath)) {
            // Update database
            $stmt = $conn->prepare("UPDATE documents SET file_path = ? WHERE id = ?");
            $stmt->bind_param("si", $newPath, $id);
            
            if ($stmt->execute()) {
                echo "✓ Updated ID $id: '$oldPath' → '$newPath'\n";
                $updated++;
            } else {
                echo "✗ Error updating ID $id: " . $stmt->error . "\n";
            }
            $stmt->close();
        } else {
            echo "⚠ Skipped ID $id: File not found at '$newPath'\n";
            $skipped++;
        }
    } else {
        $skipped++;
    }
}

echo "\n";
echo "Summary:\n";
echo "- Updated: $updated records\n";
echo "- Skipped: $skipped records (already correct or file missing)\n";
echo "\n";
echo "Done! You can now delete this file.\n";
echo "</pre>";
?>

