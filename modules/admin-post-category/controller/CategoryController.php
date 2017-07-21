<?php
/**
 * Category controller
 * @package admin-post-category
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminPostCategory\Controller;
use PostCategory\Model\PostCategory as PCategory;
use PostCategory\Model\PostCategoryChain as PCChain;
use PostCanal\Model\PostCanal as PCanal;

class CategoryController extends \AdminController
{
    private function _defaultParams(){
        return [
            'title'             => 'Post Category',
            'nav_title'         => 'Post',
            'active_menu'       => 'post',
            'active_submenu'    => 'post-category',
            'total'             => 0,
            'pagination'        => []
        ];
    }
    
    
    
    public function editAction(){
        if(!$this->user->login)
            return $this->show404();
        
        $id = $this->param->id;
        if(!$id && !$this->can_i->create_post_category)
            return $this->show404();
        elseif($id && !$this->can_i->update_post_category)
            return $this->show404();
        
        $old = null;
        $params = $this->_defaultParams();
        $params['title'] = 'Create New Post Category';
        $params['ref'] = $this->req->getQuery('ref') ?? $this->router->to('adminPostCategory');
        $params['categories'] = [];
        $params['canals'] = [];
        $params['jses'] = [
            'js/admin-post-category.js'
        ];
        
        if($id){
            $params['title'] = 'Edit Post Category';
            $object = PCategory::get($id, false);
            if(!$object)
                return $this->show404();
            $old = $object;
        }else{
            $object = new \stdClass();
            $object->user = $this->user->id;
        }
        
        // get all categories
        $cats = PCategory::get([]);
        if($cats){
            if($id){
                foreach($cats as $cat){
                    if($cat->id == $id || $cat->parent == $id)
                        continue;
                    $params['categories'][] = $cat;
                }
            }else{
                $params['categories'] = $cats;
            }
        }
        
        array_unshift($params['categories'], (object)['id'=>0, 'name'=>'None', 'parent'=>0, 'canal'=>0]);
        $params['object'] = $object;
        
        // get canal if exists
        if(isset($object->canal) && $object->canal){
            if(module_exists('post-canal') && $this->can_i->read_post_canal){
                $canal = PCanal::get($object->canal, false);
                if($canal)
                    $params['canals'][$canal->id] = $canal->name;
            }
        }
        
        if(false === ($form=$this->form->validate('admin-post-category', $object)))
            return $this->respond('post/category/edit', $params);
        
        if(!module_exists('post-canal') || !$this->can_i->read_post_canal){
            if(property_exists($form, 'canal'))
                unset($form->canal);
        }
        
        $object = object_replace($object, $form);
        
        $event = 'updated';
        
        if(!$id){
            $event = 'created';
            if(false === ($id = PCategory::create($object)))
                throw new \Exception(PCategory::lastError());
        }else{
            $object->updated = null;
            if(false === PCategory::set($object, $id))
                throw new \Exception(PCategory::lastError());
        }
        
        $this->fire('post-category:'. $event, $object, $old);
        
        return $this->redirect($params['ref']);
    }
    
    public function indexAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->read_post_category)
            return $this->show404();
        
        $params = $this->_defaultParams();
        $params['reff']  = $this->req->url;
        $params['categories'] = [];
        
        $categories = PCategory::get([], true, false, 'name ASC');
        if($categories)
            $params['categories'] = \Formatter::formatMany('post-category', $categories, 'id', false);
        $params['parentizes'] = group_by_prop($params['categories'], 'parent');
        ksort($params['parentizes']);
        
        $params['total'] = $total = PCategory::count([]);
        
        return $this->respond('post/category/index', $params);
    }
    
    public function removeAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->remove_post_category)
            return $this->show404();
        
        $id = $this->param->id;
        $object = PCategory::get($id, false);
        if(!$object)
            return $this->show404();
        
        $this->fire('post-category:deleted', $object);
        PCategory::remove($id);
        PCChain::remove(['post_category'=>$id]);
        
        $ref = $this->req->getQuery('ref');
        if($ref)
            return $this->redirect($ref);
        
        return $this->redirectUrl('adminPostCategory');
    }
}