<?php
/**
 * 为代码片段和技术文章设计的轻量归档主题。
 *
 * @package Computa
 * @version 1.1.0
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$heroImage = computa_safe_url($this->options->heroImage);
$heroImageCss = computa_css_url($heroImage);
$avatarUrl = computa_safe_url($this->options->avatarUrl);
$archiveOwner = computa_escape($this->options->archiveOwner);
?>
<section class="hero <?php echo $heroImageCss ? 'has-image' : 'has-default-image'; ?>"<?php if ($heroImageCss): ?> style="--hero-image:url('<?php echo computa_escape($heroImageCss); ?>')"<?php endif; ?>>
    <div class="hero-grid" aria-hidden="true"></div>
    <div class="hero-terminal">
        <div class="terminal-chrome">
            <div class="window-dots"><i></i><i></i><i></i></div>
            <span>archive_search.sh</span>
            <b>⌘ K</b>
        </div>
        <div class="hero-terminal-body">
            <p class="coding-title"><span>Coding Archive by <?php echo $archiveOwner; ?></span><i></i></p>
            <form class="hero-search" method="post" action="<?php $this->options->siteUrl(); ?>">
                <label for="archive-search"><span>~/archive</span> $</label>
                <input id="archive-search" name="s" type="search" placeholder="search notes, code, ideas..." autocomplete="off">
                <button type="submit">run ↵</button>
            </form>
            <div class="command-hints"><span>php</span><span>javascript</span><span>linux</span><span>typecho</span></div>
        </div>
    </div>
    <a class="scroll-cue" href="#archive"><span>scroll_to_archive()</span><i>↓</i></a>
</section>

<section class="archive-shell" id="archive">
    <header class="archive-heading reveal">
        <div>
            <span class="code-label">01 / INDEX</span>
            <h1>Technical Archive</h1>
        </div>
        <div class="archive-owner">
            <div class="owner-avatar">
                <?php if ($avatarUrl): ?>
                    <img src="<?php echo computa_escape($avatarUrl); ?>" width="58" height="58" loading="lazy" alt="<?php echo $archiveOwner; ?>">
                <?php else: ?>
                    <img src="<?php $this->options->themeUrl('assets/avatar.webp'); ?>" width="58" height="58" loading="lazy" alt="<?php echo $archiveOwner; ?>">
                <?php endif; ?>
                <i title="online"></i>
            </div>
            <div>
                <span class="owner-handle">@<?php echo $archiveOwner; ?></span>
                <p><?php echo computa_escape($this->options->archiveNote); ?></p>
            </div>
        </div>
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
                <div class="empty"><span>404</span><p>No archive entries found.</p></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $this->need('footer.php'); ?>
