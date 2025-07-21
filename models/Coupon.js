const mongoose = require('mongoose');

const couponSchema = new mongoose.Schema({
    code: { type: String, required: [true, 'Coupon code is required'], unique: true },
    householdId: { type: mongoose.Schema.Types.ObjectId, ref: 'Household', required: true },
    value: { type: Number, required: [true, 'Coupon value is required'], min: [1, 'Value must be at least 1'] },
    status: { type: String, enum: ['Disponible', 'Distribué', 'Utilisé'], default: 'Disponible' },
    createdAt: { type: Date, default: Date.now },
    distributedAt: { type: Date },
});

module.exports = mongoose.model('Coupon', couponSchema);