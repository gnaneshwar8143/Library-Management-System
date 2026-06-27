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
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Library</title>
    <link rel="stylesheet" href="../assets/css/action-menu.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 30px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
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

    .search-bar input {
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

    .search-bar select {
        padding: 14px 18px;
        flex: 0 0 20%;
        width: 20%;
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
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
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
        overflow: visible;
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
        z-index: 1;
        pointer-events: none;
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

    .borrow-btn {
        background: #4a6cf7;
        color: white;
        padding: 14px 24px;
        border-radius: 10px;
        display: inline-block;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.25s ease;
        border: none;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        text-align: center;
    }

    .borrow-btn:hover {
        background: #3c59d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
    }

    .read-more {
        color: #4a6cf7;
        cursor: pointer;
        margin-top: 10px;
        display: inline-block;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .read-more:hover {
        color: #3c59d0;
        text-decoration: underline;
    }

    #borrowSuccess {
        border-radius: 10px;
        padding: 16px;
    }

    .search-container {
        position: relative;
        flex: 0 0 80%;
        width: 80%;
    }

    .autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #d1d5ff;
        border-radius: 10px;
        margin-top: 5px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .autocomplete-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }

    .autocomplete-item:last-child {
        border-bottom: none;
    }

    .autocomplete-item:hover {
        background: #f4f6ff;
    }

    .autocomplete-item .type {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        margin-right: 8px;
    }

    .autocomplete-item .text {
        color: #333;
        font-weight: 500;
    }
</style>
</head>

<body>

    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Main Content -->
    <div class="content">

        <div class="topbar">
            <h2>My Library</h2>
        </div>

        <div class="search-bar">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search books, authors, categories..." autocomplete="off">
                <div id="autocompleteDropdown" class="autocomplete-dropdown"></div>
            </div>
            <select id="categoryFilter">
                <option value="">All Categories</option>

                <?php
                    $catQuery = "SELECT * FROM categories ORDER BY category_name ASC";
                    $catResult = $conn->query($catQuery);

                    if ($catResult->num_rows > 0):

                        while ($cat = $catResult->fetch_assoc()):

                ?>
                    <option value="<?= htmlspecialchars($cat['category_name'], ENT_QUOTES) ?>">
                        <?= htmlspecialchars($cat['category_name'], ENT_QUOTES) ?>
                    </option>
                <?php endwhile; endif; ?>
            </select>
        </div>

        <div class="grid" id="booksGrid">
            <?php
                // Initial load - show all books
                $sql = "SELECT * FROM books ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):

                    while ($row = $result->fetch_assoc()):

            ?>

                <div class="card" onclick="openPreview(<?= $row['id'] ?>)">
                    <div class="card-action-menu" onclick="event.stopPropagation();">
                        <button class="action-menu-btn" onclick="toggleActionMenu(this, event)">
                            ⋮
                        </button>
                        <div class="action-menu-dropdown">
                            <button class="action-menu-item" onclick="event.stopPropagation(); deleteBook(<?= $row['id'] ?>)">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>

                    <?php 
                    $img = $row['cover_image'];
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
                    <img 
                        src="<?= htmlspecialchars($src, ENT_QUOTES) ?>" 
                        width="100%" 
                        style="border-radius: 10px;"
                    >

                    <h3><?= htmlspecialchars($row['title'], ENT_QUOTES) ?></h3>
                    <p><?= htmlspecialchars($row['author'], ENT_QUOTES) ?></p>
                    <p><?= htmlspecialchars($row['category'], ENT_QUOTES) ?></p>
                </div>

            <?php
                    endwhile;
                else:

            ?>
                <p>No books found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Preview Panel -->
    <div class="preview-panel" id="previewPanel">
        <div class="close-btn" onclick="closePreview()">Close</div>

        <img src="../assets/images/book_placeholder.svg" width="100%" style="border-radius: 10px;">
        <h2>Book Title</h2>
        <p><b>Author:</b> <span class="author"></span></p>
        <p><b>Category:</b> <span class="category"></span></p>

        <p id="bookDescription">
            <span id="shortDesc"></span>
            <span id="fullDesc" style="display: none;"></span>
        </p>

        <span class="read-more" id="readMoreLink" onclick="toggleReadMore()">Read more</span>

        <div class="borrow-btn">Borrow</div>

        <div id="borrowSuccess" style="
            display:none;
            margin-top:15px;
            padding:12px;
            background:#e8f0ff;
            border-left:4px solid #4a6cf7;
            border-radius:8px;
            color:#4a6cf7;
            font-size:14px;
        "></div>
    </div>

<script>
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
    if (!dropdown) return;
    
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

function openPreview(bookId) {
    fetch("get_book.php?id=" + bookId)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {

                const book = data.data;

                // Handle image source (external URL or local file)
                let imgSrc = "../assets/images/book_placeholder.svg";
                if (book.cover_image) {
                    if (/^http/i.test(book.cover_image)) {
                        imgSrc = book.cover_image;
                    } else {
                        imgSrc = "../uploads/book_covers/" + book.cover_image;
                    }
                }
                document.querySelector("#previewPanel img").src = imgSrc;

                document.querySelector("#previewPanel h2").innerText = book.title;
                document.querySelector("#previewPanel .author").innerText = book.author;
                document.querySelector("#previewPanel .category").innerText = book.category;

                const desc = book.description ? book.description : "No description provided.";
                
                // Store full description
                document.getElementById("previewPanel").setAttribute("data-full-desc", desc);
                
                const shortDesc = document.getElementById("shortDesc");
                const fullDesc = document.getElementById("fullDesc");
                const readMoreLink = document.getElementById("readMoreLink");
                
                // Show short description (150 chars)
                if (desc.length > 150) {
                    shortDesc.innerText = desc.substring(0, 150) + "...";
                    fullDesc.innerText = desc;
                    shortDesc.style.display = "inline";
                    fullDesc.style.display = "none";
                    readMoreLink.style.display = "inline-block";
                    readMoreLink.innerText = "Read more";
                    document.getElementById("previewPanel").setAttribute("data-expanded", "false");
                } else {
                    shortDesc.innerText = desc;
                    shortDesc.style.display = "inline";
                    fullDesc.style.display = "none";
                    readMoreLink.style.display = "none";
                }

                document.getElementById("previewPanel").setAttribute("data-book-id", bookId);

                document.getElementById("previewPanel").classList.add("active");
            }
        })
        .catch(err => alert("Network error"));
}

