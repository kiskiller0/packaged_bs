<?php
session_start();
//logger:
include "logger.php";
//eologger
include "./views/partials/header.html";
if (!empty($_SESSION)) {
    // echo "redirecting to home ...";
    include "./views/home.php";
} else {
    include "./views/welcome.html"; //#TODO: animate the placeholder: setTimout, everytime add a letter!
}
?>
    <script src="views/scripts/home.js"></script>
<?php
include "./views/partials/footer.html";
