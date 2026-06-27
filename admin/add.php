<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="../assets/css/upload-box.css">
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6ff;
        display: flex;
        color: #333;
    }

    .content {
        margin-left: 250px;
        padding: 25px;
        width: calc(100% - 250px);
    }

    .topbar {
        background: #ffffff;
        padding: 18px 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .topbar h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        background: linear-gradient(90deg, #8fa0ff, #5d73ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .box {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .box label {
        display: block;
        margin: 15px 0 8px 0;
        font-weight: 600;
        color: #333;
        font-size: 15px;
    }

    select, input, textarea {
        width: 100%;
        padding: 14px 16px;
        margin: 0 0 20px 0;
        border: 1px solid #d1d5ff;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.25s ease;
        font-family: inherit;
    }

    select:focus, input:focus, textarea:focus {
        outline: none;
        border-color: #4a6cf7;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    button {
        padding: 14px 24px;
        background: #4a6cf7;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.25s ease;
        width: 100%;
        margin-top: 10px;
    }

    button:hover {
        background: #3c59d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
    }

</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <h2>Add Book</h2>
    </div>

    <div class="box">
        <form action="add_book.php" method="POST" enctype="multipart/form-data">

            <label>Book Title</label>
            <input type="text" name="title" required>

            <label>Author</label>
            <input type="text" name="author" required>

            <label>Category</label>
            <select name="category" required>
                <?php
                $cats = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
                while ($c = $cats->fetch_assoc()):
                ?>
                    <option value="<?= $c['category_name'] ?>"><?= $c['category_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Description</label>
            <textarea name="description" rows="4"></textarea>

            <label>Cover Image (optional)</label>
            <div class="upload-file-wrapper" data-input-name="cover">
                <div class="upload-box" id="coverUploadBox">
                    <div class="upload-icon">🖼️</div>
                    <h4>Upload Cover Image</h4>
                    <p>Drag & drop your image here or <span class="browse-text">browse</span></p>
                    <div class="file-name-display" id="coverFileName"></div>
                    <input type="file" name="cover" accept="image/*" class="file-input" id="coverFileInput">
                </div>
            </div>

            <button type="submit">Add Book</button>
        </form>
    </div>

</div>

<script>
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

