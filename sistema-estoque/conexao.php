<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_sistema_gestor', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}
?>
