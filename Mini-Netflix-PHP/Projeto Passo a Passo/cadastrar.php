<?php
// Inicializa variáveis para mensagens de feedback
$mensagem_sucesso = "";
$mensagem_erro = "";

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurações do Banco de Dados
    $host = 'localhost';
    $dbname = 'mini_netflix';
    $username = 'root'; 
    $password = '';     

    // Captura e limpa os dados enviados para remover espaços extras
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $imagem_url = trim($_POST['imagem_url'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ano_lancamento = trim($_POST['ano_lancamento'] ?? '');

    // Validação básica: garante que nenhum campo obrigatório ficou vazio
    if (empty($titulo) || empty($descricao) || empty($imagem_url) || empty($categoria) || empty($ano_lancamento)) {
        $mensagem_erro = "Por favor, preencha todos os campos do formulário.";
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepara a query SQL com placeholders (:titulo, :descricao, etc.)
            // Isso impede ataques de SQL Injection, pois o PDO trata os dados separadamente da estrutura da query
            $sql = "INSERT INTO filmes (titulo, descricao, imagem_url, categoria, ano_lancamento) 
                    VALUES (:titulo, :descricao, :imagem_url, :categoria, :ano_lancamento)";
            
            $stmt = $pdo->prepare($sql);

            // Vincula os valores capturados aos placeholders correspondentes
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':imagem_url' => $imagem_url,
                ':categoria' => $categoria,
                ':ano_lancamento' => (int)$ano_lancamento // Converte explicitamente para inteiro
            ]);

            $mensagem_sucesso = "Filme/Série cadastrado com sucesso!";
            
            // Limpa os campos após o cadastro bem-sucedido
            $titulo = $descricao = $imagem_url = $categoria = $ano_lancamento = "";

        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao salvar no banco de dados: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Título - Mini Netflix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Mini Netflix</h1>
        <a href="index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>

    <main class="container-formulario">
        <div class="form-box">
            <h2>Cadastrar Novo Filme ou Série</h2>

            <!-- Exibição de alertas de feedback para o usuário -->
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="cadastrar.php" method="POST">
                <div class="form-grupo">
                    <label for="titulo">Título do Filme/Série</label>
                    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($titulo ?? '') ?>" placeholder="Ex: Stranger Things" required>
                </div>

                <div class="form-grupo">
                    <label for="categoria">Categoria / Gênero</label>
                    <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($categoria ?? '') ?>" placeholder="Ex: Ficção Científica, Ação, Drama" required>
                </div>

                <div class="form-grupo">
                    <label for="ano_lancamento">Ano de Lançamento</label>
                    <input type="number" id="ano_lancamento" name="ano_lancamento" value="<?= htmlspecialchars($ano_lancamento ?? '') ?>" min="1890" max="<?= date('Y') + 5 ?>" placeholder="Ex: 2026" required>
                </div>

                <div class="form-grupo">
                    <label for="imagem_url">URL da Imagem da Capa</label>
                    <input type="url" id="imagem_url" name="imagem_url" value="<?= htmlspecialchars($imagem_url ?? '') ?>" placeholder="https://exemplo.com/imagem.jpg" required>
                </div>

                <div class="form-grupo">
                    <label for="descricao">Sinopse / Descrição</label>
                    <textarea id="descricao" name="descricao" rows="5" placeholder="Digite um resumo detalhado da obra..." required><?= htmlspecialchars($descricao ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-enviar">Salvar Título</button>
            </form>
        </div>
    </main>

</body>
</html>