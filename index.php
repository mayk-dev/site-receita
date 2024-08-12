<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Anonymous Chat </title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>CHATTTTTS</h1>
    <p>Escolha uma sala de bate-papo para iniciar uma conversa.</p>

    <ul>
        <?php
        $stmt = $pdo->query('SELECT * FROM chat_rooms');
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rooms as $room):
        ?>
            <li><a href="chat.php?room_id=<?php echo $room['id']; ?>"><?php echo htmlspecialchars($room['room_name']); ?></a></li>
        <?php endforeach; ?>
    </ul>

    <a href="login.php">Entrar</a> |
    <a href="register.php">Register</a> 
    <a href="profile.php">Perfil</a>|


    <?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Recuperar lista de usuários ou amigos
$stmt = $pdo->prepare('SELECT * FROM users WHERE id != ?');
$stmt->execute([$user_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
</head>
<body>
    <h1>Lista de Usuários</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <a href="profile.php?id=<?php echo $user['id']; ?>">
                    <?php echo htmlspecialchars($user['nickname']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Voltar ao Início</a>
</body>
</html>

</body>
</html>
