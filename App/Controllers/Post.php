<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;

/**
 * Post controller.
 */
class Post extends Controller
{
    /**
     * Display a single post.
     *
     * @return void
     */
    public function display()
    {
        $post = \App\Models\Post::getPost((int) $this->route_params['id']);
        $args = ['post' => $post];

        View::renderTemplate('Post/index.html', $args);
    }

    /**
     * Save the post and redirect to the post page.
     *
     * @return void
     */
    public function saveAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $data = [];

            if (isset($_POST['title']) && $_POST['title'] !== '')
            {
                // Sanitize the input using htmlspecialchars
                $data['title'] = htmlspecialchars($_POST['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

                if (isset($_POST['content']) && $_POST['content'] !== '')
                {
                    // Sanitize the input using htmlspecialchars
                    $data['content'] = htmlspecialchars($_POST['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    if ($id = \App\Models\Post::save($data))
                    {
                        // Redirect to the post page
                        header("Location: http://localhost/mvc/post/$id/display");
                    }
                }
            }
        }
    }

    /**
     * Before filter.
     */
    protected function before()
    {}

    /**
     * After filter.
     */
    protected function after()
    {}
}