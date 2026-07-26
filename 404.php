<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<section class="error-screen">
    <div class="error-window reveal">
        <header><div class="window-dots"><i></i><i></i><i></i></div><span>error.log</span></header>
        <div>
            <p><span>GET</span> <?php echo htmlspecialchars($this->request->getRequestUrl(), ENT_QUOTES, 'UTF-8'); ?></p>
            <h1>404</h1>
            <pre><code>Error: archive entry not found
at router.resolve(request)
at computa.render()
status: NOT_FOUND</code></pre>
            <a href="<?php $this->options->siteUrl(); ?>">cd ~/archive ↵</a>
        </div>
    </div>
</section>
<?php $this->need('footer.php'); ?>
