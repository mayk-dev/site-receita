<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$friend_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$friend_id) {
    echo "Usuário não encontrado.";
    exit();
}

// Enviar mensagem
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $stmt = $pdo->prepare('INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)');
    if ($stmt->execute([$user_id, $friend_id, $message])) {
        header('Location: private_chat.php?id=' . $friend_id);
        exit();
    } else {
        echo "Erro ao enviar mensagem. Tente novamente.";
    }
}
