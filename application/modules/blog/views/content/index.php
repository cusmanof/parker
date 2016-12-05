<div class="admin-box">
    <h3>Blog Posts</h3>

    <?php
    if (empty($posts) || ! is_array($posts)) :
    ?>
    <div class="alert alert-warning">
        No Posts found.
    </div>
    <?php
    else :
        $numColumns = 2;
        $canDelete = $this->auth->has_permission('Bonfire.Blog.Delete');
        if ($canDelete) {
            ++$numColumns;
        }
        echo form_open();
    ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <?php if ($canDelete) : ?>
                    <th class="column-check"><input class="check-all" type="checkbox" /></th>
                    <?php endif; ?>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
            </thead>
            <?php if ($canDelete) : ?>
            <tfoot>
                <tr>
                    <td colspan="<?php echo $numColumns; ?>">
                        <?php echo lang('bf_with_selected') . ' '; ?>
                        <input type="submit" name="delete" class="btn" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('Are you sure you want to delete these posts?')" />
                    </td>
                </tr>
            </tfoot>
            <?php endif; ?>
            <tbody>
                <?php foreach ($posts as $post) : ?>
                <tr>
                    <?php if ($canDelete) : ?>
                    <td><input type="checkbox" name="checked[]" value="<?php echo $post->post_id; ?>" /></td>
                    <?php endif; ?>
                    <td>
                        <a href="<?php echo site_url(SITE_AREA . "/content/blog/edit_post/{$post->post_id}"); ?>">
                            <?php e($post->title); ?>
                        </a>
                    </td>
                    <td>
                        <?php echo date_format(new DateTime($post->created_on),'j M, Y g:ia'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        echo form_close();
    endif;
        ?>
</div>


