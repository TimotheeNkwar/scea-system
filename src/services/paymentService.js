const processPayment = (companyId, amount) => {
  // Simulation de paiement mobile (remplacer par M-Pesa ou autre en production)
  console.log(`Paiement de ${amount} à l’entreprise ${companyId}`);
  return true;
};

module.exports = { processPayment };