<?php
// OPTIONAL: verify PayFast response here
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>

<style>
    body {
        margin: 0;
        padding: 0;
        background: rgba(0,0,0,0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: Arial, sans-serif;
    }

    .modal-box {
        background: #ffffff;
        padding: 40px 30px;
        border-radius: 16px;
        width: 380px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .success-check {
        width: 90px;
        height: 90px;
        background: #28a745;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: popIn 0.4s ease;
    }

    @keyframes popIn {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-check svg {
        width: 55px;
        height: 55px;
        fill: #fff;
    }

    h2 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
    }

    p {
        margin-top: 10px;
        color: #333;
        font-size: 15px;
    }

    #continueBtn {
        margin-top: 25px;
        padding: 12px 20px;
        background: #28a745;
        border: none;
        color: #fff;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: 0.2s;
    }

    #continueBtn:hover {
        background: #218838;
    }
</style>
</head>

<body>

<div class="modal-box">
    <div class="success-check">
        <!-- SVG TICK -->
        <svg viewBox="0 0 24 24">
            <path d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.4-1.4z"></path>
        </svg>
    </div>

    <h2>Thank You!</h2>
    <p>Your purchase has been completed successfully.</p>

    <button id="continueBtn">Continue</button>
</div>

<script>
document.getElementById("continueBtn").onclick = function() {
    window.location.href = "/dashboards/bookshelf";
};
</script>

</body>
</html>
