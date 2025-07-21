const express = require('express');
const router = express.Router();
const Coupon = require('../models/Coupon');
const Household = require('../models/Household');

router.post('/create', async (req, res) => {
    try {
        const { code, householdId, value } = req.body;
        if (!code || !householdId || !value) return res.status(400).json({ message: 'All fields are required' });
        const household = await Household.findById(householdId);
        if (!household) return res.status(404).json({ message: 'Household not found' });

        const coupon = new Coupon({ code, householdId, value });
        await coupon.save();
        res.status(201).json({ message: 'Coupon created successfully', coupon });
    } catch (err) {
        res.status(500).json({ message: 'Error creating coupon', error: err.message });
    }
});

router.post('/distribute', async (req, res) => {
    try {
        const { code } = req.body;
        const coupon = await Coupon.findOne({ code, status: 'Disponible' });
        if (!coupon) return res.status(404).json({ message: 'Coupon not found or already distributed' });

        coupon.status = 'Distribué';
        coupon.distributedAt = new Date();
        await coupon.save();
        res.json({ message: 'Coupon distributed successfully', coupon });
    } catch (err) {
        res.status(500).json({ message: 'Error distributing coupon', error: err.message });
    }
});

router.post('/transfer', async (req, res) => {
    try {
        const { code, newHouseholdId } = req.body;
        const coupon = await Coupon.findOne({ code, status: 'Distribué' });
        if (!coupon) return res.status(404).json({ message: 'Coupon not found or not distributed' });
        const newHousehold = await Household.findById(newHouseholdId);
        if (!newHousehold) return res.status(404).json({ message: 'New household not found' });

        coupon.householdId = newHouseholdId;
        await coupon.save();
        res.json({ message: 'Coupon transferred successfully', coupon });
    } catch (err) {
        res.status(500).json({ message: 'Error transferring coupon', error: err.message });
    }
});

module.exports = router;