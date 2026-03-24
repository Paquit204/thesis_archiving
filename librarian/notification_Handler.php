<?php
session_start();
require_once __DIR__ . '/data/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = ['success' => false];
    if ($_POST['action'] === 'mark_read' && isset($_POST['id'])) {
        markNotificationRead((int)$_POST['id']);
        $response['success'] = true;
    } elseif ($_POST['action'] === 'mark_all_read') {
        markAllNotificationsRead();
        $response['success'] = true;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
// If not POST, redirect
header('Location: notification.php');
exit;