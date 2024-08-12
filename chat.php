<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$room_id = isset($_GET['room_id']) ? $_GET['room_id'] : 1;

// Recuperar a sala atual
$stmt = $pdo->prepare('SELECT room_name FROM chat_rooms WHERE id = ?');
$stmt->execute([$room_id]);
$room_name = $stmt->fetchColumn();

// Recuperar mensagens da sala selecionada
$stmt = $pdo->prepare('SELECT chats.*, users.nickname FROM chats JOIN users ON chats.user_id = users.id WHERE chats.room_id = ? ORDER BY chats.timestamp ASC');
$stmt->execute([$room_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Chat - <?php echo htmlspecialchars($room_name); ?></title>
    <style>
        #chat-box {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 10px;
        }
        .message strong {
            color: #007BFF;
        }
        .message a {
            color: #007BFF;
            text-decoration: none;
        }
        .message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<script>
 fetch('send_message.php?room_id=' + room_id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
        	console.log(data)
            })
            .catch(error => {
                console.error('Erro:', error);
            });
            
</script>
    <h1><?php echo htmlspecialchars($room_name); ?> - Chat</h1>
    <div id="chat-box">
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <strong>
                    <a href="profile.php?id=<?php echo $message['user_id']; ?>">
                        <?php echo htmlspecialchars($message['nickname']); ?>
                    </a>:
                </strong>
                <?php echo htmlspecialchars($message['message']); ?>
                <span style="float: right;"><?php echo $message['timestamp']; ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <form id="chat-form" method="POST" action="send_message.php?room_id=<?php echo $room_id; ?>">
        <input type="text" name="message" placeholder="Digite sua mensagem..." required>
        <button type="submit">Enviar</button>
    </form>
    <button onclick={handleFormSubmit}> teste</button>
    <a href="index.php">Voltar ao Início</a>
</body>


</html>
