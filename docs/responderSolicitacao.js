function responderSolicitacao(amizadeId, status) {
    fetch('responder_amizade.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amizade_id: amizadeId, status: status }) // 'aceito' ou 'rejeitado'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Solicitação atualizada com sucesso!');
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => console.error('Erro ao responder solicitação:', error));
}
