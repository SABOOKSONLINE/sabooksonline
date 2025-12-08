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
        $cartItems = $this->cartModel->getCartItemsByUserId($userId);
        return $cartItems;
    }

    public function getItemsCount()
    {
        $userId = $_SESSION['ADMIN_ID'] ?? null;
        if ($userId)
            return count($this->cartModel->getCartItemsByUserId($userId));
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

    // -----------------------------
    // Save delivery address
    // -----------------------------
    public function saveDeliveryAddress($userId, array $data)
    {
        // Expected $data keys: delivery_address, delivery_contact
        if (empty($userId) || empty($data)) {
            return false;
        }

        // Call the model method to actually save to DB
        return $this->cartModel->saveDeliveryAddress($userId, $data);
    }
}
