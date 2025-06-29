<?php
// Start the session at the very beginning of the file
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Loja de Jogos</title>
  <link rel="stylesheet" href="css/styles.css">
  <script src="script.js"></script>

  <style>
    /* Navbar Styles */


    /* Original styles */
    .spiderman-box {
      background-color: #0f2f6b;
      padding: 20px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      margin-left: 6%;
      width: 82%;
      margin-top: 2%;
    }

    .spiderman-image {
      width: 30%;
      border-radius: 10px;
    }

    .spiderman-detalhes {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin-left: 15%;
    }

    .spiderman-box h2 {
      margin-right: 33%;
      margin-top: 0%;
      font-size: 50px;
    }







    .preco {
      color: #FFFF;
      font-size: 20px;
    }

    .comprar-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-right: 458px;
      margin-top: -62px;
    }



    .rd2 {
      width: 5%;
    }
  </style>
</head>
    <?php 
         include "navbar/nav.php"
    ?>
<body>


  <main>
    <section class="produtos">
      <div class="spiderman-box">
        <img src="src/capas/spiderman/capa.jpg" alt="Spider-Man 2" class="spiderman-image">
        <div class="spiderman-detalhes">
          <h2>Spider-Man 2</h2>
          <p class="preco">R$ 199,00</p>



          <!-- Botão de comprar -->
          <div class="buy-container">
            <button class="comprar-btn" id="spiderman-button">🛒 Comprar</button>
          </div>
        </div>
      </div>

      <div class="outros-produtos">
        <div class="produto">
          <img src="https://www.outerspace.com.br/wp-content/uploads/2018/04/reddeadredemption2.jpg" class="rd2" alt="Red Dead Redemption 2">
          <h3>Red Dead Redemption 2</h3>
          <p>R$ 299,00</p>
          <div class="produto-botoes">
            <button id="comprar-reddead" onclick="redirecionarRedDead()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://sm.ign.com/ign_pt/screenshot/default/stardew_55u8.jpg" alt="Stardew Valley" class="stardewimg">
          <h3>Stardew Valley</h3>
          <p>R$ 24,99</p>
          <div class="produto-botoes">
            <button id="comprar-stardew" onclick="redirecionarStardew()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://i0.wp.com/nosnerds.com.br/wp-content/uploads/2018/06/sekiro-capa.jpg?resize=664%2C374&ssl=1" alt="sekiro">
          <h3>sekiro</h3>
          <p>R$ 274,00</p>
          <div class="produto-botoes">
            <button id="comprar-sekiro" onclick="redirecionarSekiro()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgET7_oX5gHz-uxMD8u4rpwBN7YjlYUqaL9SYSL_ahRUaF1WHyI4JqV8_5wfPEZlBm4Lk6TNQJQ3S7IYO5erNS9T21p0FcHkLFa9gP2TAffttS6QvnvPMT3At8jkTeuLH3aH8-DQz21RO9Q/s1600/moonlighter-capa.jpg" alt="Moonlighter">
          <h3>Moonlighter</h3>
          <p>R$ 39,00</p>
          <div class="produto-botoes">
            <button id="comprar-moonlighter" onclick="redirecionarMoonlighter()">🛒 Comprar</button>

          </div>
        </div>
      </div>

      <div class="outros-produtos2">
        <div class="produto">
          <img src="https://cdn2.unrealengine.com/cva-epic-epic-hero-carousel-1920x1080-1920x1080-f1c07af7e1dd.jpg" alt="Civilizations 6">
          <h3>Civilizations 6</h3>
          <p>R$ 129,00</p>
          <div class="produto-botoes">
            <button id="comprar-civilizations" onclick="redirecionarCivilizations()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://p2.trrsf.com/image/fget/cf/940/0/images.terra.com/2023/07/27/baldurs-gate-3-qhswi90megdi.jpg" alt="Baldur's Gate 3">
          <h3>Baldur's Gate 3</h3>
          <p>R$ 199,99</p>
          <div class="produto-botoes">
            <button id="comprar-bg3" onclick="redirecionarBaldursGate()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="src/Capas/gta/capa.jpg" alt="GTA 6">
          <h3>GTA 6</h3>
          <p>R$ 10 MIL</p>
          <div class="produto-botoes">
            <button id="comprar-gta6" onclick="redirecionarGTA6()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://assets.nintendo.com/image/upload/c_fill,w_1200/q_auto:best/f_auto/dpr_2.0/ncom/software/switch/70010000060372/e77cbe3c83da8de952bce5bc0f0960092dbc2e08d377f0e082673239322301bb" alt="Dave The Diver">
          <h3>Dave The Diver</h3>
          <p>R$ 59,00</p>
          <div class="produto-botoes">
            <button id="comprar-dave" onclick="redirecionarDave()">🛒 Comprar</button>

          </div>
        </div>
      </div>

      <div class="outros-produtos3">
        <div class="produto">
          <img src="https://manualdosgames.com/wp-content/uploads/2025/03/Monster-Hunter-Wilds-review-capa.jpg" alt="Monster Hunter Wilds">
          <h3>Monster Hunter Wilds</h3>
          <p>R$ 219,00</p>
          <div class="produto-botoes">
            <button id="comprar-monster" onclick="redirecionarMonster()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="src/Capas/EnigmaDoMedo/capa.jpg" alt="Enigma do Medo">
          <h3>Enigma do Medo</h3>
          <p>R$ 49,50</p>
          <div class="produto-botoes">
            <button id="comprar-stardew" onclick="redirecionarEnigma()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://assets.nintendo.com/image/upload/c_fill,w_1200/q_auto:best/f_auto/dpr_2.0/ncom/software/switch/70010000006442/691ba3e0801180a9864cc8a7694b6f98097f9d9799bc7e3dc6db92f086759252" alt="Celeste">
          <h3>Celeste</h3>
          <p>R$ 40,00</p>
          <div class="produto-botoes">
            <button id="comprar-celeste" onclick="redirecionarCeleste()">🛒 Comprar</button>

          </div>
        </div>

        <div class="produto">
          <img src="https://ultimaficha.com.br/wp-content/uploads/2024/06/Black-Myth-Wukong-capa-scaled.jpg" alt="Black Myth: Wukong">
          <h3>Black Myth: Wukong</h3>
          <p>R$ 199,99</p>
          <div class="produto-botoes">
            <button id="comprar-wukong" onclick="redirecionarWukong()">🛒 Comprar</button>

          </div>
        </div>
      </div>
    </section>
  </main>
  
  <script>

  </script>

  <footer>
    <p>&copy; 2025 GAMORA. Todos os direitos reservados.</p>
  </footer>
</body>

</html>