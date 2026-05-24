<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\PostService;

class PostController extends Controller
{
    public function show(int $id): void
    {
        $page = PostService::create()->getPostPage($id);

        if ($page === null) {
            $this->notFound('Article not found');

            return;
        }

        $this->view->render('post/show.tpl', $page->toArray());
    }
}
