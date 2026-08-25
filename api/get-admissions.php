<?php
/**
 * DigiTrade Academy - Get Admissions & Live KPI Metrics API
 * Endpoint: GET /api/get-admissions.php
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/response.php';
require_once __DIR__ . '/config/auth.php';

requireAdminApiAuth();

$pdo = Database::getConnection();

// Fallback demo response if database connection is offline
if (!$pdo) {
    sendResponse(true, 'Database offline (Sample Data)', [
        'stats' => [
            'total'     => 4,
            'today'     => 2,
            'enrolled'  => 1,
            'pending'   => 2,
            'contacted' => 1
        ],
        'leads' => [
            [
                'id'               => 1,
                'full_name'        => 'Hamza Tariq',
                'whatsapp'         => '+92 300 1234567',
                'email'            => 'hamza.tariq@gmail.com',
                'city'             => 'Lahore',
                'course'           => 'Forex Trading Mastery (Engulfing, SMC, ICT & Live MT5)',
                'experience_level' => 'Intermediate',
                'mentor_choice'    => 'Muhammad Safiullah (Trading)',
                'mentor_number'    => '923327292282',
                'message'          => 'I want to master Engulfing Theory and SMC setups with Safiullah Bhai.',
                'status'           => 'enrolled',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'id'               => 2,
                'full_name'        => 'Usman Sheikh',
                'whatsapp'         => '+92 321 9876543',
                'email'            => 'usman.shk@yahoo.com',
                'city'             => 'Karachi',
                'course'           => 'Meta Ads & Performance Marketing',
                'experience_level' => 'Complete Beginner',
                'mentor_choice'    => 'Muhammad Taha (Digital Marketing)',
                'mentor_number'    => '923405201175',
                'message'          => 'Interested in scaling my local business and dropshipping stores via Facebook ads.',
                'status'           => 'contacted',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-1 days'))
            ],
            [
                'id'               => 3,
                'full_name'        => 'Bilal Ahmed',
                'whatsapp'         => '+92 333 4567890',
                'email'            => 'bilal.trade@gmail.com',
                'city'             => 'Islamabad',
                'course'           => 'Forex Trading Mastery (Engulfing, SMC, ICT & Live MT5)',
                'experience_level' => 'Advanced',
                'mentor_choice'    => 'Muhammad Safiullah (Trading)',
                'mentor_number'    => '923327292282',
                'message'          => 'Need prop firm challenge passing strategy guidance.',
                'status'           => 'pending',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'id'               => 4,
                'full_name'        => 'Zainab Noor',
                'whatsapp'         => '+92 345 6789012',
                'email'            => 'zainab.noor@outlook.com',
                'city'             => 'Rawalpindi',
                'course'           => 'Facebook Marketplace & E-Commerce',
                'experience_level' => 'Complete Beginner',
                'mentor_choice'    => 'Muhammad Taha (Digital Marketing)',
                'mentor_number'    => '923405201175',
                'message'          => 'Zero inventory dropshipping model training inquiry.',
                'status'           => 'pending',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-1 hours'))
            ]
        ],
        'pagination' => [
            'total' => 4,
            'page'  => 1,
            'limit' => 25,
            'pages' => 1
        ]
    ]);
}

try {
    // 1. Calculate Real-time KPI Stats
    $statsQuery = $pdo->query("
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS today,
            SUM(CASE WHEN status = 'enrolled' THEN 1 ELSE 0 END) AS enrolled,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) AS contacted,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled
        FROM `admissions`
    ");
    $stats = $statsQuery->fetch() ?: [
        'total' => 0, 'today' => 0, 'enrolled' => 0, 'pending' => 0, 'contacted' => 0, 'cancelled' => 0
    ];

    // 2. Build Filtered Query
    $search = trim($_GET['search'] ?? '');
    $course = trim($_GET['course'] ?? '');
    $status = trim($_GET['status'] ?? '');
    $mentor = trim($_GET['mentor'] ?? '');
    $page   = max(1, (int)($_GET['page'] ?? 1));
    $limit  = max(5, min(100, (int)($_GET['limit'] ?? 50)));
    $offset = ($page - 1) * $limit;

    $whereConditions = [];
    $params = [];

    if (!empty($search)) {
        $whereConditions[] = "(`full_name` LIKE :search OR `whatsapp` LIKE :search OR `email` LIKE :search OR `city` LIKE :search)";
        $params[':search'] = "%{$search}%";
    }

    if (!empty($course)) {
        $whereConditions[] = "`course` LIKE :course";
        $params[':course'] = "%{$course}%";
    }

    if (!empty($status)) {
        $whereConditions[] = "`status` = :status";
        $params[':status'] = $status;
    }

    if (!empty($mentor)) {
        $whereConditions[] = "`mentor_choice` LIKE :mentor";
        $params[':mentor'] = "%{$mentor}%";
    }

    $whereSql = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

    // Total filtered count
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM `admissions` {$whereSql}");
    $countStmt->execute($params);
    $totalFiltered = (int)$countStmt->fetchColumn();

    // Fetch leads
    $sql = "SELECT id, full_name, whatsapp, email, city, course, experience_level, mentor_choice, mentor_number, message, status, notes, created_at 
            FROM `admissions` 
            {$whereSql} 
            ORDER BY created_at DESC 
            LIMIT {$limit} OFFSET {$offset}";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $leads = $stmt->fetchAll();

    sendResponse(true, 'Leads fetched successfully', [
        'stats' => [
            'total'     => (int)$stats['total'],
            'today'     => (int)$stats['today'],
            'enrolled'  => (int)$stats['enrolled'],
            'pending'   => (int)$stats['pending'],
            'contacted' => (int)$stats['contacted']
        ],
        'leads'      => $leads,
        'pagination' => [
            'total' => $totalFiltered,
            'page'  => $page,
            'limit' => $limit,
            'pages' => ceil($totalFiltered / $limit)
        ]
    ]);

} catch (PDOException $e) {
    sendResponse(false, 'Database error while fetching admissions: ' . $e->getMessage(), null, 500);
}
