<div class="jumbotron" text-align="center">

    <?php if ($this->auth->is_logged_in()) :
        echo '<h2>Welcome ' . $this->auth->user()->username .'</h2>';  ?>

        <br/><br/>
        <a href="<?php echo site_url('/year') ?>" class="btn btn-large btn-info">Show free days</a>
        <a href="<?php echo site_url('/main') ?>" class="btn btn-large btn-info">Calendar</a>
        <a href="<?php echo site_url('/alloc') ?>" class="btn btn-large btn-info">List allocations</a>
        <?php if ($this->auth->role_id() == 1) : ?>
            <br/><br/><a href="<?php echo site_url('/admin') ?>" class="btn btn-large btn-info">Admin</a> 
        <?php endif ?> 
    <?php else : ?> 
        <h2>Welcome to PARKER</h2> 
        <p class="lead">If you are not using your carpark, sign in and free it up for someone else.<br/>If you require a carpark, sign in and see if any spots are available.</p>
    <?php endif ?> 
</div>
 
<hr />
<div class="admin-box">
    <h3>Latest news</h3>

    <?php
    if (empty($posts) || !is_array($posts)) :
        ?>
        <div class="alert alert-warning">
            No Posts found.
        </div>
        <?php
    else :
        $numColumns = 3;
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="16%">Title</th>
                    <th width="16%">Date</th>
                    <th>Article</th>
                </tr>
            </thead>

            <tbody>
    <?php foreach ($posts as $post) : ?>
                    <tr>
                        <td>
        <?php e($post->title); ?>
                        </td>
                        <td>
        <?php echo date_format(new DateTime($post->created_on), 'j M, Y g:ia'); ?>
                        </td>
                        <td style="white-space:pre-line;"><?php e(trim($post->body)); ?></td>
                    </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
    <?php
    endif;
    ?>
</div>