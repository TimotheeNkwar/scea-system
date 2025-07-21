require('dotenv').config();
const { Pool } = require('pg');

const pool = new Pool({
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 5432,
  user: process.env.DB_USER || 'postgres',
  password: process.env.DB_PASSWORD || '22205731',
  database: process.env.DB_NAME || 'scea_db',
});

const initDatabase = async () => {
  try {
    // Créer les tables
    await pool.query(`
      CREATE TABLE IF NOT EXISTS households (
        id SERIAL PRIMARY KEY,
        name TEXT NOT NULL,
        phone TEXT NOT NULL,
        location TEXT NOT NULL,
        eligibility_status TEXT DEFAULT 'pending'
      )
    `);

    await pool.query(`
      CREATE TABLE IF NOT EXISTS coupons (
        id SERIAL PRIMARY KEY,
        household_id INTEGER REFERENCES households(id) ON DELETE CASCADE,
        code TEXT NOT NULL,
        phone TEXT NOT NULL,
        status TEXT DEFAULT 'issued',
        issue_date TIMESTAMP
      )
    `);

    await pool.query(`
      CREATE TABLE IF NOT EXISTS companies (
        id SERIAL PRIMARY KEY,
        name TEXT NOT NULL,
        accreditation_status TEXT DEFAULT 'pending',
        contact TEXT
      )
    `);

    await pool.query(`
      CREATE TABLE IF NOT EXISTS transactions (
        id SERIAL PRIMARY KEY,
        household_id INTEGER REFERENCES households(id) ON DELETE CASCADE,
        company_id INTEGER REFERENCES companies(id) ON DELETE CASCADE,
        coupon_id INTEGER REFERENCES coupons(id) ON DELETE CASCADE,
        amount REAL,
        payment_date TIMESTAMP,
        status TEXT DEFAULT 'pending'
      )
    `);

    await pool.query(`
      CREATE TABLE IF NOT EXISTS construction (
        id SERIAL PRIMARY KEY,
        household_id INTEGER REFERENCES households(id) ON DELETE CASCADE,
        company_id INTEGER REFERENCES companies(id) ON DELETE CASCADE,
        start_date TIMESTAMP,
        completion_date TIMESTAMP,
        approval_status TEXT DEFAULT 'pending'
      )
    `);

    console.log('Base de données PostgreSQL initialisée');
  } catch (err) {
    console.error('Erreur lors de l’initialisation de la base de données:', err);
  }
};

module.exports = { initDatabase, pool };