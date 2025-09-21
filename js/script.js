import { setupCadastro } from './modules/cadastroPage.js';
import { setupReconhecimento } from './modules/reconhecimentoPage.js';
import { setupResponsiveSidebar } from './modules/responsive.js';
import { setupAjaxForm } from './modules/ajaxForms.js';

document.addEventListener('DOMContentLoaded', () => {
    
    const themeToggleBtn = document.getElementById('themeToggle');
    if (themeToggleBtn) {
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            document.body.classList.add(currentTheme);
            themeToggleBtn.innerText = currentTheme === 'dark-mode' ? 'Modo Claro' : 'Modo Escuro';
        } else {
            document.body.classList.add('light-mode');
            themeToggleBtn.innerText = 'Modo Escuro';
            localStorage.setItem('theme', 'light-mode');
        }

        themeToggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            document.body.classList.toggle('light-mode');
            const newTheme = document.body.classList.contains('dark-mode') ? 'dark-mode' : 'light-mode';
            themeToggleBtn.innerText = newTheme === 'dark-mode' ? 'Modo Claro' : 'Modo Escuro';
            localStorage.setItem('theme', newTheme);
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