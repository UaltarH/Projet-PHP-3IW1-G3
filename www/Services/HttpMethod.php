<?php
namespace App\Services\HttpMethod;

/**
 * Parse les arguments passés par les méthodes PUT et DELETE uniquement, puis les passes dans un tableau
 * eg : $post_vars['id']
 * @return array
 */
function getHttpMethodVarContent(): array
{
    $post_vars = [];
    if ($_SERVER["CONTENT_TYPE"] === 'application/x-www-form-urlencoded; charset=UTF-8') {
        parse_str(file_get_contents("php://input"), $post_vars);
    }
    return $post_vars;
}
?>