document.getElementById('formLogin').addEventListener('submit', function(e) {
    e.preventDefault(); // Previne o envio padrão do formulário

    // Captura os dados do formulário
    const usuario = document.getElementById('input_usuario').value;
    const senha = document.getElementById('input_senha').value;

    // Cria o objeto de dados para enviar
    const dados = {
        usuario: usuario,
        senha: senha
    };

    // Envia os dados para o PHP via fetch (JSON)
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dados) // Envia os dados como JSON
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Login realizado com sucesso!');
            window.location.href = 'perfil.html'; // Redireciona para a página de perfil ou feed
        } else {
            alert('Erro no login: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao se conectar com o servidor!');
    });
});
