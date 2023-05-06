<?php
$photos = construct_photo_list();
$body .= <<<HTML
<form method="post" action="/index.php?action=vote">
    $photos
    <button type="submit">Voter!</button>
</form>
HTML;
