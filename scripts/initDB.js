const mongoose = require('mongoose');
const Company = require('../models/Company');
const Household = require('../models/Household');
const Coupon = require('../models/Coupon');
const Survey = require('../models/Survey');
const dotenv = require('dotenv');

dotenv.config();

mongoose.connect(process.env.MONGO_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
})
.then(() => console.log('Connected to MongoDB'))
.catch((err) => console.error('MongoDB connection error:', err));

const sampleData = {
    companies: [
        { name: 'EcoBuild SARL', province: 'Kadiogo', registrationNumber: 'REG001', accreditationDate: new Date('2025-03-01'), status: 'Actif', documents: ['uploads/ecoBuild.pdf'] },
        { name: 'Sanitech Ltd', province: 'Haut-Bassins', registrationNumber: 'REG002', accreditationDate: new Date('2025-02-15'), status: 'En attente', documents: ['uploads/sanitech.pdf'] },
    ],
    households: [
        { name: 'Famille Diarra', province: 'Kadiogo', registrationNumber: 'HH001', members: 4 },
        { name: 'Famille Traoré', province: 'Haut-Bassins', registrationNumber: 'HH002', members: 3 },
    ],
    coupons: [
        { code: 'CPN001', householdId: 'HH001', value: 100, status: 'Distribué', distributedAt: new Date('2025-07-15') },
        { code: 'CPN002', householdId: 'HH002', value: 50, status: 'Disponible' },
    ],
    surveys: [
        { householdId: 'HH001', date: new Date('2025-07-20'), responses: { 'q1': 'Oui', 'q2': 'Non' } },
        { householdId: 'HH002', date: new Date('2025-07-19'), responses: { 'q1': 'Non', 'q2': 'Oui' } },
    ],
};

const initDB = async () => {
    try {
        await Company.deleteMany({});
        await Household.deleteMany({});
        await Coupon.deleteMany({});
        await Survey.deleteMany({});
        console.log('Cleared existing data');

        const [household1, household2] = await Household.insertMany(sampleData.households);
        sampleData.coupons[0].householdId = household1._id;
        sampleData.coupons[1].householdId = household2._id;
        sampleData.surveys[0].householdId = household1._id;
        sampleData.surveys[1].householdId = household2._id;

        await Company.insertMany(sampleData.companies);
        await Coupon.insertMany(sampleData.coupons);
        await Survey.insertMany(sampleData.surveys);
        console.log('Inserted sample data');

        console.log('Database initialized successfully');
    } catch (err) {
        console.error('Error initializing database:', err);
    } finally {
        mongoose.connection.close();
    }
};

initDB();