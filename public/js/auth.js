document.addEventListener("DOMContentLoaded", function () {
  const signIn = document.getElementById("signIn");
  const signUp = document.getElementById("signUp");

  if (signIn) {
    const togglePassword = document.getElementById("toggleSignInPassword");
    const inputPassword = document.getElementById("inputSignInPassword");
    const eyeIcon = togglePassword.querySelector("i");

    togglePassword.addEventListener("click", function () {
      const type =
        inputPassword.getAttribute("type") === "password" ? "text" : "password";
      inputPassword.setAttribute("type", type);
      eyeIcon.classList.toggle("fa-eye");
      eyeIcon.classList.toggle("fa-eye-slash");
    });
  }

  if (signUp) {
    const togglePassword = document.getElementById("toggleSignUpPassword");
    const inputPassword = document.getElementById("inputSignUpPassword");
    const eyeIcon = togglePassword.querySelector("i");

    togglePassword.addEventListener("click", function () {
      const type =
        inputPassword.getAttribute("type") === "password" ? "text" : "password";
      inputPassword.setAttribute("type", type);
      eyeIcon.classList.toggle("fa-eye");
      eyeIcon.classList.toggle("fa-eye-slash");
    });

    const toggleConfirmationPassword = document.getElementById(
      "toggleConfirmationPassword"
    );
    const inputConfirmationPassword = document.getElementById(
      "inputConfirmationPassword"
    );
    const confirmationEyeIcon = toggleConfirmationPassword.querySelector("i");

    toggleConfirmationPassword.addEventListener("click", function () {
      const type =
        inputConfirmationPassword.getAttribute("type") === "password"
          ? "text"
          : "password";
      inputConfirmationPassword.setAttribute("type", type);
      confirmationEyeIcon.classList.toggle("fa-eye");
      confirmationEyeIcon.classList.toggle("fa-eye-slash");
    });
  }
});
