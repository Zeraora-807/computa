<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<section class="archive-shell archive-page" id="archive">
    <header class="archive-heading reveal">
        <div><span class="code-label">QUERY RESULT</span><h1><?php $this->archiveTitle(['category' => 'category("%s")', 'search' => 'search("%s")', 'tag' => 'tag("%s")', 'author' => 'author("%s")'], '', ''); ?></h1></div>
        <p><?php echo computa_escape($this->getDescription() ?: 'Filtered archive entries.'); ?></p>
    </header>
    <div class="archive-layout">
        <aside class="archive-sidebar reveal">
            <?php $this->need('sidebar-categories.php'); ?>
        </aside>
        <div class="archive-list">
            <?php if ($this->have()): ?>
                <?php while ($this->next()): ?><?php $this->need('post-card.php'); ?><?php endwhile; ?>
                <?php $this->need('pagination.php'); ?>
            <?php else: ?>
                <div class="empty"><span>404</span><p>No matching entries.</p></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $this->need('footer.php'); ?>
