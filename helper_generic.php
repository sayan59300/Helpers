<?php

/* * ************** * */
/* * HELPER GENERIC * */
/* * ************** * */

use GuzzleHttp\Psr7\Response;
use Itval\core\Classes\Session;

/**
 * Retourne une URL formattée
 *
 * @param  string      $route
 * @param  string|null $params  slug ou id ...
 * @param  array       ...$args tous les paramètres supplémentaires (spread operator)
 * @return string
 */
function getUrl(string $route, string $params = null, ...$args): string
{
    if (isset($params) && ($args !== [])) {
        $additionalParams = implode('/', $args);
        return BASE_URL . '/' . $route . '/' . $params . '/' . $additionalParams;
    }
    if (!isset($params)) {
        return BASE_URL . '/' . $route;
    }
    return BASE_URL . '/' . $route . '/' . $params;
}

/**
 * Redirige vers l'url demandée
 *
 * @param  string|null $url
 * @return Response
 */
function redirect(string $url = null): Response
{
    if (!isset($url)) {
        header('Location: ' . BASE_URL);
        return new Response(301);
    }
    header('Location: ' . BASE_URL . $url);
    return new Response(301);
}

/**
 * Retourne la reponse avec le body et le statut de l'erreur 403
 *
 * @param Response $response
 * @return Response
 */
function denied(Response $response): Response
{
    ob_start();
    include ROOT . DS . 'views' . DS . 'errors' . DS . '403.phtml';
    $body = ob_get_clean();
    return $response->withStatus(403)->getBody()->write($body);
}

/**
 * Retourne la reponse avec le body et le statut de l'erreur 404
 *
 * @param Response $response
 * @return Response
 */
function error404(Response $response): Response
{
    ob_start();
    include ROOT . DS . 'views' . DS . 'errors' . DS . '404.phtml';
    $body = ob_get_clean();
    return $response->withStatus(404)->getBody()->write($body);
}

/**
 * Ecrit la valeur de la variable de session souhaitée si elle est définie
 *
 * @param  string $name
 * @return string
 */
function printSession(string $name): string
{
    return Session::read($name) ?? '';
}

/**
 * Ecrit une date au format d/m/Y depuis un objet DateTime
 *
 * @param  DateTime $date
 * @return string
 */
function printDate(DateTime $date): string
{
    return date_format($date, 'd/m/Y');
}

/**
 * Active ou non un lien en fonction de l'url
 *
 * @param  string|null $url
 * @return string
 */
function isActiveLink(string $url = null): string
{
    if ($url === null) {
        return (filter_input(INPUT_SERVER, 'REQUEST_URI') === '/') ? ' active' : '';
    }
    $urlParts = explode('?', filter_input(INPUT_SERVER, 'REQUEST_URI'));
    $requestUrl = rtrim($urlParts[0], '/');
    return (BASE_URL . $requestUrl === $url) ? ' active' : '';
}

/**
 * Formatte les css ajoutés par les contrôlleurs
 *
 * @param  $css
 * @return string
 */
function printCssAssets($css): string
{
    $formattedCss = '';
    if (is_array($css)) {
        foreach ($css as $value) {
            $formattedCss .= $value . "\n";
        }
        return $formattedCss;
    }
    return $css;
}

/**
 * Formatte les scripts javascript ajoutés par les contrôlleurs
 *
 * @param  $scripts
 * @return string
 */
function printJsAssets($scripts): string
{
    $formattedScripts = '';
    if (is_array($scripts)) {
        foreach ($scripts as $script) {
            $formattedScripts .= $script . "\n";
        }
        return $formattedScripts;
    }
    return $scripts;
}
