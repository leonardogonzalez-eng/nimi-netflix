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