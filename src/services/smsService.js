const sendSMS = (phone, message) => {
  // Simulation d’envoi SMS (remplacer par Twilio ou autre en production)
  console.log(`Envoi SMS à ${phone}: ${message}`);
  return true;
};

module.exports = { sendSMS };