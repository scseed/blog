<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller images
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Images extends Controller_Blog_Template {

	public function after()
	{
		if($this->_user == NULL)
		{
			$this->template->content = NULL;
		}
		parent::after();
	}

	/**
	 * Shows blog images
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		if( ! $this->_ajax)
			throw new HTTP_Exception_404();

		$blog_id = (int) $this->request->param('id');

		if( ! $blog_id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $blog_id)->select();
        if( ! $article->loaded())
            throw new HTTP_Exception_404();

        $images = Jelly::query('image')->where('blog', '=', $blog_id)->select();
		$this->template->content = View::factory('frontend/content/blog/images')
            ->bind('article', $article)
			->bind('images', $images)
			;
	}

    public function action_edit()
    {

    }

    public function action_rand()
    {
        if( ! $this->_ajax)
            throw new HTTP_Exception_404();
        $images = Jelly::query('image')->limit(6)->order_by(DB::expr('rand()'))->select();
        $this->template->content = View::factory('frontend/content/blog/image-block')
            ->bind('images', $images)
            ;
    }

    public function action_new()
    {
        $blog_id = (int) $this->request->param('id');

        if( ! $blog_id)
            throw new HTTP_Exception_404();

        $article = Jelly::query('blog', $blog_id)->select();
        if( ! $article->loaded())
            throw new HTTP_Exception_404();

        if ( ! ($article->author->id == $this->_user['member_id'] OR $admin_group == $this->_user['member_group_id']))
            throw new HTTP_Exception_401();

        if($this->request->method() === HTTP_Request::POST)
        {

            $error = '';
            $validate = Validation::factory($_FILES);

            $validate->rule('file', 'Upload::valid')
                ->rule('file', 'Upload::not_empty')
                ->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')))
                ->rule('file', 'Upload::size', array(':value', '1M'))
                ;

            if ($validate->check())
            {
                $image = Jelly::factory('image');
                $mkdir = @mkdir('i/photos/'.$blog_id, 0777, TRUE);
                $filename = Upload::save($_FILES['file'], NULL, 'i/photos/'.$blog_id);
                if ($filename) {
                    $image->url = 'i/photos/'.$blog_id.'/'.basename($filename);
                    $image->title = HTML::chars($_POST['title']);
                    $image->blog = $blog_id;
                    $image->save();
                }
                else
                {
                    $error = __('Error uploading file');
                }
            }
            else
            {
                $error = __('Error validating image');
            }
            if (empty($error)) {
                $this->request->redirect(Route::url('blog_article', array('id' => $blog_id)));
            }
        }
        $this->template->title = __('New Blog Image');
		$this->template->content = View::factory('frontend/form/blog/image')
			->bind('error', $error)
            ->bind('article', $blog_id)
		;
    }

    public function action_del()
    {
        $image_id = (int) $this->request->param('id');
        $image = Jelly::query('image', $image_id)->select();
        if ($image->loaded()) {
            $blog_id = $image->blog->id;

            $article = Jelly::query('blog', $blog_id)->select();
            if ($article->author->id == $this->_user['member_id'] OR $admin_group == $this->_user['member_group_id'])
            {
                //@unlink(DOCROOT.$image->url);
                $image->delete();
                if( ! $this->_ajax) {
                    $this->request->redirect(Route::url('blog_article', array('id' => $blog_id )));
                }
                $this->template->content = 'OK';
            }
            else {
                if( ! $this->_ajax) {
                    $this->request->redirect(Route::url('blog_article', array('id' => $blog_id )));
                }
                $this->template->content = '404';
            }
            return;
        }
        if( ! $this->_ajax) {
            $this->request->redirect(Route::url('blog', array('category' => 'self')));
        }
        $this->template->content = 'Not found';
    }
} // End Controller_Blog_Stats