function closePreview() {
    document.getElementById("previewPanel").classList.remove("active");
    // Reset read more state when closing
    document.getElementById("previewPanel").setAttribute("data-expanded", "false");
    document.getElementById("readMoreLink").innerText = "Read more";
}

function toggleReadMore() {
    const panel = document.getElementById("previewPanel");
    const isExpanded = panel.getAttribute("data-expanded") === "true";
    const shortDesc = document.getElementById("shortDesc");
    const fullDesc = document.getElementById("fullDesc");
    const readMoreLink = document.getElementById("readMoreLink");
    const fullText = panel.getAttribute("data-full-desc");
    
    if (!fullText || fullText.length <= 150) return;
    
    if (isExpanded) {
        // Collapse: show short version
        shortDesc.style.display = "inline";
        fullDesc.style.display = "none";
        readMoreLink.innerText = "Read more";
        panel.setAttribute("data-expanded", "false");
    } else {
        // Expand: show full version
        shortDesc.style.display = "none";
        fullDesc.style.display = "inline";
        readMoreLink.innerText = "Read less";
        panel.setAttribute("data-expanded", "true");
    }
}

function deleteBook(bookId) {
    // Close any open dropdowns
    document.querySelectorAll('.action-menu-dropdown').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
    document.querySelectorAll('.action-menu-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (!confirm("Are you sure you want to delete this book?")) return;

    fetch("delete_book.php?id=" + bookId)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                // Refresh the book list with current search/filter
                loadBooks(currentSearch, currentCategory);
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert("Network error"));
}

// Live Search and Autocomplete System
let searchTimeout;
let autocompleteTimeout;
let currentSearch = '';
let currentCategory = '';

// Load books via AJAX
function loadBooks(search = '', category = '') {
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (category && category !== 'All Categories') params.append('category', category);
    
    fetch('search_api.php?' + params.toString())
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
            <div class="card-action-menu" onclick="event.stopPropagation();">
                <button class="action-menu-btn" onclick="toggleActionMenu(this, event)">
                    ⋮
                </button>
                <div class="action-menu-dropdown">
                    <button class="action-menu-item" onclick="event.stopPropagation(); deleteBook(${book.id})">
                        🗑️ Delete
                    </button>
                </div>
            </div>
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

// Load autocomplete suggestions
function loadAutocomplete(query) {
    if (query.length < 2) {
        document.getElementById('autocompleteDropdown').style.display = 'none';
        return;
    }
    
    fetch('autocomplete_api.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAutocomplete(data.suggestions);
            }
        })
        .catch(err => console.error('Autocomplete error:', err));
}

