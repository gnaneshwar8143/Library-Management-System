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
    <title>Audio Books</title>
    <link rel="stylesheet" href="../assets/css/upload-box.css">
    <link rel="stylesheet" href="../assets/css/action-menu.css">
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
        color: #4a6cf7;
    }

    .upload-box {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .upload-box label {
        display: block;
        margin: 15px 0 8px 0;
        font-weight: 600;
        color: #333;
        font-size: 15px;
    }

    .upload-box input[type="text"],
    .upload-box input[type="file"] {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #d1d5ff;
        border-radius: 10px;
        font-size: 15px;
        margin-bottom: 15px;
        transition: all 0.25s ease;
    }

    .upload-box input[type="text"]:focus {
        outline: none;
        border-color: #4a6cf7;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
    }

    .upload-box button {
        background: #4a6cf7;
        color: white;
        padding: 14px 24px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .upload-box button:hover {
        background: #3c59d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
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
        margin: 0 0 15px 0;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }


    audio {
        width: 100%;
        margin-top: 10px;
        outline: none;
        border-radius: 8px;
    }

    .card img {
        width: 100%;
        border-radius: 12px;
        object-fit: cover;
        height: 180px;
        margin-bottom: 15px;
    }

    /* Spotify-Style Mini Player */
    .audio-player {
        margin-top: 15px;
    }

    .player-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .player-btn {
        background: #4a6cf7;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: white;
        font-size: 18px;
    }

    .player-btn:hover {
        transform: scale(1.1);
        background: #3c59d0;
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.4);
    }

    .player-btn:active {
        transform: scale(0.95);
    }

    .player-btn.play-pause {
        width: 56px;
        height: 56px;
        font-size: 22px;
        background: linear-gradient(90deg, #8c9eff, #536dfe);
    }

    .player-btn.play-pause:hover {
        background: linear-gradient(135deg, #9ba8d9 0%, #8a9de0 100%);
        box-shadow: 0 4px 16px rgba(179, 192, 237, 0.5);
    }

    .player-btn.prev-next {
        width: 40px;
        height: 40px;
        font-size: 16px;
        background: rgba(74, 108, 247, 0.1);
        color: #4a6cf7;
    }

    .player-btn.prev-next:hover {
        background: rgba(74, 108, 247, 0.2);
    }

    .waveform {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        height: 32px;
        margin-bottom: 10px;
    }

    .waveform-bar {
        width: 4px;
        background: #4a6cf7;
        border-radius: 2px;
        transition: height 0.3s ease;
        height: 8px;
    }

    .waveform-bar.active {
        animation: waveformPulse 0.8s ease-in-out infinite;
    }

    .waveform-bar:nth-child(1).active { animation-delay: 0s; }
    .waveform-bar:nth-child(2).active { animation-delay: 0.1s; }
    .waveform-bar:nth-child(3).active { animation-delay: 0.2s; }
    .waveform-bar:nth-child(4).active { animation-delay: 0.3s; }
    .waveform-bar:nth-child(5).active { animation-delay: 0.4s; }
    .waveform-bar:nth-child(6).active { animation-delay: 0.5s; }
    .waveform-bar:nth-child(7).active { animation-delay: 0.6s; }
    .waveform-bar:nth-child(8).active { animation-delay: 0.7s; }

    @keyframes waveformPulse {
        0%, 100% {
            height: 8px;
            opacity: 0.5;
        }
        50% {
            height: 28px;
            opacity: 1;
        }
    }

    .progress-container {
        position: relative;
        width: 100%;
        height: 6px;
        background: #e0e0e0;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 8px;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #8c9eff, #536dfe);
        border-radius: 3px;
        width: 0%;
        transition: width 0.1s linear;
    }

    .progress-handle {
        position: absolute;
        top: 50%;
        left: 0%;
        transform: translate(-50%, -50%);
        width: 14px;
        height: 14px;
        background: linear-gradient(90deg, #8c9eff, #536dfe);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .progress-container:hover .progress-handle {
        opacity: 1;
    }

    .time-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }

    .volume-control {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .volume-icon {
        font-size: 14px;
        color: #666;
        width: 16px;
    }

    .volume-slider {
        flex: 1;
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        outline: none;
        cursor: pointer;
        -webkit-appearance: none;
    }

    .volume-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 12px;
        height: 12px;
        background: #4a6cf7;
        border-radius: 50%;
        cursor: pointer;
    }

    .volume-slider::-moz-range-thumb {
        width: 12px;
        height: 12px;
        background: #4a6cf7;
        border-radius: 50%;
        cursor: pointer;
        border: none;
    }

    .hidden-audio {
        display: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .player-controls {
            gap: 8px;
        }

        .player-btn.play-pause {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }

        .player-btn.prev-next {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .waveform {
            height: 24px;
        }
    }
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <div class="topbar">
        <h2>Audio Books</h2>
    </div>

    <!-- UPLOAD BOX -->
    <div class="upload-box">
        <form action="upload_audio.php" method="POST" enctype="multipart/form-data">
            <label><b>Audio Title:</b></label>
            <input type="text" name="title" required>

            <label><b>Select MP3 File:</b></label>
            <div class="upload-file-wrapper" data-input-name="audio">
                <div class="upload-box" id="audioUploadBox">
                    <div class="upload-icon">🎵</div>
                    <h4>Upload Audio File</h4>
                    <p>Drag & drop your MP3 file here or <span class="browse-text">browse</span></p>
                    <div class="file-name-display" id="audioFileName"></div>
                    <input type="file" name="audio" accept=".mp3" required class="file-input" id="audioFileInput">
                </div>
            </div>

            <label><b>Select Image File (JPG/PNG):</b></label>
            <div class="upload-file-wrapper" data-input-name="image">
                <div class="upload-box" id="imageUploadBox">
                    <div class="upload-icon">🖼️</div>
                    <h4>Upload Image</h4>
                    <p>Drag & drop your image here or <span class="browse-text">browse</span></p>
                    <div class="file-name-display" id="imageFileName"></div>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png" id="imageInput" class="file-input">
                </div>
            </div>
            <div id="imagePreview" style="margin-top: 10px; display: none;">
                <img id="previewImg" src="" alt="Preview" style="max-width: 200px; border-radius: 8px; border: 2px solid #d1d5ff;">
            </div>

            <button type="submit">Upload Audio</button>
        </form>
    </div>

    <!-- AUDIO GRID -->
    <div class="grid">
        <?php
        $audios = $conn->query("SELECT * FROM audio_books ORDER BY id DESC");

        while ($audio = $audios->fetch_assoc()):

        ?>
        <div class="card">
            <div class="card-action-menu" onclick="event.stopPropagation();">
                <button class="action-menu-btn" onclick="toggleActionMenu(this, event)">
                    ⋮
                </button>
                <div class="action-menu-dropdown">
                    <button class="action-menu-item" onclick="deleteAudio(<?= $audio['id'] ?>)">
                        🗑️ Delete
                    </button>
                </div>
            </div>

            <?php 
            // Get image path
            $imagePath = '../assets/images/default_audio.jpg'; // Default
            
            // Check if audio_image column exists and has a value
            if (isset($audio['audio_image']) && !empty($audio['audio_image']) && $audio['audio_image'] !== 'default_audio.jpg') {
                $imagePath = '../uploads/audio_images/' . htmlspecialchars($audio['audio_image'], ENT_QUOTES);
            }
            ?>
            <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($audio['title'], ENT_QUOTES) ?>" 
                 onerror="this.src='../assets/images/default_audio.jpg'">

            <h3><?= htmlspecialchars($audio['title'], ENT_QUOTES) ?></h3>
            
            <div class="audio-player" data-audio-id="<?= $audio['id'] ?>">
                <audio class="hidden-audio" preload="metadata">
                    <source src="../uploads/audio/<?= htmlspecialchars($audio['audio_file'], ENT_QUOTES) ?>" type="audio/mp3">
                </audio>
                
                <div class="player-controls">
                    <button class="player-btn prev-next prev-btn" title="Previous">
                        <span>⏮</span>
                    </button>
                    <button class="player-btn play-pause" title="Play/Pause">
                        <span class="play-icon">▶</span>
                        <span class="pause-icon" style="display: none;">⏸</span>
                    </button>
                    <button class="player-btn prev-next next-btn" title="Next">
                        <span>⏭</span>
                    </button>
                </div>
                
                <div class="waveform">
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                    <div class="waveform-bar"></div>
                </div>
                
                <div class="progress-container">
                    <div class="progress-bar"></div>
                    <div class="progress-handle"></div>
                </div>
                
                <div class="time-info">
                    <span class="current-time">0:00</span>
                    <span class="total-time">0:00</span>
                </div>
                
                <div class="volume-control">
                    <span class="volume-icon">🔊</span>
                    <input type="range" class="volume-slider" min="0" max="100" value="70">
                </div>
            </div>

        </div>
        <?php endwhile; ?>
    </div>

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

function deleteAudio(id) {
    // Close any open dropdowns
    document.querySelectorAll('.action-menu-dropdown').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
    document.querySelectorAll('.action-menu-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (!confirm("Delete this audio file?")) return;

    fetch("delete_audio.php?id=" + id)
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

// Image preview functionality
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Universal Spotify-Style Audio Player
let currentPlayer = null;
let allPlayers = [];
let currentIndex = -1;

// Initialize all players
document.addEventListener('DOMContentLoaded', function() {
    const players = document.querySelectorAll('.audio-player');
    allPlayers = Array.from(players);
    
    allPlayers.forEach((player, index) => {
        const audio = player.querySelector('audio');
        const playPauseBtn = player.querySelector('.play-pause');
        const prevBtn = player.querySelector('.prev-btn');
        const nextBtn = player.querySelector('.next-btn');
        const progressBar = player.querySelector('.progress-bar');
        const progressContainer = player.querySelector('.progress-container');
        const progressHandle = player.querySelector('.progress-handle');
        const currentTimeEl = player.querySelector('.current-time');
        const totalTimeEl = player.querySelector('.total-time');
        const volumeSlider = player.querySelector('.volume-slider');
        const waveformBars = player.querySelectorAll('.waveform-bar');
        const playIcon = player.querySelector('.play-icon');
        const pauseIcon = player.querySelector('.pause-icon');
        
        // Set initial volume
        audio.volume = volumeSlider.value / 100;
        
        // Load metadata
        audio.addEventListener('loadedmetadata', function() {
            totalTimeEl.textContent = formatTime(audio.duration);
        });
        
        // Update progress
        audio.addEventListener('timeupdate', function() {
            if (audio.duration) {
                const progress = (audio.currentTime / audio.duration) * 100;
                progressBar.style.width = progress + '%';
                progressHandle.style.left = progress + '%';
                currentTimeEl.textContent = formatTime(audio.currentTime);
            }
        });
        
        // Play/Pause button
        playPauseBtn.addEventListener('click', function() {
            if (currentPlayer === player && !audio.paused) {
                pauseAll();
            } else {
                playAudio(player, index);
            }
        });
        
        // Previous button
        prevBtn.addEventListener('click', function() {
            playPrevious();
        });
        
        // Next button
        nextBtn.addEventListener('click', function() {
            playNext();
        });
        
        // Progress bar seeking
        progressContainer.addEventListener('click', function(e) {
            const rect = progressContainer.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const percentage = (clickX / rect.width) * 100;
            const seekTime = (percentage / 100) * audio.duration;
            audio.currentTime = seekTime;
        });
        
        // Volume control
        volumeSlider.addEventListener('input', function() {
            audio.volume = this.value / 100;
        });
        
        // Audio ended - play next
        audio.addEventListener('ended', function() {
            playNext();
        });
        
        // Update play/pause icon based on state
        audio.addEventListener('play', function() {
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'inline';
            waveformBars.forEach(bar => bar.classList.add('active'));
        });
        
        audio.addEventListener('pause', function() {
            playIcon.style.display = 'inline';
            pauseIcon.style.display = 'none';
            waveformBars.forEach(bar => bar.classList.remove('active'));
        });
    });
});

function playAudio(player, index) {
    // Pause all other players
    pauseAll();
    
    // Play this player
    const audio = player.querySelector('audio');
    audio.play();
    currentPlayer = player;
    currentIndex = index;
}

function pauseAll() {
    allPlayers.forEach(p => {
        const audio = p.querySelector('audio');
        if (!audio.paused) {
            audio.pause();
        }
    });
    currentPlayer = null;
}

function playNext() {
    if (currentIndex === -1) {
        // If nothing is playing, play first
        if (allPlayers.length > 0) {
            playAudio(allPlayers[0], 0);
        }
    } else {
        const nextIndex = (currentIndex + 1) % allPlayers.length;
        playAudio(allPlayers[nextIndex], nextIndex);
    }
}

function playPrevious() {
    if (currentIndex === -1) {
        // If nothing is playing, play last
        if (allPlayers.length > 0) {
            const lastIndex = allPlayers.length - 1;
            playAudio(allPlayers[lastIndex], lastIndex);
        }
    } else {
        const prevIndex = currentIndex === 0 ? allPlayers.length - 1 : currentIndex - 1;
        playAudio(allPlayers[prevIndex], prevIndex);
    }
}

function formatTime(seconds) {
    if (isNaN(seconds)) return '0:00';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return mins + ':' + (secs < 10 ? '0' : '') + secs;
}

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
                
                // Trigger change event for existing preview functionality
                if (fileInput.id === 'imageInput') {
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
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
    
    // Keep existing image preview functionality
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
});
</script>

</body>
</html>

