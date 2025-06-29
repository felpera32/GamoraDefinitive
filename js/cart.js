document.addEventListener('DOMContentLoaded', () => {
    // Atualizar quantidade do item
    const updateQuantityForms = document.querySelectorAll('.update-quantity');
    updateQuantityForms.forEach(form => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const productId = form.dataset.productId;
            const quantity = form.querySelector('input[name="quantity"]').value;

            fetch('../update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    productId: productId,
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload(); // Recarrega a página para mostrar o carrinho atualizado
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao atualizar quantidade');
            });
        });
    });

    // Remover item do carrinho
    const removeButtons = document.querySelectorAll('.remove-item');
    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.dataset.productId;

            fetch('../remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    productId: productId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload(); // Recarrega a página para mostrar o carrinho atualizado
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao remover item');
            });
        });
    });

    // Botões de ações
    const continueShoppingBtn = document.getElementById('continue-shopping');
    if (continueShoppingBtn) {
        continueShoppingBtn.addEventListener('click', () => {
            window.location.href = '../products/'; // Ajuste para a página de produtos
        });
    }

    const finalizePurchaseBtn = document.getElementById('finalize-purchase');
    if (finalizePurchaseBtn) {
        finalizePurchaseBtn.addEventListener('click', () => {
            fetch('../finalize_purchase.php', {
                method: 'POST'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Compra finalizada com sucesso!');
                    window.location.href = '../orders/'; 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao finalizar compra');
            });
        });
    }
});