const graphicRoutes = {
    
    // Produção
    'ordens-producao': 'pages/producao/ordens.html',
    'acabamento': 'pages/producao/acabamento.html',
    'laminacao': 'pages/producao/laminacao.html',
    'corte': 'pages/producao/corte.html',
    
    // Materiais
    'papel': 'pages/materiais/papel.html',
    'tinta': 'pages/materiais/tinta.html',
    'acessorios': 'pages/materiais/acessorios.html',
    
    // Gestão
    'clientes': 'pages/gestao/clientes.html',
    'fornecedores': 'pages/gestao/fornecedores.html',
    'financeiro': 'pages/gestao/financeiro.html',
    
    // Relatórios
    'produtivo': 'pages/relatorios/produtividade.html',
    'estoque': 'pages/relatorios/estoque.html'
};

// Função modificada para carregar páginas
async function fetchPageContent(route) {
    if (!graphicRoutes[route]) {
        throw new Error('Seção não encontrada');
    }
    
    try {
        const response = await fetch(graphicRoutes[route]);
        if (!response.ok) throw new Error('Página não encontrada');
        return await response.text();
    } catch (error) {
        return `
            <div class="alert alert-error">
                <i class="fas fa-print"></i>
                <h3>Erro na gráfica: ${error.message}</h3>
                <p>Não foi possível carregar a seção ${route}</p>
            </div>
        `;
    }
}