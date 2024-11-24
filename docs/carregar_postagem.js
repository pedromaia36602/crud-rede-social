document.getElementById('formPostagem').addEventListener('submit', async function (event) {
    event.preventDefault(); // Evita o recarregamento da página

    const formData = new FormData(this);

    try {
        const response = await fetch('salvar_postagem.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();

        if (result.success) {
            alert('Postagem salva com sucesso!');
            carregarPostagens(); // Atualiza a lista de postagens dinamicamente
        } else {
            alert('Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro ao enviar a postagem:', error);
        alert('Erro ao processar sua solicitação.');
    }
});

async function carregarPostagens() {
    try {
        const response = await fetch('postagens.php');
        const data = await response.json();

        if (data.success) {
            const postagensContainer = document.getElementById('postagensContainer');
            postagensContainer.innerHTML = ''; // Limpa as postagens anteriores

            data.postagens.forEach(post => {
                const postElement = document.createElement('div');
                postElement.classList.add('post');

                const postText = post.texto ? `<p>${post.texto}</p>` : '';
                const postImage = post.imagem ? `<img src="${post.imagem}" alt="Imagem do post">` : '';

                postElement.innerHTML = `
                    <div class="post-header">
                        <h3>Postado em: ${new Date(post.data_postagem).toLocaleString()}</h3>
                    </div>
                    ${postText}
                    ${postImage}
                `;

                postagensContainer.appendChild(postElement);
            });
        } else {
            console.log('Erro ao carregar postagens:', data.message);
        }
    } catch (error) {
        console.error('Erro ao buscar as postagens:', error);
    }
}

// Carrega as postagens ao abrir a página
document.addEventListener('DOMContentLoaded', carregarPostagens);