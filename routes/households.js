const express = require('express');
const router = express.Router();
const Household = require('../models/Household');

router.get('/', async (req, res) => {
    try {
        const { search } = req.query;
        let query = {};
        if (search) query.name = { $regex: search, $options: 'i' };
        const households = await Household.find(query).sort({ createdAt: -1 });
        res.json(households);
    } catch (err) {
        res.status(500).json({ message: 'Error fetching households', error: err.message });
    }
});

router.post('/register', async (req, res) => {
    try {
        const { name, province, registrationNumber, members } = req.body;
        if (!name || !province || !registrationNumber || !members) return res.status(400).json({ message: 'All fields are required' });
        const existingHousehold = await Household.findOne({ registrationNumber });
        if (existingHousehold) return res.status(400).json({ message: 'Registration number already exists' });

        const household = new Household({ name, province, registrationNumber, members });
        await household.save();
        res.status(201).json({ message: 'Household registered successfully', household });
    } catch (err) {
        res.status(500).json({ message: 'Error registering household', error: err.message });
    }
});

module.exports = router;