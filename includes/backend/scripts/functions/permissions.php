<?php
// Assuming you have a database connection established
$userId = 'Free'; // Replace with the actual user ID
$maxBooksAllowed = 5; // Maximum number of books allowed based on subscription plan

// Fetch user's subscription plan and uploaded book count from the database
$query = "SELECT PLAN, PRICED FROM subscriptions WHERE PLAN = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $subscriptionPlan = $user['subscription_plan'];
    $uploadedBooks = $user['uploaded_books'];
    
    // Define the allowed limits for each subscription plan
    $subscriptionLimits = [
        'Free' => 3,
        'Standard' => 5,
        'Premium' => 5,
        'Deluxe' => 10
    ];
    
    // Check if the user's subscription plan is valid and if they haven't exceeded the limit
    if (array_key_exists($subscriptionPlan, $subscriptionLimits) && $uploadedBooks < $subscriptionLimits[$subscriptionPlan]) {
        echo "You are allowed to upload a book.";
        // You can include your book uploading code here
    } else {
        echo "Sorry, you have reached the maximum limit of books you can upload for your subscription plan.";
    }
} else {
    echo "User not found.";
}
?>
