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

// Verificar se são amigos
$stmt = $pdo->prepare('SELECT * FROM friends WHERE (user_id = ? AND friend_id = ? AND status = ?) OR (user_id = ? AND friend_id = ? AND status = ?)');
$stmt->execute([$user_id, $friend_id, 'accepted', $friend_id, $user_id, 'accepted']);
$are_friends = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$are_friends) {
    echo "Você não é amigo(a) deste usuário.";
    exit();
}

// Recuperar informações do perfil do amigo
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$friend_id]);
$friend = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Chat Privado com <?php echo htmlspecialchars($friend['nickname']); ?></title>
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
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadMessages() {
            $.ajax({
                url: 'load_private_messages.php',
                method: 'GET',
                data: {
                    friend_id: '<?php echo $friend_id; ?>'
                },
                success: function(data) {
                    $('#chat-box').html(data);
                }
            });
        }

        $(document).ready(function() {
            // Carregar as mensagens inicialmente
            loadMessages();

            // Atualizar as mensagens a cada 2 segundos
            setInterval(loadMessages, 2000);

            // Descer a rolagem para o final do chat ao carregar
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        });
    </script>
</head>
<body>
    <h1>Chat Privado com <?php echo htmlspecialchars($friend['nickname']); ?></h1>
    <div id="chat-box">
        <!-- Mensagens carregadas via AJAX -->
    </div>
    <form method="POST" action="send_private_message.php?id=<?php echo $friend_id; ?>">
        <input type="text" name="message" placeholder="Digite sua mensagem..." required>
        <button type="submit">Enviar</button>
    </form>
    <a href="index.php">Voltar ao Início</a>
</body>
</html>
