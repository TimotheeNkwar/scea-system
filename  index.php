<?php
require_once __DIR__ . '/config/db_config.php';

// Basic routing logic
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

switch ($url) {
    case '':
    case 'admin/dashboard':
        $file = 'views/admin/0_tableau_de_bord.html';
        break;
    case 'admin/households':
        $file = 'views/admin/1_liste_des_menages.html';
        break;
    case 'admin/register-household':
        $file = 'views/admin/2_enregistrer_un_menage.html';
        break;
    case 'admin/surveys':
        $file = 'views/admin/3_enquetes.html';
        break;
    case 'admin/create-coupons':
        $file = 'views/admin/4_creer_des_coupons.html';
        break;
    case 'admin/distribute-coupons':
        $file = 'views/admin/5_distribuer_des_coupons.html';
        break;
    case 'admin/transfer-coupons':
        $file = 'views/admin/6_transferts_des_coupons.html';
        break;
    case 'admin/accredited-companies':
        $file = 'views/admin/7_entreprises_accreditees.html';
        break;
    case 'admin/accreditation':
        $file = 'views/admin/8_accreditation.html';
        break;
    case 'agent/dashboard':
        $file = 'views/agent/agent_dashboard.html';
        break;
    case 'company/dashboard':
        $file = 'views/company/company_dashboard.html';
        break;
    default:
        $file = 'views/admin/0_tableau_de_bord.html';
        break;
}

if (file_exists($file)) {
    readfile($file);
} else {
    http_response_code(404);
    echo 'Page not found';
}
?>