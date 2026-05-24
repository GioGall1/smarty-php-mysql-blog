<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    public function __construct(
        protected View $view
    ) {
    }

    protected function notFound(string $pageTitle = 'Page not found'): void
    {
        http_response_code(404);
        $this->view->render('errors/404.tpl', [
            'pageTitle' => $pageTitle,
        ]);
    }
}
