<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/db_config.php';

class Database {
    private $conn;

    public function connect() {
        global $db_config;
        try {
            $this->conn = new PDO(
                "mysql:host={$db_config['host']};dbname={$db_config['dbname']}",
                $db_config['username'],
                $db_config['password']
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
            exit;
        }
    }
}

$db = (new Database())->connect();

$request_method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

switch ($segments[0] ?? '') {
    case 'companies':
        handleCompanies($db, $request_method, $segments);
        break;
    case 'households':
        handleHouseholds($db, $request_method, $segments);
        break;
    case 'surveys':
        handleSurveys($db, $request_method, $segments);
        break;
    case 'coupons':
        handleCoupons($db, $request_method, $segments);
        break;
    case 'distributions':
        handleDistributions($db, $request_method, $segments);
        break;
    case 'transfers':
        handleTransfers($db, $request_method, $segments);
        break;
    case 'notifications':
        handleNotifications($db, $request_method, $segments);
        break;
    default:
        echo json_encode(['error' => 'Invalid endpoint']);
        http_response_code(404);
        exit;
}

function handleCompanies($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            if (isset($segments[1])) {
                $stmt = $db->prepare("SELECT * FROM companies WHERE id = ?");
                $stmt->execute([$segments[1]]);
                $company = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($company ?: ['error' => 'Company not found']);
            } else {
                $stmt = $db->prepare("SELECT * FROM companies");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO companies (name, province, registration_number, accreditation_date, status) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $data['name'],
                $data['province'],
                $data['registration_number'],
                $data['accreditation_date'],
                $data['status'] ?? 'En attente'
            ]);
            if ($result) {
                $company_id = $db->lastInsertId();
                if (!empty($data['documents'])) {
                    $stmt = $db->prepare("INSERT INTO company_documents (company_id, document_path) VALUES (?, ?)");
                    foreach ($data['documents'] as $doc) {
                        $stmt->execute([$company_id, $doc]);
                    }
                }
                $stmt = $db->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
                $stmt->execute([
                    'Nouvelle demande d\'accréditation',
                    "{$data['name']} a soumis une demande"
                ]);
                echo json_encode(['message' => 'Company created']);
            } else {
                echo json_encode(['error' => 'Failed to create company']);
                http_response_code(400);
            }
            break;

        case 'PUT':
            if (isset($segments[1])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $db->prepare("UPDATE companies SET name = ?, province = ?, registration_number = ?, accreditation_date = ?, status = ? WHERE id = ?");
                $result = $stmt->execute([
                    $data['name'],
                    $data['province'],
                    $data['registration_number'],
                    $data['accreditation_date'],
                    $data['status'],
                    $segments[1]
                ]);
                echo json_encode($result ? ['message' => 'Company updated'] : ['error' => 'Failed to update company']);
            } else {
                echo json_encode(['error' => 'Company ID required']);
                http_response_code(400);
            }
            break;

        case 'DELETE':
            if (isset($segments[1])) {
                $stmt = $db->prepare("DELETE FROM companies WHERE id = ?");
                $result = $stmt->execute([$segments[1]]);
                echo json_encode($result ? ['message' => 'Company deleted'] : ['error' => 'Failed to delete company']);
            } else {
                echo json_encode(['error' => 'Company ID required']);
                http_response_code(400);
            }
            break;
    }
}

function handleHouseholds($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            if (isset($segments[1])) {
                $stmt = $db->prepare("SELECT * FROM households WHERE id = ?");
                $stmt->execute([$segments[1]]);
                $household = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($household ?: ['error' => 'Household not found']);
            } else {
                $stmt = $db->prepare("SELECT * FROM households");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO households (name, phone, address, province, eligibility_status, member_count, latrine_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $data['name'],
                $data['phone'],
                $data['address'],
                $data['province'],
                $data['eligibility_status'],
                $data['member_count'],
                $data['latrine_status'] ?? 'Non construit'
            ]);
            echo json_encode($result ? ['message' => 'Household created'] : ['error' => 'Failed to create household']);
            break;

        case 'PUT':
            if (isset($segments[1])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $db->prepare("UPDATE households SET name = ?, phone = ?, address = ?, province = ?, eligibility_status = ?, member_count = ?, latrine_status = ? WHERE id = ?");
                $result = $stmt->execute([
                    $data['name'],
                    $data['phone'],
                    $data['address'],
                    $data['province'],
                    $data['eligibility_status'],
                    $data['member_count'],
                    $data['latrine_status'],
                    $segments[1]
                ]);
                echo json_encode($result ? ['message' => 'Household updated'] : ['error' => 'Failed to update household']);
            } else {
                echo json_encode(['error' => 'Household ID required']);
                http_response_code(400);
            }
            break;

        case 'DELETE':
            if (isset($segments[1])) {
                $stmt = $db->prepare("DELETE FROM households WHERE id = ?");
                $result = $stmt->execute([$segments[1]]);
                echo json_encode($result ? ['message' => 'Household deleted'] : ['error' => 'Failed to delete household']);
            } else {
                echo json_encode(['error' => 'Household ID required']);
                http_response_code(400);
            }
            break;
    }
}

