document.addEventListener('DOMContentLoaded', function () {
    fetch('postagens.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const postagensContainer = document.getElementById('postagensContainer');
                postagensContainer.innerHTML = ''; // Limpa o conteúdo antigo

                data.postagens.forEach(post => {
                    const postElement = document.createElement('div');
                    postElement.classList.add('post');

                    // Verifica os dados da postagem
                    const postText = post.texto ? `<p>${post.texto}</p>` : '';
                    const postImage = post.imagem ? `<img src="${post.imagem}" alt="Imagem do post">` : '';
                    const postVideo = post.video ? `<video controls><source src="${post.video}" type="video/mp4"></video>` : '';
                    const postDate = new Date(post.data_postagem).toLocaleString('pt-BR');

                    // Cria o layout da postagem com as informações do usuário
                    postElement.innerHTML = `
                        <div class="post-header">
                            <div class="user-info">
                                <img src="${post.foto_usuario}" alt="Foto do usuário" class="user-photo">
                                <div class="user-details">
                                    <h3>${post.nome_usuario}</h3>
                                    <p>Postado em: ${postDate}</p>
                                </div>
                            </div>
                        </div>
                        <div class="post-content">
                            ${postText}
                            ${postImage}
                            ${postVideo}
                        </div>
                    `;

                    postagensContainer.appendChild(postElement);
                });
            } else {
                console.error('Erro ao carregar postagens:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro ao buscar as postagens:', error);
        });
});
