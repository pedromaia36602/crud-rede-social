function responderSolicitacao(amizadeId, status) {
    fetch('responder_amizade.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amizade_id: amizadeId, status })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Remove solicitação da lista
                const solicitacaoItem = document.querySelector(`[data-amizade-id="${amizadeId}"]`);
                if (solicitacaoItem) solicitacaoItem.remove();

                if (status === 'aceito') {
                    // Adiciona novo amigo à lista
                    const amigosLista = document.getElementById('amigosLista');
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <a href="perfilusuario.php?id=${data.amigo_id}" style="text-decoration: none; color: inherit;">
                            <img src="${data.foto_perfil || 'images/usuario_default.jpg'}" 
                                 alt="${data.nome}" 
                                 class="user-photo">
                            <span>${data.nome}</span>
                        </a>
                    `;
                    amigosLista.appendChild(li);
                }
            } else {
                alert('Erro ao responder solicitação: ' + data.message);
            }
        })
        .catch(error => console.error('Erro ao responder solicitação:', error));
}
