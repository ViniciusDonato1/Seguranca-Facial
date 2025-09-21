// js/modules/ajaxForms.js

export function setupAjaxForm(formId, statusDivId) {
    const form = document.getElementById(formId);
    const statusDiv = document.getElementById(statusDivId);
    const submitBtn = form.querySelector('button[type="submit"]');

    if (!form || !statusDiv || !submitBtn) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Impede o recarregamento da página

        // Mostra feedback de carregamento
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Salvando...';
        submitBtn.disabled = true;
        statusDiv.style.display = 'none';

        try {
            // Pega os dados do formulário
            const formData = new FormData(form);

            // Envia os dados para o script PHP definido no 'action' do form
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            // Pega a resposta JSON do PHP
            const result = await response.json();

            // Mostra a mensagem de sucesso ou erro
            statusDiv.innerHTML = result.message;
            statusDiv.className = `status-message ${result.success ? 'success' : 'error'}`;
            statusDiv.style.display = 'block';

            if (result.success) {
                form.reset(); // Limpa o formulário se o cadastro deu certo
            }

        } catch (error) {
            // Trata erros de rede ou JSON inválido
            statusDiv.innerHTML = 'Ocorreu um erro de comunicação. Tente novamente.';
            statusDiv.className = 'status-message error';
            statusDiv.style.display = 'block';
            console.error('Erro no formulário AJAX:', error);
        } finally {
            // Restaura o botão ao estado original
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });
}