// Display autocomplete suggestions
function showAutocomplete(suggestions) {
    const dropdown = document.getElementById('autocompleteDropdown');
    
    if (suggestions.length === 0) {
        dropdown.style.display = 'none';
        return;
    }
    
    dropdown.innerHTML = suggestions.map((suggestion, index) => {
        const safeText = escapeHtml(suggestion.text);
        return `
        <div class="autocomplete-item" data-index="${index}" data-text="${safeText.replace(/"/g, '&quot;')}">
            <span class="type">${suggestion.type}:</span>
            <span class="text">${safeText}</span>
        </div>
    `;
    }).join('');
    
    // Add click event listeners
    dropdown.querySelectorAll('.autocomplete-item').forEach(item => {
        item.addEventListener('click', function() {
            const text = this.getAttribute('data-text');
            selectSuggestion(text);
        });
    });
    
    dropdown.style.display = 'block';
}

// Select a suggestion
function selectSuggestion(text) {
    document.getElementById('searchInput').value = text;
    document.getElementById('autocompleteDropdown').style.display = 'none';
    currentSearch = text;
    loadBooks(currentSearch, currentCategory);
}

// Search input event handler
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = this.value.trim();
    currentSearch = query;
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    clearTimeout(autocompleteTimeout);
    
    // Load autocomplete suggestions
    autocompleteTimeout = setTimeout(() => {
        loadAutocomplete(query);
    }, 200);
    
    // Load search results (debounced)
    searchTimeout = setTimeout(() => {
        loadBooks(currentSearch, currentCategory);
    }, 300);
});

// Category filter event handler
document.getElementById('categoryFilter').addEventListener('change', function() {
    currentCategory = this.value;
    loadBooks(currentSearch, currentCategory);
    // Hide autocomplete when category changes
    document.getElementById('autocompleteDropdown').style.display = 'none';
});

// Hide autocomplete when clicking outside
document.addEventListener('click', function(e) {
    const searchContainer = document.querySelector('.search-container');
    if (!searchContainer.contains(e.target)) {
        document.getElementById('autocompleteDropdown').style.display = 'none';
    }
});

// Open borrow modal
document.querySelector(".borrow-btn").onclick = function() {
    document.getElementById("borrowModal").style.display = "flex";
};

// Close modal
function closeBorrowModal() {
    document.getElementById("borrowModal").style.display = "none";
}

function confirmBorrow() {
    const bookId = document.getElementById("previewPanel").getAttribute("data-book-id");
    const roll = document.getElementById("borrowRoll").value;
    const bDate = document.getElementById("borrowDate").value;
    const rDate = document.getElementById("returnDate").value;

    if (!roll || !bDate || !rDate) {
        alert("Please fill all fields");
        return;
    }

    fetch("borrow_book.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            book_id: bookId,
            roll: roll,
            borrow_date: bDate,
            return_date: rDate
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            // Build success message
            let msg = `
                <b>Borrow Successful ✔</b><br>
                Student: ${roll}<br>
                Borrow: ${bDate}<br>
                Return: ${rDate}
            `;

            // Show success panel
            document.getElementById("borrowSuccess").innerHTML = msg;
            document.getElementById("borrowSuccess").style.display = "block";

            // Close modal
            closeBorrowModal();

            // Keep preview panel open
            // Optionally refresh book details later if needed
        } else {
            alert(data.message);
        }
    })
    .catch(err => alert("Network error"));
}
</script>

