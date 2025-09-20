document.addEventListener('DOMContentLoaded', () => {
    // --- Lógica de Tema ---
    const themeToggleBtn = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme) {
        document.body.classList.add(currentTheme);
        if (currentTheme === 'dark-mode') {
            themeToggleBtn.innerText = 'Modo Claro';
        } else {
            themeToggleBtn.innerText = 'Modo Escuro';
        }
    } else {
        document.body.classList.add('light-mode'); // Padrão
        themeToggleBtn.innerText = 'Modo Escuro';
        localStorage.setItem('theme', 'light-mode');
    }

    themeToggleBtn.addEventListener('click', () => {
        if (document.body.classList.contains('light-mode')) {
            document.body.classList.remove('light-mode');
            document.body.classList.add('dark-mode');
            themeToggleBtn.innerText = 'Modo Claro';
            localStorage.setItem('theme', 'dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
            document.body.classList.add('light-mode');
            themeToggleBtn.innerText = 'Modo Escuro';
            localStorage.setItem('theme', 'light-mode');
        }
    });
    // --- Fim da Lógica de Tema ---

    const video = document.getElementById('video');
    const isCadastroPage = document.querySelector('#cadastroForm');
    const isReconhecimentoPage = document.getElementById('resultado');

    const startVideo = async () => {
        if (!video) return;
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            video.srcObject = stream;
        } catch (err) {
            console.error("Erro ao acessar a câmera: ", err);
        }
    };

    const captureFrameAsBase64 = () => {
        if (!video || !video.srcObject) return null;
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL('image/jpeg', 0.9);
    };

    const setupCadastro = () => {
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
    };

    const setupReconhecimento = () => {
        startVideo();
        const resultadoEl = document.getElementById('resultado');
        let isProcessing = false;

        setInterval(async () => {
            if (isProcessing || video.paused || video.ended || !video.srcObject) return;
            isProcessing = true;
            const imageData = captureFrameAsBase64();
            
            try {
                const response = await fetch('php/processa_reconhecimento.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ image: imageData })
                });
                const result = await response.json();

                if (result.success) {
                    let alunosHtml = '<ul>';
                    result.alunos.forEach(aluno => {
                        alunosHtml += `<li><strong>${aluno.nome_completo}</strong> (Turma: ${aluno.turma})</li>`;
                    });
                    alunosHtml += '</ul>';

                    resultadoEl.innerHTML = `
                        <p class="success">Responsável Reconhecido!</p>
                        <p><strong>Nome:</strong> ${result.responsavel.nome_completo}</p>
                        <p><strong>Crianças associadas:</strong></p>
                        ${alunosHtml}
                        <p><strong>LIBERAÇÃO AUTORIZADA <u>para o(s) aluno(s) listado(s) acima.</u></strong></p>
                    `;
                } else {
                    resultadoEl.innerText = result.message || "Procurando rosto...";
                    resultadoEl.className = "status-message";
                }
            } catch (error) {
                console.error('Erro:', error);
            }
            isProcessing = false;
        }, 2000);
    };

    if (isCadastroPage) {
        setupCadastro();
    } else if (isReconhecimentoPage) {
        setupReconhecimento();
    }
});
