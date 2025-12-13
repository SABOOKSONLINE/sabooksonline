<?php

class CartController
{
    private $cartModel;

    public function __construct($conn)
    {
        $this->cartModel = new CartModel($conn);
    }

    public function renderCartItems($userId)
    {
        $cartItems = $this->cartModel->getCartItemsByUserId($userId);
        include __DIR__ . "/../views/includes/cartItems.php";
    }

    public function renderCartCheckout($userId)
    {
        $cartItems = $this->cartModel->getCartItemsByUserId($userId);
        include __DIR__ . "/../views/includes/cartItemsCheckout.php";
    }

    public function getCartCheckoutItems($userId)
    {
        return $this->cartModel->getCartItemsByUserId($userId);
    }

     public function getOrders($userId)
    {
        return $this->cartModel->getOrders($userId);
    }

    public function getItemsCount()
    {
        $userId = $_SESSION['ADMIN_ID'] ?? null;
        if ($userId) return $this->cartModel->countItems($userId);
        return 0;
    }

    public function addCartItem($userId, $bookId, $qty)
    {
        $userId = $_SESSION['ADMIN_ID'];
        return $this->cartModel->addItem($userId, $bookId, $qty);
    }

    public function removeCartItem($userId, $bookId)
    {
        $userId = $_SESSION['ADMIN_ID'];
        return $this->cartModel->removeItem($userId, $bookId);
    }

    public function updateCartItem($userId, $bookId, $qty)
    {
        $userId = $_SESSION['ADMIN_ID'];
        return $this->cartModel->updateItemCount($userId, $bookId, $qty);
    }

    public function saveDeliveryAddress($userId, array $data)
    {
        if (empty($userId) || empty($data)) return false;
        return $this->cartModel->saveDeliveryAddress($userId, $data);
    }

    public function getDeliveryAddress($userId)
    {
        return $this->cartModel->getDeliveryAddress($userId);
    }

    public function clearCart($userId)
    {
        $userId = $_SESSION['ADMIN_ID'];
        return $this->cartModel->clearCart($userId);
    }

    public function createOrder($userId)
    {
        $userId = $_SESSION['ADMIN_ID'];
        return $this->cartModel->createOrder($userId);
    }

    public function getOrderDetails($orderId)
    {
        return $this->cartModel->getOrderDetails($orderId);
    }

    public function updateOrderTotals(int $orderId, ?float $totalAmount = null, ?float $shippingFee = null, ?string $paymentMethod = null): bool
    {
        return $this->cartModel->updateOrderTotals($orderId, $totalAmount, $shippingFee, $paymentMethod);
    }
}
