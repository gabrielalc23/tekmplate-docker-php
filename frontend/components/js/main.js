document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const menuLinks = document.querySelectorAll('.sidebar-menu a');
    const dynamicContent = document.getElementById('dynamic-content');
    
    // Rotas válidas
    const validRoutes = [
        'acabamento', 
        'acessorios', 
        'clientes', 
        'fornecedor', 
        'laminacao', 
        'papel'
    ];
    
    // Carrega a página inicial
    const initialRoute = window.location.hash.substring(1);
    loadPage(validRoutes.includes(initialRoute) ? initialRoute : 'acabamento');
    
    // Configura os listeners do menu
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const route = this.getAttribute('href').substring(1);
            navigateTo(route);
        });
    });
    
    // Função para navegar entre páginas
    function navigateTo(route) {
        if (!validRoutes.includes(route)) return;
        
        history.pushState({}, '', `#${route}`);
        loadPage(route);
    }
    
    // Função para carregar páginas
    async function loadPage(route) {
        updateActiveMenu(route);
        
        try {
            const content = await fetchPageContent(route);
            dynamicContent.innerHTML = content;
        } catch (error) {
            dynamicContent.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Erro ao carregar "${route}"</h3>
                    <p>${error.message}</p>
                </div>
            `;
        }
    }
    
    // Atualiza o menu ativo
    function updateActiveMenu(activeRoute) {
        menuLinks.forEach(link => {
            const linkRoute = link.getAttribute('href').substring(1);
            link.classList.toggle('active', linkRoute === activeRoute);
        });
    }
    
    // Carrega conteúdo do servidor
    async function fetchPageContent(route) {
        const response = await fetch(`pages/${route}.html`);
        
        if (!response.ok) {
            throw new Error(`Página não encontrada (${response.status})`);
        }
        
        return await response.text();
    }
    
    // Manipula o botão voltar/avançar
    window.addEventListener('popstate', function() {
        const route = window.location.hash.substring(1);
        if (validRoutes.includes(route)) {
            loadPage(route);
        }
    });
});