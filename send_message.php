<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $room_id = isset($_GET['room_id']) ? $_GET['room_id'] : 1;
    $message = $_POST['message'];

    // Inserir mensagem no banco de dados
    $stmt = $pdo->prepare('INSERT INTO chats (user_id, room_id, message) VALUES (?, ?, ?)');
    if ($stmt->execute([$user_id, $room_id, $message])) {
        header('Location: chat.php?room_id=' . $room_id);
        exit();
    } else {
        echo "Erro ao enviar mensagem. Tente novamente.";
    }
}
?>
