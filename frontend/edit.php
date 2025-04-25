<?php
// Start output buffering to prevent premature output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Post - CyberOpsNexus Blogs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Add SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Add animate.css for smooth animations -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
    :root {
      --cyber-primary: #0cefff;
      --cyber-primary-dark: #0cbeff;
      --cyber-secondary: #8f31fe;
      --cyber-dark: #0f172a;
      --cyber-darker: #06091c;
      --cyber-text: #f1f5f9;
      --cyber-accent: #ff3dac;
      --cyber-gradient: linear-gradient(135deg, #0cefff 0%, #8f31fe 100%);
      --card-shadow: 0 12px 30px rgba(12, 239, 255, 0.15);
      --card-glow: 0 0 20px rgba(12, 239, 255, 0.25);
      --border-glow: 0 0 8px rgba(12, 239, 255, 0.3);
    }

    body {
      background-color: var(--cyber-dark);
      color: var(--cyber-text);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background-image: 
        radial-gradient(circle at 25% 10%, rgba(12, 239, 255, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(143, 49, 254, 0.05) 0%, transparent 50%);
      background-attachment: fixed;
      margin: 0;
      overflow: hidden; /* Prevent scrolling while loader is active */
    }

    /* Loader Styles */
    .loader-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--cyber-darker);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      opacity: 1;
      transition: opacity 0.5s ease-out;
    }

    .loader-wrapper.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .cyber-loader {
      width: 80px;
      height: 80px;
      border: 6px solid rgba(12, 239, 255, 0.2);
      border-top: 6px solid var(--cyber-primary);
      border-bottom: 6px solid var(--cyber-secondary);
      border-radius: 50%;
      animation: spin 1.2s linear infinite, glowLoader 2s ease-in-out infinite;
      position: relative;
    }

    .cyber-loader::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      background: var(--cyber-gradient);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      filter: blur(10px);
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @keyframes glowLoader {
      0%, 100% { box-shadow: 0 0 20px rgba(12, 239, 255, 0.3), 0 0 30px rgba(143, 49, 254, 0.3); }
      50% { box-shadow: 0 0 30px rgba(12, 239, 255, 0.5), 0 0 40px rgba(143, 49, 254, 0.5); }
    }

    .loader-text {
      position: absolute;
      bottom: 20%;
      color: var(--cyber-text);
      font-size: 1.2rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 2px;
      animation: pulseText 2s infinite;
    }

    @keyframes pulseText {
      0%, 100% { opacity: 0.7; text-shadow: 0 0 5px var(--cyber-primary); }
      50% { opacity: 1; text-shadow: 0 0 10px var(--cyber-primary); }
    }

    .content-wrapper {
      opacity: 0;
      transition: opacity 0.5s ease-in;
    }

    .content-wrapper.loaded {
      opacity: 1;
      overflow: auto; /* Restore scrolling after loading */
    }

    .cyber-navbar {
      background-color: rgba(6, 9, 28, 0.8);
      border-bottom: 1px solid rgba(12, 239, 255, 0.3);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      padding: 1rem 0;
    }

    .cyber-title {
      color: var(--cyber-text);
      font-weight: 700;
      background: var(--cyber-gradient);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: 1px;
      position: relative;
      animation: glowPulse 2s infinite ease-in-out;
    }

    @keyframes glowPulse {
      0%, 100% { text-shadow: 0 0 5px rgba(12, 239, 255, 0.3), 0 0 10px rgba(143, 49, 254, 0.3); }
      50% { text-shadow: 0 0 10px rgba(12, 239, 255, 0.5), 0 0 20px rgba(143, 49, 254, 0.5); }
    }

    /* Enhanced Cyber Card Styles */
    .cyber-card {
      background-color: rgba(15, 23, 42, 0.85);
      border: 1px solid rgba(12, 239, 255, 0.2);
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      margin-bottom: 2.5rem;
      overflow: hidden;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      position: relative;
      transform-style: preserve-3d;
      perspective: 1000px;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .cyber-card:hover {
      transform: translateY(-10px) rotateX(5deg);
      box-shadow: var(--card-glow), var(--card-shadow);
      border-color: var(--cyber-primary);
    }

    .cyber-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--cyber-gradient);
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: -1;
    }

    .cyber-card:hover::before {
      opacity: 0.08;
    }

    .cyber-card::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        to bottom right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0) 40%,
        rgba(255, 255, 255, 0.1) 50%,
        rgba(255, 255, 255, 0) 60%,
        rgba(255, 255, 255, 0) 100%
      );
      transform: rotate(45deg);
      transition: all 0.8s;
      z-index: 1;
      opacity: 0;
    }

    .cyber-card:hover::after {
      animation: shine 1.5s ease-in-out;
    }

    @keyframes shine {
      0% { left: -150%; opacity: 0.1; }
      100% { left: 100%; opacity: 0; }
    }

    .cyber-card-body {
      padding: 2rem;
      position: relative;
      z-index: 2;
    }

    /* Interactive Background */
    .card-interactive-bg {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), 
                rgba(12, 239, 255, 0.15) 0%, 
                rgba(12, 239, 255, 0) 50%);
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
      z-index: 0;
    }

    .cyber-card:hover .card-interactive-bg {
      opacity: 1;
    }

    /* Scan Lines */
    .scan-lines {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        to bottom,
        transparent 50%,
        rgba(12, 239, 255, 0.03) 51%,
        transparent 52%
      );
      background-size: 100% 4px;
      pointer-events: none;
      opacity: 0;
      z-index: 3;
    }

    .cyber-card:hover .scan-lines {
      opacity: 1;
      animation: scanAnimation 8s linear infinite;
    }

    @keyframes scanAnimation {
      0% { background-position: 0 0; }
      100% { background-position: 0 100%; }
    }

    /* Neon Border */
    .neon-border {
      position: absolute;
      inset: 0;
      border: 1px solid transparent;
      border-radius: 16px;
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }

    .neon-border::before {
      content: '';
      position: absolute;
      inset: -2px;
      border-radius: 18px;
      background: var(--cyber-gradient);
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .cyber-card:hover .neon-border::before {
      opacity: 1;
      animation: borderPulse 2s infinite;
    }

    @keyframes borderPulse {
      0%, 100% { opacity: 0.3; filter: blur(2px); }
      50% { opacity: 0.6; filter: blur(4px); }
    }

    /* Glitch Effect for Titles */
    .glitch-effect {
      position: relative;
      display: inline-block;
    }

    .glitch-effect::before,
    .glitch-effect::after {
      content: attr(data-text);
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
    }

    .cyber-card:hover .glitch-effect::before,
    .glitch-effect:hover::before {
      left: 2px;
      text-shadow: -1px 0 var(--cyber-accent);
      animation: glitch-1 0.8s ease-in-out;
      clip-path: polygon(0 0, 100% 0, 100% 45%, 0 45%);
    }

    .cyber-card:hover .glitch-effect::after,
    .glitch-effect:hover::after {
      left: -2px;
      text-shadow: 1px 0 var(--cyber-primary);
      animation: glitch-2 0.8s ease-in-out;
      clip-path: polygon(0 55%, 100% 55%, 100% 100%, 0 100%);
    }

    @keyframes glitch-1 {
      0%, 100% { opacity: 0; }
      10%, 30%, 50%, 70%, 90% { opacity: 0.1; }
      20%, 40%, 60%, 80% { opacity: 0; }
    }

    @keyframes glitch-2 {
      0%, 100% { opacity: 0; }
      10%, 30%, 50%, 70%, 90% { opacity: 0; }
      20%, 40%, 60%, 80% { opacity: 0.1; }
    }

    /* Animated Heading */
    .animated-heading {
      position: relative;
      display: inline-block;
      overflow: hidden;
    }

    .animated-heading::before {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 3px;
      background: var(--cyber-gradient);
      transform: translateX(-100%);
      animation: lineAnimation 3s infinite ease-in-out;
    }

    @keyframes lineAnimation {
      0%, 100% { transform: translateX(-100%); }
      50% { transform: translateX(0); }
      100% { transform: translateX(100%); }
    }

    /* Button Styles */
    .cyber-btn {
      background: var(--cyber-gradient);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 5px 15px rgba(12, 239, 255, 0.3);
      position: relative;
      overflow: hidden;
      z-index: 1;
      animation: buttonGlow 3s infinite ease-in-out;
    }

    @keyframes buttonGlow {
      0%, 100% { box-shadow: 0 5px 15px rgba(12, 239, 255, 0.3); }
      50% { box-shadow: 0 5px 25px rgba(12, 239, 255, 0.5); }
    }

    .cyber-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #8f31fe 0%, #0cefff 100%);
      z-index: -1;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .cyber-btn:hover {
      box-shadow: 0 8px 25px rgba(12, 239, 255, 0.4);
      color: white;
      transform: translateY(-2px);
    }

    .cyber-btn:hover::before {
      opacity: 1;
    }

    .cyber-btn-accent {
      background: linear-gradient(135deg, #ff3dac 0%, #ff7c44 100%);
      box-shadow: 0 5px 15px rgba(255, 61, 172, 0.3);
    }

    .cyber-btn-accent:hover {
      box-shadow: 0 8px 25px rgba(255, 61, 172, 0.4);
      color: white;
    }

    .cyber-btn-accent::before {
      background: linear-gradient(135deg, #ff7c44 0%, #ff3dac 100%);
    }

    /* Form Styles */
    .form-control, .form-select {
      background-color: rgba(6, 9, 28, 0.5);
      border: 1px solid rgba(12, 239, 255, 0.3);
      border-radius: 12px;
      color: var(--cyber-text);
      padding: 1rem;
      transition: all 0.3s ease;
      font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--cyber-primary);
      box-shadow: 0 0 0 3px rgba(12, 239, 255, 0.25);
      background-color: rgba(6, 9, 28, 0.7);
    }

    .form-label {
      color: var(--cyber-primary);
      font-weight: 600;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      display: inline-block;
      transition: color 0.3s ease;
    }

    .form-label:hover {
      color: var(--cyber-accent);
      text-shadow: 0 0 10px var(--cyber-accent);
    }

    .form-label::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 100%;
      height: 2px;
      background: var(--cyber-gradient);
      transform: scaleX(0);
      transform-origin: right;
      transition: transform 0.3s ease;
    }

    .form-label:hover::after {
      transform: scaleX(1);
      transform-origin: left;
    }

    /* Text Reveal Animation for Form Labels */
    .cyber-text-reveal {
      display: inline-block;
      overflow: hidden;
      position: relative;
    }

    .cyber-text-reveal .line {
      display: block;
      transform: translateY(100%);
      opacity: 0;
      transition: all 0.5s ease;
    }

    .cyber-card:hover .cyber-text-reveal .line {
      transform: translateY(0);
      opacity: 1;
    }

    /* Reveal Fade Up Animation */
    .reveal-fade-up {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s ease;
    }

    .reveal-fade-up.active {
      opacity: 1;
      transform: translateY(0);
    }

    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }

    /* Typing Effect */
    .typing-effect {
      overflow: hidden;
      white-space: nowrap;
      animation: typing 3s steps(40, end) forwards, blink-caret 0.75s step-end infinite;
      border-right: 2px solid var(--cyber-primary);
    }

    @keyframes typing {
      from { width: 0; }
      to { width: 100%; }
    }

    @keyframes blink-caret {
      from, to { border-color: transparent; }
      50% { border-color: var(--cyber-primary); }
    }

    /* Footer Styles */
    .modern-footer {
      background-color: rgba(6, 9, 28, 0.8);
      border-top: 1px solid rgba(12, 239, 255, 0.2);
      padding: 2rem 0;
      margin-top: 5rem;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: var(--cyber-text);
      margin-left: 2rem;
      text-decoration: none;
      transition: color 0.2s ease;
      position: relative;
    }

    .footer-links a::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--cyber-gradient);
      transition: width 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--cyber-primary);
      text-shadow: 0 0 5px var(--cyber-primary);
    }

    .footer-links a:hover::after {
      width: 100%;
    }

    /* Loading Spinner */
    .loading-spinner {
      display: inline-block;
      width: 60px;
      height: 60px;
      border: 4px solid rgba(12, 239, 255, 0.2);
      border-radius: 50%;
      border-top-color: var(--cyber-primary);
      animation: spin 1s ease-in-out infinite;
    }

    /* Pulse Animation */
    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { opacity: 0.7; }
      50% { opacity: 1; }
      100% { opacity: 0.7; }
    }

    /* Background Grid */
    .bg-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-image: 
        linear-gradient(rgba(12, 239, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(12, 239, 255, 0.03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
    }

    /* Error Message */
    .error-message {
      color: var(--cyber-accent);
      font-size: 0.9rem;
      margin-top: 0.25rem;
      animation: fadeInError 0.5s ease-out;
    }

    @keyframes fadeInError {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Custom SweetAlert2 Styles */
    .swal2-popup {
      background: rgba(15, 23, 42, 0.95) !important;
      border: 1px solid var(--cyber-primary) !important;
      border-radius: 16px !important;
      box-shadow: var(--card-glow), var(--card-shadow) !important;
      backdrop-filter: blur(12px) !important;
      -webkit-backdrop-filter: blur(12px) !important;
      padding: 1.5rem !important;
    }

    .swal2-title {
      color: var(--cyber-text) !important;
      font-weight: 700 !important;
      background: var(--cyber-gradient) !important;
      background-clip: text !important;
      -webkit-background-clip: text !important;
      -webkit-text-fill-color: transparent !important;
      letter-spacing: 1px !important;
      text-transform: uppercase !important;
      animation: glowPulse 2s infinite ease-in-out;
    }

    .swal2-content {
      color: var(--cyber-text) !important;
      font-size: 1rem !important;
    }

    .swal2-confirm {
      background: var(--cyber-gradient) !important;
      color: white !important;
      border: none !important;
      border-radius: 12px !important;
      padding: 0.75rem 1.5rem !important;
      font-weight: 600 !important;
      transition: all 0.3s ease !important;
      text-transform: uppercase !important;
      letter-spacing: 1px !important;
      box-shadow: 0 5px 15px rgba(12, 239, 255, 0.3) !important;
    }

    .swal2-confirm:hover {
      box-shadow: 0 8px 25px rgba(12, 239, 255, 0.4) !important;
      transform: translateY(-2px) !important;
    }

    .swal2-icon.swal2-success {
      border-color: var(--cyber-primary) !important;
      color: var(--cyber-primary) !important;
    }

    .swal2-icon.swal2-error {
      border-color: var(--cyber-accent) !important;
      color: var(--cyber-accent) !important;
    }

    .swal2-timer-progress-bar {
      background: var(--cyber-primary) !important;
    }

    .swal2-popup::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--cyber-gradient);
      opacity: 0.05;
      z-index: -1;
      border-radius: 16px;
    }
  </style>
</head>
<body>
  <!-- Loader -->
  <div class="loader-wrapper" id="loaderWrapper">
    <div class="cyber-loader"></div>
    <div class="loader-text">Initializing CyberOpsNexus...</div>
  </div>

  <!-- Main Content Wrapper -->
  <div class="content-wrapper" id="contentWrapper">
    <div class="bg-grid"></div>

    <nav class="navbar cyber-navbar fixed-top">
      <div class="container">
        <a class="navbar-brand cyber-title" href="index.php">
          <i class="fas fa-shield-alt"></i> CYBEROPSNEXUS BLOGS
        </a>
        <div class="d-flex">
          <a href="index.php" class="cyber-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
          </a>
        </div>
      </div>
    </nav>

    <div class="container mt-5 pt-5">
      <div class="row">
        <div class="col-12">
          <h1 class="cyber-title display-4 animated-heading glitch-effect" data-text="Edit Post"><i class="fas fa-edit"></i> Edit Post</h1>
          <p class="lead reveal-fade-up">Update your security insights and technical content</p>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
          <div class="cyber-card">
            <div class="card-interactive-bg"></div>
            <div class="scan-lines"></div>
            <div class="neon-border"></div>
            <div class="cyber-card-body">
              <div id="loadingMessage" class="text-center py-5">
                <div class="loading-spinner mb-3"></div>
                <h3 class="cyber-title">Loading post...</h3>
              </div>
              <div id="errorMessage" class="text-center py-5 d-none">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 pulse" style="color: var(--cyber-accent);"></i>
                <h3 class="cyber-title">Failed to load post</h3>
                <p id="errorDetails">Please try again later.</p>
              </div>
              <form id="editPostForm" class="d-none">
                <div class="mb-4 reveal-fade-up reveal-delay-1">
                  <label for="postTitle" class="form-label cyber-text-reveal"><span class="line">Post Title</span></label>
                  <input type="text" class="form-control" id="postTitle" required>
                  <div id="titleError" class="error-message d-none">Title must be between 3 and 100 characters</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-2">
                  <label for="postAuthor" class="form-label cyber-text-reveal"><span class="line">Author</span></label>
                  <input type="text" class="form-control" id="postAuthor" required>
                  <div id="authorError" class="error-message d-none">Author name must be between 2 and 50 characters</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-3">
                  <label for="postContent" class="form-label cyber-text-reveal"><span class="line">Content (Markdown)</span></label>
                  <textarea class="form-control" id="postContent" rows="10" required></textarea>
                  <div id="contentError" class="error-message d-none">Content must be at least 10 characters</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-1">
                  <label for="postCategory" class="form-label cyber-text-reveal"><span class="line">Category</span></label>
                  <select class="form-select" id="postCategory" required>
                    <option value="Security">Security</option>
                    <option value="Networking">Networking</option>
                    <option value="Development">Development</option>
                    <option value="Cloud">Cloud</option>
                    <option value="AI">AI</option>
                  </select>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-2">
                  <label for="postTags" class="form-label cyber-text-reveal"><span class="line">Tags (comma-separated)</span></label>
                  <input type="text" class="form-control" id="postTags" placeholder="e.g. cybersecurity, hacking, tech">
                  <div id="tagsError" class="error-message d-none">Each tag must be less than 20 characters or total length must be less than 255 characters</div>
                </div>
                <div class="d-flex justify-content-end gap-3 reveal-fade-up reveal-delay-3">
                  <button type="button" class="cyber-btn cyber-btn-accent" onclick="window.location.href='index.php'">Cancel</button>
                  <button type="submit" class="cyber-btn" id="submitButton">Update Post</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="modern-footer">
      <div class="container">
        <div class="footer-content">
          <div>
            <p class="mb-0">© 2025 CyberTech Blogs. All security insights reserved.</p>
          </div>
          <div class="footer-links d-none d-md-block">
            <a href="#" class="cyber-title"><i class="fas fa-shield-alt"></i> Security</a>
            <a href="#" class="cyber-title"><i class="fas fa-users"></i> Team</a>
            <a href="#" class="cyber-title"><i class="fas fa-envelope"></i> Contact</a>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Add SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Loader Handling
    document.addEventListener('DOMContentLoaded', function() {
      const loaderWrapper = document.getElementById('loaderWrapper');
      const contentWrapper = document.getElementById('contentWrapper');

      // Simulate loading delay (adjust as needed)
      setTimeout(() => {
        loaderWrapper.classList.add('hidden');
        contentWrapper.classList.add('loaded');
        document.body.style.overflow = 'auto'; // Restore scrolling
      }, 1500); // 1.5 seconds delay for demo; adjust based on actual content load

      // Trigger reveal animations
      const revealElements = document.querySelectorAll('.reveal-fade-up');
      revealElements.forEach((el, index) => {
        setTimeout(() => {
          el.classList.add('active');
        }, index * 100);
      });
    });

    // Get post ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    console.log('Post ID from URL:', postId);

    // Elements
    const loadingMessage = document.getElementById('loadingMessage');
    const errorMessage = document.getElementById('errorMessage');
    const errorDetails = document.getElementById('errorDetails');
    const editPostForm = document.getElementById('editPostForm');
    const submitButton = document.getElementById('submitButton');

    // Validation functions
    function validateForm() {
      let isValid = true;
      
      // Title validation
      const title = document.getElementById('postTitle').value.trim();
      const titleError = document.getElementById('titleError');
      if (title.length < 3 || title.length > 100) {
        titleError.classList.remove('d-none');
        isValid = false;
      } else {
        titleError.classList.add('d-none');
      }

      // Author validation
      const author = document.getElementById('postAuthor').value.trim();
      const authorError = document.getElementById('authorError');
      if (author.length < 2 || author.length > 50) {
        authorError.classList.remove('d-none');
        isValid = false;
      } else {
        authorError.classList.add('d-none');
      }

      // Content validation
      const content = document.getElementById('postContent').value.trim();
      const contentError = document.getElementById('contentError');
      if (content.length < 10) {
        contentError.classList.remove('d-none');
        isValid = false;
      } else {
        contentError.classList.add('d-none');
      }

      // Tags validation
      const tags = document.getElementById('postTags').value.trim();
      const tagsError = document.getElementById('tagsError');
      if (tags) {
        const tagArray = tags.split(',').map(tag => tag.trim());
        if (tagArray.some(tag => tag.length > 20) || tags.length > 255) {
          tagsError.classList.remove('d-none');
          isValid = false;
        } else {
          tagsError.classList.add('d-none');
        }
      }

      return isValid;
    }

    // Fetch post data
    async function fetchPost() {
      if (!postId) {
        console.error('No post ID provided');
        loadingMessage.classList.add('d-none');
        errorMessage.classList.remove('d-none');
        errorDetails.textContent = 'No post ID provided in URL';
        return;
      }

      try {
        console.log('Fetching post from:', `http://localhost:5000/posts/${postId}`);
        const response = await fetch(`http://localhost:5000/posts/${postId}`);
        if (!response.ok) {
          const text = await response.text();
          console.log('Fetch response text:', text);
          throw new Error(`HTTP error! Status: ${response.status}, Response: ${text.substring(0, 100)}...`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await response.text();
          console.log('Non-JSON response:', text);
          throw new Error('Received non-JSON response from server');
        }

        const post = await response.json();
        console.log('Fetched post data:', post);

        // Populate form
        document.getElementById('postTitle').value = post.title || '';
        document.getElementById('postAuthor').value = post.author || '';
        document.getElementById('postContent').value = post.content || '';
        document.getElementById('postCategory').value = post.category || 'Security';
        document.getElementById('postTags').value = post.tags || ''; // Tags is a string

        loadingMessage.classList.add('d-none');
        editPostForm.classList.remove('d-none');
      } catch (error) {
        console.error('Error fetching post:', error);
        loadingMessage.classList.add('d-none');
        errorMessage.classList.remove('d-none');
        errorDetails.textContent = `Failed to load post: ${error.message}`;
      }
    }

    // Update post
    async function updatePost(event) {
      event.preventDefault();

      if (!validateForm()) {
        console.log('Form validation failed');
        return;
      }

      if (!postId) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No post ID provided. Cannot update post.',
          background: 'rgba(15, 23, 42, 0.95)',
          color: '#f1f5f9',
          confirmButtonColor: '#0cefff',
          confirmButtonText: 'OK',
          showClass: { popup: 'animate__animated animate__zoomIn' },
          hideClass: { popup: 'animate__animated animate__zoomOut' }
        });
        return;
      }

      const originalButtonText = submitButton.textContent;
      submitButton.disabled = true;
      submitButton.textContent = 'Updating...';

      const tagsInput = document.getElementById('postTags').value.trim();

      const postData = {
        title: document.getElementById('postTitle').value.trim(),
        author: document.getElementById('postAuthor').value.trim(),
        content: document.getElementById('postContent').value.trim(),
        category: document.getElementById('postCategory').value,
        tags: tagsInput, // Send tags as a string
        date: new Date().toISOString()
      };

      console.log('Sending update request to:', `http://localhost:5000/posts/${postId}`);
      console.log('Request body:', JSON.stringify(postData, null, 2));

      try {
        const response = await fetch(`http://localhost:5000/posts/${postId}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(postData)
        });

        const contentType = response.headers.get('content-type');
        if (!response.ok) {
          const text = await response.text();
          console.log('Update response text:', text);
          throw new Error(`HTTP error! Status: ${response.status}, Response: ${text.substring(0, 100)}...`);
        }

        if (!contentType || !contentType.includes('application/json')) {
          const text = await response.text();
          console.log('Non-JSON response:', text);
          throw new Error('Received non-JSON response from server');
        }

        const data = await response.json();
        console.log('Update response:', data);
        
        // Show SweetAlert2 success message
        await Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Post updated successfully!',
          background: 'rgba(15, 23, 42, 0.95)',
          color: '#f1f5f9',
          confirmButtonColor: '#0cefff',
          confirmButtonText: 'Back to Home',
          timer: 3000,
          timerProgressBar: true,
          showClass: { popup: 'animate__animated animate__zoomIn' },
          hideClass: { popup: 'animate__animated animate__zoomOut' }
        });
        
        window.location.href = 'index.php';
      } catch (error) {
        console.error('Error updating post:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: `Failed to update post: ${error.message}`,
          background: 'rgba(15, 23, 42, 0.95)',
          color: '#f1f5f9',
          confirmButtonColor: '#0cefff',
          confirmButtonText: 'OK',
          showClass: { popup: 'animate__animated animate__zoomIn' },
          hideClass: { popup: 'animate__animated animate__zoomOut' }
        });
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      fetchPost();
      editPostForm.addEventListener('submit', updatePost);

      // Interactive background effect
      const card = document.querySelector('.cyber-card');
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty('--x', `${x}%`);
        card.style.setProperty('--y', `${y}%`);
      });
    });
  </script>
</body>
</html>

<?php
// End output buffering
ob_end_flush();
?>