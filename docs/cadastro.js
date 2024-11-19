document.getElementById('formCadastro').addEventListener('submit', function(e) {
    e.preventDefault(); // Previne o envio padrão do formulário

    // Captura os dados do formulário
    const nome = document.getElementById('input_novousuario').value;
    const email = document.getElementById('input_novoemail').value;
    const senha = document.getElementById('input_novasenha').value;
    const matricula = document.getElementById('input_novamatricula').value;
    const dataNascimento = document.getElementById('input_novadatanascimento').value;

    // Cria o objeto de dados para enviar
    const dados = {
        novousuario: nome,
        novoemail: email,
        novasenha: senha,
        novamatricula: matricula,
        novadatanascimento: dataNascimento
    };

    // Envia os dados para o PHP via fetch (JSON)
    fetch('cadastro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dados) // Envia os dados como JSON
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Usuário cadastrado com sucesso!');
            window.location.href = 'login.html'; // Redireciona para a página de login
        } else {
            alert('Erro ao cadastrar usuário: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao se conectar com o servidor!');
    });
});
