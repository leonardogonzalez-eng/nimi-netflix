<?php
// Configurações do Banco de Dados
$host = 'localhost';
$dbname = 'mini_netflix';
$username = 'root'; 
$password = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, titulo, descricao, imagem_url, categoria, ano_lancamento FROM filmes ORDER BY id DESC");
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Netflix</title>
    <!-- Vinculando o arquivo CSS externo -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Mini Netflix</h1>
    </header>

    <main class="container">
        <h2 class="section-title">Adicionados Recentemente</h2>

        <div class="filmes-grid">
            <?php if (count($filmes) > 0): ?>
                <?php foreach ($filmes as $filme): ?>
                    <div class="filme-card">
                        <img class="filme-capa" src="<?= htmlspecialchars($filme['imagem_url']) ?>" alt="Capa de <?= htmlspecialchars($filme['titulo']) ?>">
                        <div class="filme-info">
                            <h3 class="filme-titulo" title="<?= htmlspecialchars($filme['titulo']) ?>">
                                <?= htmlspecialchars($filme['titulo']) ?>
                            </h3>
                            <div class="filme-meta">
                                <span class="categoria"><?= htmlspecialchars($filme['categoria']) ?></span>
                                <span><?= htmlspecialchars($filme['ano_lancamento']) ?></span>
                            </div>
                            <p class="filme-descricao">
                                <?= htmlspecialchars($filme['descricao']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum filme cadastrado no momento.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>