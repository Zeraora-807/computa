<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!doctype html>
<html lang="zh-Hans">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#f0eee8">
    <title><?php $this->archiveTitle('', '', ' / '); ?><?php $this->options->title(); ?></title>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    <?php $this->header('generator=&template=&pingback=&xmlrpc=&wlw='); ?>
</head>
<body>
<a class="skip-link" href="#main">skip_to_content();</a>
<header class="topbar">
    <div class="topbar-inner">
        <a class="window-dots" href="<?php $this->options->siteUrl(); ?>" aria-label="返回首页">
            <i></i><i></i><i></i>
        </a>
        <a class="topbar-path" href="<?php $this->options->siteUrl(); ?>">
            <span>~/</span><?php $this->options->title(); ?><b>_</b>
        </a>
        <nav>
            <a href="<?php $this->options->siteUrl(); ?>#archive">archive</a>
            <?php \Widget\Contents\Page\Rows::alloc()->to($navPages); ?>
            <?php while ($navPages->next()): ?><a href="<?php $navPages->permalink(); ?>"><?php $navPages->title(); ?></a><?php endwhile; ?>
            <?php $githubUrl = computa_safe_url($this->options->githubUrl, false); ?>
            <?php if ($githubUrl): ?><a href="<?php echo computa_escape($githubUrl); ?>" target="_blank" rel="noopener noreferrer">github ↗</a><?php endif; ?>
        </nav>
        <button class="menu-toggle" type="button" aria-label="打开导航" data-menu-toggle>menu</button>
    </div>
</header>
<main id="main">
