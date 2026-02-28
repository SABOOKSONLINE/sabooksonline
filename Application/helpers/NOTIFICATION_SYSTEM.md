# Automatic Push Notification System

## Overview
This system provides automatic push notifications for various events in the application. Notifications are sent to mobile app users who have device tokens registered and notifications enabled.

## Features
- ✅ Automatic welcome notification on user signup
- ✅ Notifications when new books are added
- ✅ Notifications when new academic books are added
- ✅ Order status notifications (created, paid, status changes)
- ✅ Publisher notifications when their books are added to orders
- ✅ Admin notifications for order updates
- ✅ Auto-initialization on mobile app login

## Usage

### Basic Usage

Simply call the appropriate static method from `NotificationHelper`:

```php
require_once __DIR__ . "/helpers/NotificationHelper.php";

// Send welcome notification
NotificationHelper::sendWelcomeNotification($userEmail, $userName, $conn);

// Notify about new book
NotificationHelper::notifyNewBook($bookTitle, $bookId, $conn);

// Notify about new academic book
NotificationHelper::notifyNewAcademicBook($bookTitle, $bookId, $conn);

// Order notifications
NotificationHelper::notifyOrderCreated($orderId, $userId, $orderNumber, $conn);
NotificationHelper::notifyOrderPaid($orderId, $userId, $orderNumber, $conn);
NotificationHelper::notifyOrderStatusChanged($orderId, $userId, $newStatus, $orderNumber, $conn);

// Publisher notification
NotificationHelper::notifyBookAddedToOrder($orderId, $bookId, $bookTitle, $publisherId, $orderNumber, $conn);
```

## Integration Points

### 1. User Signup
**Files Modified:**
- `Application/views/auth/signup_handler.php`
- `Application/controllers/AuthController.php`
- `Application/api.php` (device token registration)

**What happens:**
- When user signs up, welcome notification is sent if they have device token
- When device token is registered, welcome notification is sent if user signed up in last 24 hours
- On login, welcome notification is sent if user signed up in last 24 hours

### 2. New Book Added
**Files Modified:**
- `Dashboard/handlers/book_handler.php`

**What happens:**
- When a new regular book is created, all users with device tokens receive a notification

### 3. New Academic Book Added
**Files Modified:**
- `Dashboard/handlers/academic_book_handler.php`

**What happens:**
- When a new academic book is created, all users with device tokens receive a notification

### 4. Order Events
**Files Modified:**
- `Application/models/CartModel.php` (order creation)
- `Application/helpers/PaymentHelper.php` (payment status)
- `Admin/Helpers/process_order.php` (order status changes)

**What happens:**
- When order is created: User receives "Order Created" notification
- When order is paid: User receives "Payment Successful" notification
- When order status changes: User receives status update notification
- When book is added to order: Publisher receives notification about their book being ordered
- When order status changes: Admins receive notification

### 5. Mobile App Auto-Initialization
**Files Modified:**
- `src/hooks/storageHelpers.js`

**What happens:**
- When user logs in, notifications are automatically initialized
- Device token is registered with backend
- User will receive notifications if they have permissions enabled

## Notification Types

### User Notifications
- **Welcome**: Sent when user signs up or logs in for first time
- **New Book**: Sent to all users when new book is added
- **New Academic Book**: Sent to all users when new academic book is added
- **Order Created**: Sent when user creates an order
- **Order Paid**: Sent when order payment is successful
- **Order Status Changed**: Sent when order status is updated (processing, shipped, delivered, etc.)

### Publisher Notifications
- **Book Added to Order**: Sent to publisher when their book is added to a customer's order

### Admin Notifications
- **Order Status Update**: Sent to admins when order status changes

## How It Works

1. **Device Token Registration**: When user logs into mobile app, device token is registered
2. **Notification Check**: System checks if user has active device tokens
3. **Event Trigger**: When event occurs (new book, order, etc.), notification helper is called
4. **Push Notification**: Notification is sent via Expo Push Notification Service
5. **Mobile App**: App receives notification and displays it to user

## Requirements

- User must have device token registered in `device_tokens` table
- User must have `is_active = 1` in device_tokens table
- Mobile app must have notification permissions enabled
- Expo Push Notification Service must be configured

## Error Handling

All notification methods include try-catch blocks and error logging. If notification fails:
- Error is logged to error log
- Application continues normally (notifications don't block main functionality)
- User experience is not affected

## Future Enhancements

- Add user preferences table to allow users to opt-in/opt-out of specific notification types
- Add notification scheduling
- Add notification templates
- Add notification analytics
