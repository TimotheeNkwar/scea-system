const mongoose = require('mongoose');

const surveySchema = new mongoose.Schema({
    householdId: { type: mongoose.Schema.Types.ObjectId, ref: 'Household', required: true },
    date: { type: Date, required: [true, 'Survey date is required'] },
    responses: { type: Map, of: String },
    createdAt: { type: Date, default: Date.now },
});

module.exports = mongoose.model('Survey', surveySchema);