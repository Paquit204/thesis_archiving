<?php
// data/functions.php
define('DATA_DIR', __DIR__ . '/../data/');
define('THESES_FILE', DATA_DIR . 'theses.json');
define('NOTIFICATIONS_FILE', DATA_DIR . 'notifications.json');
define('PROFILE_FILE', DATA_DIR . 'profile.json');

// Ensure data directory exists
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0777, true);
}

// Initialize default data if files don't exist
if (!file_exists(THESES_FILE)) {
    $defaultTheses = [
        'pending' => [
            ['id' => 1, 'title' => 'Test Title', 'author' => 'mylene raganas', 'date' => 'Mar 01, 2026'],
            ['id' => 2, 'title' => 'asdAFADSFSDFGSDF', 'author' => 'mylene raganas', 'date' => 'Feb 27, 2026']
        ],
        'archived' => [
            ['id' => 3, 'title' => 'Blockchain in Academia', 'author' => 'John Doe', 'date' => 'Jan 15, 2025', 'archived_date' => 'Mar 10, 2026', 'year' => '2025']
        ]
    ];
    file_put_contents(THESES_FILE, json_encode($defaultTheses, JSON_PRETTY_PRINT));
}

if (!file_exists(NOTIFICATIONS_FILE)) {
    $defaultNotifications = [
        ['id' => 1, 'message' => 'Thesis "Test Title" has been forwarded by Dean.', 'date' => 'Mar 01, 2026', 'read' => false, 'icon' => 'fa-envelope'],
        ['id' => 2, 'message' => 'Welcome to the Librarian Dashboard.', 'date' => 'Feb 28, 2026', 'read' => true, 'icon' => 'fa-check-circle']
    ];
    file_put_contents(NOTIFICATIONS_FILE, json_encode($defaultNotifications, JSON_PRETTY_PRINT));
}

if (!file_exists(PROFILE_FILE)) {
    $defaultProfile = [
        'name' => 'Camille Joyce Geocall!',
        'email' => 'camille.geocall@university.edu',
        'role' => 'Head Librarian',
        'department' => 'Library Services',
        'office' => 'Main Library, Room 201',
        'member_since' => 'January 2024'
    ];
    file_put_contents(PROFILE_FILE, json_encode($defaultProfile, JSON_PRETTY_PRINT));
}

function getPendingTheses() {
    $data = json_decode(file_get_contents(THESES_FILE), true);
    return $data['pending'] ?? [];
}

function getArchivedTheses() {
    $data = json_decode(file_get_contents(THESES_FILE), true);
    return $data['archived'] ?? [];
}

function archiveThesis($id) {
    $data = json_decode(file_get_contents(THESES_FILE), true);
    $found = null;
    foreach ($data['pending'] as $key => $thesis) {
        if ($thesis['id'] == $id) {
            $found = $thesis;
            unset($data['pending'][$key]);
            break;
        }
    }
    if ($found) {
        $found['archived_date'] = date('M d, Y');
        $found['year'] = date('Y');
        $data['archived'][] = $found;
        $data['pending'] = array_values($data['pending']);
        file_put_contents(THESES_FILE, json_encode($data, JSON_PRETTY_PRINT));
        addNotification('Thesis "' . $found['title'] . '" has been archived.');
        return true;
    }
    return false;
}

function addNotification($message) {
    $notifications = json_decode(file_get_contents(NOTIFICATIONS_FILE), true);
    $newId = count($notifications) + 1;
    array_unshift($notifications, [
        'id' => $newId,
        'message' => $message,
        'date' => date('M d, Y H:i'),
        'read' => false,
        'icon' => 'fa-archive'
    ]);
    file_put_contents(NOTIFICATIONS_FILE, json_encode($notifications, JSON_PRETTY_PRINT));
}

function getNotifications() {
    return json_decode(file_get_contents(NOTIFICATIONS_FILE), true);
}

function markNotificationRead($id) {
    $notifications = json_decode(file_get_contents(NOTIFICATIONS_FILE), true);
    foreach ($notifications as &$n) {
        if ($n['id'] == $id) {
            $n['read'] = true;
            break;
        }
    }
    file_put_contents(NOTIFICATIONS_FILE, json_encode($notifications, JSON_PRETTY_PRINT));
}

function markAllNotificationsRead() {
    $notifications = json_decode(file_get_contents(NOTIFICATIONS_FILE), true);
    foreach ($notifications as &$n) {
        $n['read'] = true;
    }
    file_put_contents(NOTIFICATIONS_FILE, json_encode($notifications, JSON_PRETTY_PRINT));
}

function getLibrarianProfile() {
    return json_decode(file_get_contents(PROFILE_FILE), true);
}

function updateLibrarianProfile($data) {
    return file_put_contents(PROFILE_FILE, json_encode($data, JSON_PRETTY_PRINT));
}
?>