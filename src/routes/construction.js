const express = require('express');
const router = express.Router();
const { pool } = require('../models/database');

// Mettre à jour l’état de la construction
router.post('/update', async (req, res) => {
  const { household_id, company_id, status } = req.body;
  if (!household_id || !company_id || !status) {
    return res.status(400).json({ message: 'Tous les champs sont requis' });
  }

  const startDate = new Date().toISOString();

  try {
    const result = await pool.query(
      'INSERT INTO construction (household_id, company_id, start_date, approval_status) VALUES ($1, $2, $3, $4) RETURNING id',
      [household_id, company_id, startDate, status]
    );
    res.json({ message: 'Construction mise à jour avec succès', id: result.rows[0].id });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: 'Erreur lors de la mise à jour de la construction' });
  }
});

module.exports = router;