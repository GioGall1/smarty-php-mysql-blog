<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function show(int $id): void
    {
        $page = CategoryService::create()->getCategoryPage(
            $id,
            $_GET['sort'] ?? null,
            $this->getPage()
        );

        if ($page === null) {
            $this->notFound('Category not found');

            return;
        }

        $this->view->render('category/show.tpl', $page->toArray());
    }

    private function getPage(): ?int
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

        return $page !== false ? $page : null;
    }
}
