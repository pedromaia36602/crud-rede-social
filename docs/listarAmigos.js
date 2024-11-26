function listarAmigos(usuarioId) {
    console.log(`Chamando listarAmigos com usuarioId: ${usuarioId}`);

    fetch(`listar_amigos.php?usuario_id=${usuarioId}`)
        .then(response => {
            console.log('Resposta do servidor:', response);
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);

            const listaAmigos = document.getElementById('lista-amigos');
            listaAmigos.innerHTML = ''; // Limpa a lista antes de renderizar

            if (data.status === 'success' && data.amigos.length > 0) {
                data.amigos.forEach(amigo => {
                    const li = document.createElement('li');
                    li.textContent = `${amigo.nome} (ID: ${amigo.id})`;
                    listaAmigos.appendChild(li);
                });
            } else {
                listaAmigos.innerHTML = '<li>Nenhum amigo encontrado.</li>';
            }
        })
        .catch(error => console.error('Erro ao listar amigos:', error));
}
