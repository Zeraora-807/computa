<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; $stack = computa_stack($this); $thumbnail = computa_safe_url($this->fields->thumbnail ?? ''); ?>
<article class="archive-card reveal">
    <a class="card-cover" href="<?php $this->permalink(); ?>" aria-label="<?php $this->title(); ?>">
        <?php if ($thumbnail): ?>
            <img src="<?php echo computa_escape($thumbnail); ?>" loading="lazy" decoding="async" alt="">
        <?php else: ?>
            <div class="cover-code" aria-hidden="true">
                <span>01&nbsp;&nbsp;const archive = {</span>
                <span>02&nbsp;&nbsp;&nbsp;&nbsp;type: "<?php echo strtolower(computa_archive_type($this)); ?>",</span>
                <span>03&nbsp;&nbsp;&nbsp;&nbsp;status: "published",</span>
                <span>04&nbsp;&nbsp;};</span>
            </div>
        <?php endif; ?>
        <span class="file-extension">.<?php echo isset($this->fields->archiveType) && $this->fields->archiveType === 'snippet' ? 'code' : 'md'; ?></span>
    </a>
    <div class="card-info">
        <div class="card-meta">
            <span><?php echo computa_archive_type($this); ?></span>
            <time datetime="<?php $this->date('c'); ?>"><?php $this->date('Y-m-d'); ?></time>
        </div>
        <h2><a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></h2>
        <p><?php $this->excerpt(125, '…'); ?></p>
        <footer>
            <div class="stack-list">
                <?php foreach (array_slice($stack, 0, 4) as $item): ?><span><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></span><?php endforeach; ?>
            </div>
            <a class="read-command" href="<?php $this->permalink(); ?>">open_entry() <i>↗</i></a>
        </footer>
    </div>
</article>
