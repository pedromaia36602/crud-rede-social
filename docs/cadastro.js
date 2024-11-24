document.getElementById('formCadastro').addEventListener('submit', function(e) {
    e.preventDefault(); // Previne o envio padrão do formulário

    // Captura os dados do formulário
    const formData = new FormData(this);

    // Envia os dados para o PHP via fetch
    fetch('cadastro.php', {
        method: 'POST',
        body: formData // Envia os dados no formato esperado pelo PHP
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Usuário cadastrado com sucesso!'); // Exibe o pop-up de sucesso
        } else {
            alert('Erro ao cadastrar usuário: ' + data.message); // Exibe o pop-up de erro
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao se conectar com o servidor!');
    });
});
