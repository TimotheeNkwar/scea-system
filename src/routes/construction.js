const express = require('express');
const router = express.Router();
const sqlite3 = require('sqlite3').verbose();
const db = new sqlite3.Database(':memory:');

// Mettre à jour l’état de la construction
router.post('/update', (req, res) => {
  const { household_id, company_id, status } = req.body;
  if (!household_id || !company_id || !status) {
    return res.status(400).json({ message: 'Tous les champs sont requis' });
  }

  const startDate = new Date().toISOString();
  const stmt = db.prepare('INSERT INTO construction (household_id, company_id, start_date, approval_status) VALUES (?, ?, ?, ?)');
  stmt.run(household_id, company_id, startDate, status, function (err) {
    if (err) {
      return res.status(500).json({ message: 'Erreur lors de la mise à jour de la construction' });
    }
    res.json({ message: 'Construction mise à jour avec succès', id: this.lastID });
  });
  stmt.finalize();
});

module.exports = router;