<div id="borrowModal" class="modal-bg" style="
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
">
    <div class="modal-box" style="
        background: white;
        padding: 25px;
        border-radius: 12px;
        width: 350px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        animation: fadeIn 0.3s ease;
        z-index: 10000;
        position: relative;
    ">

        <h3 style="margin-top:0; color:#4a6cf7;">Borrow Book</h3>

        <label>Student Roll Number</label>
        <input type="text" id="borrowRoll" style="
            width: 100%; padding: 10px; margin: 5px 0 15px 0;
            border: 1px solid #ccc; border-radius: 8px;
        ">

        <label>Borrow Date</label>
        <input type="text" id="borrowDate" placeholder="Select borrow date" style="
            width: 100%; padding: 10px; margin: 5px 0 15px 0;
            border: 1px solid #ccc; border-radius: 8px;
            font-family: 'Poppins', sans-serif;
        ">

        <label>Return Date</label>
        <input type="text" id="returnDate" placeholder="Select return date" style="
            width: 100%; padding: 10px; margin: 5px 0 15px 0;
            border: 1px solid #ccc; border-radius: 8px;
            font-family: 'Poppins', sans-serif;
        ">

        <button onclick="confirmBorrow()" style="
            width: 100%; padding: 12px;
            background:#4a6cf7; color:white;
            border:none; border-radius:8px;
            margin-top:10px; font-size:16px; cursor:pointer;
        ">Confirm Borrow</button>

        <button onclick="closeBorrowModal()" style="
            width: 100%; padding: 12px;
            background:#ddd; color:#333;
            border:none; border-radius:8px;
            margin-top:10px; font-size:16px; cursor:pointer;
        ">Cancel</button>

    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Initialize Flatpickr on date inputs
document.addEventListener('DOMContentLoaded', function() {
    // Borrow Date Picker
    const borrowDatePicker = flatpickr("#borrowDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enableTime: false,
        theme: "material_blue",
        animate: true,
        allowInput: true,
        clickOpens: true,
        defaultDate: new Date(),
        onChange: function(selectedDates, dateStr, instance) {
            // Update return date minimum to be after borrow date
            if (selectedDates.length > 0) {
                returnDatePicker.set('minDate', selectedDates[0]);
            }
        }
    });

    // Return Date Picker
    const returnDatePicker = flatpickr("#returnDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enableTime: false,
        theme: "material_blue",
        animate: true,
        allowInput: true,
        clickOpens: true
    });

    // Update return date minimum when borrow date changes
    document.getElementById('borrowDate').addEventListener('change', function() {
        const borrowDate = this.value;
        if (borrowDate) {
            returnDatePicker.set('minDate', borrowDate);
        }
    });
});

// Custom Flatpickr styling to match project design
const style = document.createElement('style');
style.textContent = `
    .flatpickr-calendar {
        font-family: 'Poppins', sans-serif !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(179, 192, 237, 0.2) !important;
        z-index: 10001 !important;
    }
    
    .flatpickr-months {
        background: linear-gradient(135deg, #4a6cf7 0%, #6b7fd7 100%) !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 10px 0 !important;
    }
    
    .flatpickr-month {
        color: white !important;
    }
    
    .flatpickr-current-month {
        color: white !important;
        font-weight: 600 !important;
    }
    
    .flatpickr-prev-month,
    .flatpickr-next-month {
        color: white !important;
        fill: white !important;
    }
    
    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 6px !important;
    }
    
    .flatpickr-weekdays {
        background: rgba(179, 192, 237, 0.1) !important;
        padding: 8px 0 !important;
    }
    
    .flatpickr-weekday {
        color: #4a6cf7 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
    }
    
    .flatpickr-day {
        border-radius: 8px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }
    
    .flatpickr-day:hover {
        background: rgba(74, 108, 247, 0.1) !important;
        border-color: rgba(74, 108, 247, 0.3) !important;
    }
    
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: linear-gradient(135deg, #4a6cf7 0%, #6b7fd7 100%) !important;
        border-color: #4a6cf7 !important;
        color: white !important;
        font-weight: 600 !important;
    }
    
    .flatpickr-day.today {
        border-color: #4a6cf7 !important;
        background: rgba(74, 108, 247, 0.15) !important;
        color: #4a6cf7 !important;
        font-weight: 600 !important;
    }
    
    .flatpickr-day.today.selected {
        background: linear-gradient(135deg, #4a6cf7 0%, #6b7fd7 100%) !important;
        color: white !important;
    }
    
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: #ccc !important;
        opacity: 0.5 !important;
    }
    
    .flatpickr-day.flatpickr-disabled:hover {
        background: transparent !important;
        cursor: not-allowed !important;
    }
    
    .flatpickr-input {
        cursor: pointer !important;
    }
    
    .flatpickr-input:focus {
        border-color: #4a6cf7 !important;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1) !important;
        outline: none !important;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .flatpickr-calendar {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .flatpickr-day {
            height: 36px !important;
            line-height: 36px !important;
        }
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>

