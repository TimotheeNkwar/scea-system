const express = require('express');
const router = express.Router();
const { pool } = require('../models/database');

router.post('/accredit', async (req, res) => {
  const { name, contact } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO companies (name, contact) VALUES ($1, $2) RETURNING id',
      [name, contact]
    );
    res.json({ message: 'Entreprise accréditée avec succès', id: result.rows[0].id });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: 'Erreur lors de l\'accréditation' });
  }
});

module.exports = router;