const express = require('express');
const router = express.Router();
const { pool } = require('../models/database');

router.post('/create', async (req, res) => {
  const { household_id, company_id, coupon_id, amount, payment_date } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO transactions (household_id, company_id, coupon_id, amount, payment_date) VALUES ($1, $2, $3, $4, $5) RETURNING id',
      [household_id, company_id, coupon_id, amount, payment_date]
    );
    res.json({ message: 'Transaction créée avec succès', id: result.rows[0].id });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: 'Erreur lors de la création de la transaction' });
  }
});

module.exports = router;