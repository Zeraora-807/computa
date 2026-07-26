<?php
/**
 * 归档索引
 *
 * @package custom
 */
use Widget\Contents\Post\Recent;
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<section class="archive-shell archive-page">
    <header class="archive-heading reveal"><div><span class="code-label">TREE --CHRONOLOGICAL</span><h1><?php $this->title(); ?></h1></div><p>All entries ordered by publish time.</p></header>
    <div class="archive-layout">
        <aside class="archive-sidebar reveal">
            <?php $this->need('sidebar-categories.php'); ?>
        </aside>
        <div class="tree-list reveal">
            <?php Recent::alloc(['pageSize' => 500])->to($posts); $year = null; ?>
            <?php while ($posts->next()): $postYear = $posts->date->year; ?>
                <?php if ($year !== $postYear): $year = $postYear; ?><h2><span>▾</span> <?php echo $year; ?>/</h2><?php endif; ?>
                <a href="<?php $posts->permalink(); ?>"><time><?php $posts->date('m-d'); ?></time><span><?php $posts->title(); ?></span><i><?php echo strtolower(computa_archive_type($posts)); ?></i></a>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php $this->need('footer.php'); ?>
