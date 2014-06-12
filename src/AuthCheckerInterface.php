<?php namespace Slim\Middleware;

/**
 * Interface AuthCheckerInterface
 * @package Slim\Middleware
 */
interface AuthCheckerInterface
{
    public function checkCredentials($username, $password);
}