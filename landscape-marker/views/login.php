<?php
session_start();

if (!empty($_SESSION)) {
    echo "redirecting to home ...";
}

include "./partials/header.html";
?>

    <link rel="stylesheet" href="style/signin.css">
    <title>Login</title>
    </head>

    <body>
    <form action="../api/login.php" method="post">
        <label for="username">username</label>
        <input type="text" name="username">
        <label for="password">password</label>
        <input type="text" name="password">

        <input type="submit" value="login">
    </form>


    <div class="msgOverlay hidden">
        <p class="msg"></p>
    </div>

    <script src="scripts/signin.js"></script>
    </body>

<?php
include "./partials/footer.html";
?>