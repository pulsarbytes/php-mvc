<?php
namespace Core;

/**
 * Base view.
 */
class View
{
    /**
     * Render a view file.
     *
     * @param string $view The view file to render
     * @param array $args Associative array of data to display in the view
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = '../App/Views/'.$view;

        if (is_readable($file))
            require $file;
        else
            throw new \Exception("File $file not found", 500);
    }

    /**
     * Render a template using Twig.
     *
     * @param string $template The template file to render
     * @param array $args Associative array of data to display in the view
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}