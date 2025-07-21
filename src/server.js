require('dotenv').config();
const express = require('express');
const cors = require('cors');
const householdRoutes = require('./routes/households');
const couponRoutes = require('./routes/coupons');
const companyRoutes = require('./routes/companies');
const transactionRoutes = require('./routes/transactions');
const constructionRoutes = require('./routes/construction');
const { initDatabase } = require('./models/database');

const app = express();
const port = 5000;

// Middleware
app.use(cors());
app.use(express.json());

// Initialiser la base de données
initDatabase();

// Routes
app.use('/api/households', householdRoutes);
app.use('/api/coupons', couponRoutes);
app.use('/api/companies', companyRoutes);
app.use('/api/transactions', transactionRoutes);
app.use('/api/construction', constructionRoutes);

// Démarrer le serveur
app.listen(port, () => {
  console.log(`Serveur démarré sur http://localhost:${port}`);
});