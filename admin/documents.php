<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/db.php";
include "../config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Documents</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/document_viewer_modal.css">
    <link rel="stylesheet" href="../assets/css/upload-box.css">
    <link rel="stylesheet" href="../assets/css/action-menu.css">

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f3f6ff 0%, #e8edff 100%);
        display: flex;
        color: #1a1a2e;
    }

    .content {
        margin-left: 260px;
        padding: 30px;
        width: calc(100% - 260px);
    }

    .topbar {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 20px 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(179, 192, 237, 0.2);
    }

    .topbar h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(90deg, #8c9eff, #536dfe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
    }

    .upload-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(179, 192, 237, 0.2);
    }

    .upload-box label {
        display: block;
        margin: 20px 0 12px 0;
        font-weight: 600;
        color: #1a1a2e;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
    }

    .upload-box input[type="text"] {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid rgba(179, 192, 237, 0.2);
        border-radius: 12px;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #1a1a2e;
    }

    .upload-box input[type="text"]:focus {
        outline: none;
        border-color: #b3c0ed;
        box-shadow: 0 0 0 4px rgba(179, 192, 237, 0.1);
        transform: translateY(-1px);
    }

    .upload-box button {
        background: linear-gradient(90deg, #8c9eff, #536dfe);
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        padding: 14px 28px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(179, 192, 237, 0.3);
        margin-top: 10px;
    }

    .upload-box button:hover {
        background: linear-gradient(90deg, #7c8dfc, #4e60e6);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 96, 230, 0.5);
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .card {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        transition: all 0.25s ease;
        position: relative;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .card h3 {
        margin: 0 0 12px 0;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }


    .download-btn {
        background: #4a6cf7;
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        margin-top: 15px;
        transition: all 0.25s ease;
    }

    .download-btn:hover {
        background: #3c59d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
    }

    .view-btn {
        background: linear-gradient(90deg, #8c9eff, #536dfe);
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        margin-top: 15px;
        margin-right: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(179, 192, 237, 0.3);
    }

    .view-btn:hover {
        background: linear-gradient(90deg, #7c8dfc, #4e60e6);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 96, 230, 0.5);
    }
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <h2>Documents</h2>
    </div>

    <!-- UPLOAD BOX -->
    <div class="upload-box">
        <form action="upload_document.php" method="POST" enctype="multipart/form-data">
            <label><b>Document Name:</b></label>
            <input type="text" name="name" required>

            <label><b>Select File:</b></label>
            <div class="upload-file-wrapper" data-input-name="file">
                <div class="upload-box" id="documentUploadBox">
                    <div class="upload-icon">📁</div>
                    <h4>Upload Document</h4>
                    <p>Drag & drop your file here or <span class="browse-text">browse</span></p>
                    <div class="file-name-display" id="documentFileName"></div>
                    <input type="file" name="file" required class="file-input" id="documentFileInput">
                </div>
            </div>

            <button type="submit">Upload Document</button>
        </form>
    </div>

    <!-- DOCUMENT GRID -->
    <div class="grid">
        <?php
        $docs = $conn->query("SELECT * FROM documents ORDER BY id DESC");

        while ($doc = $docs->fetch_assoc()):

        ?>
        <div class="card">
            <div class="card-action-menu" onclick="event.stopPropagation();">
                <button class="action-menu-btn" onclick="toggleActionMenu(this, event)">
                    ⋮
                </button>
                <div class="action-menu-dropdown">
                    <button class="action-menu-item" onclick="deleteDocument(<?= $doc['id'] ?>)">
                        🗑️ Delete
                    </button>
                </div>
            </div>

            <h3><?= htmlspecialchars($doc['name'], ENT_QUOTES) ?></h3>

            <p style="color:#666;font-size:14px;margin:8px 0;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                <?php 
                // Extract just filename (handle both old full paths and new filenames)
                $displayPath = basename($doc['file_path']);
                echo htmlspecialchars($displayPath, ENT_QUOTES); 
                ?>
            </p>

            <?php 
            // Extract just filename for view_file.php (handle both old full paths and new filenames)
            $fileName = basename($doc['file_path']);
            // Extract just filename for download (handle both old full paths and new filenames)
            $downloadFileName = basename($doc['file_path']);
            ?>

            <button class="view-btn"
                    onclick="openDocumentViewer('<?= htmlspecialchars($doc['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($fileName, ENT_QUOTES) ?>')">
                View
            </button>

            <a class="download-btn"
               href="../uploads/documents/<?= htmlspecialchars($downloadFileName, ENT_QUOTES) ?>"
               download>
                Download
            </a>

        </div>
        <?php endwhile; ?>
    </div>

</div>

<!-- Document Viewer Modal -->
<div id="documentViewerModal" class="document-viewer-modal">
    <div class="document-viewer-overlay" onclick="closeDocumentViewer()"></div>
    <div class="document-viewer-container">
        <div class="document-viewer-header">
            <h3 class="document-viewer-title" id="documentViewerTitle">Document Preview</h3>
            <button class="document-viewer-close" onclick="closeDocumentViewer()" aria-label="Close">×</button>
        </div>
        <div class="document-viewer-body">
            <div class="document-viewer-content" id="documentViewerContent">
                <div class="document-viewer-loading">Loading document...</div>
            </div>
        </div>
        <div class="document-viewer-footer">
            <button class="document-viewer-btn document-viewer-btn-secondary" onclick="closeDocumentViewer()">Close</button>
            <button class="document-viewer-btn document-viewer-btn-primary" id="documentViewerOpenTab" onclick="openDocumentInNewTab()">Open in New Tab</button>
        </div>
    </div>
</div>

<script>
function deleteDocument(id) {
    // Close any open dropdowns
    document.querySelectorAll('.action-menu-dropdown').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
    document.querySelectorAll('.action-menu-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (!confirm("Delete this document?")) return;

    fetch("delete_document.php?id=" + id)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") location.reload();
            else alert(data.message);
        })
        .catch(err => alert("Network error"));
}

// Document Viewer Modal Functions
let currentDocumentUrl = '';

function openDocumentViewer(title, fileName) {
    // Build view_file.php URL
    const viewUrl = 'view_file.php?file=' + encodeURIComponent(fileName);
    currentDocumentUrl = viewUrl;
    
    const modal = document.getElementById('documentViewerModal');
    const titleEl = document.getElementById('documentViewerTitle');
    const contentEl = document.getElementById('documentViewerContent');
    
    titleEl.textContent = title;
    contentEl.innerHTML = '<div class="document-viewer-loading">Loading document...</div>';
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Detect file type and render accordingly
    const fileExtension = fileName.split('.').pop().toLowerCase();
    
    if (fileExtension === 'pdf') {
        // PDF - use iframe with view_file.php wrapped in pdf-container
        contentEl.innerHTML = `<div class="pdf-container"><iframe class="pdf-frame" src="${escapeHtml(viewUrl)}" type="application/pdf" allow="fullscreen"></iframe></div>`;
    } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(fileExtension)) {
        // Image - use img tag with view_file.php
        contentEl.innerHTML = `<img src="${escapeHtml(viewUrl)}" alt="${escapeHtml(title)}">`;
    } else if (fileExtension === 'txt') {
        // Text file - load via fetch from view_file.php
        fetch(viewUrl)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load text file');
                return response.text();
            })
            .then(text => {
                contentEl.innerHTML = `<pre>${escapeHtml(text)}</pre>`;
            })
            .catch(err => {
                contentEl.innerHTML = `<div class="document-viewer-loading" style="color: #ff6b6b;">Error loading file: ${escapeHtml(err.message)}</div>`;
            });
    } else {
        // Default: try iframe with view_file.php wrapped in pdf-container
        contentEl.innerHTML = `<div class="pdf-container"><iframe class="pdf-frame" src="${escapeHtml(viewUrl)}" allow="fullscreen"></iframe></div>`;
    }
}

function closeDocumentViewer() {
    const modal = document.getElementById('documentViewerModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    currentDocumentUrl = '';
}

function openDocumentInNewTab() {
    if (currentDocumentUrl) {
        window.open(currentDocumentUrl, '_blank');
    }
}

// ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('documentViewerModal');
        if (modal.classList.contains('active')) {
            closeDocumentViewer();
        }
    }
});

