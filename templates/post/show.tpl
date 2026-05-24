{extends file="layouts/main.tpl"}

{block name="content"}
    <article class="page-section">
        <header class="page-heading">
            <a class="back-link" href="/">Back to all categories</a>
            <h1>{$post->title|escape}</h1>
            <p>{$post->description|escape}</p>

            <p class="post-meta">
                <span>Published: {$post->publishedAt|escape}</span>
                <span>Views: {$post->viewsCount|escape}</span>
            </p>

            {if $categories|count > 0}
                <nav class="tag-list" aria-label="Article categories">
                    {foreach $categories as $category}
                        <a class="tag" href="/category/{$category->id|escape}">{$category->title|escape}</a>
                    {/foreach}
                </nav>
            {/if}
        </header>

        {if $post->image}
            <img class="article-image" src="{$post->image|escape}" alt="{$post->title|escape}">
        {/if}

        <div class="article-content">
            <p>{$post->content|escape}</p>
        </div>
    </article>

    <section class="page-section">
        <header class="section-heading">
            <h2>Related Articles</h2>
        </header>

        {if $relatedPosts|count === 0}
            <p class="empty-state">No related articles found.</p>
        {else}
            <div class="post-grid">
                {foreach $relatedPosts as $relatedPost}
                    <article class="post-card">
                        {if $relatedPost->image}
                            <img class="post-image" src="{$relatedPost->image|escape}" alt="{$relatedPost->title|escape}" width="320" height="180">
                        {/if}

                        <h3>
                            <a href="/post/{$relatedPost->id|escape}">{$relatedPost->title|escape}</a>
                        </h3>

                        <p>{$relatedPost->description|escape}</p>

                        <p class="post-meta">
                            <span>Published: {$relatedPost->publishedAt|escape}</span>
                            <span>Views: {$relatedPost->viewsCount|escape}</span>
                        </p>
                    </article>
                {/foreach}
            </div>
        {/if}
    </section>
{/block}
