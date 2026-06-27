<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: ../login.php");
    exit;
}
include "../config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Library</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: transparent;
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
        background: linear-gradient(90deg, #8fa0ff, #5d73ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
    }

    .search-bar {
        width: 100%;
        display: flex;
        gap: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid rgba(179, 192, 237, 0.2);
    }

    .search-bar input, .search-bar select {
        padding: 14px 18px;
        width: 100%;
        border: 2px solid rgba(179, 192, 237, 0.2);
        border-radius: 12px;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #1a1a2e;
    }

    .search-bar input:focus, .search-bar select:focus {
        outline: none;
        border-color: #b3c0ed;
        box-shadow: 0 0 0 4px rgba(179, 192, 237, 0.1);
        transform: translateY(-1px);
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 24px;
    }

    .card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(179, 192, 237, 0.2);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #b3c0ed 0%, #d8e2ff 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .card:hover::before {
        opacity: 1;
    }

    .card img {
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .card h3 {
        margin: 8px 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a2e;
        font-family: 'Poppins', sans-serif;
    }

    .card p {
        margin: 4px 0;
        font-size: 14px;
        color: #6b7fd7;
        font-family: 'Poppins', sans-serif;
    }

    .preview-panel {
        width: 400px;
        background: #ffffff;
        height: 100vh;
        position: fixed;
        right: -420px;
        top: 0;
        box-shadow: -6px 0 24px rgba(0,0,0,0.15);
        padding: 30px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        z-index: 1000;
    }

    .preview-panel.active {
        right: 0;
        animation: slideIn 0.4s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .close-btn {
        background: #e8ebff;
        color: #4a6cf7;
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        display: inline-block;
        margin-bottom: 15px;
        font-weight: 600;
        transition: all 0.25s ease;
        border: none;
    }

    .close-btn:hover {
        background: #d1d5ff;
        transform: translateY(-2px);
    }

    .preview-panel img {
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .preview-panel h2 {
        margin: 15px 0 10px 0;
        color: #333;
        font-size: 22px;
    }

    .preview-panel p {
        margin: 8px 0;
        color: #666;
        line-height: 1.6;
    }

    .no-results {
        text-align: center;
        padding: 60px 20px;
        color: #999;
        font-size: 18px;
        grid-column: 1 / -1;
    }

    .no-results-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #4a6cf7;
        grid-column: 1 / -1;
    }
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <h2>My Library</h2>
    </div>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search books...">
        <select id="categoryFilter">
            <option value="">All Categories</option>

            <?php
            $catQuery = "SELECT * FROM categories ORDER BY category_name ASC";
            $cats = $conn->query($catQuery);
            while ($c = $cats->fetch_assoc()):
            ?>
                <option value="<?= $c['category_name'] ?>"><?= $c['category_name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="grid" id="booksGrid">
        <?php
        $books = $conn->query("SELECT * FROM books ORDER BY id DESC");
        while ($b = $books->fetch_assoc()):
        ?>
        <div class="card" onclick="openPreview(<?= $b['id'] ?>)">
            <?php 
            $img = $b['cover_image'];
            if ($img) {
                if (preg_match('/^http/i', $img)) {
                    $src = $img;
                } else {
                    $src = '../uploads/book_covers/' . $img;
                }
            } else {
                $src = '../assets/images/book_placeholder.svg';
            }
            ?>
            <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
                 width="100%" style="border-radius:10px;">

            <h3><?= htmlspecialchars($b['title'], ENT_QUOTES) ?></h3>
            <p><?= htmlspecialchars($b['author'], ENT_QUOTES) ?></p>
            <p><?= htmlspecialchars($b['category'], ENT_QUOTES) ?></p>
        </div>
        <?php endwhile; ?>
    </div>

</div>

<!-- Preview Panel -->
<div class="preview-panel" id="previewPanel">

    <div class="close-btn" onclick="closePreview()">Close</div>

    <img id="pCover" src="" width="100%" style="border-radius:10px;">
    <h2 id="pTitle"></h2>
    <p><b>Author:</b> <span id="pAuthor"></span></p>
    <p><b>Category:</b> <span id="pCategory"></span></p>

    <p id="pDesc"></p>
</div>

<script>
// PREVIEW
function openPreview(id) {
    fetch("../admin/get_book.php?id=" + id)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                const b = data.data;

                // Handle image source (external URL or local file)
                let imgSrc = "../assets/images/book_placeholder.svg";
                if (b.cover_image) {
                    if (/^http/i.test(b.cover_image)) {
                        imgSrc = b.cover_image;
                    } else {
                        imgSrc = "../uploads/book_covers/" + b.cover_image;
                    }
                }
                document.getElementById("pCover").src = imgSrc;

                document.getElementById("pTitle").innerText = b.title;
                document.getElementById("pAuthor").innerText = b.author;
                document.getElementById("pCategory").innerText = b.category;
                document.getElementById("pDesc").innerText = b.description ?? "No description.";

                document.getElementById("previewPanel").classList.add("active");
            }
        })
        .catch(err => alert("Network error"));
}

function closePreview() {
    document.getElementById("previewPanel").classList.remove("active");
}

// Live Search System (matching admin implementation)
let searchTimeout;
let currentSearch = '';
let currentCategory = '';

// Load books via AJAX
function loadBooks(search = '', category = '') {
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (category && category !== 'All Categories') params.append('category', category);
    
    fetch('searchBooks.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateBooksGrid(data.books);
            }
        })
        .catch(err => console.error('Search error:', err));
}

// Update books grid with new results
function updateBooksGrid(books) {
    const grid = document.getElementById('booksGrid');
    
    if (books.length === 0) {
        grid.innerHTML = '<p>No books found.</p>';
        return;
    }
    
    grid.innerHTML = books.map(book => `
        <div class="card" onclick="openPreview(${book.id})">
            <img src="${escapeHtml(book.cover_image)}" width="100%" style="border-radius: 10px;">
            <h3>${escapeHtml(book.title)}</h3>
            <p>${escapeHtml(book.author)}</p>
            <p>${escapeHtml(book.category)}</p>
        </div>
    `).join('');
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Search input event handler
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = this.value.trim();
    currentSearch = query;
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Load search results (debounced)
    searchTimeout = setTimeout(() => {
        loadBooks(currentSearch, currentCategory);
    }, 300);
});

// Category filter event handler
document.getElementById('categoryFilter').addEventListener('change', function() {
    currentCategory = this.value;
    loadBooks(currentSearch, currentCategory);
});
</script>

</body>
</html>

