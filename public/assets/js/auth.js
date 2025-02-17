document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("#login-form");
    
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            alert("Login enviado!");
        });
    }
});
