<?php
$host = 'localhost';
$dbname = 'mini_netflix';
$username = 'root'; 
$password = '';     

$mensagem_sucesso = "";
$mensagem_erro = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Verifica se veio um aviso de sucesso pela URL
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem_sucesso = "Dados atualizados com sucesso!";
}

// Carrega o ID atual
$id = $_GET['id'] ?? $_POST['id'] ?? '';

if (empty($id)) {
    header("Location: index.php");
    exit;
}

// Busca os dados atuais do filme para o formulário
$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = :id");
$stmt->execute([':id' => $id]);
$filme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$filme) {
    die("Filme não encontrado.");
}

// Processa a alteração quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $imagem_url = trim($_POST['imagem_url'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ano_lancamento = trim($_POST['ano_lancamento'] ?? '');

    if (empty($titulo) || empty($descricao) || empty($imagem_url) || empty($categoria) || empty($ano_lancamento)) {
        $mensagem_erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $sql = "UPDATE filmes SET 
                        titulo = :titulo, 
                        descricao = :descricao, 
                        imagem_url = :imagem_url, 
                        categoria = :categoria, 
                        ano_lancamento = :ano_lancamento 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':imagem_url' => $imagem_url,
                ':categoria' => $categoria,
                ':ano_lancamento' => (int)$ano_lancamento,
                ':id' => (int)$id
            ]);

            /* 
              Redireciona passando o ID do filme e o aviso de sucesso.
              Isso "limpa" o botão atualizar (F5) do navegador.
            */
            header("Location: editar.php?id=" . $id . "&sucesso=1");
            exit;

        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Título - Mini Netflix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Mini Netflix</h1>
        <a href="index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>

    <main class="container-formulario">
        <div class="form-box">
            <h2>Alterar Dados: <?= htmlspecialchars($filme['titulo']) ?></h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="editar.php" method="POST">
                <!-- Campo invisível para enviar o ID do filme que está sendo alterado -->
                <input type="hidden" name="id" value="<?= $filme['id'] ?>">

                <div class="form-grupo">
                    <label for="titulo">Título do Filme/Série</label>
                    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($filme['titulo']) ?>" required>
                </div>

                <div class="form-grupo">
                    <label for="categoria">Categoria / Gênero</label>
                    <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($filme['categoria']) ?>" required>
                </div>

                <div class="form-grupo">
                    <label for="ano_lancamento">Ano de Lançamento</label>
                    <input type="number" id="ano_lancamento" name="ano_lancamento" value="<?= htmlspecialchars($filme['ano_lancamento']) ?>" min="1890" required>
                </div>

                <div class="form-grupo">
                    <label for="imagem_url">URL da Imagem da Capa</label>
                    <input type="url" id="imagem_url" name="imagem_url" value="<?= htmlspecialchars($filme['imagem_url']) ?>" required>
                </div>

                <div class="form-grupo">
                    <label for="descricao">Sinopse / Descrição</label>
                    <textarea id="descricao" name="descricao" rows="5" required><?= htmlspecialchars($filme['descricao']) ?></textarea>
                </div>

                <button type="submit" class="btn-enviar">Salvar Alterações</button>
            </form>
        </div>
    </main>

</body>
</html>