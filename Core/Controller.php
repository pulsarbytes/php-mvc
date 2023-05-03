<?php
namespace Core;

/**
 * Base controller.
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     */
    protected $route_params = [];

    /**
     * Class constructor.
     *
     * @param array $route_params Parameters from the route
     *
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * Magic method that is invoked automatically when a non-existing method
     * or inaccessible method is called.
     *
     * @param string $name The method name
     * @param array $arguments The method arguments
     *
     * @return void
     */
    public function __call($name, $arguments)
    {
        // Add a suffix to the method name
        $method = $name.'Action';

        if (method_exists($this, $method))
        {
            if ($this->before() !== false)
            {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        }
        else
            throw new \Exception("Method $method not found in controller ".get_class($this), 500);
    }

    /**
     * Before filter - called before an action method.
     *
     * @return bool|null True or null if successfull, false if we want to stop further execution
     */
    protected function before()
    {}

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after()
    {}
}