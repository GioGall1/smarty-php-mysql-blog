<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\HomeService;

class HomeController extends Controller
{
    public function index(): void
    {
        $page = HomeService::create()->getHomePage();

        $this->view->render('home.tpl', $page->toArray());
    }
}
