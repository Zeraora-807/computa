<?php

use Typecho\Widget\Helper\Form\Element\Select;
use Typecho\Widget\Helper\Form\Element\Text;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

function themeConfig($form)
{
    $form->addInput(new Text(
        'heroImage',
        null,
        null,
        _t('开屏图片'),
        _t('填写图片 URL。建议使用宽度 1920px 以内的 AVIF 或 WebP 图片。')
    ));
    $form->addInput(new Text(
        'archiveOwner',
        null,
        'Genarch',
        _t('Archive 作者名')
    ));
    $form->addInput(new Text(
        'avatarUrl',
        null,
        null,
        _t('作者头像'),
        _t('可选。不填写时使用主题自带头像。')
    ));
    $form->addInput(new Text(
        'archiveNote',
        null,
        'Lightweight code, technical notes, and things worth remembering.',
        _t('Archive 简介')
    ));
    $form->addInput(new Text(
        'githubUrl',
        null,
        null,
        _t('GitHub 地址')
    ));
}

function themeFields($layout)
{
    $layout->addItem(new Text(
        'thumbnail',
        null,
        null,
        _t('封面图片'),
        _t('文章列表左侧和文章详情页使用的图片 URL。')
    ));
    $layout->addItem(new Text(
        'techStack',
        null,
        null,
        _t('技术栈'),
        _t('使用英文逗号分隔，例如 PHP, Typecho, CSS。')
    ));
    $layout->addItem(new Text(
        'repoUrl',
        null,
        null,
        _t('代码仓库地址')
    ));
    $layout->addItem(new Select(
        'archiveType',
        ['article' => _t('技术文章'), 'snippet' => _t('轻量代码'), 'note' => _t('开发笔记')],
        'article',
        _t('归档类型')
    ));
}

function computa_archive_type($archive)
{
    $types = [
        'article' => 'TECHNICAL ARTICLE',
        'snippet' => 'CODE SNIPPET',
        'note' => 'DEV NOTE',
    ];
    $type = isset($archive->fields->archiveType) ? (string) $archive->fields->archiveType : 'article';
    return $types[$type] ?? $types['article'];
}

function computa_stack($archive)
{
    if (!isset($archive->fields->techStack) || !$archive->fields->techStack) {
        return [];
    }
    return array_values(array_filter(array_map('trim', explode(',', (string) $archive->fields->techStack))));
}

function computa_stats($archive)
{
    $content = trim(preg_replace('/\s+/u', '', strip_tags((string) $archive->content)));
    $length = function_exists('mb_strlen') ? mb_strlen($content, 'UTF-8') : strlen($content);
    return ['words' => $length, 'minutes' => max(1, (int) ceil($length / 350))];
}

function computa_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function computa_safe_url($value, $allowRelative = true)
{
    $value = trim((string) $value);
    if ($value === '' || preg_match('/[\x00-\x1F\x7F]/', $value)) {
        return '';
    }
    if ($allowRelative && strpos($value, '/') === 0 && strpos($value, '//') !== 0) {
        return $value;
    }
    if (!filter_var($value, FILTER_VALIDATE_URL)) {
        return '';
    }
    $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
    return in_array($scheme, ['http', 'https'], true) ? $value : '';
}

function computa_css_url($value)
{
    $value = computa_safe_url($value);
    if ($value === '') {
        return '';
    }
    return str_replace(
        ["\\", "'", "\n", "\r", "\f"],
        ["\\\\", "\\27 ", "\\A ", "\\D ", "\\C "],
        $value
    );
}

function computa_comment($comments, $options)
{
    $isOwner = $comments->authorId && $comments->authorId === $comments->ownerId;
    $author = computa_escape($comments->author);
    $authorUrl = computa_safe_url($comments->url, false);
    ?>
    <li id="<?php $comments->theId(); ?>" class="comment-item<?php echo $isOwner ? ' is-owner' : ''; ?>">
        <header>
            <?php $comments->gravatar(38, 'identicon', true); ?>
            <div>
                <strong><?php if ($authorUrl): ?><a href="<?php echo computa_escape($authorUrl); ?>" rel="nofollow ugc noopener"><?php echo $author; ?></a><?php else: ?><?php echo $author; ?><?php endif; ?></strong>
                <?php if ($isOwner): ?><span>OWNER</span><?php endif; ?>
                <time datetime="<?php $comments->date('c'); ?>"><?php $comments->date('Y-m-d H:i'); ?></time>
            </div>
            <div class="comment-reply"><?php $comments->reply('reply()'); ?></div>
        </header>
        <div class="comment-text"><?php $comments->content(); ?></div>
        <?php if ($comments->children): ?>
            <ol class="comment-children"><?php $comments->threadedComments($options); ?></ol>
        <?php endif; ?>
    </li>
    <?php
}

/**
 * Build a hierarchical category -> post navigation tree for the sidebar.
 * Each category only lists posts directly assigned to it; child categories
 * are rendered as nested folders so the structure matches Typecho's taxonomy.
 */
