const mongoose = require('mongoose');

const householdSchema = new mongoose.Schema({
    name: { type: String, required: [true, 'Household name is required'], trim: true },
    province: { type: String, required: [true, 'Province is required'], enum: ['Kadiogo', 'Haut-Bassins', 'Kourwéogo'] },
    registrationNumber: { type: String, required: [true, 'Registration number is required'], unique: true },
    members: { type: Number, required: [true, 'Number of members is required'], min: [1, 'Must have at least 1 member'] },
    createdAt: { type: Date, default: Date.now },
});

module.exports = mongoose.model('Household', householdSchema);