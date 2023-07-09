<?php
// empty the $_SESSION array
session_start();
session_destroy();


header("location: ../..");