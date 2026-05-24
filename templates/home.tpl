{extends file="layouts/main.tpl"}

{block name="content"}
    <section class="page-section">
        <header class="page-heading">
            <h1>Latest Articles by Category</h1>
            <p>Categories with published articles and their latest posts.</p>
        </header>

        {if $categories|count === 0}
            <p class="empty-state">No articles have been published yet.</p>
        {/if}

        <div class="category-list">
        {foreach $categories as $category}
            <section class="category-block">
                <header class="category-header">
                    <div>
                        <p class="category-count">{$category->postsCount|escape} articles</p>
                        <h2>
                            <a href="/category/{$category->id|escape}">{$category->title|escape}</a>
                        </h2>
                        <p>{$category->description|escape}</p>
                    </div>

                    <a class="button button-primary" href="/category/{$category->id|escape}">
                        View all articles
                    </a>
                </header>

                {if $category->posts|count > 0}
                    <div class="post-grid">
                        {foreach $category->posts as $post}
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

                <p class="category-footer">
                    <a href="/category/{$category->id|escape}">
                        Browse all {$category->postsCount|escape} articles in {$category->title|escape}
                    </a>
                </p>
            </section>
        {/foreach}
        </div>
    </section>
{/block}
