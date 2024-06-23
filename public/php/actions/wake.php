<?php
exec("wakeonlan b4:2e:99:f1:76:aa");
$body = "<h2>Le serveur se lève!</h2>";
$body .= "<p><a href='/index.php?action=server'>Okay !</a></p>";
