<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if (!empty($id)) {
        $host = 'localhost';
        $dbname = 'mini_netflix';
        $username = 'root'; 
        $password = '';     

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Deleta o registro de forma segura usando Prepared Statements
            $stmt = $pdo->prepare("DELETE FROM filmes WHERE id = :id");
            $stmt->execute([':id' => (int)$id]);

        } catch (PDOException $e) {
            die("Erro ao excluir do banco de dados: " . $e->getMessage());
        }
    }
}

// Redireciona de volta para a página inicial
header("Location: index.php");
exit;