<div class="admin-box">
    <h3>New Post</h3>
    <?php echo form_open(current_url(), 'class="form-horizontal"'); ?>
        <fieldset>
            <div class="control-group<?php echo form_error('title') ? ' error' : ''; ?>">
                <label for="title">Title</label>
                <div class="controls">
                    <input type="text" name="title" id="title" class="input-xxlarge" value="<?php echo isset($post) ? $post->title : set_value('title'); ?>" />
                    <span class='help-inline'><?php echo form_error('title'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('slug') ? ' error' : ''; ?>">
                <label for="slug">Slug</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><?php echo site_url() . '/blog/'; ?></span>
                        <input type="text" name="slug" id="slug" class="input-xlarge" value="<?php echo isset($post) ? $post->slug : set_value('slug'); ?>" />
                    </div>
                    <span class="help-inline"><?php echo form_error('slug'); ?></span>
                    <p class="help-block">The unique URL that this post can be viewed at.</p>
                </div>
            </div>

            <div class="control-group<?php echo form_error('body') ? ' error' : ''; ?>">
                <label for="body">Content</label>
                <div class="controls">
                    <span class="help-inline"><?php echo form_error('body'); ?></span>
                    <textarea name="body" id="body" class="input-xxlarge" rows="15"><?php echo isset($post) ? $post->body : set_value('body'); ?></textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="form-actions">
            <input type="submit" name="submit" class="btn btn-primary" value="Save Post" />
            <?php echo ' ' . lang('bf_or') . ' '; ?>
            <a href="<?php echo site_url(SITE_AREA . '/content/blog'); ?>">Cancel</a>
        </fieldset>
    <?php echo form_close(); ?>
</div>