function handleSurveys($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            if (isset($segments[1])) {
                $stmt = $db->prepare("SELECT s.*, h.name as household_name FROM surveys s JOIN households h ON s.household_id = h.id WHERE s.id = ?");
                $stmt->execute([$segments[1]]);
                $survey = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($survey ?: ['error' => 'Survey not found']);
            } else {
                $stmt = $db->prepare("SELECT s.*, h.name as household_name FROM surveys s JOIN households h ON s.household_id = h.id");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO surveys (survey_id, household_id, survey_date, status) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([
                $data['survey_id'],
                $data['household_id'],
                $data['survey_date'],
                $data['status'] ?? 'En cours'
            ]);
            echo json_encode($result ? ['message' => 'Survey created'] : ['error' => 'Failed to create survey']);
            break;

        case 'PUT':
            if (isset($segments[1])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $db->prepare("UPDATE surveys SET survey_id = ?, household_id = ?, survey_date = ?, status = ? WHERE id = ?");
                $result = $stmt->execute([
                    $data['survey_id'],
                    $data['household_id'],
                    $data['survey_date'],
                    $data['status'],
                    $segments[1]
                ]);
                echo json_encode($result ? ['message' => 'Survey updated'] : ['error' => 'Failed to update survey']);
            } else {
                echo json_encode(['error' => 'Survey ID required']);
                http_response_code(400);
            }
            break;
    }
}

function handleCoupons($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            if (isset($segments[1])) {
                $stmt = $db->prepare("SELECT * FROM coupons WHERE id = ?");
                $stmt->execute([$segments[1]]);
                $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($coupon ?: ['error' => 'Coupon not found']);
            } else {
                $stmt = $db->prepare("SELECT * FROM coupons");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO coupons (coupon_code, type, province, expiration_date, status) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $data['coupon_code'],
                $data['type'],
                $data['province'],
                $data['expiration_date'],
                $data['status'] ?? 'En attente'
            ]);
            if ($result) {
                $stmt = $db->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
                $stmt->execute([
                    'Nouveau coupon à distribuer',
                    "{$data['coupon_code']} prêt pour distribution"
                ]);
                echo json_encode(['message' => 'Coupon created']);
            } else {
                echo json_encode(['error' => 'Failed to create coupon']);
                http_response_code(400);
            }
            break;
    }
}

function handleDistributions($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            if (isset($segments[1])) {
                $stmt = $db->prepare("SELECT d.*, c.coupon_code, h.name as household_name FROM coupon_distributions d JOIN coupons c ON d.coupon_id = c.id JOIN households h ON d.household_id = h.id WHERE d.id = ?");
                $stmt->execute([$segments[1]]);
                $distribution = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($distribution ?: ['error' => 'Distribution not found']);
            } else {
                $stmt = $db->prepare("SELECT d.*, c.coupon_code, h.name as household_name FROM coupon_distributions d JOIN coupons c ON d.coupon_id = c.id JOIN households h ON d.household_id = h.id");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO coupon_distributions (coupon_id, household_id, distribution_date) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $data['coupon_id'],
                $data['household_id'],
                $data['distribution_date']
            ]);
            if ($result) {
                $stmt = $db->prepare("UPDATE coupons SET status = 'Actif' WHERE id = ?");
                $stmt->execute([$data['coupon_id']]);
                $stmt = $db->prepare("SELECT coupon_code, name FROM coupons c JOIN households h ON h.id = ? WHERE c.id = ?");
                $stmt->execute([$data['household_id'], $data['coupon_id']]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $db->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
                $stmt->execute([
                    'Nouveau coupon distribué',
                    "Coupon {$data['coupon_code']} distribué à {$data['name']}"
                ]);
                echo json_encode(['message' => 'Coupon distributed']);
            } else {
                echo json_encode(['error' => 'Failed to distribute coupon']);
                http_response_code(400);
            }
            break;
    }
}

function handleTransfers($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            if (isset($segments[1])) {
                $stmt = $db->prepare("SELECT t.*, c.coupon_code, hs.name as source_name, hd.name as destination_name FROM coupon_transfers t JOIN coupons c ON t.coupon_id = c.id JOIN households hs ON t.source_household_id = hs.id JOIN households hd ON t.destination_household_id = hd.id WHERE t.id = ?");
                $stmt->execute([$segments[1]]);
                $transfer = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($transfer ?: ['error' => 'Transfer not found']);
            } else {
                $stmt = $db->prepare("SELECT t.*, c.coupon_code, hs.name as source_name, hd.name as destination_name FROM coupon_transfers t JOIN coupons c ON t.coupon_id = c.id JOIN households hs ON t.source_household_id = hs.id JOIN households hd ON t.destination_household_id = hd.id");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO coupon_transfers (coupon_id, source_household_id, destination_household_id, transfer_date, reason) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $data['coupon_id'],
                $data['source_household_id'],
                $data['destination_household_id'],
                $data['transfer_date'],
                $data['reason']
            ]);
            if ($result) {
                $stmt = $db->prepare("SELECT coupon_code, name FROM coupons c JOIN households h ON h.id = ? WHERE c.id = ?");
                $stmt->execute([$data['destination_household_id'], $data['coupon_id']]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $db->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
                $stmt->execute([
                    'Coupon transféré',
                    "Coupon {$data['coupon_code']} transféré à {$data['name']}"
                ]);
                echo json_encode(['message' => 'Coupon transferred']);
            } else {
                echo json_encode(['error' => 'Failed to transfer coupon']);
                http_response_code(400);
            }
            break;
    }
}

function handleNotifications($db, $method, $segments) {
    switch ($method) {
        case 'GET':
            $stmt = $db->prepare("SELECT * FROM notifications ORDER BY created_at DESC");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
            $result = $stmt->execute([$data['title'], $data['message']]);
            echo json_encode($result ? ['message' => 'Notification created'] : ['error' => 'Failed to create notification']);
            break;

        case 'PUT':
            if (isset($segments[1]) && $segments[1] === 'mark-read') {
                $stmt = $db->prepare("UPDATE notifications SET is_read = TRUE");
                $result = $stmt->execute();
                echo json_encode($result ? ['message' => 'Notifications marked as read'] : ['error' => 'Failed to mark notifications']);
            } else {
                echo json_encode(['error' => 'Invalid action']);
                http_response_code(400);
            }
            break;
    }
}
?>