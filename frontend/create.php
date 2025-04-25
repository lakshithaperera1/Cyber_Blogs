```php
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Client-side validation (server-side for redundancy)
  $errors = [];
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');
  $author = trim($_POST['author'] ?? 'Anonymous');
  $category = trim($_POST['category'] ?? '');
  $tags = trim($_POST['tags'] ?? '');

  if (strlen($title) < 3 || strlen($title) > 100) {
    $errors[] = 'Title must be between 3 and 100 characters';
  }
  if (strlen($content) < 10) {
    $errors[] = 'Content must be at least 10 characters';
  }
  if (strlen($author) > 50) {
    $errors[] = 'Author name must be less than 50 characters';
  }
  if (!in_array($category, ['Security', 'Networking', 'Development', 'Cloud', 'AI'])) {
    $errors[] = 'Invalid category selected';
  }
  if ($tags) {
    $tagArray = array_map('trim', explode(',', $tags));
    if (array_filter($tagArray, fn($tag) => strlen($tag) > 20) || strlen($tags) > 255) {
      $errors[] = 'Each tag must be less than 20 characters and total length less than 255';
    }
  }

  if (empty($errors)) {
    $data = [
      'title' => $title,
      'content' => $content,
      'author' => $author,
      'category' => $category,
      'tags' => $tags, // Send as string
      'date' => date('Y-m-d\TH:i:s\Z')
    ];

    $options = [
      'http' => [
        'header' => "Content-type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data)
      ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents("http://localhost:5000/posts", false, $context);

    if ($result !== false) {
      header("Location: index.php");
      exit();
    } else {
      $error = 'Failed to create post. Please try again.';
    }
  }
}

// Start output buffering
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Post - CyberOpsNexus Blogs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
      overflow: hidden;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

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
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .content-wrapper.loaded {
      opacity: 1;
      overflow: auto;
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

    .banner {
      position: relative;
      overflow: hidden;
      height: 280px;
      border-radius: 24px;
      margin-bottom: 3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--cyber-darker) 0%, rgba(15, 23, 42, 0.9) 100%);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      border: 1px solid rgba(12, 239, 255, 0.2);
    }

    .particles-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0.7;
      z-index: 1;
    }

    .banner-content {
      position: relative;
      z-index: 2;
      text-align: center;
      padding: 0 20px;
    }

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

    .cyber-btn-secondary {
      background: linear-gradient(135deg, #ff3dac 0%, #ff7c44 100%);
      box-shadow: 0 5px 15px rgba(255, 61, 172, 0.3);
    }

    .cyber-btn-secondary:hover {
      box-shadow: 0 8px 25px rgba(255, 61, 172, 0.4);
      color: white;
    }

    .cyber-btn-secondary::before {
      background: linear-gradient(135deg, #ff7c44 0%, #ff3dac 100%);
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
    .reveal-delay-4 { transition-delay: 0.4s; }

    .markdown-toolbar {
      background-color: rgba(6, 9, 28, 0.7);
      border: 1px solid rgba(12, 239, 255, 0.2);
      border-bottom: none;
      border-radius: 12px 12px 0 0;
      padding: 0.75rem;
    }

    .markdown-toolbar button {
      background-color: transparent;
      border: none;
      color: var(--cyber-text);
      padding: 0.5rem 1rem;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .markdown-toolbar button:hover {
      background-color: rgba(12, 239, 255, 0.2);
      color: var(--cyber-primary);
    }

    .preview-panel {
      background-color: rgba(6, 9, 28, 0.7);
      border: 1px solid rgba(12, 239, 255, 0.2);
      border-radius: 12px;
      padding: 1.5rem;
      max-height: 500px;
      overflow-y: auto;
      color: var(--cyber-text);
      line-height: 1.8;
    }

    .preview-panel code {
      background-color: rgba(6, 9, 28, 0.7);
      color: var(--cyber-primary);
      padding: 0.2rem 0.4rem;
      border-radius: 4px;
      font-family: 'JetBrains Mono', monospace;
    }

    .preview-panel pre {
      background-color: rgba(6, 9, 28, 0.7);
      padding: 1.25rem;
      border-radius: 8px;
      border-left: 3px solid var(--cyber-primary);
      overflow-x: auto;
      margin: 1.5rem 0;
    }

    .preview-panel strong {
      color: var(--cyber-primary);
      font-weight: 700;
    }

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

    .modern-footer {
      background-color: rgba(6, 9, 28, 0.8);
      border-top: 1px solid rgba(12, 239, 255, 0.2);
      padding: 2rem 0;
      margin-top: auto;
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

    .form-check-label {
      color: var(--cyber-text);
    }

    .form-check-input {
      background-color: rgba(6, 9, 28, 0.5);
      border: 1px solid rgba(12, 239, 255, 0.3);
    }

    .form-check-input:checked {
      background-color: var(--cyber-primary);
      border-color: var(--cyber-primary);
    }

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

    h1, h2, h3, h4, h5, h6 {
      font-weight: 700;
    }

    .display-4 {
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .lead {
      font-weight: 400;
      color: #cbd5e1;
    }

    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { opacity: 0.7; }
      50% { opacity: 1; }
      100% { opacity: 0.7; }
    }
  </style>
</head>
<body>
  <div class="loader-wrapper" id="loaderWrapper">
    <div class="cyber-loader"></div>
    <div class="loader-text">Initializing CyberOpsNexus...</div>
  </div>

  <div class="content-wrapper" id="contentWrapper">
    <div class="bg-grid"></div>

    <nav class="navbar cyber-navbar fixed-top">
      <div class="container">
        <a class="navbar-brand cyber-title" href="index.php">
          <i class="fas fa-shield-alt"></i> CYBEROPSNEXUS BLOGS
        </a>
        <div class="d-flex">
          <a href="index.php" class="cyber-btn cyber-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Posts
          </a>
        </div>
      </div>
    </nav>

    <div class="container mt-5 pt-5">
      <div class="banner">
        <canvas id="particles-canvas" class="particles-bg"></canvas>
        <div class="banner-content">
          <h1 class="cyber-title display-4 animated-heading glitch-effect" data-text="Create New Post"><i class="fas fa-edit"></i> Create New Post</h1>
          <p class="lead reveal-fade-up">Share your cybersecurity insights</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8 offset-md-2">
          <div class="cyber-card">
            <div class="card-interactive-bg"></div>
            <div class="scan-lines"></div>
            <div class="neon-border"></div>
            <div class="cyber-card-body">
              <h2 class="cyber-title mb-4 glitch-effect" data-text="New Post"><i class="fas fa-edit"></i> New Post</h2>
              <?php if (isset($error)): ?>
                <div class="alert alert-danger mb-4" role="alert">
                  <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
              <?php endif; ?>
              <form method="POST" id="postForm">
                <div class="mb-4 reveal-fade-up reveal-delay-1">
                  <label for="title" class="form-label cyber-text-reveal"><span class="line"><i class="fas fa-heading"></i> Post Title</span></label>
                  <input type="text" name="title" id="title" class="form-control" required>
                  <div id="titleError" class="error-message d-none">Title must be between 3 and 100 characters</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-2">
                  <label for="author" class="form-label cyber-text-reveal"><span class="line"><i class="fas fa-user-secret"></i> Author Handle</span></label>
                  <input type="text" name="author" id="author" class="form-control" placeholder="Anonymous">
                  <div id="authorError" class="error-message d-none">Author name must be less than 50 characters</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-3">
                  <label for="category" class="form-label cyber-text-reveal"><span class="line"><i class="fas fa-folder"></i> Category</span></label>
                  <select name="category" id="category" class="form-select" required>
                    <option value="Security">Security</option>
                    <option value="Networking">Networking</option>
                    <option value="Development">Development</option>
                    <option value="Cloud">Cloud</option>
                    <option value="AI">AI</option>
                  </select>
                  <div id="categoryError" class="error-message d-none">Please select a valid category</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-4">
                  <label for="tags" class="form-label cyber-text-reveal"><span class="line"><i class="fas fa-tags"></i> Tags (comma-separated)</span></label>
                  <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g. cybersecurity, hacking, tech">
                  <div id="tagsError" class="error-message d-none">Each tag must be less than 20 characters and total length less than 255</div>
                </div>
                <div class="mb-4 reveal-fade-up reveal-delay-1">
                  <label for="content" class="form-label cyber-text-reveal"><span class="line"><i class="fas fa-code"></i> Content (Markdown supported)</span></label>
                  <div class="markdown-toolbar d-flex flex-wrap">
                    <button type="button" data-md="**" data-wrap="true"><i class="fas fa-bold"></i></button>
                    <button type="button" data-md="*" data-wrap="true"><i class="fas fa-italic"></i></button>
                    <button type="button" data-md="# " data-wrap="false"><i class="fas fa-heading"></i></button>
                    <button type="button" data-md="[Link text](url)" data-wrap="false"><i class="fas fa-link"></i></button>
                    <button type="button" data-md="![Alt text](image-url)" data-wrap="false"><i class="fas fa-image"></i></button>
                    <button type="button" data-md="`" data-wrap="true"><i class="fas fa-code"></i></button>
                    <button type="button" data-md="```\ncode block\n```" data-wrap="false"><i class="fas fa-file-code"></i></button>
                    <button type="button" data-md="- " data-wrap="false"><i class="fas fa-list-ul"></i></button>
                    <button type="button" data-md="1. " data-wrap="false"><i class="fas fa-list-ol"></i></button>
                    <button type="button" data-md="> " data-wrap="false"><i class="fas fa-quote-right"></i></button>
                    <button type="button" data-md="---\n" data-wrap="false"><i class="fas fa-minus"></i></button>
                  </div>
                  <textarea name="content" id="content" class="form-control" rows="12" required></textarea>
                  <div id="contentError" class="error-message d-none">Content must be at least 10 characters</div>
                </div>
                <div class="form-check mb-4 reveal-fade-up reveal-delay-2">
                  <input class="form-check-input" type="checkbox" id="togglePreview">
                  <label class="form-label cyber-text-reveal" for="togglePreview"><span class="line">Show preview</span></label>
                </div>
                <div id="previewContainer" class="mb-4 d-none reveal-fade-up reveal-delay-3">
                  <label class="form-label cyber-text-reveal"><span class="line"><i class="fas fa-eye"></i> Preview</span></label>
                  <div class="preview-panel" id="preview"></div>
                </div>
                <div class="d-flex justify-content-end gap-2 reveal-fade-up reveal-delay-4">
                  <button type="reset" class="cyber-btn cyber-btn-secondary"><i class="fas fa-eraser"></i> Clear</button>
                  <button type="submit" class="cyber-btn" id="submitButton"><i class="fas fa-paper-plane"></i> Publish Post</button>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.2/marked.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loaderWrapper = document.getElementById('loaderWrapper');
      const contentWrapper = document.getElementById('contentWrapper');
      const postForm = document.getElementById('postForm');
      const submitButton = document.getElementById('submitButton');
      const contentTextarea = document.getElementById('content');
      const previewPanel = document.getElementById('preview');
      const togglePreview = document.getElementById('togglePreview');
      const previewContainer = document.getElementById('previewContainer');
      const card = document.querySelector('.cyber-card');

      // Loader handling
      setTimeout(() => {
        loaderWrapper.classList.add('hidden');
        contentWrapper.classList.add('loaded');
        document.body.style.overflow = 'auto';
      }, 1500);

      // Trigger reveal animations
      const revealElements = document.querySelectorAll('.reveal-fade-up');
      revealElements.forEach((el, index) => {
        setTimeout(() => {
          el.classList.add('active');
        }, index * 100);
      });

      // Interactive background effect
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty('--x', `${x}%`);
        card.style.setProperty('--y', `${y}%`);
      });

      // Form validation
      function validateForm() {
        let isValid = true;

        const title = document.getElementById('title').value.trim();
        const titleError = document.getElementById('titleError');
        if (title.length < 3 || title.length > 100) {
          titleError.classList.remove('d-none');
          isValid = false;
        } else {
          titleError.classList.add('d-none');
        }

        const author = document.getElementById('author').value.trim() || 'Anonymous';
        const authorError = document.getElementById('authorError');
        if (author.length > 50) {
          authorError.classList.remove('d-none');
          isValid = false;
        } else {
          authorError.classList.add('d-none');
        }

        const content = document.getElementById('content').value.trim();
        const contentError = document.getElementById('contentError');
        if (content.length < 10) {
          contentError.classList.remove('d-none');
          isValid = false;
        } else {
          contentError.classList.add('d-none');
        }

        const category = document.getElementById('category').value;
        const categoryError = document.getElementById('categoryError');
        const validCategories = ['Security', 'Networking', 'Development', 'Cloud', 'AI'];
        if (!validCategories.includes(category)) {
          categoryError.classList.remove('d-none');
          isValid = false;
        } else {
          categoryError.classList.add('d-none');
        }

        const tags = document.getElementById('tags').value.trim();
        const tagsError = document.getElementById('tagsError');
        if (tags) {
          const tagArray = tags.split(',').map(tag => tag.trim());
          if (tagArray.some(tag => tag.length > 20) || tags.length > 255) {
            tagsError.classList.remove('d-none');
            isValid = false;
          } else {
            tagsError.classList.add('d-none');
          }
        } else {
          tagsError.classList.add('d-none');
        }

        return isValid;
      }

      // Form submission
      postForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        if (!validateForm()) {
          return;
        }

        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Publishing...';

        const formData = new FormData(postForm);
        const postData = {
          title: formData.get('title').trim(),
          author: formData.get('author').trim() || 'Anonymous',
          content: formData.get('content').trim(),
          category: formData.get('category'),
          tags: formData.get('tags').trim(),
          date: new Date().toISOString()
        };

        try {
          const response = await fetch('http://localhost:5000/posts', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(postData)
          });

          if (!response.ok) {
            const text = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${text.substring(0, 100)}...`);
          }

          const data = await response.json();
          await Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Post created successfully!',
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
          console.error('Error creating post:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to create post: ${error.message}`,
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
      });

      // Markdown toolbar functionality
      document.querySelectorAll('.markdown-toolbar button').forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const markdown = this.getAttribute('data-md');
          const isWrap = this.getAttribute('data-wrap') === 'true';
          const start = contentTextarea.selectionStart;
          const end = contentTextarea.selectionEnd;
          const selectedText = contentTextarea.value.substring(start, end);

          let newText = '';
          let newCursorPosStart = start;
          let newCursorPosEnd = end;

          if (isWrap) {
            if (selectedText) {
              newText = markdown + selectedText + markdown;
              newCursorPosStart = start + markdown.length;
              newCursorPosEnd = end + markdown.length;
            } else {
              newText = markdown + 'text' + markdown;
              newCursorPosStart = start + markdown.length;
              newCursorPosEnd = start + markdown.length + 4;
            }
          } else {
            if (markdown.includes('[') || markdown.includes('```')) {
              newText = markdown;
              if (markdown.includes('[Link text](url)')) {
                newCursorPosStart = start + 1;
                newCursorPosEnd = start + 10;
              } else if (markdown.includes('![Alt text](image-url)')) {
                newCursorPosStart = start + 2;
                newCursorPosEnd = start + 11;
              } else if (markdown.includes('```')) {
                newCursorPosStart = start + 4;
                newCursorPosEnd = start + 14;
              }
            } else {
              if (selectedText) {
                const lines = selectedText.split('\n');
                newText = lines.map(line => markdown + line).join('\n');
                newCursorPosStart = start;
                newCursorPosEnd = end + (markdown.length * lines.length);
              } else {
                newText = markdown;
                newCursorPosStart = start + markdown.length;
                newCursorPosEnd = newCursorPosStart;
              }
            }
          }

          contentTextarea.value = 
            contentTextarea.value.substring(0, start) + 
            newText + 
            contentTextarea.value.substring(end);

          contentTextarea.focus();
          contentTextarea.setSelectionRange(newCursorPosStart, newCursorPosEnd);

          if (togglePreview.checked) {
            updatePreview();
          }
        });
      });

      // Toggle preview panel
      togglePreview.addEventListener('change', function() {
        if (this.checked) {
          previewContainer.classList.remove('d-none');
          updatePreview();
        } else {
          previewContainer.classList.add('d-none');
        }
      });

      // Live preview update
      contentTextarea.addEventListener('input', function() {
        if (togglePreview.checked) {
          updatePreview();
        }
      });

      function updatePreview() {
        const markdown = contentTextarea.value;
        previewPanel.innerHTML = marked.parse(markdown || '*Preview will appear here*');
      }

      // Particles animation
      const canvas = document.getElementById('particles-canvas');
      const ctx = canvas.getContext('2d');

      canvas.width = canvas.parentElement.offsetWidth;
      canvas.height = canvas.parentElement.offsetHeight;

      const particlesArray = [];
      const numberOfParticles = 80;

      class Particle {
        constructor() {
          this.x = Math.random() * canvas.width;
          this.y = Math.random() * canvas.height;
          this.size = Math.random() * 3 + 1;
          this.speedX = Math.random() * 1 - 0.5;
          this.speedY = Math.random() * 1 - 0.5;
          const colors = [
            `rgba(12, 239, 255, ${Math.random() * 0.5 + 0.3})`,
            `rgba(143, 49, 254, ${Math.random() * 0.5 + 0.3})`,
            `rgba(255, 61, 172, ${Math.random() * 0.3 + 0.2})`
          ];
          this.color = colors[Math.floor(Math.random() * colors.length)];
        }

        update() {
          this.x += this.speedX;
          this.y += this.speedY;
          if (this.size > 0.2) this.size -= 0.01;
          if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
          if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
        }

        draw() {
          ctx.beginPath();
          ctx.fillStyle = this.color;
          ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
          ctx.fill();
        }
      }

      function init() {
        for (let i = 0; i < numberOfParticles; i++) {
          particlesArray.push(new Particle());
        }
      }

      function connectParticles() {
        for (let a = 0; a < particlesArray.length; a++) {
          for (let b = a; b < particlesArray.length; b++) {
            const dx = particlesArray[a].x - particlesArray[b].x;
            const dy = particlesArray[a].y - particlesArray[b].y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance < 100) {
              const opacity = 1 - (distance / 100);
              const gradient = ctx.createLinearGradient(
                particlesArray[a].x, 
                particlesArray[a].y, 
                particlesArray[b].x, 
                particlesArray[b].y
              );
              gradient.addColorStop(0, particlesArray[a].color.replace(/([\d.]+)\)/, `${opacity * 0.3})`));
              gradient.addColorStop(1, particlesArray[b].color.replace(/([\d.]+)\)/, `${opacity * 0.3})`));
              ctx.strokeStyle = gradient;
              ctx.lineWidth = 1;
              ctx.beginPath();
              ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
              ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
              ctx.stroke();
            }
          }
        }
      }

      function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < particlesArray.length; i++) {
          particlesArray[i].update();
          particlesArray[i].draw();
        }
        connectParticles();
        for (let i = 0; i < particlesArray.length; i++) {
          if (particlesArray[i].size <= 0.2) {
            particlesArray.splice(i, 1);
            particlesArray.push(new Particle());
          }
        }
        requestAnimationFrame(animate);
      }

      init();
      animate();

      window.addEventListener('resize', function() {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
        particlesArray.length = 0;
        init();
      });
    });
  </script>
</body>
</html>

<?php
ob_end_flush();
?>
```