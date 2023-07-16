<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  // Redirect the user to the login page or show an error message
  header("Location: index.php"); // Replace "login.php" with the actual login page URL
  exit(); // Stop further execution of the code
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Beatbox recorder</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="icon" href="../f.png" type="image/x-icon">
  </head>
  <body>
  <header>
      <h2>BeatBox NP</h2>
      <nav>
        <a href="#">HOME</a>
        <a href="#">BLOG</a>
        <a href="#">CONTACT</a>
        <a href="#">ABOUT</a>
      </nav>
      <?php 
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true) {
        echo "
        <div class='user'>
        $_SESSION[username]- <a href='../logout.php'>LOGOUT</a>
        </div>";
      } else {
        echo "
        <div class='sign-in-up'>
          <button type='button' onclick=\"popup('login-popup')\">LOGIN</button>
          <button type='button' onclick=\"popup('register-popup')\">REGISTER</button>
        </div>
        ";
      }
      ?>
    </header>
    <div class="background"></div>
    <h1>Beatbox Mixer[2023]</h1>
    <div class="file-input-row">
      <div class="file-input-container">
        <label for="file1" class="file-label">
          <span class="file-name">Choose File 1</span>
          <input
            type="file"
            accept="audio/*"
            multiple
            id="file1"
            class="file-input"
          />
        </label>
        <span id="custom-text1">No file chosen, yet</span>
      </div>
      <div class="file-input-container">
        <label for="file2" class="file-label">
          <span class="file-name">Choose File 2</span>
          <input
            type="file"
            accept="audio/*"
            multiple
            id="file2"
            class="file-input"
          />
        </label>
        <span id="custom-text2">No file chosen, yet</span>
      </div>
      <div class="file-input-container">
        <label for="file3" class="file-label">
          <span class="file-name">Choose File 3</span>
          <input
            type="file"
            accept="audio/*"
            multiple
            id="file3"
            class="file-input"
          />
        </label>
        <span id="custom-text3">No file chosen, yet</span>
      </div>
    </div>
    <button onclick="mergeAndControlAudio()">Merge and Control</button>
    <canvas id="visualizer"></canvas>
    <div class="grid">
      <div class="row">
        <div class="slider-container">
          <label for="volume">Volume</label>
          <div class="slider-wrapper">
            <input
              type="range"
              min="0"
              max="1"
              value="0.5"
              step="0.01"
              id="volume"
              class="slider"
            />
          </div>
        </div>
        <div class="slider-container">
          <label for="bass">Bass</label>
          <div class="slider-wrapper">
            <input
              type="range"
              min="-10"
              max="10"
              value="0"
              id="bass"
              class="slider"
            />
          </div>
        </div>
        <div class="slider-container">
          <label for="mid">Mid</label>
          <div class="slider-wrapper">
            <input
              type="range"
              min="-10"
              max="10"
              value="0"
              id="mid"
              class="slider"
            />
          </div>
        </div>
        <div class="slider-container">
          <label for="treble">Treble</label>
          <div class="slider-wrapper">
            <input
              type="range"
              min="-10"
              max="10"
              value="0"
              id="treble"
              class="slider"
            />
          </div>
        </div>
      </div>
    </div>
    <div id="header">
      <div class="live-indicator">
        <div class="dot"></div>
        <span>99 Raser's Live</span>
      </div>
    </div>
    <div id="recordings">
      <div class="recording">
        <button class="record-button button">Record 1</button>
        <button class="stop-button button">Stop</button>
        <button class="play-button button">Play</button>
        <button class="pause-button button">Pause</button>
      </div>
      <div class="recording">
        <button class="record-button button">Record 2</button>
        <button class="stop-button button">Stop</button>
        <button class="play-button button">Play</button>
        <button class="pause-button button">Pause</button>
      </div>
      <div class="recording">
        <button class="record-button button">Record 3</button>
        <button class="stop-button button">Stop</button>
        <button class="play-button button">Play</button>
        <button class="pause-button button">Pause</button>
      </div>
      <div class="recording">
        <button class="record-button button">Record 4</button>
        <button class="stop-button button">Stop</button>
        <button class="play-button button">Play</button>
        <button class="pause-button button">Pause</button>
      </div>
    </div>
    <div id="top-row">
      <button id="play-all" class="button">Play All</button>
      <button id="clear-all" class="button">Clear All</button>
    </div>
    <button id="download" class="button">Download</button>
    <script src="script.js"></script>
    <script src="script.js" defer></script>
  </body>
</html>
