import { setupCadastro } from './modules/cadastroPage.js';
import { setupReconhecimento } from './modules/reconhecimentoPage.js';
import { setupResponsiveSidebar } from './modules/responsive.js';
import { setupAjaxForm } from './modules/ajaxForms.js';

document.addEventListener('DOMContentLoaded', () => {
    
    const themeToggleBtn = document.getElementById('themeToggle');

    const applyTheme = () => {
        const currentTheme = localStorage.getItem('theme') || 'light-mode';
        document.body.className = '';
        document.body.classList.add(currentTheme);
    
    
     if (themeToggleBtn) {
            if (currentTheme === 'dark-mode') {
                themeToggleBtn.classList.add('active')
            } else {
                themeToggleBtn.classList.remove('active')
            }
        }
    };

    applyTheme();

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const isDarkMode = document.body.classList.contains('dark-mode');
            const newTheme = isDarkMode ? 'light-mode' : 'dark-mode';
            localStorage.setItem('theme', newTheme);
            applyTheme(); 
        });
    }

     setupResponsiveSidebar();



    const isCadastroPage = document.querySelector('#cadastroForm');
    const isReconhecimentoPage = document.getElementById('resultado');
    const formCadastroAluno = document.getElementById('formCadastroAluno');
    const formEditaAluno = document.getElementById('formEditaAluno');
    const formEditaResponsavel = document.getElementById('formEditaResponsavel');

    if (isCadastroPage) {
        setupCadastro();
    } else if (isReconhecimentoPage) {
        setupReconhecimento();
    }

    if (formCadastroAluno) {
        setupAjaxForm('formCadastroAluno', 'formStatus');
    }

    if (formEditaAluno) {
        setupAjaxForm('formEditaAluno', 'formStatus');
    }
    if (formEditaResponsavel) {
        setupAjaxForm('formEditaResponsavel', 'formStatus');
    }
});