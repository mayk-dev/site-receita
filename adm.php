<?php
include('db.php');

$username = 'Admin';
$email = 'mayk.cel16@gmail.com';
$password = password_hash('123456', PASSWORD_DEFAULT);
$role = 'admin';

// Verificar se o administrador já existe
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo "Administrador já cadastrado.";
} else {
    // Inserir o administrador no banco de dados
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$username, $email, $password, $role])) {
        echo "Administrador criado com sucesso.";
    } else {
        echo "Erro ao criar administrador.";
    }
}
?>
