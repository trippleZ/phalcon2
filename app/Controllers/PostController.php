<?php
use Phalcon\Mvc\Controller;

class PostController extends Controller
{

    public function indexAction()
    {

    }

    public function addTopicAction()
    {

    }

    public function addAction()
    {
        $newPost = new Blogs;
        $newPost->created_at = date("Y-m-d H:i:s");
        $auth = $this->session->get('auth');
        $newPost->user_id = $auth['id'];
        if ($newPost->save($this->request->getPost(), array('title', 'content'))) {
            $this->response->redirect('post/showbyauthor');
        } else {
            $this->flash->error("Can't add new topic");
        }
    }

    public function updateTopicAction($id)
    {
        $this->view->post = Blogs::findFirstById($id);
    }

    public function updateAction($id)
    {
        $post = Blogs::findFirstById($id);
        $user = $this->session->get('auth');
        if ($post->user_id === $user['id']) {
            $post->modified_at = date("Y-m-d H:i:s");
            if ($post->update($this->request->getPost(), array('title', 'content'))) {
                $this->response->redirect('post/showbyauthor');
            } else {
                $this->flash->error('Post can not be updated');
            }
        } else {
            $this->flash->error('Post can not be updated');
        }
    }

    public function deleteTopicAction($id)
    {
        $post = Blogs::findFirstById($id);
        $user = $this->session->get('auth');
        if ($post->user_id === $user['id']) {
            if ($post->delete()) {
                $this->response->redirect('post/showbyauthor');
            } else {
                $this->flash->error('Unfortunately, can not delete this post');
            }
        } else {
            $this->flash->error('Unfortunately, can not delete this post');
        }
    }

    public function showAction($id)
    {
        $this->view->post = Blogs::findFirstById($id);
    }

    public function showAllAction()
    {
        $this->view->all = Blogs::find();
    }

    public function showMyAction()
    {
        $auth = $this->session->get('auth');
        $showAll = new Blogs;
        $this->view->showAll = $showAll->find("user_id = " . $auth['id']);
    }
    
}