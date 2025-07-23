document.addEventListener('DOMContentLoaded', () => {
    const API_BASE_URL = '/api';

    // Generic function to fetch data
    async function fetchData(endpoint) {
        try {
            const response = await fetch(`${API_BASE_URL}/${endpoint}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    }

    // Load companies
    async function loadCompanies() {
        const companies = await fetchData('companies');
        const tableBody = document.querySelector('#companies-table tbody');
        if (tableBody) {
            tableBody.innerHTML = companies.map(c => `
                <tr>
                    <td>${c.name}</td>
                    <td>${c.province}</td>
                    <td>${c.accreditation_date}</td>
                    <td>${c.status}</td>
                    <td><button onclick="viewCompany(${c.id})">View</button></td>
                </tr>
            `).join('');
        }
    }

    // Load households
    async function loadHouseholds() {
        const households = await fetchData('households');
        const tableBody = document.querySelector('#households-table tbody');
        if (tableBody) {
            tableBody.innerHTML = households.map(h => `
                <tr>
                    <td>${h.name}</td>
                    <td>${h.province}</td>
                    <td>${h.eligibility_status}</td>
                    <td>${h.latrine_status}</td>
                    <td><button onclick="viewHousehold(${h.id})">View</button></td>
                </tr>
            `).join('');
        }
    }

    // Load surveys
    async function loadSurveys() {
        const surveys = await fetchData('surveys');
        const tableBody = document.querySelector('#surveys-table tbody');
        if (tableBody) {
            tableBody.innerHTML = surveys.map(s => `
                <tr>
                    <td>${s.survey_id}</td>
                    <td>${s.household_name}</td>
                    <td>${s.survey_date}</td>
                    <td>${s.status}</td>
                    <td>
                        <button onclick="viewSurvey(${s.id})">View Results</button>
                        <button onclick="markSurveyComplete(${s.id})">Mark Complete</button>
                    </td>
                </tr>
            `).join('');
        }
    }

    // Load coupons
    async function loadCoupons() {
        const coupons = await fetchData('coupons');
        const tableBody = document.querySelector('#coupons-table tbody');
        if (tableBody) {
            tableBody.innerHTML = coupons.map(c => `
                <tr>
                    <td>${c.coupon_code}</td>
                    <td>${c.type}</td>
                    <td>${c.province}</td>
                    <td>${c.expiration_date}</td>
                    <td>${c.status}</td>
                </tr>
            `).join('');
        }
    }

    // Load distributions
    async function loadDistributions() {
        const distributions = await fetchData('distributions');
        const tableBody = document.querySelector('#distributions-table tbody');
        if (tableBody) {
            tableBody.innerHTML = distributions.map(d => `
                <tr>
                    <td>${d.coupon_code}</td>
                    <td>${d.household_name}</td>
                    <td>${d.distribution_date}</td>
                </tr>
            `).join('');
        }
    }

    // Load transfers
    async function loadTransfers() {
        const transfers = await fetchData('transfers');
        const tableBody = document.querySelector('#transfers-table tbody');
        if (tableBody) {
            tableBody.innerHTML = transfers.map(t => `
                <tr>
                    <td>${t.coupon_code}</td>
                    <td>${t.source_name}</td>
                    <td>${t.destination_name}</td>
                    <td>${t.transfer_date}</td>
                </tr>
            `).join('');
        }
    }

    // Load notifications
    async function loadNotifications() {
        const notifications = await fetchData('notifications');
        const container = document.querySelector('#notifications');
        if (container) {
            container.innerHTML = notifications.map(n => `
                <div class="notification">
                    <strong>${n.title}</strong>
                    <p>${n.message}</p>
                    <small>${new Date(n.created_at).toLocaleString()}</small>
                </div>
            `).join('');
        }
    }

    // Form submission handler
    function handleFormSubmit(formId, endpoint, successMessage) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                try {
                    const response = await fetch(`${API_BASE_URL}/${endpoint}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();
                    alert(result.message || successMessage);
                    form.reset();
                } catch (error) {
                    alert('Error: ' + error.message);
                }
            });
        }
    }

    // Initialize page
    if (document.getElementById('companies-table')) loadCompanies();
    if (document.getElementById('households-table')) loadHouseholds();
    if (document.getElementById('surveys-table')) loadSurveys();
    if (document.getElementById('coupons-table')) loadCoupons();
    if (document.getElementById('distributions-table')) loadDistributions();
    if (document.getElementById('transfers-table')) loadTransfers();
    if (document.getElementById('notifications')) loadNotifications();

    handleFormSubmit('company-form', 'companies', 'Company created successfully');
    handleFormSubmit('household-form', 'households', 'Household created successfully');
    handleFormSubmit('survey-form', 'surveys', 'Survey created successfully');
    handleFormSubmit('coupon-form', 'coupons', 'Coupon created successfully');
    handleFormSubmit('distribution-form', 'distributions', 'Coupon distributed successfully');
    handleFormSubmit('transfer-form', 'transfers', 'Coupon transferred successfully');
});