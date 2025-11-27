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

    public function getItemsCount()
    {
        $userId = $_SESSION['ADMIN_ID'];
        return count($this->cartModel->getCartItemsByUserId($userId));
    }

    // addItem(int $userId, int $bookId, int $qty = 1)

    public function addCartItem($userId, $bookId, $qty)
    {
        $userId = $_SESSION['ADMIN_ID'];
        if ($this->cartModel->addItem($userId, $bookId, $qty)) {
            return true;
        } else {
            return false;
        }
    }

    // removeCartItem($userId, $bookId)
    public function removeCartItem($userId, $bookId)
    {
        $userId = $_SESSION['ADMIN_ID'];
        if ($this->cartModel->removeItem($userId, $bookId)) {
            return true;
        } else {
            return false;
        }
    }

    // updateCartItem($userId, $bookId, $qty)
    public function updateCartItem($userId, $bookId, $qty)
    {
        $userId = $_SESSION['ADMIN_ID'];
        if ($this->cartModel->updateItemCount($userId, $bookId, $qty)) {
            return true;
        } else {
            return false;
        }
    }
}
