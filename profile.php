<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$profile_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$profile_id) {
    echo "Usuário não encontrado.";
    exit();
}

// Recuperar informações do perfil
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$profile_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    echo "Perfil não encontrado.";
    exit();
}

// Verificar status de amizade
$stmt = $pdo->prepare('SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)');
$stmt->execute([$user_id, $profile_id, $profile_id, $user_id]);
$friendship = $stmt->fetch(PDO::FETCH_ASSOC);

// Adicionar amizade
if (isset($_POST['add_friend'])) {
    if (!$friendship) {
        $stmt = $pdo->prepare('INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $profile_id, 'pending']);
        $success = "Pedido de amizade enviado.";
    } elseif ($friendship['status'] == 'pending' && $friendship['user_id'] == $profile_id) {
        $stmt = $pdo->prepare('UPDATE friends SET status = ? WHERE user_id = ? AND friend_id = ?');
        $stmt->execute(['accepted', $profile_id, $user_id]);
        $success = "Pedido de amizade aceito.";
    }
}

// Excluir amizade
if (isset($_POST['remove_friend'])) {
    if ($friendship) {
        $stmt = $pdo->prepare('DELETE FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)');
        $stmt->execute([$user_id, $profile_id, $profile_id, $user_id]);
        $success = "Amizade removida.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($profile['nickname']); ?></title>
</head>
<body>
    <h1>Perfil de <?php echo htmlspecialchars($profile['nickname']); ?></h1>
    <p>Email: <?php echo htmlspecialchars($profile['email']); ?></p>
    
    <?php if ($friendship): ?>
        <?php if ($friendship['status'] == 'accepted'): ?>
            <p>Você é amigo(a) deste usuário.</p>
            <a href="private_chat.php?id=<?php echo $profile_id; ?>">Conversar no Chat Privado</a>
        <?php elseif ($friendship['status'] == 'pending' && $friendship['user_id'] == $user_id): ?>
            <p>Pedido de amizade pendente.</p>
        <?php elseif ($friendship['status'] == 'pending' && $friendship['user_id'] == $profile_id): ?>
            <p>Pedido de amizade recebido. <form method="POST"><button type="submit" name="add_friend">Aceitar Pedido</button></form></p>
        <?php endif; ?>
        <form method="POST">
            <button type="submit" name="remove_friend">Remover Amigo</button>
        </form>
    <?php else: ?>
        <form method="POST">
            <button type="submit" name="add_friend">Adicionar como Amigo</button>
        </form>
    <?php endif; ?>
    
    <?php if ($friendship && $friendship['status'] == 'accepted'): ?>
        <a href="private_chat.php?id=<?php echo $profile_id; ?>">Conversar no Chat Privado</a>
    <?php endif; ?>

    <a href="index.php">Voltar ao Início</a>
</body>
</html>
