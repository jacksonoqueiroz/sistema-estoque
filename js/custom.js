// Filtro de Busca em Tempo Real
document.getElementById('busca').addEventListener('keyup', function() {
    var filtro = this.value.toLowerCase();
    var linhas = document.querySelectorAll('tbody tr');

    linhas.forEach(function(linha) {
        var texto = linha.innerText.toLowerCase();
        linha.style.display = texto.includes(filtro) ? '' : 'none';
    });
});

//Filtro em Ajax
function buscarItens(query = '') {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'buscar_itens.php?query=' + query, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById('tabela-itens').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Atualizar conforme digita
document.getElementById('busca').addEventListener('keyup', function() {
    buscarItens(this.value);
});

// Carregar todos ao entrar na página
window.onload = function() {
    buscarItens();
};



