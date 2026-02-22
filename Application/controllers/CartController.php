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

    public function addCartItem($userId, $bookId, $qty, $bookType = 'regular')
    {
        $effectiveUserId = $userId ?: ($_SESSION['ADMIN_ID'] ?? null);
        if (!$effectiveUserId) return false;
        return $this->cartModel->addItem((int)$effectiveUserId, $bookId, (int)$qty, $bookType);
    }

    public function removeCartItem($userId, $bookId, $bookType = 'regular')
    {
        $effectiveUserId = $userId ?: ($_SESSION['ADMIN_ID'] ?? null);
        if (!$effectiveUserId) return false;
        return $this->cartModel->removeItem((int)$effectiveUserId, $bookId, $bookType);
    }

    public function updateCartItem($userId, $bookId, $qty, $bookType = 'regular')
    {
        $effectiveUserId = $userId ?: ($_SESSION['ADMIN_ID'] ?? null);
        if (!$effectiveUserId) return false;
        return $this->cartModel->updateItemCount((int)$effectiveUserId, $bookId, (int)$qty, $bookType);
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

    // =========================
    // Mobile API helpers
    // =========================
    public function updateCartItemByCartId(int $cartId, int $qty): bool
    {
        return $this->cartModel->updateItemCountByCartId($cartId, $qty);
    }

    public function removeCartItemByCartId(int $cartId): bool
    {
        return $this->cartModel->removeItemByCartId($cartId);
    }

    public function updateDeliveryAddressById(int $addressId, array $data): bool
    {
        return $this->cartModel->updateDeliveryAddressById($addressId, $data);
    }
}
