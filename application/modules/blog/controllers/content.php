<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Content extends Admin_Controller {

    /**
     * Basic constructor. Calls the Admin_Controller's constructor, then sets
     * the toolbar title displayed on the admin/content/blog page.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('post_model');
        Template::set('toolbar_title', 'Manage Your Blog');
        Template::set_block('sub_nav', 'content/sub_nav');
    }

    /**
     * The default page for this context.
     *
     * @return void
     */
    public function index() {
        if (isset($_POST['delete'])) {
            $this->deletePosts($this->input->post('checked'));
        }
        
        // Finished handling the post, now display the list
        $posts = $this->post_model->where('deleted', 0)->find_all();

        Template::set('posts', $posts);

        Template::render();
    }

    public function deletePosts($postIds) {
        // If no posts were selected, display an error message.
        if (empty($postIds) || !is_array($postIds)) {
            Template::set_message('You have not selected any records to delete.', 'error');
            return false;
        }

        // Only allow users with the correct permission to delete posts
        $this->auth->restrict('Bonfire.Blog.Delete');

        // Track any failures while deleting the selected posts.
        $failed = 0;
        foreach ($postIds as $postId) {
            $result = $this->post_model->delete($postId);
            if (!$result) {
                ++$failed;
            }
        }

        $result = false;
        if ($failed) {
            Template::set_message("There was a problem deleting {$failed} post(s): {$this->post_model->error}", 'error');
        } else {
            Template::set_message('Deleted ' . count($postIds) . ' post(s)', 'success');
            $result = true;
        }

        // if any tickets were deleted, log the activity.
        if ((count($postIds) - $failed) > 0) {
            log_activity(
                    $this->auth->user_id(), 'Deleted ' . count($postIds) . ' post(s) : ' . $this->input->ip_address(), 'blog'
            );
        }

        return $result;
    }

    public function create() {
        if ($this->input->post('submit')) {
            $data = array(
                'title' => $this->input->post('title'),
                'slug' => $this->input->post('slug'),
                'body' => $this->input->post('body')
            );

            if ($this->post_model->insert($data)) {
                Template::set_message('Your post was successfully saved.', 'success');
                redirect(SITE_AREA . '/content/blog');
            }
        }
        Template::set('toolbar_title', 'Create New Post');
        Template::set_view('content/post_form');
        Template::render();
    }

    public function edit_post($id = null) {
        if ($this->input->post('submit')) {
            $data = array(
                'title' => $this->input->post('title'),
                'slug' => $this->input->post('slug'),
                'body' => $this->input->post('body')
            );

            if ($this->post_model->update($id, $data)) {
                Template::set_message('You post was successfully saved.', 'success');
                redirect(SITE_AREA . '/content/blog');
            }
        }

        Template::set('post', $this->post_model->find($id));

        Template::set('toolbar_title', 'Edit Post');
        Template::set_view('content/post_form');
        Template::render();
    }

}
