// <script>
document.addEventListener("DOMContentLoaded", function () {
    const billingToggle = document.getElementById("billingToggle");

    function updatePlanTypes() {
        const isYearly = billingToggle.checked;

        // Toggle price displays
        document.querySelectorAll(".monthly-price").forEach((el) => el.classList.toggle("d-none", isYearly));
        document.querySelectorAll(".yearly-price").forEach((el) => el.classList.toggle("d-none", !isYearly));

        // Update each form's hidden planType field
        document.querySelectorAll(".subscription-form").forEach((form) => {
            const planName = form.querySelector("h3").innerText.trim();
            const planTypeInput = form.querySelector(".plan-type-input");
            planTypeInput.value = `${planName}-${isYearly ? 'Yearly' : 'Monthly'}`;
        });
    }

    if (billingToggle) {
        billingToggle.addEventListener("change", updatePlanTypes);
        updatePlanTypes(); // initial load
    }
});
