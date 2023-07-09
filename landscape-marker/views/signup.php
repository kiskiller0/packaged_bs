<?php
session_start();
//logger:
include "../logger.php";
//eologger
if (!empty($_SESSION)) {
    echo "redirecting to home ...";
}

include "./partials/header.html";
?>

    <link rel="stylesheet" href="style/signup.css">
    <title>Register</title>
    </head>

    <body>

    <div id="msgBox">
    </div>
    <form action="../api/signup.php" method="post" enctype="multipart/form-data">
        <div class="file">
            <input type="file" name="picture">
            <div class="userImage">
                <img src="../public/profiles/default/user.png" alt="">
            </div>
        </div>
        <label for="username">username</label>
        <input type="text" name="username">
        <label for="email">email</label>
        <input type="text" name="email">
        <label for="password">password</label>
        <input type="text" name="password">

        <input type="submit" value="Sign Up">
    </form>
    <script src="scripts/signup.js"></script>
    </body>

<?php
include "./partials/footer.html";
?>