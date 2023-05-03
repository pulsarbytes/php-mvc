<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;

/**
 * Posts controller.
 */
class Posts extends Controller
{
    /**
     * Display all posts.
     *
     * @return void
     */
    public function display()
    {
        $args = ['posts' => \App\Models\Posts::getPosts()];

        View::renderTemplate('Posts/index.html', $args);
    }
}