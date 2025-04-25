const express = require('express');
const cors = require('cors');
require('dotenv').config();

const postRoutes = require('./routes/posts');

const app = express();

// Middlewares
app.use(cors());
app.use(express.json());

// Routes
app.use('/posts', postRoutes);

// Handle invalid routes at the app level
app.use((req, res) => {
  console.log('Invalid app-level route accessed:', req.method, req.originalUrl);
  res.status(404).json({ error: 'Not found' });
});

// Server Listen
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`🚀 Server running on http://localhost:${PORT}`);
});

// Handle server errors
app.on('error', (err) => {
  console.error('Server error:', err);
});