INSERT IGNORE INTO `user_perms` ( `name`, `group`, `role`, `about` ) VALUES
    ( 'create_post_category',  'Post Category', 'Management', 'Allow user to create new post category' ),
    ( 'read_post_category',    'Post Category', 'Management', 'Allow user to view all post categories' ),
    ( 'remove_post_category',  'Post Category', 'Management', 'Allow user to remove exists post category' ),
    ( 'update_post_category',  'Post Category', 'Management', 'Allow user to update exists post category' );