function computa_category_navigation_data($archive, $postLimit = 40)
{
    $postLimit = max(1, min(100, (int) $postLimit));
    $nodes = [];

    \Widget\Metas\Category\Rows::alloc()->to($categories);
    while ($categories->next()) {
        $mid = (int) $categories->mid;
        $count = (int) $categories->count;
        if ($mid <= 0 || $count <= 0) {
            continue;
        }

        $posts = [];
        try {
            $categoryPosts = \Widget\Archive::allocWithAlias(
                'computa-category-' . $mid,
                [
                    'pageSize' => $postLimit,
                    'type' => 'category',
                    'checkPermalink' => false,
                ],
                ['mid' => $mid],
                false
            );

            while ($categoryPosts && $categoryPosts->next()) {
                $posts[] = [
                    'cid' => (int) $categoryPosts->cid,
                    'title' => (string) $categoryPosts->title,
                    'permalink' => (string) $categoryPosts->permalink,
                ];
            }
        } catch (\Throwable $e) {
            // A navigation failure should never take the whole site down.
            $posts = [];
        }

        $nodes[$mid] = [
            'mid' => $mid,
            'parent' => (int) $categories->parent,
            'name' => (string) $categories->name,
            'slug' => (string) $categories->slug,
            'permalink' => (string) $categories->permalink,
            'count' => $count,
            'posts' => $posts,
            'children' => [],
        ];
    }

    foreach (array_keys($nodes) as $mid) {
        $parent = $nodes[$mid]['parent'];
        if ($parent > 0 && isset($nodes[$parent])) {
            $nodes[$parent]['children'][] = $mid;
        }
    }

    $roots = [];
    foreach ($nodes as $mid => $node) {
        if ($node['parent'] <= 0 || !isset($nodes[$node['parent']])) {
            $roots[] = $mid;
        }
    }

    return ['nodes' => $nodes, 'roots' => $roots];
}

function computa_current_category_mids($archive)
{
    $mids = [];
    if (isset($archive->categories) && is_array($archive->categories)) {
        foreach ($archive->categories as $category) {
            if (isset($category['mid'])) {
                $mids[] = (int) $category['mid'];
            }
        }
    }
    return array_values(array_unique($mids));
}

function computa_render_category_nodes($archive, array $nodes, array $nodeIds, $activeCid, array $activeCategoryMids, $depth = 0)
{
    if (!$nodeIds) {
        return;
    }

    echo '<ol class="category-tree-level depth-' . (int) $depth . '">';
    foreach ($nodeIds as $mid) {
        if (!isset($nodes[$mid])) {
            continue;
        }

        $node = $nodes[$mid];
        $isCategoryActive = in_array((int) $mid, $activeCategoryMids, true)
            || (method_exists($archive, 'is') && $archive->is('category', $node['slug']));
        $hasBranch = !empty($node['posts']) || !empty($node['children']);
        $nodeClass = 'category-node';
        if ($isCategoryActive) {
            $nodeClass .= ' is-active';
        }

        echo '<li class="' . $nodeClass . '" data-category-node>';
        echo '<div class="category-row">';
        if ($hasBranch) {
            echo '<button class="category-toggle" type="button" aria-expanded="true" aria-label="折叠或展开 ' . computa_escape($node['name']) . '" data-category-toggle><span aria-hidden="true">⌄</span></button>';
        } else {
            echo '<span class="category-toggle-spacer" aria-hidden="true"></span>';
        }
        echo '<a class="category-link" href="' . computa_escape($node['permalink']) . '"><span class="category-folder" aria-hidden="true">▰</span><span>' . computa_escape($node['name']) . '</span></a>';
        echo '<b>' . number_format((int) $node['count']) . '</b>';
        echo '</div>';

        if ($hasBranch) {
            echo '<div class="category-branch">';
            if (!empty($node['posts'])) {
                echo '<ol class="category-posts">';
                foreach ($node['posts'] as $post) {
                    $postClass = ((int) $post['cid'] === (int) $activeCid) ? ' class="is-current"' : '';
                    echo '<li' . $postClass . '><a href="' . computa_escape($post['permalink']) . '"><span aria-hidden="true">└</span>' . computa_escape($post['title']) . '</a></li>';
                }
                if ((int) $node['count'] > count($node['posts'])) {
                    echo '<li class="category-more"><a href="' . computa_escape($node['permalink']) . '">查看全部 ' . number_format((int) $node['count']) . ' 篇 →</a></li>';
                }
                echo '</ol>';
            }

            if (!empty($node['children'])) {
                computa_render_category_nodes($archive, $nodes, $node['children'], $activeCid, $activeCategoryMids, $depth + 1);
            }
            echo '</div>';
        }

        echo '</li>';
    }
    echo '</ol>';
}

function computa_render_category_navigation($archive)
{
    $tree = computa_category_navigation_data($archive);
    $activeCid = isset($archive->cid) ? (int) $archive->cid : 0;
    $activeCategoryMids = computa_current_category_mids($archive);

    if (!$tree['roots']) {
        echo '<p class="category-empty">还没有可显示的分类文档。</p>';
        return;
    }

    computa_render_category_nodes(
        $archive,
        $tree['nodes'],
        $tree['roots'],
        $activeCid,
        $activeCategoryMids
    );
}
