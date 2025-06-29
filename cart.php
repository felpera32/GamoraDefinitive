<?php
session_start();
include 'connect.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    // Salvar a URL atual para redirecionar de volta após o login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    // Redirecionar para a página de login
    header('Location: login.php');
    exit;
}

$carrinhoVazio = empty($_SESSION['carrinho']);
$valorTotal = 0;

if (!$carrinhoVazio) {
    foreach ($_SESSION['carrinho'] as $id => $item) {
        $subtotal = $item['preco'] * $item['quantidade'];
        $valorTotal += $subtotal;
    }
}

// Função para criar uma nova conexão sempre que necessário
function criarNovaConexao() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gamoraloja";

    $novaConn = new mysqli($servername, $username, $password, $dbname);
    
    if ($novaConn->connect_error) {
        throw new Exception("Falha na conexão: " . $novaConn->connect_error);
    }
    
    // Definir charset para evitar problemas de codificação
    $novaConn->set_charset("utf8");
    
    return $novaConn;
}

// Função para obter a imagem principal do produto
function getImagemPrincipal($idProduto)
{
    try {
        // Criar uma nova conexão independente para esta função
        $conn = criarNovaConexao();
        
        $sql = "SELECT urlImagem FROM imagensproduto WHERE idProduto = ? AND ordemExibicao = 1 AND status = 'Ativa' LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }
        
        $stmt->bind_param("i", $idProduto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            $conn->close();
            return $row['urlImagem'];
        }
        
        $stmt->close();
        
        // Fallback para a imagem do produto na tabela produtos
        $sql = "SELECT imagemPrincipal FROM produtos WHERE idProduto = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro na preparação da query fallback: " . $conn->error);
        }
        
        $stmt->bind_param("i", $idProduto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            $conn->close();
            return $row['imagemPrincipal'] ? $row['imagemPrincipal'] : 'imagens/placeholder.jpg';
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        error_log("Erro ao buscar imagem do produto $idProduto: " . $e->getMessage());
    }

    return 'imagens/placeholder.jpg'; // Imagem padrão caso nenhuma seja encontrada
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <?php include "navbar/nav.php"; ?>
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>
    <div class="cart-container">
        <div class="cart-header">
            <h2>Carrinho</h2>
            <div class="nav-links">
                <a href="biblioteca.php" class="nav-link">Minha Biblioteca</a>
                <a href="index.php" class="nav-link">Loja</a>
            </div>
        </div>

        <div class="user-info">
            <span>Olá, <?php echo isset($_SESSION['nome_usuario']) ? htmlspecialchars($_SESSION['nome_usuario']) : 'Usuário'; ?></span>
        </div>

        <?php if ($carrinhoVazio): ?>
            <div class='empty-cart-message'>Seu carrinho está vazio.</div>
            <button class='finalize-button' disabled>Finalizar Compra</button>
        <?php else: ?>
            <?php
            foreach ($_SESSION['carrinho'] as $id => $item) {
                // Obter a imagem principal do banco de dados
                $imagePath = getImagemPrincipal($id);
                ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="cart-item-image">
                    <div class="cart-item-details">
                        <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                        <p>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    </div>
                    <a href="remover_item.php?id=<?php echo $id; ?>" class="remove-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </a>
                </div>
            <?php } ?>

            <div class="total-price">
                Total: R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?>
            </div>

            <div class="payment-methods">
                <h3>Método de Pagamento</h3>
                <?php
                $paymentMethods = ['Cartão de crédito', 'Pix', 'PicPay', 'Boleto', 'Cripto'];
                foreach ($paymentMethods as $index => $method) {
                    $methodId = strtolower(str_replace(' ', '-', $method));
                    $methodValue = strtolower(str_replace(' ', '-', $method));
                    echo "<div class='payment-method'>
                        <input type='radio' id='" . htmlspecialchars($methodId) . "' 
                               name='payment_method' value='" . htmlspecialchars($methodValue) . "'"
                               . ($index === 0 ? ' checked' : '') . ">
                        <label for='" . htmlspecialchars($methodId) . "'>" . htmlspecialchars($method) . "</label>
                      </div>";
                }
                ?>
            </div>

            <form method="POST" action="finalizar_compra.php" id="checkout-form">
                <input type="hidden" name="idCliente" value="1">
                <input type="hidden" name="payment_method" id="selected_payment">
                <button type="submit" class="finalize-button">Finalizar Compra</button>
            </form>

            <script>
                // Script para garantir que um método de pagamento seja selecionado
                document.getElementById('checkout-form').addEventListener('submit', function (e) {
                    // Obter método de pagamento selecionado
                    const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (selectedMethod) {
                        document.getElementById('selected_payment').value = selectedMethod.value;
                    }
                });
            </script>
        <?php endif; ?>

        <a href="index.php" class="back-to-store">Voltar para a loja</a>
    </div>
</body>

</html>