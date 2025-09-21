// js/modules/cadastroPage.js
import { startVideo, captureFrameAsBase64 } from './camera.js';

export function setupCadastro() {
    startVideo();
    const form = document.getElementById('cadastroForm');
    const captureBtns = document.querySelectorAll('.capture-btn');
    const submitBtn = document.getElementById('submitBtn');
    const statusDiv = document.getElementById('formStatus'); // Alterado de statusEl para statusDiv
    const capturedImages = { 1: false, 2: false, 3: false, 4: false, 5: false };

    if (!form) return; // Se não houver formulário, não faz nada

    // Lógica de captura de imagens (existente)
    captureBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetInputId = btn.getAttribute('data-target');
            const imageField = document.getElementById(targetInputId);
            const previewImgId = 'preview_' + targetInputId.slice(-1);
            const previewImg = document.getElementById(previewImgId);
            const imageData = captureFrameAsBase64();

            if (imageData) {
                imageField.value = imageData;
                previewImg.src = imageData;
                previewImg.classList.add('visible');
                btn.style.backgroundColor = '#28a745';
                capturedImages[targetInputId.slice(-1)] = true;

                const allCaptured = Object.values(capturedImages).every(status => status === true);
                if (allCaptured) {
                    submitBtn.disabled = false;
                    // Mensagem de status temporária
                    const tempStatus = document.getElementById('status'); // A <p> original
                    if(tempStatus) tempStatus.innerText = "Todas as 5 fotos foram capturadas! Pode salvar.";
                }
            }
        });
    });

    // --- LÓGICA AJAX ADICIONADA AQUI ---
    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Impede o recarregamento da página

        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Salvando...';
        submitBtn.disabled = true;
        statusDiv.style.display = 'none';

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            statusDiv.innerHTML = result.message;
            statusDiv.className = `status-message ${result.success ? 'success' : 'error'}`;
            statusDiv.style.display = 'block';

            if (result.success) {
                form.reset(); // Limpa o formulário
                // Reseta visualmente os botões e prévias
                captureBtns.forEach(btn => btn.style.backgroundColor = '');
                document.querySelectorAll('.preview-img').forEach(img => {
                    img.src = '';
                    img.classList.remove('visible');
                });
                // Re-desabilita o botão de submit
                submitBtn.disabled = true;
            }
        } catch (error) {
            statusDiv.innerHTML = 'Ocorreu um erro de comunicação. Tente novamente.';
            statusDiv.className = 'status-message error';
            statusDiv.style.display = 'block';
            console.error('Erro no formulário AJAX:', error);
        } finally {
            submitBtn.innerHTML = originalBtnText;
            // Só reabilita o botão se o envio falhar
            if(!statusDiv.classList.contains('success')) {
                submitBtn.disabled = false;
            }
        }
    });
}