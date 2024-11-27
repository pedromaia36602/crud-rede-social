document.getElementById('formLogin').addEventListener('submit', function(e) {
    e.preventDefault(); // Previne o envio padrão do formulário

    // Captura os dados do formulário
    const formData = new FormData(this);

    // Envia os dados para o PHP via fetch
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Login realizado com sucesso!');
            window.location.href = 'feed.php'; // Redireciona para a página de perfil do usuário
        } else {
            alert('Erro no login: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao se conectar com o servidor!');
    });
});
