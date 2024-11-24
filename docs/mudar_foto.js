// Função para alterar a foto de perfil
document.getElementById('formFotoPerfil').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('mudar_foto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Foto de perfil alterada com sucesso!');
            document.querySelector('.foto img').src = data.novaFotoPerfil;
        } else {
            alert('Erro ao alterar foto de perfil.');
        }
    })
    .catch(error => {
        console.error('Erro ao enviar a foto de perfil:', error);
        alert('Erro ao enviar a foto de perfil.');
    });
});

// Função para alterar a foto de fundo
document.getElementById('formFotoFundo').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('mudar_foto.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Foto de fundo alterada com sucesso!');
            document.body.style.backgroundImage = `url(${data.novaFotoFundo})`;
        } else {
            alert('Erro ao alterar foto de fundo.');
        }
    })
    .catch(error => {
        console.error('Erro ao enviar a foto de fundo:', error);
        alert('Erro ao enviar a foto de fundo.');
    });
});
