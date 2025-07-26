# SCEA - PASEEA: Electronic Coupon Management System for Sanitation

## Project Overview

The **SCEA - PASEEA** (Electronic Coupon System for Sanitation) is a web application designed to manage a sanitation program, focusing on the distribution of electronic coupons to facilitate the construction of latrines and other sanitation infrastructure for eligible households. The application supports the management of households, accredited companies, coupons, and provides tracking and reporting features to monitor program progress.

The user interface is designed to be intuitive, featuring a centralized dashboard, navigation menus, and dedicated sections for each key functionality. The application enables administrators to manage program data, generate reports, and visualize statistics.

## Key Features

### 1. **Dashboard (index.html)**
- Provides an overview of the program with key metrics:
  - Number of eligible households
  - Coupons distributed
  - Accredited companies
  - Latrines constructed
- Displays recent activities, project progress, and steps in the coupon management process.
- Lists recent coupons with their status (active, pending, used, expired).

### 2. **Household Management**
- **Household List (households.html)**:
  - Displays a filterable list of registered households with details such as location, coupon status, and latrine status.
  - Allows viewing details for each household.
- **Register Household (register-household.html)**:
  - Form to add a new household with fields for name, phone number, address, province, eligibility status, and number of members.

### 3. **Coupon Management**
- **Create Coupons (create-coupon.html)**:
  - Allows generating new coupons by specifying type (latrine, sanitation), quantity, expiration date, and province.
- **Distribute Coupons (distribute-coupon.html)**:
  - Assigns coupons to eligible households by selecting the household, coupon code, type, and expiration date.
  - Displays recent distributions.

### 4. **Company Management**
- **Accredited Companies (accredited-companies.html)**:
  - Lists accredited companies with details like name, province, accreditation date, and status.
- **Accreditation (accreditation.html)**:
  - Form to accredit a new company with fields for name, province, registration number, accreditation date, and supporting documents.
  - Displays recent accreditations.

### 5. **Tracking & Reporting**
- **Statistics (statistics.html)**:
  - Visualizes key metrics using Chart.js charts:
    - Bar chart: Coupons distributed by province.
    - Pie chart: Latrine status (not constructed, under construction, completed).
- **Reports (reports.html)**:
  - Allows generating reports by selecting type (coupon distribution, latrine status, household surveys), period, and province.
  - Lists generated reports with options to view them.

### 6. **Notifications**
- Present on all pages, displaying real-time alerts (new coupons, completed constructions, accreditations, etc.).
- Option to mark all notifications as read.

## File Structure

- **index.html**: Main dashboard.
- **households.html**: Household list management.
- **register-household.html**: Register new households.
- **create-coupon.html**: Coupon creation.
- **distribute-coupon.html**: Coupon distribution.
- **accredited-companies.html**: List of accredited companies.
- **accreditation.html**: Accreditation of new companies.
- **statistics.html**: Data visualization.
- **reports.html**: Report generation and viewing.

## Technologies Used

- **HTML**: Page structure.
- **Chart.js**: Data visualization (charts).
- **CSS**: Styling (not included in provided files but implied).
- **JavaScript**: Assumed for dynamic interactions (e.g., form submissions, filtering, notifications).
- **Backend**: Not specified in files but likely a database to store household, coupon, and company data.

## Coupon Management Process

1. **Survey and Eligibility**: Identify eligible households.
2. **Household Contribution**: Payment of financial contribution.
3. **Coupon Distribution**: Send electronic coupon via SMS.
4. **Company Selection**: Choose an accredited company.
5. **Construction**: Execution of work by the company.
6. **Coupon Transfer**: Transfer coupon to the company.
7. **Payment**: Company remuneration via UPEP.

## Installation and Deployment

1. **Prerequisites**:
   - A web server (Apache, Nginx, etc.) to host HTML files.
   - Chart.js library included for statistics.
   - A backend (not provided) to handle dynamic data and forms.

2. **Steps**:
   - Clone the repository: `git clone <repository-url>`
   - Place files in the web server directory.
   - Configure the backend to manage data (households, coupons, companies).
   - Include Chart.js via CDN or locally in `statistics.html`.
   - Test the application in a browser.

3. **Dependencies**:
   ```html
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   ```

## Contribution

1. Fork the repository.
2. Create a branch for your changes: `git checkout -b feature/new-feature`
3. Make changes and test locally.
4. Submit a pull request with a clear description of changes.

## License

This project is licensed under the MIT License (to be confirmed based on project requirements).

## Contact

For questions or suggestions, contact the SCEA - PASEEA team via [email@example.com](mailto:email@example.com).
