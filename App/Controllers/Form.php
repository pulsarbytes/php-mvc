<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;

/**
 * Form controller.
 */
class Form extends Controller
{
    /**
     * Display the form.
     *
     * @return void
     */
    public function display()
    {
        $args = [];
        $title = \App\Models\Form::getTitle();

        if (is_string($title) && $title !== '')
        {
            $args['title'] = $title;
            $content = \App\Models\Form::generateContent($title);

            if (is_string($content) && $content !== '')
                $args['content'] = $content;
        }

        View::renderTemplate('Form/index.html', $args);
    }
}