const inputFields = document.querySelectorAll("input");
const formAlert = document.querySelector("#form-alert");

const email = document.querySelector('input[name="reg_mail"]');
const confirm = document.querySelector('input[name="confirm_mail"]');

const quickFormAlert = (message = "") => {
    if (message) {
        formAlert.classList.remove("d-none");
        formAlert.innerText = message;
    } else {
        formAlert.classList.add("d-none");
        formAlert.innerText = "";
    }
};

const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

const isAlphaOnly = (name) => {
    const alphaRegex = /^[A-Za-z\s]+$/;
    return alphaRegex.test(name);
};

const isNumberOnly = (number) => {
    const numberRegex = /^[0-9]+$/;
    return numberRegex.test(number);
};

const inValidInputPop = (input) => {
    input.classList.add("is-invalid");
    const inputLabel = input.parentElement.firstElementChild.innerText;
    quickFormAlert(`Please enter a valid ${inputLabel.toLowerCase()}.`);
};

const forceFocusInput = (input) => {
    if (input && typeof input.focus === "function") {
        if (!input.disabled && input.offsetParent !== null) {
            setTimeout(() => {
                input.focus();
                input.select();
            }, 0);
        }
    }
};

const validateInput = (input) => {
    if (!input || input.value.trim() === "") {
        return true;
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
    input.addEventListener("keydown", (e) => {
        if (e.key === "Tab") {
            const isValid = validateInput(input);
            if (!isValid) {
                e.preventDefault();
                input.focus();
            }
        }
    });

    input.addEventListener("blur", () => {
        validateInput(input);
    });
};

inputFields.forEach((input) => {
    validInputField(input);
});

document
    .getElementById("togglePassword")
    .addEventListener("click", function () {
        const passwordInput = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");
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
