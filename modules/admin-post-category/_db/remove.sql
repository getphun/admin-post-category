DELETE FROM `user_perms_chain` WHERE `user_perms` IN (
    SELECT `id` FROM `user_perms` WHERE `group` = 'Post Category'
);

DELETE FROM `user_perms` WHERE `group` = 'Post Category';