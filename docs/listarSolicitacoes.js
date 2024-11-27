document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM totalmente carregado');
    listarSolicitacoes();
});

function listarSolicitacoes() {
    fetch('listar_solicitacoes.php')
    .then(response => {
        console.log("Resposta recebida:", response);
        return response.json();
    })
        .then(data => {
            console.log("Dados recebidos:", data);
            const lista = document.getElementById('solicitacoesLista');

            if (!lista) {
                console.error("Elemento 'solicitacoesLista' não encontrado.");
                return;
            }

            lista.innerHTML = ''; // Limpa a lista antes de renderizar

            if (data.status === 'success') {
                const solicitacoes = data.data;

                if (solicitacoes.length > 0) {
                    solicitacoes.forEach(solicitacao => {
                        const li = document.createElement('li');
                        li.setAttribute('data-amizade-id', solicitacao.amizade_id); // Adicionado
                        li.innerHTML = `
                            <div>
                                <a href="perfilusuario.php?id=${solicitacao.usuario_id}" style="text-decoration: none; color: inherit;">
                                    <img src="${solicitacao.foto_perfil || 'images/usuario_default.jpg'}" 
                                         alt="${solicitacao.nome}" 
                                         class="user-photo">
                                    <span>${solicitacao.nome}</span>
                                </a>
                                <button onclick="responderSolicitacao(${solicitacao.amizade_id}, 'aceito')">Aceitar</button>
                                <button onclick="responderSolicitacao(${solicitacao.amizade_id}, 'rejeitado')">Recusar</button>
                            </div>
                        `;
                        lista.appendChild(li);
                    });

                } else {
                    lista.innerHTML = '<li>Nenhuma solicitação pendente.</li>';
                }
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => console.error('Erro ao listar solicitações:', error));
}
