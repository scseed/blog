<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller images
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Images extends Controller_Blog_Template {

    private $path_prefix = 'media/cars';

    public function before() {
        parent::before();
        if (@isset (Kohana::config('images')->gallery_path))
            $this->path_prefix = Kohana::config('images')->gallery_path;
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

		$car_id = (int) $this->request->param('id');

		if($car_id) {
            // загружаем картинки по id авто

            $car = Jelly::query('car', $car_id)->select();
            if( ! $car->loaded())
                throw new HTTP_Exception_404();

            $images = Jelly::query('image')->where('car', '=', $car_id)->select();
            $this->template->content = View::factory('frontend/content/blog/images')
                ->bind('car', $car)
                ->bind('images', $images)
                ->set('path', $this->path_prefix)
                ;
        }
        else {
            $this->template->content = '';
            // загружаем картинки по id пользователя
            /*
            $images = Jelly::query('image')
                    ->where('user', '=', $this->_user['member_id'])
                    ->and_where('car', '=', 0)
                    ->select();
            $this->template->content = View::factory('frontend/content/blog/images')
                ->set('car', NULL)
                ->bind('images', $images)
                ;
            */
        }
	}

    public function action_rand()
    {
        if( ! $this->_ajax)
            throw new HTTP_Exception_404();
        $images = Jelly::query('image')->order_by(DB::expr('rand()'))->select();
        $this->template->content = View::factory('frontend/content/blog/image-block')
            ->bind('images', $images)
            ->set('path', $this->path_prefix)
            ;
    }

    public function action_new()
    {
        $car_id = (int) $this->request->param('id');
        $user_id = $this->_user['member_id'];
        $car_path = '';
        if($car_id) {
            $car_path = '/'.$car_id;
            $car = Jelly::query('car', $car_id)->select();
            if( ! $car->loaded())
                throw new HTTP_Exception_404();
        }

        if($this->request->method() === HTTP_Request::POST)
        {
	        if ( ! ($car->user->id == $this->_user['member_id'] OR $admin_group == $this->_user['member_group_id']))
                throw new HTTP_Exception_401();

            $error = '';
            $validate = Validation::factory($_FILES);

            $validate->rule('file', 'Upload::valid')
                ->rule('file', 'Upload::not_empty')
                ->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')))
                ->rule('file', 'Upload::size', array(':value', '2.5M'))
                ;

            if ($validate->check())
            {
                $image = Jelly::factory('image');
                @mkdir($this->path_prefix.$car_path, 0777, TRUE);
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $url = uniqid();
                $filename = Upload::save($_FILES['file'], $url.".".$ext, $this->path_prefix.$car_path);
                if ($filename) {
                    $image->url = $url; //$this->path_prefix.$car_path.'/'.basename($filename);
                    $image->ext = $ext;
                    $image->title = HTML::chars($_POST['title']);
                    $image->car = $car_id;
                    $image->user = $user_id;
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
                $this->request->redirect(Route::url('blog_cars', array(
                                                                   'action' => 'gallery',
                                                                      'id' => $car_id)));
            }
        }
        $this->template->content = View::factory('frontend/form/blog/image')
            ->bind('error', $error)
            ->bind('car', $car_id)
        ;
    }

    public function action_del()
    {
        $image_id = (int) $this->request->param('id');
        $image = Jelly::query('image', $image_id)->select();
        if ($image->loaded()) {
            $car_id = $image->car->id;
            $user_id = $image->user->id;

            $car = Jelly::query('car', $car_id)->select();
            if ($user_id == $this->_user['member_id'] OR $car->user->id == $this->_user['member_id'] OR $admin_group == $this->_user['member_group_id'])
            {
                @unlink(DOCROOT. $this->path_prefix.'/'.$car_id.'/'.$image->url.'.'.$image->ext);
                @unlink(DOCROOT. $this->path_prefix.'/'.$car_id.'/'.$image->url.'.thumb.'.$image->ext);
                $image->delete();
                if( ! $this->_ajax) {
                    $this->request->redirect(Route::url('blog_cars', array('action'=>'gallery', 'id' => $car_id )));
                }
                $this->template->content = 'OK';
            }
            else {
                if( ! $this->_ajax) {
                    $this->request->redirect(Route::url('blog_cars', array('action'=>'gallery', 'id' => $car_id )));
                }
                $this->template->content = '404';
            }
            return;
        }
        if( ! $this->_ajax) {
            $this->request->redirect(Route::url('blog_cars', array('action' => 'list')));
        }
        $this->template->content = 'Not found';
    }

    /**
     * makes default avatar for car_id
     * @return void
     */
    public function action_avatar()
    {
        $image_id = (int) Arr::get($_GET, 'image');
        $car_id = (int) Arr::get($_GET, 'car');
        $image = Jelly::query('image', $image_id)->select();
        $car = Jelly::query('car', $car_id)->select();
        $error = '';
        try {
            if ( ! $image->loaded())
                throw new HTTP_Exception_404();
            if( ! $car->loaded())
                throw new HTTP_Exception_404();
            $car->avatar = $image_id;
            $car->save();
        }
        catch (Exception $e) {
            $error = $e->getMessage();
        }

        if( ! $this->_ajax) {
            $this->request->redirect(Route::url('blog_cars', array('action'=>'gallery', 'id' => $car_id )));
        }
        else {
            if ($error)
                $this->template->content = $error;
            else
                $this->template->content = 'OK';
        }
    }
} // End Controller_Blog_Stats