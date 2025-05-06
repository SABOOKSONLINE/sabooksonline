$("#login").on("submit", function (e) {
  e.preventDefault();

  $("#reg_load").html(
    '<div class="d-flex justify-content-center align-content-center align-items-center" style="width: 100%;height:100%;position:relative;"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>'
  );

  $.ajax({
    url: "../../Application/views/includes/backend/login.php",
    type: "POST",
    data: new FormData(this),
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      $("#reg_load").html("Login");
      $("#reg_status").html(data);
    },
    error: function () {},
  });
});

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
