<?php
// Inicializa variáveis para mensagens de feedback
$mensagem_sucesso = "";
$mensagem_erro = "";

// Se o cadastro deu certo e fomos redirecionados, exibe a mensagem de sucesso
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem_sucesso = "Filme/Série cadastrado com sucesso!";
}

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $dbname = 'mini_netflix';
    $username = 'root'; 
    $password = '';     

    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $imagem_url = trim($_POST['imagem_url'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ano_lancamento = trim($_POST['ano_lancamento'] ?? '');

    if (empty($titulo) || empty($descricao) || empty($imagem_url) || empty($categoria) || empty($ano_lancamento)) {
        $mensagem_erro = "Por favor, preencha todos os campos do formulário.";
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO filmes (titulo, descricao, imagem_url, categoria, ano_lancamento) 
                    VALUES (:titulo, :descricao, :imagem_url, :categoria, :ano_lancamento)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':imagem_url' => $imagem_url,
                ':categoria' => $categoria,
                ':ano_lancamento' => (int)$ano_lancamento
            ]);

            /* 
              AQUI ESTÁ A MÁGICA:
              Redireciona para a mesma página limpando os dados do POST.
              O exit impede que o resto do script abaixo continue rodando.
            */
            header("Location: cadastrar.php?sucesso=1");
            exit;

        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao salvar no banco de dados: " . $e->getMessage();
        }
    }
}
?>