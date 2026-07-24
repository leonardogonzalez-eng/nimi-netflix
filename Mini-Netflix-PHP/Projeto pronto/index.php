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
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Mini Netflix</h1>
        <!-- Botão adicionado para ir ao formulário -->
        <a href="cadastrar.php" class="btn-navegacao">+ Cadastrar Título</a>
    </header>

    <main class="container">
        <h2 class="section-title">Adicionados Recentemente</h2>

        <div class="filmes-grid">
            <?php if (count($filmes) > 0): ?>
                <!-- ATENÇÃO: Adicionamos atributos data-* para passar as informações completas para o JavaScript -->
                <?php foreach ($filmes as $filme): ?>
                    <div class="filme-card" 
                        data-titulo="<?= htmlspecialchars($filme['titulo']) ?>"
                        data-descricao="<?= htmlspecialchars($filme['descricao']) ?>"
                        data-imagem="<?= htmlspecialchars($filme['imagem_url']) ?>"
                        data-categoria="<?= htmlspecialchars($filme['categoria']) ?>"
                        data-ano="<?= htmlspecialchars($filme['ano_lancamento']) ?>">
                        
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
                            
                            <!-- NOVA SEÇÃO: Botões de Ação do CRUD -->
                            <div class="card-acoes" onclick="event.stopPropagation();">
                                <a href="editar.php?id=<?= $filme['id'] ?>" class="btn-acao btn-editar">Alterar</a>
                                
                                <form action="excluir.php" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este título?');">
                                    <input type="hidden" name="id" value="<?= $filme['id'] ?>">
                                    <button type="submit" class="btn-acao btn-excluir">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum filme cadastrado no momento.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- ESTRUTURA DO MODAL (Inicia escondido pelo CSS) -->
    <div class="modal-overlay" id="modalFilme">
        <div class="modal-content">
            <button class="btn-fechar" id="btnFecharModal">&times;</button>
            <img class="modal-banner" id="modalImagem" src="" alt="Banner do Filme">
            <div class="modal-body">
                <h2 class="modal-titulo" id="modalTitulo">Título do Filme</h2>
                <div class="modal-meta">
                    <span class="categoria" id="modalCategoria">Categoria</span>
                    <span id="modalAno">0000</span>
                </div>
                <p class="modal-descricao" id="modalDescricao">Descrição completa aqui...</p>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT PARA CONTROLAR O MODAL -->
    <script>
        const cards = document.querySelectorAll('.filme-card');
        const modal = document.getElementById('modalFilme');
        const btnFechar = document.getElementById('btnFecharModal');

        // Elementos internos do modal que vamos preencher dinamicamente
        const modalTitulo = document.getElementById('modalTitulo');
        const modalImagem = document.getElementById('modalImagem');
        const modalCategoria = document.getElementById('modalCategoria');
        const modalAno = document.getElementById('modalAno');
        const modalDescricao = document.getElementById('modalDescricao');

        // Adiciona evento de clique em cada card de filme
        cards.forEach(card => {
            card.addEventListener('click', () => {
                // Captura os dados salvos nos atributos 'data-*' do card clicado
                const titulo = card.getAttribute('data-titulo');
                const descricao = card.getAttribute('data-descricao');
                const imagem = card.getAttribute('data-imagem');
                const categoria = card.getAttribute('data-categoria');
                const ano = card.getAttribute('data-ano');

                // Injeta os dados capturados dentro do modal
                modalTitulo.textContent = titulo;
                modalImagem.src = imagem;
                modalImagem.alt = `Banner do filme ${titulo}`;
                modalCategoria.textContent = categoria;
                modalAno.textContent = ano;
                modalDescricao.textContent = descricao;

                // Abre o modal adicionando a classe CSS 'active'
                modal.classList.add('active');
            });
        });

        // Função para fechar o modal
        function fecharModal() {
            modal.classList.remove('active');
        }

        // Fecha ao clicar no botão X
        btnFechar.addEventListener('click', fecharModal);

        // Fecha se o usuário clicar na área escura (fora da caixa do modal)
        modal.addEventListener('click', (evento) => {
            if (evento.target === modal) {
                fecharModal();
            }
        });
    </script>
</body>
</html>