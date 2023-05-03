<?php
namespace Core;

/**
 * Router.
 */
class Router
{
    /**
     * Associative array of routes (routing table)
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from the matched route
     * @var array
     */
    protected $params = [];

    /**
     * Add a route to the routing table,
     * convert a variable route to a proper regex to be used in routing table.
     *
     * @param string $route The route URL
     * @param array $params Parameters (controller, action, etc)
     *
     * @return void
     */
    public function add($route, $params = [])
    {
        // Escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert any string {variable}
        $route = preg_replace('/\{([a-z-]+)\}/', '(?<\1>[a-z-]+)', $route);

        // Convert any custom {variable}
        $route = preg_replace('/\{([a-z-]+):([^\}]+)\}/', '(?<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag
        $route = '/^'.$route.'$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Get all routes from the routing table.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Match the route URL to the routes in the routing table,
     * setting the $params property if a route is found.
     *
     * @param string $url The route URL
     *
     * @return boolean True if a match is found, false otherwise
     */
    protected function match($url)
    {
        foreach ($this->routes as $route => $params)
        {
            if (preg_match($route, $url, $matches))
            {
                foreach ($matches as $key => $match)
                {
                    if (is_string($key))
                        $params[$key] = $match;
                }

                $this->params = $params;

                return true;
            }
    }

        return false;
    }

    /**
     * Get the currently matched parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Dispatches the route: creates the controller object
     * and runs the action method.
     *
     * @param string $url The route URL
     *
     * @return void
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringParameters($url);
        $url = rtrim($url, '/');

        if ($this->match($url))
        {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace().$controller;

            if (class_exists($controller))
            {
                $controller_object = new $controller($this->params);
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                // Prevent direct access to methods ending in 'Action'
                if (preg_match('/action$/i', $action) == 0)
                    $controller_object->$action();
                else
                    throw new \Exception("Method $action (in controller $controller) not found.", 500);
            }
            else
                throw new \Exception("Controller class $controller not found.", 500);
        }
        else
            throw new \Exception('No route matched.', 404);
    }

    /**
     * Convert a string with hyphens to stydly caps,
     * e.g. post-authors => PostAuthors.
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert a string with hyphens to camel case,
     * e.g. add-new => addNew.
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove query string parameters from url.
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string parameters removed
     */
    protected function removeQueryStringParameters($url)
    {
        if ($url != '')
        {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false)
                $url = $parts[0];
            else
                $url = '';
        }

        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace
     * defined in the route parameters is added if present.
     *
     * @return string The namespace
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params))
            $namespace .= $this->params['namespace'].'\\';

        return $namespace;
    }
}