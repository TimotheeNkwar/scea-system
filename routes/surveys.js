const express = require('express');
const router = express.Router();
const Survey = require('../models/Survey');
const Household = require('../models/Household');

router.get('/', async (req, res) => {
    try {
        const surveys = await Survey.find().populate('householdId').sort({ date: -1 });
        res.json(surveys);
    } catch (err) {
        res.status(500).json({ message: 'Error fetching surveys', error: err.message });
    }
});

router.post('/submit', async (req, res) => {
    try {
        const { householdId, date, responses } = req.body;
        if (!householdId || !date || !responses) return res.status(400).json({ message: 'All fields are required' });
        const household = await Household.findById(householdId);
        if (!household) return res.status(404).json({ message: 'Household not found' });

        const survey = new Survey({ householdId, date, responses });
        await survey.save();
        res.status(201).json({ message: 'Survey submitted successfully', survey });
    } catch (err) {
        res.status(500).json({ message: 'Error submitting survey', error: err.message });
    }
});

module.exports = router;