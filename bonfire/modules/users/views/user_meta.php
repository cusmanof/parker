<?php

if (! empty($meta_fields)) :
 
    $displayFrontend = isset($frontend_only) ? $frontend_only : false;
    $userIsAdmin     = isset($current_user) ? ($current_user->role_id == 1) : false;

    foreach ($meta_fields as $field) :
        $adminField = isset($field['admin_only']) ? $field['admin_only'] : false;
        // If this is an admin field and the user is not an admin, skip it.
        if ($adminField && ! $userIsAdmin) {
            continue;
        }

        // Unlike the other values, assume true if $field['frontend'] is not set.
        $frontField = isset($field['frontend']) ? $field['frontend'] : true;

        // If displaying the front end and this is not a frontend field, skip it.
        if ($displayFrontend && ! $frontField) {
            continue;
        }

        if ($field['form_detail']['type'] == 'dropdown') :
            echo form_dropdown(
                $field['form_detail']['settings'],
                $field['form_detail']['options'],
                set_value($field['name'], isset($user->{$field['name']}) ? $user->{$field['name']} : ''),
                $field['label']
            );
        elseif ($field['form_detail']['type'] == 'checkbox') :
?>
<div class="control-group<?php echo form_error($field['name']) ? ' error' : ''; ?>">
    <label class="control-label" for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label>
    <div class="controls">
        <?php
        echo form_checkbox(
            $field['form_detail']['settings'],
            $field['form_detail']['value'],
            $field['form_detail']['value'] == set_value(
                $field['name'],
                isset($user->{$field['name']}) ? $user->{$field['name']} : ''
            )
        );
        ?>
    </div>
</div>
<?php
        else :
            $form_method = "form_{$field['form_detail']['type']}";
            if (is_callable($form_method)) {
                $set = $field['form_detail']['settings'];
                 $val = array_key_exists ('default' , $set ) ?  $set['default'] :'';
                echo $form_method(
                    $field['form_detail']['settings'],
                    set_value($field['name'], isset($user->{$field['name']}) ? $user->{$field['name']} : $val),
                    $field['label']
                );
            }
        endif;
    endforeach;   
endif;
