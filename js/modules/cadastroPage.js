import { startVideo, captureFrameAsBase64 } from './camera.js';


export function setupCadastro() {
    startVideo();

    const captureBtns = document.querySelectorAll('.capture-btn');
    const submitBtn = document.getElementById('submitBtn');
    const statusEl = document.getElementById('status');
    const capturedImages = { 1: false, 2: false, 3: false, 4: false, 5: false };

    if (submitBtn.innerText === "Salvar Alterações") {
        submitBtn.disabled = false;
    }

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
                    statusEl.innerText = "Todas as 5 fotos foram capturadas! Pode salvar.";
                    statusEl.className = "status-message success";
                } else {
                    const remaining = Object.values(capturedImages).filter(s => !s).length;
                    statusEl.innerText = `Falta(m) ${remaining} foto(s) para capturar.`;
                    statusEl.className = "status-message";
                }
            }
        });
    });
}