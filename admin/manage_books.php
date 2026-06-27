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
<title>Manage Books</title>

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
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(179, 192, 237, 0.2);
    }

    th, td {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(179, 192, 237, 0.2);
        text-align: left;
    }

    th {
        background: linear-gradient(90deg, #8fa0ff, #5d73ff);
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(93, 115, 255, 0.3);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: #f3f6ff;
        transition: background 0.2s ease;
    }

    td {
        color: #1a1a2e;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        border: none;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .borrow-btn {
        background: linear-gradient(90deg, #8c9eff, #536dfe);
        box-shadow: 0 4px 12px rgba(83, 109, 254, 0.4);
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .borrow-btn:hover {
        background: linear-gradient(90deg, #7c8dfc, #4e60e6);
        box-shadow: 0 6px 20px rgba(78, 96, 230, 0.5);
    }

    .return-btn {
        background: linear-gradient(135deg, #ff7043 0%, #ff5722 100%);
        box-shadow: 0 4px 12px rgba(255, 112, 67, 0.3);
    }

    .return-btn:hover {
        background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%);
        box-shadow: 0 6px 20px rgba(255, 112, 67, 0.4);
    }

    .delete-btn {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
    }

    .delete-btn:hover {
        background: linear-gradient(135deg, #ee5a5a 0%, #dd4949 100%);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .modal-bg {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        backdrop-filter: blur(8px);
    }

    .modal-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 30px;
        border-radius: 16px;
        width: 380px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(179, 192, 237, 0.2);
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .modal-box h3 {
        margin-top: 0;
        color: #4a6cf7;
        font-size: 20px;
    }

    .modal-box label {
        display: block;
        margin: 15px 0 8px 0;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .modal-box input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5ff;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.25s ease;
    }

    .modal-box input:focus {
        outline: none;
        border-color: #4a6cf7;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
    }

    .modal-box button {
        margin-top: 8px;
    }

</style>
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <h2>Manage Books</h2>
    </div>

    <table>
        <tr>
            <th>Book Name</th>
            <th>Author</th>
            <th>Category</th>
            <th>Student Roll</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
            <th>Action</th>
        </tr>

        <?php
        // Fetch only borrowed books
        $query = "SELECT 
            books.id AS book_id,
            books.title,
            books.author,
            books.category,
            borrow_records.id AS borrow_id,
            borrow_records.student_roll,
            borrow_records.borrow_date,
            borrow_records.return_date,
            borrow_records.status
        FROM borrow_records
        INNER JOIN books ON books.id = borrow_records.book_id
        WHERE borrow_records.status = 'borrowed'
        ORDER BY borrow_records.borrow_date DESC";
        
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['title'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($row['author'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($row['category'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($row['student_roll'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($row['borrow_date'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($row['return_date'], ENT_QUOTES) ?></td>
            <td>
                <button class="btn return-btn"
                    onclick="openReturnModal(<?= $row['borrow_id'] ?>)">
                    Return
                </button>
            </td>
        </tr>
        <?php 
            endwhile;
        else:
        ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                No borrowed books at the moment.
            </td>
        </tr>
        <?php endif; ?>
    </table>
</div>

<!-- RETURN MODAL -->
<div class="modal-bg" id="returnModal">
    <div class="modal-box">
        <h3 style="color:#4a6cf7;">Return Book</h3>

        <label>Enter Return Password</label>
        <input type="password" id="returnPass" style="
            width: 100%; padding: 10px; 
            border:1px solid #ccc; border-radius:8px;
            margin: 8px 0 15px 0;
        ">

        <button class="btn return-btn"
            onclick="confirmReturn()">
            Confirm Return
        </button>

        <button class="btn" style="background:#ccc; color:black;"
            onclick="closeReturnModal()">
            Cancel
        </button>
    </div>
</div>

<script>
let activeBorrowId = null;

// RETURN MODAL
function openReturnModal(id) {
    activeBorrowId = id;
    document.getElementById("returnModal").style.display = "flex";
}

function closeReturnModal() {
    activeBorrowId = null;
    document.getElementById("returnModal").style.display = "none";
}

function confirmReturn() {
    let pass = document.getElementById("returnPass").value;

    fetch("return_book.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            borrow_id: activeBorrowId,
            password: pass
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert("Book Returned ✔");
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(err => alert("Network error"));
}

function deleteBook(bookId) {
    if (!confirm("Are you sure you want to delete this book?")) return;

    fetch("delete_book.php?id=" + bookId)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert("Network error"));
}

function openBorrowFromTable(bookId) {
    window.location.href = "my_library.php";
}

</script>

</body>
</html>
