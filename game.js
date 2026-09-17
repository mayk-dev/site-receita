let totalSaca = 0;
let totalTratativa = 0;

const areaPacotes = document.getElementById("pacotes");


// =============================
// LISTA DE PACOTES
// =============================

  const listaPacotes = [
{
        imagem: "assets/caixa violada.png",
        destino: "tratativa"
    },
{
        imagem: "assets/amassado.png",
        destino: "tratativa"
    },

    {
        imagem: "assets/pacotinhob.png",
        destino: "saca"
    },

    {
        imagem: "assets/caixa 1,10 -12kg.png",
        destino: "caixa"
        
    },

    {
        imagem: "assets/caixa simples.png",
        destino: "saca"
    },
    {
        imagem: "assets/dinheiro.png",
        destino: "tratativa"
    },
    {
        imagem: "assets/fragil.png",
        destino: "caixa"
        
    },

];

function verificarTratativa(pacote) {

    const gaiola =
        document.getElementById("gaiolatratativa");

    const posicaoPacote =
        pacote.getBoundingClientRect();

    const posicaoGaiola =
        gaiola.getBoundingClientRect();


    const centroX =
        posicaoPacote.left +
        posicaoPacote.width / 2;

    const centroY =
        posicaoPacote.top +
        posicaoPacote.height / 2;


    const dentroDaTratativa =
        centroX >= posicaoGaiola.left &&
        centroX <= posicaoGaiola.right &&
        centroY >= posicaoGaiola.top &&
        centroY <= posicaoGaiola.bottom;


    if (
        dentroDaTratativa &&
        pacote.dataset.destino === "tratativa"
    ) {
        totalTratativa++;
        console.log("PACOTE CORRETO NA TRATATIVA");

        pacote.style.transition = "0.3s";

        pacote.style.transform =
            "scale(0) rotate(20deg)";

        pacote.style.opacity = "0";


        setTimeout(function() {

            pacote.remove();

        }, 300);

    }

}

function verificarSaca(pacote) {

    const saca =
        document.getElementById("saca");

    const posicaoPacote =
        pacote.getBoundingClientRect();

    const posicaoSaca =
        saca.getBoundingClientRect();


    const centroX =
        posicaoPacote.left +
        posicaoPacote.width / 2;

    const centroY =
        posicaoPacote.top +
        posicaoPacote.height / 2;


    const dentroDaSaca =
        centroX >= posicaoSaca.left &&
        centroX <= posicaoSaca.right &&
        centroY >= posicaoSaca.top &&
        centroY <= posicaoSaca.bottom;


    if (
        dentroDaSaca &&
        pacote.dataset.destino === "saca"
    ) {

        totalSaca++;

        console.log("PACOTE CORRETO NA SACA");

        pacote.style.transition = "0.3s";

        pacote.style.transform =
            "scale(0) rotate(20deg)";

        pacote.style.opacity = "0";


        setTimeout(function() {

            pacote.remove();

        }, 300);

    }


}

function verificarCaixa(pacote) {

    const caixa =
        document.getElementById("caixa");

    const posicaoPacote =
        pacote.getBoundingClientRect();

    const posicaoCaixa =
        caixa.getBoundingClientRect();


    const dentroDaCaixa =

        posicaoPacote.left >= posicaoCaixa.left &&
        posicaoPacote.right <= posicaoCaixa.right &&

        posicaoPacote.top >= posicaoCaixa.top &&
        posicaoPacote.bottom <= posicaoCaixa.bottom;


    if (dentroDaCaixa) {

        pacote.dataset.travado = "true";

        pacote.style.cursor = "default";

        console.log("Pacote travado na caixa");

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

    verificarTratativa(pacote);

});

}

// =============================
// PRIMEIRO PACOTE
// =============================

criarPacote();




 
 // ==============================
   
    document
    .getElementById("botão")
    .addEventListener("click", function(evento) {

        evento.preventDefault();


        // =============================
        // ÁREA REAL DA CAIXA
        // =============================

        const caixaElemento =
            document.getElementById("caixa");

        const caixa =
            caixaElemento.getBoundingClientRect();


        // Pacotes travados dentro da caixa
        const pacotesNaCaixa =
            document.querySelectorAll(
                '.pacote[data-travado="true"]'
            );


        let totalCaixa = 0;
        let caixaCorretos = 0;
        let caixaErrados = 0;


        // =============================
        // LIMITES DA OCUPAÇÃO
        // =============================

        let menorLeft = caixa.right;
        let maiorRight = caixa.left;

        let menorTop = caixa.bottom;
        let maiorBottom = caixa.top;


        // =============================
        // ANALISAR PACOTES
        // =============================

        pacotesNaCaixa.forEach(function(pacote) {

            totalCaixa++;

            const posicao =
                pacote.getBoundingClientRect();


            // Verifica destino
            if (
                pacote.dataset.destino === "caixa"
            ) {

                caixaCorretos++;

            } else {

                caixaErrados++;

            }


            // Mede largura ocupada
            if (posicao.left < menorLeft) {
                menorLeft = posicao.left;
            }

            if (posicao.right > maiorRight) {
                maiorRight = posicao.right;
            }


            // Mede altura ocupada
            if (posicao.top < menorTop) {
                menorTop = posicao.top;
            }

            if (posicao.bottom > maiorBottom) {
                maiorBottom = posicao.bottom;
            }

        });


        // =============================
        // CALCULAR OCUPAÇÃO
        // =============================

        let ocupacaoLargura = 0;
        let ocupacaoAltura = 0;


        if (totalCaixa > 0) {

            const larguraOcupada =
                maiorRight - menorLeft;

            const alturaOcupada =
                maiorBottom - menorTop;


            ocupacaoLargura =
                (larguraOcupada / caixa.width) * 100;

            ocupacaoAltura =
                (alturaOcupada / caixa.height) * 100;

        }


        // Limita em 100%
        ocupacaoLargura =
            Math.min(ocupacaoLargura, 100);

        ocupacaoAltura =
            Math.min(ocupacaoAltura, 100);


        // =============================
        // META DA CAIXA
        // =============================

        const caixaPreenchida =
            ocupacaoLargura >= 85 &&
            ocupacaoAltura >= 85;


        // =============================
        // RESULTADO
        // =============================

        const aprovado =
            totalCaixa > 0 &&
            caixaErrados === 0 &&
            caixaPreenchida;


        // =============================
        // IR PARA RESULTADO
        // =============================

        window.location.href =
            "resultado.html" +

            "?saca=" + totalSaca +

            "&tratativa=" + totalTratativa +

            "&caixa=" + totalCaixa +

            "&corretos=" + caixaCorretos +

            "&errados=" + caixaErrados +

            "&largura=" +
            ocupacaoLargura.toFixed(0) +

            "&altura=" +
            ocupacaoAltura.toFixed(0) +

            "&aprovado=" + aprovado;

    });