<div class="sidebar">
    <h2>Segurança Facial</h2>
    <p>Bem-vinda, <?php echo htmlspecialchars($nome_instituicao ?? 'Usuário'); ?></p>
    <ul>
        <li><a href="painel.php">Início</a></li>
        <li><a href="cadastro_aluno.php">Cadastrar Aluno</a></li>
        <li><a href="gerenciar_alunos.php">Gerenciar Alunos</a></li>
        <li><a href="cadastro_responsavel.php">Cadastrar Responsável</a></li>
        <li><a href="gerenciar_responsaveis.php">Gerenciar Responsáveis</a></li>
        <li><a href="reconhecimento.php">Realizar Reconhecimento</a></li>
        <li><a href="historico.php">Histórico de Saídas</a></li>
        <li><a href="logout.php">Sair</a></li>
        <li class="theme-switcher">
            <button id="themeToggle" class="btn-theme-toggle">
                Modo Escuro
            </button>
        </li>
    </ul>
</div>