const express = require('express');
const router = express.Router();
const { databases } = require('../appwrite/client');
const cors = require('cors');
require('dotenv').config();

router.use(cors()); // Enable CORS for all routes
router.use(express.json()); // Parse JSON bodies

const databaseId = process.env.APPWRITE_DB_ID;
const collectionId = process.env.APPWRITE_COLLECTION_ID;

// Middleware to validate environment variables
router.use((req, res, next) => {
  if (!databaseId || !collectionId) {
    console.error('Environment variables missing:', { databaseId, collectionId });
    return res.status(500).json({ error: 'Server configuration error' });
  }
  next();
});

// GET all posts
router.get('/', async (req, res) => {
  try {
    console.log('Fetching all posts');
    const result = await databases.listDocuments(databaseId, collectionId);
    res.status(200).json(result.documents);
  } catch (error) {
    console.error('Error fetching posts:', error);
    res.status(500).json({ error: 'Failed to fetch posts', details: error.message });
  }
});

// GET a single post by ID
router.get('/:id', async (req, res) => {
  const postId = req.params.id;
  console.log('Fetching post with ID:', postId);

  try {
    const post = await databases.getDocument(databaseId, collectionId, postId);
    res.status(200).json(post);
  } catch (error) {
    console.error('Error fetching post by ID:', error);
    if (error.code === 404) {
      res.status(404).json({ error: `Post with ID ${postId} not found` });
    } else {
      res.status(500).json({ error: 'Failed to fetch post', details: error.message });
    }
  }
});

// POST a new post
router.post('/', async (req, res) => {
  const { title, content, author, category, tags } = req.body;
  console.log('Creating new post:', { title, author, category, tags });

  try {
    const tagsString = Array.isArray(tags) ? tags.join(',') : tags || ''; // Convert tags array to comma-separated string
    if (tagsString.length > 255) {
      throw new Error('Tags string exceeds 255 characters');
    }

    const result = await databases.createDocument(
      databaseId,
      collectionId,
      'unique()',
      {
        title,
        content,
        author,
        category,
        tags: tagsString, // Store tags as a string
        date: new Date().toISOString(),
      }
    );
    res.status(201).json(result);
  } catch (error) {
    console.error('Error creating post:', error);
    res.status(500).json({ error: 'Failed to create post', details: error.message });
  }
});

// PUT update a post by ID
router.put('/:id', async (req, res) => {
  const postId = req.params.id;
  const { title, content, category, tags, author, date } = req.body;
  console.log('Updating post with ID:', postId);
  console.log('Update data:', { title, content, category, tags, author, date });

  try {
    const tagsString = Array.isArray(tags) ? tags.join(',') : tags || ''; // Convert tags array to comma-separated string
    if (tagsString.length > 255) {
      throw new Error('Tags string exceeds 255 characters');
    }

    const updateData = {
      title,
      content,
      category,
      author,
      date,
      tags: tagsString, // Store tags as a string
    };

    const result = await databases.updateDocument(
      databaseId,
      collectionId,
      postId,
      updateData
    );
    console.log('Update successful:', result);
    res.status(200).json(result);
  } catch (error) {
    console.error('Error updating post:', error);
    if (error.code === 404) {
      res.status(404).json({ error: `Post with ID ${postId} not found` });
    } else {
      res.status(500).json({ error: 'Failed to update post', details: error.message });
    }
  }
});

// DELETE a post by ID
router.delete('/:id', async (req, res) => {
  const postId = req.params.id;
  console.log('Deleting post with ID:', postId);

  try {
    // Attempt to delete the document
    await databases.deleteDocument(databaseId, collectionId, postId);
    console.log('Post deleted successfully:', postId);
    res.status(200).json({ message: `Post with ID ${postId} deleted successfully` });
  } catch (error) {
    console.error('Error deleting post:', error);
    if (error.code === 404) {
      res.status(404).json({ error: `Post with ID ${postId} not found` });
    } else {
      res.status(500).json({ error: 'Failed to delete post', details: error.message });
    }
  }
});

// Handle invalid routes
router.use((req, res) => {
  console.log('Invalid route accessed:', req.method, req.originalUrl);
  res.status(404).json({ error: 'Route not found' });
});

module.exports = router;