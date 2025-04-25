<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CyberOpsNexus Blogs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    }
    
    .cyber-card {
      background-color: rgba(15, 23, 42, 0.85);
      border: 1px solid rgba(12, 239, 255, 0.2);
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      margin-bottom: 2.5rem;
      overflow: hidden;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      position: relative;
      animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .cyber-card:hover {
      transform: translateY(-10px);
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
    
    .cyber-card-header {
      background: linear-gradient(135deg, rgba(6, 9, 28, 0.9), rgba(12, 239, 255, 0.1));
      border-bottom: 1px solid rgba(12, 239, 255, 0.3);
      padding: 1.75rem;
      position: relative;
      overflow: hidden;
    }
    
    .cyber-card-header::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(12, 239, 255, 0.2) 0%, transparent 70%);
      opacity: 0.3;
      transform: rotate(45deg);
      transition: transform 0.5s ease;
    }
    
    .cyber-card:hover .cyber-card-header::after {
      transform: rotate(45deg) translate(20%, 20%);
    }
    
    .cyber-card-body {
      padding: 2rem;
      display: flex;
      flex-direction: column;
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
    
    .cyber-btn-danger {
      background: linear-gradient(135deg, #ff3d3d 0%, #ff7c44 100%);
      box-shadow: 0 5px 15px rgba(255, 61, 61, 0.3);
    }
    
    .cyber-btn-danger:hover {
      box-shadow: 0 8px 25px rgba(255, 61, 61, 0.4);
      color: white;
    }
    
    .cyber-btn-danger::before {
      background: linear-gradient(135deg, #ff7c44 0%, #ff3d3d 100%);
    }
    
    .post-title {
      color: var(--cyber-primary);
      font-weight: 800;
      font-size: 1.6rem;
      margin-bottom: 0.75rem;
      transition: color 0.3s ease;
      text-shadow: 0 0 5px rgba(12, 239, 255, 0.3);
    }
    
    .cyber-card:hover .post-title {
      color: var(--cyber-text);
    }
    
    .post-meta {
      color: #a0aec0;
      font-size: 0.85rem;
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .post-meta span {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .markdown-content {
      color: var(--cyber-text);
      line-height: 1.9;
      font-size: 0.95rem;
      position: relative;
      flex-grow: 1;
    }
    
    .markdown-content code {
      background-color: rgba(6, 9, 28, 0.7);
      color: var(--cyber-primary);
      padding: 0.2rem 0.4rem;
      border-radius: 4px;
      font-family: 'JetBrains Mono', monospace;
    }
    
    .markdown-content pre {
      background-color: rgba(6, 9, 28, 0.7);
      padding: 1.25rem;
      border-radius: 8px;
      border-left: 4px solid var(--cyber-primary);
      overflow-x: auto;
      margin: 1.5rem 0;
      box-shadow: var(--border-glow);
    }
    
    .markdown-content strong {
      color: var(--cyber-primary);
      font-weight: 700;
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
    
    .search-wrapper {
      position: relative;
    }
    
    .search-input {
      background-color: rgba(6, 9, 28, 0.5);
      border: 1px solid rgba(12, 239, 255, 0.3);
      border-radius: 12px;
      color: var(--cyber-text);
      padding: 1rem 1.25rem 1rem 3rem;
      transition: all 0.3s ease;
      font-size: 1rem;
    }
    
    .search-input:focus {
      border-color: var(--cyber-primary);
      box-shadow: 0 0 0 3px rgba(12, 239, 255, 0.25);
      background-color: rgba(6, 9, 28, 0.7);
    }
    
    .search-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--cyber-primary);
    }
    
    .glow-effect {
      display: inline-block;
      position: relative;
      margin-bottom: 0.5rem;
    }
    
    .glow-effect::after {
      content: "";
      position: absolute;
      top: 100%;
      width: 100%;
      left: 0;
      height: 4px;
      background: var(--cyber-gradient);
      border-radius: 4px;
      opacity: 0.7;
      filter: blur(4px);
    }
    
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
    }
    
    .footer-links a:hover::after {
      width: 100%;
    }
    
    .btn-sm {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }
    
    .category-chip {
      display: inline-block;
      background: linear-gradient(135deg, #ff3dac 0%, #ff7c44 100%);
      color: white;
      padding: 0.4rem 0.9rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      margin: 0.3rem 0.5rem 0.3rem 0;
      transition: all 0.3s ease;
      box-shadow: 0 3px 10px rgba(255, 61, 172, 0.3);
    }
    
    .category-chip:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 61, 172, 0.4);
    }
    
    .card-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 1rem;
    }
    
    .loading-spinner {
      display: inline-block;
      width: 60px;
      height: 60px;
      border: 4px solid rgba(12, 239, 255, 0.2);
      border-radius: 50%;
      border-top-color: var(--cyber-primary);
      animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    .pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { opacity: 0.7; }
      50% { opacity: 1; }
      100% { opacity: 0.7; }
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
      text-transform: uppercase !important;
      letter-spacing: 1px !important;
      box-shadow: 0 5px 15px rgba(12, 239, 255, 0.3) !important;
    }

    .swal2-confirm:hover {
      box-shadow: 0 8px 25px rgba(12, 239, 255, 0.4) !important;
      transform: translateY(-2px) !important;
    }

    .swal2-cancel {
      background: linear-gradient(135deg, #ff3d3d 0%, #ff7c44 100%) !important;
      color: white !important;
      border: none !important;
      border-radius: 12px !important;
      padding: 0.75rem 1.5rem !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 1px !important;
      box-shadow: 0 5px 15px rgba(255, 61, 61, 0.3) !important;
    }

    .swal2-cancel:hover {
      box-shadow: 0 8px 25px rgba(255, 61, 61, 0.4) !important;
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

    .swal2-icon.swal2-warning {
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
  <div class="bg-grid"></div>
  
  <nav class="navbar cyber-navbar fixed-top">
    <div class="container">
      <a class="navbar-brand cyber-title" href="index.php">
        <i class="fas fa-shield-alt"></i> CYBEROPSNEXUS BLOGS
      </a>
      <div class="d-flex">
        <a href="create.php" class="cyber-btn d-none d-md-inline-block">
          <i class="fas fa-plus-circle"></i> Create
        </a>
      </div>
    </div>
  </nav>

  <div class="container mt-5 pt-5">
    <div class="banner">
      <canvas id="particles-canvas" class="particles-bg"></canvas>
      <div class="banner-content">
        <h1 class="cyber-title display-4 glow-effect"><i class="fas fa-terminal"></i> CyberOpsNexus Blogs</h1>
        <p class="lead">Advanced security insights and technical resources</p>
      </div>
    </div>
    
    <div class="row mb-4">
      <div class="col-md-6 mb-3 mb-md-0">
        <a href="create.php" class="cyber-btn d-inline-block d-md-none">
          <i class="fas fa-plus-circle"></i> Create New Post
        </a>
        <a href="create.php" class="cyber-btn d-none d-md-inline-block">
          <i class="fas fa-plus-circle"></i> Create New Post
        </a>
      </div>
      <div class="col-md-6">
        <div class="search-wrapper">
          <i class="fas fa-search search-icon"></i>
          <input type="text" class="form-control search-input" placeholder="Search posts..." id="searchInput">
        </div>
      </div>
    </div>
    
    <div class="row" id="postsContainer">
      <div class="col-12 text-center py-5" id="loadingMessage">
        <div class="loading-spinner mb-3"></div>
        <h3>Loading posts...</h3>
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
          <a href="#"><i class="fas fa-shield-alt"></i> Security</a>
          <a href="#"><i class="fas fa-users"></i> Team</a>
          <a href="#"><i class="fas fa-envelope"></i> Contact</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.2/marked.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function calculateReadTime(content) {
      const wordsPerMinute = 200;
      const wordCount = content.split(/\s+/).length;
      const minutes = Math.ceil(wordCount / wordsPerMinute);
      return minutes === 0 ? 1 : minutes;
    }

    async function fetchPosts() {
      const postsContainer = document.getElementById('postsContainer');
      const loadingMessage = document.getElementById('loadingMessage');
      
      try {
        const response = await fetch('http://localhost:5000/posts');
        if (!response.ok) throw new Error('Failed to fetch posts');
        const posts = await response.json();
        
        console.log('Fetched posts:', posts);
        loadingMessage.remove();
        
        if (posts.length === 0) {
          postsContainer.innerHTML = `
            <div class="col-12 text-center py-5">
              <i class="fas fa-file-alt fa-3x mb-3 pulse" style="color: var(--cyber-primary);"></i>
              <h3>No posts available</h3>
              <p>Be the first to create content!</p>
            </div>
          `;
          return;
        }
        
        postsContainer.innerHTML = posts.map(post => {
          if (!post.$id) {
            console.warn('Post missing $id:', post);
            return '';
          }

          return `
            <div class="col-lg-6 mb-4" data-post-id="${post.$id}">
              <div class="cyber-card">
                <div class="cyber-card-header">
                  <h3 class="post-title mb-1">${post.title || 'Untitled'}</h3>
                  <div class="post-meta">
                    <span><i class="fas fa-user-ninja"></i> ${post.author || 'Anonymous'}</span>
                    <span><i class="far fa-calendar-alt"></i> ${new Date(post.date || Date.now()).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</span>
                    <span><i class="fas fa-clock"></i> ${calculateReadTime(post.content || '')} min read</span>
                  </div>
                  <div class="card-meta">
                    <span class="category-chip">${post.category || 'Security'}</span>
                    ${post.tags ? post.tags.split(',').map(tag => `<span class="category-chip">${tag.trim()}</span>`).join('') : ''}
                  </div>
                </div>
                <div class="cyber-card-body">
                  <div class="markdown-content" data-content="${post.content || ''}"></div>
                  <div class="d-flex justify-content-end mt-3 gap-2">
                    <button class="cyber-btn btn-sm read-more">Read More</button>
                    <a href="edit.php?id=${post.$id}" class="cyber-btn cyber-btn-accent btn-sm">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <button class="cyber-btn cyber-btn-danger btn-sm delete-post" data-id="${post.$id}">
                      <i class="fas fa-trash-alt"></i> Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          `;
        }).join('');
        
        document.querySelectorAll('.markdown-content').forEach(div => {
          const markdown = div.getAttribute('data-content');
          div.innerHTML = marked.parse(markdown);
          
          const content = div.innerHTML;
          if (content.length > 300) {
            div.innerHTML = content.substring(0, 300) + '...';
            div.setAttribute('data-full-content', content);
          }
        });
        
        document.querySelectorAll('.read-more').forEach(button => {
          button.addEventListener('click', function() {
            const contentDiv = this.closest('.cyber-card-body').querySelector('.markdown-content');
            const fullContent = contentDiv.getAttribute('data-full-content');
            
            if (fullContent) {
              contentDiv.innerHTML = fullContent;
              contentDiv.removeAttribute('data-full-content');
              this.textContent = 'Show Less';
            } else {
              const content = contentDiv.innerHTML;
              contentDiv.innerHTML = content.substring(0, 300) + '...';
              contentDiv.setAttribute('data-full-content', content);
              this.textContent = 'Read More';
            }
          });
        });
        
        document.querySelectorAll('.delete-post').forEach(button => {
          button.addEventListener('click', function() {
            const postId = this.getAttribute('data-id');
            confirmDelete(postId, this);
          });
        });
        
      } catch (error) {
        console.error('Error fetching posts:', error);
        loadingMessage.innerHTML = `
          <i class="fas fa-exclamation-triangle fa-3x mb-3 pulse" style="color: var(--cyber-accent);"></i>
          <h3>Failed to load posts</h3>
          <p>Please try again later.</p>
        `;
      }
    }
    
    async function deletePost(postId, button, retries = 2) {
      if (!postId) {
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No post ID provided. Cannot delete post.',
          background: 'rgba(15, 23, 42, 0.95)',
          color: '#f1f5f9',
          confirmButtonColor: '#0cefff',
          confirmButtonText: 'OK'
        });
        return;
      }

      const originalButtonText = button.textContent;
      button.disabled = true;
      button.textContent = 'Deleting...';

      try {
        console.log('Sending delete request to:', `http://localhost:5000/posts/${postId}`);
        
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);

        const response = await fetch(`http://localhost:5000/posts/${postId}`, {
          method: 'DELETE',
          headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          signal: controller.signal
        });

        clearTimeout(timeoutId);

        if (!response.ok) {
          const errorData = await response.json().catch(() => ({}));
          console.error('Delete response:', { status: response.status, errorData });
          if (response.status === 404) {
            throw new Error(`Post with ID ${postId} not found`);
          }
          throw new Error(errorData.error || `HTTP error! Status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        let data = {};
        if (contentType && contentType.includes('application/json')) {
          data = await response.json();
          console.log('Delete response:', data);
        }

        await Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: data.message || 'Post deleted successfully!',
          background: 'rgba(15, 23, 42, 0.95)',
          color: '#f1f5f9',
          confirmButtonColor: '#0cefff',
          confirmButtonText: 'OK',
          timer: 2000,
          timerProgressBar: true
        });

        const postCard = document.querySelector(`[data-post-id="${postId}"]`);
        if (postCard) postCard.remove();
      } catch (error) {
        console.error('Error deleting post:', error);
        if (retries > 0 && error.name !== 'AbortError') {
          console.log(`Retrying delete... (${retries} attempts left)`);
          button.textContent = originalButtonText;
          button.disabled = false;
          return setTimeout(() => deletePost(postId, button, retries - 1), 1000);
        }
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.name === 'AbortError' 
            ? 'Request timed out. Please check your server connection.'
            : `Failed to delete post: ${error.message}`,
          background: 'rgba(15, 23, 42, 0.95)',
          color: '#f1f5f9',
          confirmButtonColor: '#0cefff',
          confirmButtonText: 'OK'
        });
      } finally {
        button.disabled = false;
        button.textContent = originalButtonText;
      }
    }

    async function confirmDelete(postId, button) {
      const result = await Swal.fire({
        title: 'Are you sure?',
        text: 'This action will permanently delete the post. This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        background: 'rgba(15, 23, 42, 0.95)',
        color: '#f1f5f9',
        confirmButtonColor: '#0cefff',
        cancelButtonColor: '#ff3d3d'
      });

      if (result.isConfirmed) {
        await deletePost(postId, button);
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      fetchPosts();
      
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
      
      document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchQuery = this.value.toLowerCase();
        const cards = document.querySelectorAll('.cyber-card');
        
        cards.forEach(card => {
          const title = card.querySelector('.post-title').textContent.toLowerCase();
          const content = card.querySelector('.markdown-content').getAttribute('data-content').toLowerCase();
          const author = card.querySelector('.post-meta').textContent.toLowerCase();
          const chips = Array.from(card.querySelectorAll('.category-chip')).map(chip => chip.textContent.toLowerCase());
          
          if (title.includes(searchQuery) || content.includes(searchQuery) || author.includes(searchQuery) || chips.some(chip => chip.includes(searchQuery))) {
            card.closest('.col-lg-6').style.display = '';
          } else {
            card.closest('.col-lg-6').style.display = 'none';
          }
        });
      });
    });
  </script>
</body>
</html>

<?php
ob_end_flush();
?>