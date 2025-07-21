const mongoose = require('mongoose');

const companySchema = new mongoose.Schema({
    name: { type: String, required: [true, 'Company name is required'], trim: true, minlength: [2, 'Company name must be at least 2 characters'] },
    province: { type: String, required: [true, 'Province is required'], enum: { values: ['Kadiogo', 'Haut-Bassins', 'Kourwéogo'], message: 'Invalid province' } },
    registrationNumber: { type: String, required: [true, 'Registration number is required'], unique: true, trim: true },
    accreditationDate: { type: Date, required: [true, 'Accreditation date is required'] },
    status: { type: String, enum: { values: ['Actif', 'En attente', 'Suspendu'], message: 'Invalid status' }, default: 'En attente' },
    documents: [{ type: String }],
    createdAt: { type: Date, default: Date.now },
});

module.exports = mongoose.model('Company', companySchema);