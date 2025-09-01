<?php
session_start();
require_once 'db.php';

function is_logged_in() {
    return isset($_SESSION['usuario_id']);
}

function login($usuario, $senha) {
    global $conn;
    $stmt = $conn->prepare('SELECT id, senha FROM usuarios WHERE usuario = ?');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($senha, $hash)) {
            $_SESSION['usuario_id'] = $id;
            return true;
        }
    }
    return false;
}

function logout() {
    session_destroy();
}
?>

