<?php

include "logger.php";

if (!empty($_FILES)) {
    echo "Files:";
    var_dump($_FILES);
} else {
    echo "FILES is empty";
}


