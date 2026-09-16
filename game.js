

const areaPacotes = document.getElementById("pacotes");


// =============================
// LISTA DE PACOTES
// =============================

  const listaPacotes = [

    {
        imagem: "assets/caixa 1,10 -12kg.png",
        destino: "caixa"
        
    },

    {
        imagem: "assets/caixa simples.png",
        destino: "saca"
    }

];

function verificarSaca(pacote) {

    const areaSaca = document.getElementById("area-saca");

    const posicaoPacote = pacote.getBoundingClientRect();

    const posicaoSaca = areaSaca.getBoundingClientRect();


    const centroX =
        posicaoPacote.left + posicaoPacote.width / 2;

    const centroY =
        posicaoPacote.top + posicaoPacote.height / 2;


    const dentroDaSaca =

        centroX >= posicaoSaca.left &&
        centroX <= posicaoSaca.right &&

        centroY >= posicaoSaca.top &&
        centroY <= posicaoSaca.bottom;


    if (dentroDaSaca) {

        console.log("PACOTE COLOCADO NA SACA");


        // Faz o pacote diminuir e desaparecer
        pacote.style.transition = "0.3s";

        pacote.style.transform = "scale(0)";

        pacote.style.opacity = "0";


        // Remove definitivamente
        setTimeout(function() {

            pacote.remove();

        }, 300);

    }

}

function verificarCaixa(pacote) {

    const areaCaixa = document.getElementById("area-caixa");

    const posicaoPacote = pacote.getBoundingClientRect();
    const posicaoCaixa = areaCaixa.getBoundingClientRect();

    // Centro do pacote
    const centroX =
        posicaoPacote.left + posicaoPacote.width / 2;

    const centroY =
        posicaoPacote.top + posicaoPacote.height / 2;


    // Verifica se o centro está dentro da caixa
    const dentroDaCaixa =

    posicaoPacote.left >= posicaoCaixa.left &&
    posicaoPacote.right <= posicaoCaixa.right &&
    posicaoPacote.top >= posicaoCaixa.top &&
    posicaoPacote.bottom <= posicaoCaixa.bottom;

    if (dentroDaCaixa) {

    // Marca o pacote como colocado
    pacote.dataset.travado = "true";

    // Muda o cursor
    pacote.style.cursor = "default";

    console.log("Pacote travado na caixa");

}

}

function mostrarResultado(aprovado) {

    const telaResultado =
        document.getElementById("tela-resultado");

    const status =
        document.getElementById("status-resultado");

    const texto =
        document.getElementById("texto-resultado");

    telaResultado.style.display = "flex";


    if (aprovado) {

        status.textContent = "CARGA APROVADA";

        texto.textContent =
            "Unitização concluída corretamente. Carga liberada para expedição.";

    } else {

        status.textContent = "AUDITORIA";

        texto.textContent =
            "Sua caixa foi pega na auditoria.";

    }
}

// =============================
// CRIAR PACOTE
// =============================

function criarPacote() {

    // Sorteia uma posição da lista
    const numeroAleatorio = Math.floor(
        Math.random() * listaPacotes.length
    );

    // Pega o pacote sorteado
    const dadosPacote = listaPacotes[numeroAleatorio];

    // Cria a imagem
    const pacote = document.createElement("img");

    // Define a imagem
    pacote.src = dadosPacote.imagem;

    // Guarda o destino correto
    pacote.dataset.destino = dadosPacote.destino;

    // Adiciona a classe CSS
    pacote.classList.add("pacote");

    // Desativa o arraste padrão
    pacote.draggable = false;

    // Coloca o pacote na tela
    areaPacotes.appendChild(pacote);

    // Ativa seu sistema de arrastar
    ativarMovimento(pacote);
}


// =============================
// ARRASTAR PACOTE
// =============================

function ativarMovimento(pacote) {

    let arrastando = false;

    let criouProximo = false;

    let diferencaX = 0;
    let diferencaY = 0;


    // Quando apertar no pacote
    pacote.addEventListener("pointerdown", function(evento) {

 // Se já foi colocado na caixa,
    // não pode mais ser movimentado
    if (pacote.dataset.travado === "true") {
        return;
    }

    evento.preventDefault();

        arrastando = true;


        // Descobre onde o usuário clicou dentro do pacote
        const posicao = pacote.getBoundingClientRect();

        diferencaX = evento.clientX - posicao.left;
        diferencaY = evento.clientY - posicao.top;


        // O pacote passa a ser posicionado pela tela
        pacote.style.position = "fixed";

        pacote.style.left = posicao.left + "px";
        pacote.style.top = posicao.top + "px";

        pacote.style.zIndex = "1000";


        // Captura mouse/dedo
        pacote.setPointerCapture(evento.pointerId);

    });


    // Enquanto movimentar
    pacote.addEventListener("pointermove", function(evento) {

        if (!arrastando) {
            return;
        }


        // Move o pacote
        pacote.style.left =
            (evento.clientX - diferencaX) + "px";

        pacote.style.top =
            (evento.clientY - diferencaY) + "px";


        // Quando começar a arrastar,
        // cria o próximo pacote
        if (!criouProximo) {

            criouProximo = true;

            criarPacote();
        }

    });

// Quando soltar
pacote.addEventListener("pointerup", function(evento) {

    arrastando = false;

    pacote.releasePointerCapture(evento.pointerId);

    verificarSaca(pacote);

    verificarCaixa(pacote);

});

}

// =============================
// PRIMEIRO PACOTE
// =============================

criarPacote();

function auditarExpedicao() {

    const areaCaixa = document.getElementById("area-caixa");
    const caixa = areaCaixa.getBoundingClientRect();

    const pacotesNaCaixa =
        document.querySelectorAll('.pacote[data-local="caixa"]');

    let caixaValida = true;

    // Verifica se todos os pacotes colocados na caixa
    // realmente pertencem à caixa
    pacotesNaCaixa.forEach(function(pacote) {

        if (pacote.dataset.destino !== "caixa") {
            caixaValida = false;
        }

    });


    // ==============================
    // VERIFICAR OCUPAÇÃO ATÉ O TETO
    // ==============================

    let chegouNoTeto = false;

    pacotesNaCaixa.forEach(function(pacote) {

        const posicao = pacote.getBoundingClientRect();

        // tolerância de 10px
        if (posicao.top <= caixa.top + 10) {
            chegouNoTeto = true;
        }

    });


    // ==============================
    // RESULTADO DA AUDITORIA
    // ==============================

    if (
    caixaValida &&
    pacotesInvalidos === 0 &&
    chegouNoTeto
) {

    mostrarResultado(true);

} else {

    mostrarResultado(false);

}

document
    .getElementById("jogar-novamente")
    .addEventListener("click", function() {

        location.reload();

    });
}