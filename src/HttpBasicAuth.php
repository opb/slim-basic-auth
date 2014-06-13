<?php namespace Slim\Middleware;

/**
 * HTTP Basic Auth
 *
 * HTTP Basic Auth Middleware for the PHP Slim Framework
 *
 * @author     Olly Butterfield <olly@opb.me.uk>
 * @package Slim\Middleware
 */
class HttpBasicAuth extends \Slim\Middleware
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @var AuthCheckerInterface
     */
    protected $checker;

    /**
     * @param AuthCheckerInterface $checker
     * @param null $options
     */
    public function __construct(AuthCheckerInterface $checker, $options = null)
    {

        $this->checker = $checker;

        $this->options = array(
            'path' => '/',
            'realm' => 'Protected Area'
        );

        if ($options)
        {
            $this->options = array_merge($this->options, (array)$options);
        }
    }

    /**
     * Slim Middleware call() function
     */
    public function call()
    {
        $req = $this->app->request;
        $user = $req->headers('PHP_AUTH_USER');
        $pass = $req->headers('PHP_AUTH_PW');
        $path = $req->getPath();


        if (!$this->checkPath($path, $req))
        {
            $this->next->call();
        }
        else
        {
            if ($this->userAuthorised($user, $pass))
            {
                $this->next->call();
            }
            else
            {
                $this->denyAccess();
                return;
            }
        }
    }

    /**
     * Set HTTP 401 response headers
     */
    private function denyAccess()
    {
        $this->app->response->status(401);
        $this->app->response->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->options['realm']));
    }

    /**
     * Matches current path against configured private area
     *
     * @param $path The configured private path
     * @param $req the Slim request object
     * @return bool
     */
    private function checkPath($path, $req)
    {
        $pattern = "/^{$path}/";
        return !!preg_match($pattern, $req->getPath());
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    private function userAuthorised($username, $password)
    {
        return $this->checker->checkCredentials($username, $password);
    }

}
