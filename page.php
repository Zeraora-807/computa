<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<article class="entry page-entry">
    <header class="entry-header reveal">
        <div class="entry-window-bar">
            <div class="window-dots"><i></i><i></i><i></i></div>
            <span><?php echo htmlspecialchars($this->slug, ENT_QUOTES, 'UTF-8'); ?>.md</span>
            <b>PAGE</b>
        </div>
        <div class="entry-title"><p><span>~/archive/pages</span> / <?php echo htmlspecialchars($this->slug, ENT_QUOTES, 'UTF-8'); ?></p><h1><?php $this->title(); ?></h1></div>
    </header>
    <div class="entry-layout">
        <aside class="entry-aside reveal">
            <?php $this->need('sidebar-categories.php'); ?>
            <div class="aside-block on-this-page"><span class="code-label">ON THIS PAGE</span><ol data-toc></ol></div>
        </aside>
        <div class="entry-main"><div class="entry-content reveal" data-entry-content><?php $this->content(); ?></div></div>
    </div>
</article>
<?php $this->need('comments.php'); ?>
<?php $this->need('footer.php'); ?>
