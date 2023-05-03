<?php
namespace App\Controllers\Admin;

use Core\Controller;

/**
 * Admin dashboard controller.
 */
class Dashboard extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return void
     */
    public function display()
    {
        echo '<h1>Admin dashboard</h1>';
    }
}