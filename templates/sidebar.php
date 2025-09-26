<button id="sidebarToggleBtn" class="sidebar-toggle">☰</button>
<div class="sidebar">
    <div class="theme-switcher">
    <button id="themeToggle" class="btn-theme-toggle">
            <div class="bolaa"></div>
        </button>
            </div>
    <h2>Segurança Facial</h2>
    <p>Bem-vinda, <?php echo htmlspecialchars($nome_instituicao ?? 'Usuário'); ?></p>
    <ul>
        <li><a href="painel.php">Início</a></li>
        <li><a href="../pages/cadastro_aluno.php">Cadastrar Aluno</a></li>
        <li><a href="../pages/gerenciar_alunos.php">Gerenciar Alunos</a></li>
        <li><a href="../pages/cadastro_responsavel.php">Cadastrar Responsável</a></li>
        <li><a href="../pages/gerenciar_responsaveis.php">Gerenciar Responsáveis</a></li>
        <li><a href="../pages/reconhecimento.php">Realizar Reconhecimento</a></li>
        <li><a href="../pages/historico.php">Histórico de Saídas</a></li>
        <li><a href="../logout.php">Sair</a></li>
    </ul>
</div>