// Escape HTML helper
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Toggle action menu dropdown
function toggleActionMenu(btn, event) {
    event.stopPropagation();
    
    // Close all other dropdowns
    document.querySelectorAll('.action-menu-dropdown').forEach(dropdown => {
        if (dropdown !== btn.nextElementSibling) {
            dropdown.classList.remove('active');
        }
    });
    
    document.querySelectorAll('.action-menu-btn').forEach(button => {
        if (button !== btn) {
            button.classList.remove('active');
        }
    });
    
    // Toggle current dropdown
    const dropdown = btn.nextElementSibling;
    const isActive = dropdown.classList.contains('active');
    
    if (isActive) {
        dropdown.classList.remove('active');
        btn.classList.remove('active');
    } else {
        dropdown.classList.add('active');
        btn.classList.add('active');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.card-action-menu')) {
        document.querySelectorAll('.action-menu-dropdown').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
        document.querySelectorAll('.action-menu-btn').forEach(btn => {
            btn.classList.remove('active');
        });
    }
});

// Drag and Drop Upload Functionality
function initDragAndDrop() {
    const uploadBoxes = document.querySelectorAll('.upload-file-wrapper .upload-box');
    
    uploadBoxes.forEach(uploadBox => {
        const fileInput = uploadBox.querySelector('.file-input');
        const fileNameDisplay = uploadBox.parentElement.querySelector('.file-name-display');
        
        if (!fileInput) return;
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadBox.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadBox.addEventListener(eventName, () => {
                uploadBox.classList.add('drag-over');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadBox.addEventListener(eventName, () => {
                uploadBox.classList.remove('drag-over');
            }, false);
        });
        
        // Handle dropped files
        uploadBox.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                updateFileNameDisplay(fileNameDisplay, files[0].name);
            }
        }, false);
        
        // Handle file selection via click
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                updateFileNameDisplay(fileNameDisplay, e.target.files[0].name);
            }
        });
    });
}

function updateFileNameDisplay(displayElement, fileName) {
    if (displayElement) {
        displayElement.textContent = fileName;
        displayElement.classList.add('has-file');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initDragAndDrop();
});
</script>

</body>
</html>

