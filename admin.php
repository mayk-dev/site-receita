<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Criar nova sala de bate-papo
if (isset($_POST['create_room'])) {
    $room_name = $_POST['room_name'];
    $stmt = $pdo->prepare('INSERT INTO chat_rooms (room_name) VALUES (?)');
    $stmt->execute([$room_name]);
}

// Excluir uma sala de bate-papo
if (isset($_POST['delete_room'])) {
    $room_id = $_POST['room_id'];
    $stmt = $pdo->prepare('DELETE FROM chat_rooms WHERE id = ?');
    $stmt->execute([$room_id]);
}

// Recuperar todas as salas
$stmt = $pdo->query('SELECT * FROM chat_rooms');
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Administração - Gerenciar Salas</title>
</head>
<body>
    <h1>Gerenciar Salas de Bate-Papo</h1>

    <h2>Criar Nova Sala</h2>
    <form method="POST">
        <input type="text" name="room_name" placeholder="Nome da sala" required>
        <button type="submit" name="create_room">Criar Sala</button>
    </form>

    <h2>Salas Existentes</h2>
    <ul>
        <?php foreach ($rooms as $room): ?>
            <li>
                <?php echo htmlspecialchars($room['room_name']); ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                    <button type="submit" name="delete_room" onclick="return confirm('Tem certeza que deseja excluir esta sala?');">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="index.php">Voltar ao Início</a>
</body>
</html>
