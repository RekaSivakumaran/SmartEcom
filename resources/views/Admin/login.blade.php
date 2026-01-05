<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - E-Commerce</title>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Roboto", sans-serif;
}

body, html {
    height: 100%;
    width: 100%;
    background: #eef5ff; /* Theme 5 background */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* LOGIN CARD */
.login-card {
    display: flex;
    width: 820px;
    max-width: 95%;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 25px 55px rgba(0,0,0,0.15);
    background: #ffffff;
}

/* LEFT SIDE */
.card-left {
    flex: 1;
 
background: linear-gradient(135deg, #536dfe 15%, #0097a7 85%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    padding: 35px;
    text-align: center;
}

.card-left img {
    width: 130px;
    margin-bottom: 25px;
    border-radius: 12px;
    border: 2px solid rgba(255,255,255,0.4);
}

.card-left h1 {
    font-size: 30px;
    margin-bottom: 10px;
    font-weight: 700;
}

.card-left p {
    font-size: 16px;
}

/* RIGHT SIDE */
.card-right {
    flex: 1;
    padding: 45px 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.card-right .logo {
    width: 65px;
    margin: 0 auto 20px auto;
}

.card-right h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #0097a7;
    font-weight: 700;
}

/* INPUTS */
.input-group {
    position: relative;
    margin-bottom: 22px;
}

.input-group input {
    width: 100%;
    padding: 14px;
    border-radius: 8px;
    border: 1px solid #d6e0ff;
    outline: none;
    transition: 0.3s;
    background: #f8fbff;
}

.input-group input:focus {
    border-color: #536dfe;
    box-shadow: 0 0 6px rgba(83,109,254,0.4);
}

.input-group label {
    position: absolute;
    left: 14px;
    top: 14px;
    color: #777;
    pointer-events: none;
    transition: 0.25s;
}

.input-group input:focus + label,
.input-group input:not(:placeholder-shown) + label {
    top: -10px;
    font-size: 12px;
    background: white;
    padding: 0 5px;
    color: #536dfe;
}

/* PASSWORD TOGGLE */
.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #536dfe;
    font-weight: 600;
    user-select: none;
}

/* BUTTON */
button {
    width: 100%;
    padding: 13px;
    background: #0097a7;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 17px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #007c8b;
}

/* FORGOT PASSWORD */
.forgot-password {
    margin-top: 12px;
    text-align: center;
    font-size: 14px;
}

.forgot-password a {
    color: #536dfe;
    text-decoration: none;
}

.forgot-password a:hover {
    text-decoration: underline;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .login-card {
        flex-direction: column;
    }
    .card-left {
        padding: 25px;
    }
    .card-right {
        padding: 30px 20px;
    }
}
</style>
</head>

<body>

<div class="login-card">

    <!-- LEFT -->
    <div class="card-left">
        <img src="https://cdn-icons-png.flaticon.com/512/2910/2910764.png">
        <h1>Welcome Back!</h1>
        <p>Log in to manage your store dashboard securely.</p>
    </div>

    <!-- RIGHT -->
    <div class="card-right">
        <img src="https://cdn-icons-png.flaticon.com/512/34/34568.png" class="logo">
        <h2>Admin Login</h2>

        <form id="loginForm">
            <div class="input-group">
                <input type="email" id="email" required placeholder=" ">
                <label>Email</label>
            </div>

            <div class="input-group">
                <input type="password" id="password" required placeholder=" ">
                <label>Password</label>
                <span class="toggle-password" id="togglePassword" onclick="togglePassword()">Show</span>
            </div>

            <button type="submit">Login</button>

            <p class="forgot-password">
                <a href="#">Forgot Password?</a>
            </p>
        </form>
    </div>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');

    if (pass.type === 'password') {
        pass.type = 'text';
        toggle.textContent = 'Hide';
    } else {
        pass.type = 'password';
        toggle.textContent = 'Show';
    }
}

document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();
    alert("Login submitted!");
});
</script>

</body>
</html>
