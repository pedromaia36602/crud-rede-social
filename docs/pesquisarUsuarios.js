function pesquisarUsuarios() {
    console.log('Função pesquisarUsuarios chamada.');

    const nome = document.getElementById('nomePesquisa').value;
    if (!nome) {
        alert('Por favor, digite um nome para pesquisar.');
        return;
    }

    console.log(`Pesquisando por nome: ${nome}`);

    fetch(`pesquisar_usuarios.php?nome=${encodeURIComponent(nome)}`)
        .then(response => response.json())
        .then(data => {
            console.log('Dados recebidos:', data);

            const resultados = document.getElementById('resultados');
            resultados.innerHTML = ''; // Limpa a lista antes de renderizar

            if (data.status === 'success' && data.usuarios.length > 0) {
                data.usuarios.forEach(usuario => {
                    const li = document.createElement('li');
                    li.textContent = `${usuario.nome} (ID: ${usuario.id})`;

                    const button = document.createElement('button');
                    button.textContent = 'Adicionar';
                    button.onclick = () => enviarSolicitacao(usuario.id); // Passa apenas o ID do usuário alvo
                    li.appendChild(button);

                    resultados.appendChild(li);
                });
            } else {
                resultados.innerHTML = '<li>Nenhum usuário encontrado.</li>';
            }
        })
        .catch(error => console.error('Erro ao buscar usuários:', error));
}
