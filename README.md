# Basic Auth Middleware for Slim

This HTTP Basic Auth Middleware plugin for Slim. Key features:

- Protects a path and all sub-paths. For example, setting it to protect `/admin` will also protect `/admin/foo` and `/admin/bar/baz` but not `/foo`.

- Provides an interface `AuthCheckerInterface` for you to implement, in order to check a username/password combo against your own user database. An example is provided below of this in use.

## Install

Install via composer:

```json
{
    "require": {
        "opb/slim-basic-auth": "dev-master"
    }
}
```

## Usage

The `HttpBasicAuth` middleware class is instantiated with two parameters: a mandatory implementation of `AuthCheckerInterface` and an optional array of options. The two options currently supported are the `path` to match, and the `realm` if you wish to set that. The example below shows how you might implement this.


```php
// MyAuthClass - implementing the required AuthCheckerInterface

class MyAuthClass implements \Slim\Middleware\AuthCheckerInterface
{
	// only function required by the interface
	public function checkCredentials($username, $password)	{
		return $this->myAuthFunction($username, $password);	
	}
	
	// interact with your own auth system
	protected function myAuthFunction($username, $password)
	{
		// do some stuff and return true if authorised, false if not
	}
}

// the rest of your Slim app, adding in the middleware

$app = new \Slim\Slim();

$authChecker = new MyAuthCLass;

$app->add(new \Slim\Middleware\HttpBasicAuth($authChecker, array(
	'path' => '/api', // optional, defaults to '/'
	'realm' => 'Protected API' // optional, defaults to 'Protected Area'
)));
```



