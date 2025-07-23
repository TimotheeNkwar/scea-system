# SCEA - SYSTEM Project Structure

This is the updated directory structure for the SCEA - PASEEA system, a web application for managing electronic coupons for sanitation programs. The `views/` directory is now organized into three subdirectories: `admin/`, `agent/`, and `company/`, as per the request.

```
scea-system/
├── api/
│   └── api.php                     # Main PHP API handling all endpoints
├── assets/
│   ├── css/
│   │   └── styles.css             # CSS styles for the frontend
│   ├── js/
│   │   └── scripts.js            # JavaScript for frontend interactions
│   └── images/                    # Static images (e.g., logos, icons)
├── config/
│   └── db_config.php              # Database connection configuration
├── models/
│   └── Company.js                 # Mongoose schema (optional, if using Node.js alongside PHP)
├── sql/
│   └── scea_paseea_schema.sql     # MySQL database schema
├── views/
│   ├── admin/
│   │   ├── 0_tableau_de_bord.html     # Admin dashboard page
│   │   ├── 1_liste_des_menages.html   # Admin households list page
│   │   ├── 2_enregistrer_un_menage.html # Admin register household page
│   │   ├── 3_enquetes.html            # Admin surveys page
│   │   ├── 4_creer_des_coupons.html   # Admin create coupons page
│   │   ├── 5_distribuer_des_coupons.html # Admin distribute coupons page
│   │   ├── 6_transferts_des_coupons.html # Admin coupon transfers page
│   │   ├── 7_entreprises_accreditees.html # Admin accredited companies page
│   │   └── 8_accreditation.html       # Admin company accreditation page
│   ├── agent/
│   │   └── agent_dashboard.html       # Placeholder for agent interface (HTML to be defined)
│   └── company/
│       └── company_dashboard.html     # Placeholder for company interface (HTML to be defined)
├── .htaccess                      # Apache configuration for URL routing
├── index.php                      # Entry point for the application
├── README.md                      # Project documentation
└── package.json                   # Node.js dependencies (if using Node.js)
```

## Directory and File Descriptions

- **api/**
  - `api.php`: The PHP backend file handling RESTful API endpoints for companies, households, surveys, coupons, distributions, transfers, and notifications. Uses PDO for MySQL interactions.

- **assets/**
  - `css/styles.css`: Contains styles for the HTML pages, ensuring consistent UI across all interfaces.
  - `js/scripts.js`: JavaScript for frontend functionality, such as form submissions, API calls, and dynamic table updates.
  - `images/`: Stores static assets like logos or icons used in the HTML interfaces.

- **config/**
  - `db_config.php`: Configuration file for database connection settings (host, username, password, database name).

- **models/**
  - `Company.js`: Mongoose schema for companies (included for reference, used if integrating Node.js with MongoDB; optional if fully using MySQL/PHP).

- **sql/**
  - `scea_paseea_schema.sql`: MySQL schema defining tables for companies, households, surveys, coupons, distributions, transfers, and notifications.

- **views/**
  - **admin/**: Contains HTML files for the admin interface, as provided in the original files:
    - `0_tableau_de_bord.html`: Admin dashboard with overview stats and recent activities.
    - `1_liste_des_menages.html`: Lists registered households with filtering options.
    - `2_enregistrer_un_menage.html`: Form to register a new household.
    - `3_enquetes.html`: Manages household eligibility surveys.
    - `4_creer_des_coupons.html`: Form to create new coupons.
    - `5_distribuer_des_coupons.html`: Form to distribute coupons to households.
    - `6_transferts_des_coupons.html`: Manages coupon transfers between households.
    - `7_entreprises_accreditees.html`: Lists accredited companies.
    - `8_accreditation.html`: Form to accredit new companies.
  - **agent/**: Placeholder for agent interface HTML files.
    - `agent_dashboard.html`: Placeholder file for the agent interface (specific HTML content to be defined).
  - **company/**: Placeholder for company interface HTML files.
    - `company_dashboard.html`: Placeholder file for the company interface (specific HTML content to be defined).

- **Root Files**
  - `.htaccess`: Configures Apache for clean URL routing and API access (e.g., redirecting `/api/*` to `api/api.php`).
  - `index.php`: Main entry point for the application, likely serving the admin dashboard or routing to other views based on user role.
  - `README.md`: Project documentation with setup instructions and overview.
  - `package.json`: Optional, for Node.js dependencies if using Node.js alongside PHP (e.g., for Company.js).

## Notes
- The `agent/` and `company/` subdirectories currently contain placeholder HTML files (`agent_dashboard.html` and `company_dashboard.html`) as no specific HTML content was provided for these interfaces. You can replace these with actual HTML files tailored to the agent and company roles.
- The `admin/` subdirectory includes all the provided HTML files (0 to 8), maintaining their original functionality.
- Ensure the `api/api.php` endpoints are updated to handle role-based access if agents and companies have different permissions.

## Setup Instructions
1. **Database**: Import `sql/scea_paseea_schema.sql` into MySQL to create the database and tables.
2. **Backend**: Place `api/api.php` in the `api/` directory and update `config