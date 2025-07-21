const express = require('express');
const router = express.Router();
const { pool } = require('../models/database');
const { sendSMS } = require('../services/smsService');

router.post('/issue', async (req, res) => {
  const { phone } = req.body;
  const code = Math.random().toString(36).substr(2, 9).toUpperCase();
  const issueDate = new Date().toISOString();

  try {
    // Insérer le coupon dans la base de données
    const result = await pool.query(
      'INSERT INTO coupons (household_id, code, phone, status, issue_date) VALUES ($1, $2, $3, $4, $5) RETURNING id',
      [null, code, phone, 'issued', issueDate] // household_id peut être null si non lié encore
    );

    const couponId = result.rows[0].id;

    // Envoyer le SMS
    const success = await sendSMS(phone, `Votre coupon SCEA : ${code}`);

    if (success) {
      res.json({ message: 'Coupon généré et envoyé', couponCode: code, id: couponId });
    } else {
      // Marquer comme erreur si SMS échoue, mais garder le coupon
      await pool.query(
        'UPDATE coupons SET status = $1 WHERE id = $2',
        ['failed', couponId]
      );
      res.status(500).json({ message: 'Erreur lors de l\'envoi du SMS, coupon enregistré' });
    }
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: 'Erreur lors de la génération du coupon' });
  }
});

module.exports = router;