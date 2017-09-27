<?php
/**
 * admin-post-category config file
 * @package admin-post-category
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'admin-post-category',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/admin-post-category',
    '__files' => [
        'modules/admin-post-category' => [ 'install', 'remove', 'update' ],
        'theme/admin/post/category'   => [ 'install', 'remove', 'update' ],
        'theme/admin/form/post-category_radio-tree.phtml' => [ 'install', 'remove', 'update' ],
        'theme/admin/static/js/admin-post-category.js'    => [ 'install', 'remove', 'update' ]
    ],
    '__dependencies' => [
        'admin',
        'post-category',
        '/slug-history'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'AdminPostCategory\\Controller\\CategoryController' => 'modules/admin-post-category/controller/CategoryController.php'
        ],
        'files' => []
    ],
    
    '_routes' => [
        'admin' => [
            'adminPostCategory' => [
                'rule' => '/post/category',
                'handler' => 'AdminPostCategory\\Controller\\Category::index'
            ],
            'adminPostCategoryEdit' => [
                'rule'  => '/post/category/:id',
                'handler' => 'AdminPostCategory\\Controller\\Category::edit'
            ],
            'adminPostCategoryRemove' => [
                'rule'  => '/post/category/:id/remove',
                'handler' => 'AdminPostCategory\\Controller\\Category::remove'
            ]
        ]
    ],
    
    'admin' => [
        'menu' => [
            'post' => [
                'label'     => 'Post',
                'icon'      => 'newspaper-o',
                'order'     => 10,
                'submenu'   => [
                    'post-category'  => [
                        'label'     => 'Category',
                        'perms'     => 'read_post_category',
                        'target'    => 'adminPostCategory',
                        'order'     => 40
                    ]
                ]
            ]
        ]
    ],
    
    'form' => [
        'admin-post-category' => [
            'name' => [
                'type'      => 'text',
                'label'     => 'Name',
                'rules'     => [
                    'required'  => true
                ]
            ],
            'slug' => [
                'type'      => 'text',
                'label'     => 'Slug',
                'attrs'     => [
                    'data-slug' => '#field-name'
                ],
                'rules'     => [
                    'required'  => true,
                    'alnumdash' => true,
                    'unique' => [
                        'model' => 'PostCategory\\Model\\PostCategory',
                        'field' => 'slug',
                        'self'  => [
                            'uri'   => 'id',
                            'field' => 'id'
                        ]
                    ]
                ]
            ],
            'canal' => [
                'type'      => 'select-ajax',
                'label'     => 'Canal',
                'source'    => 'adminPostCanalFilter',
                'rules'     => []
                
            ],
            'parent' => [
                'type'      => 'post-category_radio-tree',
                'label'     => 'Parent',
                'rules'     => []
            ],
            'about' => [
                'type'      => 'textarea',
                'label'     => 'About',
                'rules'     => []
            ],
            'meta_title' => [
                'type'      => 'text',
                'label'     => 'Meta Title',
                'rules'     => []
            ],
            'meta_description' => [
                'type'      => 'textarea',
                'label'     => 'Meta Description',
                'rules'     => []
            ],
            'meta_keywords' => [
                'type'      => 'text',
                'label'     => 'Meta Keywords',
                'rules'     => []
            ]
        ]
    ]
];