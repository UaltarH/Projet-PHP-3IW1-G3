<?php
if (!empty($messageInfo)) {
    print_r($messageInfo);
} else {
    echo "<p>No Access Application :</p>";
}

if(!isset($typeError)){
    $typeError = 'default';
}
$delay = 3; // délai en secondes
switch ($typeError) {
    case 'noArticleFound':
        header("Refresh: $delay; URL=default");
        break;
    case 'noConnection':
        header("Refresh: $delay; URL=login");
        break;
    default:
        header("Refresh: $delay; URL=login");
        break;
}
exit();
?>