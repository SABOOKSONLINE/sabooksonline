document.addEventListener("DOMContentLoaded", function () {
    const inputFields = document.querySelectorAll("input");
    const formAlert = document.querySelector("#form-alert");
    const email = document.querySelector('input[name="reg_mail"]');
    const confirm = document.querySelector('input[name="confirm_mail"]');
    const togglePassword = document.getElementById("togglePassword");
    const termsCheckbox = document.getElementById("terms_accepted");
    const signupBtn = document.getElementById("signupBtn");

    // Helper: quick alert handler
    const quickFormAlert = (message = "") => {
        if (!formAlert) return;
        if (message) {
            formAlert.classList.remove("d-none");
            formAlert.innerText = message;
        } else {
            formAlert.classList.add("d-none");
            formAlert.innerText = "";
        }
    };

    // Validation helpers
    const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const isAlphaOnly = (name) => /^[A-Za-z\s]+$/.test(name);
    const isNumberOnly = (number) => /^[0-9]+$/.test(number);

    // Invalid input visual feedback
    const inValidInputPop = (input) => {
        if (!input) return;
        input.classList.add("is-invalid");
        const inputLabel =
            input?.parentElement?.firstElementChild?.innerText || "field";
        quickFormAlert(`Please enter a valid ${inputLabel.toLowerCase()}.`);
    };

    const forceFocusInput = (input) => {
        if (input && typeof input.focus === "function") {
            if (!input.disabled && input.offsetParent !== null) {
                setTimeout(() => {
                    input.focus();
                    input.select?.();
                }, 0);
            }
        }
    };

    const validateInput = (input) => {
        if (!input || typeof input.value !== "string") return false;

        if (input.value.trim() === "") {
            inValidInputPop(input);
            return false;
        }

        if (input.type === "text" && !isAlphaOnly(input.value)) {
            inValidInputPop(input);
            return false;
        } else if (input.type === "email" && !isValidEmail(input.value)) {
            inValidInputPop(input);
            return false;
        } else if (input.type === "number" && !isNumberOnly(input.value)) {
            inValidInputPop(input);
            return false;
        }

        input.classList.remove("is-invalid");
        quickFormAlert();
        return true;
    };

    const validInputField = (input) => {
        if (!input) return;
        input.addEventListener("keydown", (e) => {
            if (e.key === "Tab") {
                const isValid = validateInput(input);
                if (!isValid) {
                    e.preventDefault();
                    forceFocusInput(input);
                }
            }
        });

        input.addEventListener("blur", () => {
            validateInput(input);
        });
    };

    // Attach validation handlers
    if (inputFields?.length > 0) {
        inputFields.forEach((input) => validInputField(input));
    }

    // Toggle password visibility
    if (togglePassword) {
        togglePassword.addEventListener("click", function () {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            if (!passwordInput || !icon) return;

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    }

    // Terms checkbox toggle for button
    function toggleButtons() {
        if (!termsCheckbox || !signupBtn) return;

        if (termsCheckbox.checked) {
            signupBtn.disabled = false;
            signupBtn.classList.remove("btn-secondary");
            signupBtn.classList.add("btn-red");
        } else {
            signupBtn.disabled = true;
            signupBtn.classList.remove("btn-red");
            signupBtn.classList.add("btn-secondary");
        }
    }

    if (termsCheckbox && signupBtn) {
        termsCheckbox.addEventListener("change", toggleButtons);
        toggleButtons();
    }
});
