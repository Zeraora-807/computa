<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<nav class="pagination" aria-label="翻页">
    <?php $this->pageNav('prev()', 'next()', 2, '…', ['wrapTag' => 'ol', 'wrapClass' => 'page-list', 'itemTag' => 'li']); ?>
</nav>
