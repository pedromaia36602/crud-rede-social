function enviarSolicitacao(amigoId) {
    const usuarioId = getUsuarioLogadoId(); // Agora a função retorna o ID do usuário logado

    if (!usuarioId || !amigoId) {
        console.error('Erro: IDs inválidos. Verifique se "usuarioId" e "amigoId" estão definidos.');
        return;
    }

    console.log(`Enviando solicitação de amizade de ${usuarioId} para ${amigoId}`);

    fetch('enviar_amizade.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ usuario_alvo: amigoId }) 
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Solicitação enviada com sucesso!');
        } else {
            console.error('Erro ao enviar solicitação:', data.message);
        }
    })
    .catch(error => console.error('Erro na solicitação:', error));
}
