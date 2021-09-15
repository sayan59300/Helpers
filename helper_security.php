<?php

/* * *************** * */
/* * HELPER SECURITY * */
/* * *************** * */

use Itval\core\Classes\Session;

/**
 * Génération de mot de passe aléatoire
 *
 * @param  int $length
 * @return string
 */
function random_token(int $length = 74): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$-_';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Retourne l'utilisateur connecté
 *
 * @return stdClass|null
 */
function currentUser()
{
    return Session::read('auth');
}

/**
 * Retourne un token csrf
 *
 * @return string
 */
function generateToken(): string
{
    $token = random_token();
    Session::set('csrf_token', $token);
    Session::set('time', time());
    return $token;
}

/**
 * Vérifie que la session d'authentification existe et qu'un utilisateur est connecté
 *
 * @return bool
 */
function isAuthenticated(): bool
{
    if (currentUser() && currentUser()->statut === 1) {
        return true;
    }
    return false;
}

/**
 * Contrôle si l'utilisateur est l'utilisateur connecté
 *
 * @param  string $id
 * @return bool
 */
function isCurrentUser(string $id): bool
{
    if (currentUser()->id === $id) {
        return true;
    }
    return false;
}

/**
 * Contrôle si le token csrf est valide
 *
 * @param string $limit au format 'nombre unité' (unité = minutes, hours, days,...)
 * @return bool
 */
function isValidToken(string $limit = '15 minutes'): bool
{
    if ((filter_input(INPUT_POST, 'csrf_token') === Session::read('csrf_token')) && Session::read('time') >= strtotime("- $limit")) {
        return true;
    }
    return false;
}
