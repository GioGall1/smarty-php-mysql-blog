{extends file="layouts/main.tpl"}

{block name="content"}
    <section class="page-section">
        <header class="page-heading">
            <a class="back-link" href="/">Back to all categories</a>
            <h1>{$category->title|escape}</h1>
            <p>{$category->description|escape}</p>
            <p class="post-meta">
                <span>{$totalPosts|escape} articles</span>
                <span>Page {$page|escape} of {$totalPages|escape}</span>
            </p>
        </header>

        <nav class="toolbar" aria-label="Sort articles">
            <strong>Sort by:</strong>
            <a class="button{if $sort === 'date'} button-primary{/if}" href="/category/{$category->id|escape}?sort=date"{if $sort === 'date'} aria-current="true"{/if}>
                Newest
            </a>
            <a class="button{if $sort === 'views'} button-primary{/if}" href="/category/{$category->id|escape}?sort=views"{if $sort === 'views'} aria-current="true"{/if}>
                Most viewed
            </a>
        </nav>

        {if $totalPages > 1}
            <nav class="pagination pagination-top" aria-label="Pagination">
                <strong>Page {$page|escape} of {$totalPages|escape}:</strong>

                {if $page > 1}
                    <a class="button" href="/category/{$category->id|escape}?sort={$sort|escape}&page={$page - 1}">Previous</a>
                {/if}

                {for $paginationPage=1 to $totalPages}
                    <a class="button{if $paginationPage === $page} button-primary{/if}" href="/category/{$category->id|escape}?sort={$sort|escape}&page={$paginationPage}"{if $paginationPage === $page} aria-current="page"{/if}>
                        {$paginationPage}
                    </a>
                {/for}

                {if $page < $totalPages}
                    <a class="button" href="/category/{$category->id|escape}?sort={$sort|escape}&page={$page + 1}">Next</a>
                {/if}
            </nav>
        {/if}

        {if $posts|count === 0}
            <p class="empty-state">No articles in this category yet.</p>
        {else}
            <div class="post-grid">
                {foreach $posts as $post}
                    <article class="post-card">
                        {if $post->image}
                            <img class="post-image" src="{$post->image|escape}" alt="{$post->title|escape}" width="320" height="180">
                        {/if}

                        <h3>
                            <a href="/post/{$post->id|escape}">{$post->title|escape}</a>
                        </h3>

                        <p>{$post->description|escape}</p>

                        <p class="post-meta">
                            <span>Published: {$post->publishedAt|escape}</span>
                            <span>Views: {$post->viewsCount|escape}</span>
                        </p>
                    </article>
                {/foreach}
            </div>
        {/if}

        {if $totalPages > 1}
            <nav class="pagination" aria-label="Pagination">
                <strong>Page {$page|escape} of {$totalPages|escape}:</strong>

                {if $page > 1}
                    <a class="button" href="/category/{$category->id|escape}?sort={$sort|escape}&page={$page - 1}">Previous</a>
                {/if}

                {for $paginationPage=1 to $totalPages}
                    <a class="button{if $paginationPage === $page} button-primary{/if}" href="/category/{$category->id|escape}?sort={$sort|escape}&page={$paginationPage}"{if $paginationPage === $page} aria-current="page"{/if}>
                        {$paginationPage}
                    </a>
                {/for}

                {if $page < $totalPages}
                    <a class="button" href="/category/{$category->id|escape}?sort={$sort|escape}&page={$page + 1}">Next</a>
                {/if}
            </nav>
        {/if}
    </section>
{/block}
