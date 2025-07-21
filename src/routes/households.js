const express = require('express');
const router = express.Router();
const { pool } = require('../models/database');

router.post('/register', async (req, res) => {
  const { name, phone, location } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO households (name, phone, location) VALUES ($1, $2, $3) RETURNING id',
      [name, phone, location]
    );
    res.json({ message: 'Ménage enregistré avec succès', id: result.rows[0].id });
  } catch (err) {
    console.error(err); // Vérifie cette ligne dans les logs
    res.status(500).json({ message: 'Erreur lors de l’enregistrement' });
  }
});

module.exports = router;