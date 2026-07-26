<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<section class="comments" id="comments">
    <?php if ($this->allow('comment')): ?>
        <?php $this->comments()->to($comments); ?>
        <header class="comments-heading reveal">
            <span class="code-label">DISCUSSION.LOG</span>
            <h2><?php $this->commentsNum('No comments yet', '1 comment', '%d comments'); ?></h2>
        </header>
        <div id="<?php $this->respondId(); ?>" class="respond reveal">
            <div class="respond-bar"><div class="window-dots"><i></i><i></i><i></i></div><span>new_comment.json</span></div>
            <form method="post" action="<?php $this->commentUrl(); ?>">
                <?php if ($this->user->hasLogin()): ?>
                    <p class="login-state">logged_in_as: <a href="<?php $this->options->profileUrl(); ?>"><?php echo computa_escape($this->user->screenName); ?></a> · <a href="<?php $this->options->logoutUrl(); ?>">logout()</a></p>
                <?php else: ?>
                    <div class="comment-fields">
                        <label><span>"name":</span><input name="author" value="<?php $this->remember('author'); ?>" required autocomplete="name" placeholder="required"></label>
                        <label><span>"email":</span><input name="mail" type="email" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail): ?>required<?php endif; ?> autocomplete="email" placeholder="private"></label>
                        <label><span>"url":</span><input name="url" type="url" value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireUrl): ?>required<?php endif; ?> autocomplete="url" placeholder="optional"></label>
                    </div>
                <?php endif; ?>
                <label class="comment-message"><span>"message":</span><textarea name="text" rows="5" required placeholder="Write a useful comment…"></textarea></label>
                <div class="respond-submit"><?php $comments->cancelReply(); ?><button type="submit">commit_comment() ↗</button></div>
            </form>
        </div>
        <?php if ($comments->have()): ?>
            <ol class="comment-list">
                <?php $comments->listComments(['before' => '', 'after' => '', 'callback' => 'computa_comment']); ?>
            </ol>
            <nav class="pagination"><?php $comments->pageNav('prev()', 'next()', 2, '…', ['wrapTag' => 'ol', 'wrapClass' => 'page-list', 'itemTag' => 'li']); ?></nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty"><span>LOCKED</span><p>Comments are disabled for this entry.</p></div>
    <?php endif; ?>
</section>
