<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
</main>
<footer class="site-footer">
    <div>
        <span class="prompt">genarch@archive:~$</span>
        <span>echo "The brain is wider than the sky"</span>
    </div>
    <p>© <?php echo date('Y'); ?> <?php echo computa_escape($this->options->archiveOwner); ?></p>
</footer>
<script src="<?php $this->options->themeUrl('app.js'); ?>" defer></script>
<?php $this->footer(); ?>
</body>
</html>
