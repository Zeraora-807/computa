<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$stats = computa_stats($this);
$stack = computa_stack($this);
$thumbnail = computa_safe_url($this->fields->thumbnail ?? '');
$repoUrl = computa_safe_url($this->fields->repoUrl ?? '', false);
?>
<article class="entry">
    <header class="entry-header reveal">
        <div class="entry-window-bar">
            <div class="window-dots"><i></i><i></i><i></i></div>
            <span><?php echo htmlspecialchars($this->slug, ENT_QUOTES, 'UTF-8'); ?>.md</span>
            <b><?php echo computa_archive_type($this); ?></b>
        </div>
        <div class="entry-title">
            <p><span>~/archive/entries</span> / <?php echo htmlspecialchars($this->slug, ENT_QUOTES, 'UTF-8'); ?></p>
            <h1><?php $this->title(); ?></h1>
            <div class="entry-meta">
                <span>created: <?php $this->date('Y-m-d'); ?></span>
                <span>reading_time: <?php echo $stats['minutes']; ?>min</span>
                <span>chars: <?php echo number_format($stats['words']); ?></span>
                <span>comments: <?php $this->commentsNum('0', '1', '%d'); ?></span>
            </div>
            <?php if ($stack): ?>
                <div class="stack-list entry-stack"><?php foreach ($stack as $item): ?><span><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></span><?php endforeach; ?></div>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($thumbnail): ?>
        <figure class="entry-cover reveal"><img src="<?php echo computa_escape($thumbnail); ?>" width="1280" height="720" fetchpriority="high" alt=""></figure>
    <?php endif; ?>

    <div class="entry-layout">
        <aside class="entry-aside reveal">
            <?php $this->need('sidebar-categories.php'); ?>
            <div class="aside-block on-this-page">
                <span class="code-label">ON THIS PAGE</span>
                <ol data-toc></ol>
            </div>
            <div class="aside-block">
                <span class="code-label">METADATA</span>
                <p>category</p><div><?php $this->category(', '); ?></div>
                <p>tags</p><div class="aside-tags"><?php $this->tags('', true, 'none'); ?></div>
            </div>
            <?php if ($repoUrl): ?>
                <a class="repo-link" href="<?php echo computa_escape($repoUrl); ?>" target="_blank" rel="noopener noreferrer">view_repository() ↗</a>
            <?php endif; ?>
        </aside>
        <div class="entry-main">
            <div class="entry-content reveal" data-entry-content><?php $this->content(); ?></div>
            <footer class="entry-footer reveal">
                <div class="entry-license"><span>// end_of_entry</span><p>Thanks for reading. Corrections and thoughtful comments are welcome.</p></div>
                <nav>
                    <div><span>← PREVIOUS</span><?php $this->thePrev('%s', 'null'); ?></div>
                    <div><span>NEXT →</span><?php $this->theNext('%s', 'null'); ?></div>
                </nav>
            </footer>
        </div>
    </div>
</article>
<?php $this->need('comments.php'); ?>
<?php $this->need('footer.php'); ?>
