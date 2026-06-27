<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    die("Unauthorized");
}

if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(404);
    echo "File not found";
    exit;
}

$file = basename($_GET['file']);

$baseDir = $_SERVER['DOCUMENT_ROOT'] . "/Library_Managment/uploads/documents/";
$filePath = $baseDir . $file;

if (!file_exists($filePath)) {
    http_response_code(404);
    echo "File not found";
    exit;
}

$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

// Build public URL for PDF.js
$fileUrl = "/Library_Managment/uploads/documents/" . $file;

if ($extension === "pdf") {
    echo "<!DOCTYPE html>
<html style='width:100%; height:100%; margin:0; padding:0; overflow:hidden;'>
<head>
    <meta charset='UTF-8'>
    <style>
        html, body {
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }
        .pdf-frame {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            display: block !important;
        }
    </style>
</head>
<body style='width:100%; height:100%; margin:0; padding:0;'>
    <iframe 
        class='pdf-frame'
        src='/Library_Managment/pdfjs/web/viewer.html?file=" . urlencode($fileUrl) . "'>
    </iframe>
</body>
</html>";
    exit;
}

// Non-PDF files fallback
echo "
    <div style='padding:20px; color:white;'>
        <h3>Preview not supported for this file type.</h3>
        <p>Please click <b>Open in New Tab</b> or <b>Download</b> to view this file.</p>
    </div>
";

exit;
?>
