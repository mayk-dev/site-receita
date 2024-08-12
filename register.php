<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nickname = $_POST['nickname'];

    // Validação do apelido
    if (strlen($nickname) < 4 || strlen($nickname) > 9) {
        $error = "O apelido deve ter entre 4 e 9 caracteres.";
    } else {
        // Verificar se o e-mail já está registrado
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Este e-mail já está registrado.";
        } else {
            // Inserir novo usuário no banco de dados
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, nickname, role) VALUES (?, ?, ?, ?, ?)');
            $role = 'volunteer'; // Por padrão, o usuário será registrado como voluntário
            if ($stmt->execute([$username, $email, $password, $nickname, $role])) {
                $success = "Cadastro realizado com sucesso. Faça login.";
            } else {
                $error = "Erro ao registrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Voluntários</title>
</head>
<body>
    <h1>Cadastro</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Nome de usuário" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Senha" required><br>
        <input type="text" name="nickname" placeholder="Apelido" required><br>
        <button type="submit">Cadastrar</button>
    </form>
    <?php
    if (isset($error)) { echo "<p>$error</p>"; }
    if (isset($success)) { echo "<p>$success</p>"; }
    ?>
</body>
</html>
