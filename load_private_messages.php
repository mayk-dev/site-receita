<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    exit();
}

$user_id = $_SESSION['user_id'];
$friend_id = isset($_GET['friend_id']) ? $_GET['friend_id'] : null;

if (!$friend_id) {
    exit();
}

// Recuperar mensagens privadas
$stmt = $pdo->prepare('SELECT * FROM private_messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC');
$stmt->execute([$user_id, $friend_id, $friend_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gerar HTML das mensagens
foreach ($messages as $message) {
    echo '<div class="message">';
    echo '<strong>' . htmlspecialchars($message['sender_id'] == $user_id ? 'Você' : 'Amigo') . ':</strong> ';
    echo htmlspecialchars($message['message']);
    echo '<span style="float: right;">' . $message['timestamp'] . '</span>';
    echo '</div>';
}
