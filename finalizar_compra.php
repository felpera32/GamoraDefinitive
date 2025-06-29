<?php
session_start();
include 'connect.php';

// Verificar se a conexão com o banco está funcionando
if (!isset($conn) || $conn === null) {
    // Tentativa de criar uma nova conexão
    try {
        $servername = "localhost";
        $username = "root";  
        $password = "";      
        $dbname = "gamoraloja";  

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            error_log("Falha na conexão: " . $conn->connect_error);
            $_SESSION['erro_compra'] = "Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.";
            header('Location: cart.php');
            exit;
        }
    } catch (Exception $e) {
        error_log("Erro ao conectar ao banco de dados: " . $e->getMessage());
        $_SESSION['erro_compra'] = "Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.";
        header('Location: cart.php');
        exit;
    }
}

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo_pagamento = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    
    if (empty($metodo_pagamento)) {
        $_SESSION['erro_pagamento'] = 'Por favor, selecione um método de pagamento.';
        header('Location: cart.php');
        exit;
    }
    
    // Salvar o método de pagamento na sessão
    $_SESSION['metodo_pagamento'] = $metodo_pagamento;
    
    $idCliente = isset($_POST['idCliente']) ? $_POST['idCliente'] : $_SESSION['id_usuario'] ?? 1;
    
    // Salvar uma cópia do carrinho antes de limpá-lo
    $_SESSION['carrinho_finalizado'] = $_SESSION['carrinho'];
    
    // Calcular o valor total da compra para o cálculo de moedas
    $valorTotal = 0;
    foreach ($_SESSION['carrinho'] as $idJogo => $item) {
        $subtotal = $item['preco'] * $item['quantidade'];
        $valorTotal += $subtotal;
    }
    
    // Calcular moedas de fidelidade (5% do valor total)
    $novasMoedas = floor($valorTotal * 0.05);
    
    // Atualizar as moedas do cliente no banco de dados
    if ($novasMoedas > 0 && isset($conn) && $conn !== null) {
        $atualizouMoedas = atualizarMoedasFidelidade($conn, $idCliente, $novasMoedas);
        
        if ($atualizouMoedas) {
            // Salvar na sessão para exibir mensagem de confirmação
            $_SESSION['moedas_ganhas'] = $novasMoedas;
        } else {
            error_log("Falha ao atualizar moedas para o cliente ID: $idCliente");
        }
    }
    
    if (!isset($_SESSION['biblioteca'])) {
        $_SESSION['biblioteca'] = [];
    }
    
    foreach ($_SESSION['carrinho'] as $idJogo => $item) {
        if (!isset($_SESSION['biblioteca'][$idJogo])) {
            $_SESSION['biblioteca'][$idJogo] = [
                'nome' => $item['nome'],
                'data_compra' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    // Limpar o carrinho após finalizar a compra
    $_SESSION['carrinho'] = [];
    
    $_SESSION['compra_finalizada'] = true;
    
    // Redirecionar para a página de pedidos em vez da biblioteca
    header('Location: pedidos.php');
    exit;
} else {
    header('Location: cart.php');
    exit;
}

/**
 * Função para atualizar as moedas de fidelidade do cliente
 * @param mysqli $conn Conexão com o banco de dados
 * @param int $idCliente ID do cliente
 * @param int $novasMoedas Quantidade de moedas a adicionar
 * @return bool True se atualizado com sucesso, False caso contrário
 */
function atualizarMoedasFidelidade($conn, $idCliente, $novasMoedas) {
    // Verificar se a conexão é válida
    if (!$conn || $conn->connect_error) {
        error_log("Conexão inválida ao atualizar moedas");
        return false;
    }
    
    try {
        // Primeiro, vamos buscar a quantidade atual de moedas
        $sqlSelect = "SELECT moedas FROM clientes WHERE idCliente = ?";
        $stmtSelect = $conn->prepare($sqlSelect);
        
        if (!$stmtSelect) {
            error_log("Erro ao preparar consulta: " . $conn->error);
            return false;
        }
        
        $stmtSelect->bind_param("i", $idCliente);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $moedasAtuais = $row['moedas'];
            
            // Calcular o novo total de moedas
            $totalMoedas = $moedasAtuais + $novasMoedas;
            
            // Atualizar o registro do cliente
            $sqlUpdate = "UPDATE clientes SET moedas = ? WHERE idCliente = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            
            if (!$stmtUpdate) {
                error_log("Erro ao preparar atualização: " . $conn->error);
                return false;
            }
            
            $stmtUpdate->bind_param("ii", $totalMoedas, $idCliente);
            $success = $stmtUpdate->execute();
            
            if ($success) {
                return true;
            } else {
                error_log("Erro ao atualizar moedas: " . $stmtUpdate->error);
                return false;
            }
        } else {
            error_log("Cliente não encontrado: ID " . $idCliente);
            return false;
        }
    } catch (Exception $e) {
        error_log("Exceção ao atualizar moedas: " . $e->getMessage());
        return false;
    }
}
?>