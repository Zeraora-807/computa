<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<nav class="category-nav" aria-label="文档分类导航">
    <header class="category-nav-head">
        <div>
            <span class="code-label">DOCUMENT TREE</span>
            <strong>文档分类</strong>
        </div>
        <a href="<?php $this->options->siteUrl(); ?>#archive">全部</a>
    </header>
    <div class="category-nav-scroll">
        <?php computa_render_category_navigation($this); ?>
    </div>
</nav>
