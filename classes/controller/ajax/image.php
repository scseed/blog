<?php defined('SYSPATH') or die('No direct access allowed.');
/**
* Ajax Image Controller template
*
* @author Sergei Toporkov <stopkin0@gmail.com>
*/
class Controller_Ajax_Image extends Controller_Ajax_Template  {

    public function action_new1($id = NULL)
    {
        $content = '';

        if($this->request->method() === HTTP_Request::POST)
        {
            $error = '';
            $car_id = (int)Arr::get($_POST, 'car');
            $user_id = $this->_user['member_id'];
            $car_path = '';
            if($car_id) {
                $car_path = '/'.$car_id;
                $car = Jelly::query('car', $car_id)->select();
                try
                {
                    if( ! $car->loaded())
                        throw new HTTP_Exception_404();

                    if ( ! ($car->user->id == $this->_user['member_id'] OR $admin_group == $this->_user['member_group_id']))
                        throw new HTTP_Exception_401();
                }
                catch(Exception $e) {
                    $error = $e->getMessage();
                }
            }
            $validate = Validation::factory($_FILES);

            $validate->rule('file', 'Upload::valid')
                ->rule('file', 'Upload::not_empty')
                ->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')))
                ->rule('file', 'Upload::size', array(':value', '1M'))
                ;

            if ($validate->check())
            {
                $image = Jelly::factory('image');
                @mkdir('media/cars'.$car_path, 0777, TRUE);
                try {
                    $filename = Upload::save($_FILES['file'], NULL, 'media/cars'.$car_path);
                    if ($filename) {
                        $image->url = 'media/cars'.$car_path.'/'.basename($filename);
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
                catch (Exception $e) {
                    $error = __('Error uploading file');
                }
            }
            else
            {
                $error = __('Error validating image');
            }

            if (empty($error)) {
                $content = View::factory('/frontend/content/response/image-add')
                        ->set('error', '')
                        ->set('success', '1')
                        ->set('url', $image->url)
                        ->set('title', $image->title)
                        ->set('image_id', $image->id)
                        ;
            }
            else {
                $content = View::factory('/frontend/content/response/image-add')
                        ->set('error', $error)
                        ->set('success', '0')
                        ;
            }
        }
        $this->response->body($content);
    }
    
    public function action_new($id = NULL)
    {
        $content = '';

        if($this->request->method() === HTTP_Request::POST)
        {
            $error = '';
            $car_id = (int)Arr::get($_POST, 'car');
            $user_id = $this->_user['member_id'];
            $car_path = '/'.$car_id;
            try
            {
                $car = Jelly::query('car', $car_id)->select();
                if( ! $car->loaded())
                    throw new HTTP_Exception_404();

                if ( ! ($car->user->id == $this->_user['member_id'] OR $admin_group == $this->_user['member_group_id']))
                    throw new HTTP_Exception_401();
            }
            catch(Exception $e) {
                $error = $e->getMessage();
            }
            if (empty($error)) {
                switch (Arr::get($_POST, 'step')) {
                    case 1:
                        $validate = Validation::factory($_FILES);

                        $validate->rule('file', 'Upload::valid')
                            ->rule('file', 'Upload::not_empty')
                            ->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')))
                            ->rule('file', 'Upload::size', array(':value', '1M'))
                            ;

                        if ($validate->check())
                        {
                            try {
                                @mkdir('media/cars'.$car_path, 0777, TRUE);
                                $filename = Upload::save($_FILES['file'], NULL, 'media/cars'.$car_path);
                                if (! $filename) {
                                    $error = __('Error uploading file');
                                }
                            }
                            catch (Exception $e) {
                                $error = __('Error uploading file');
                            }
                        }
                        else
                        {
                            $error = __('Error validating image');
                        }
                        if (empty($error)) {
                            $content = View::factory('/frontend/content/response/image-add')
                                    ->set('error', '')
                                    ->set('success', '1')
                                    ->set('filename', 'media/cars'.$car_path.'/'.basename($filename))
                                    ->set('step', '1')
                                    ;
                        } else {
                            $content = View::factory('/frontend/content/response/image-add')
                                    ->set('error', $error)
                                    ->set('success', '0')
                                    ;
                        }
                        break;
                    case 2:
                        $image = Jelly::factory('image');
                        $image->url = $_POST['filename'];
                        $image->title = HTML::chars($_POST['title']);
                        $image->car = $car_id;
                        $image->user = $user_id;
                        $image->save();
                            
                        $w = (int)Arr::get($_POST, 'w');
                        $h = (int)Arr::get($_POST, 'h');
                        $x1 = (int)Arr::get($_POST, 'x1');
                        $y1 = (int)Arr::get($_POST, 'y1');

                        if ($w>0) {
                            $img = Image::factory($_POST['filename']);
                            $scale = $img->width/300;
                            $img->crop($w*$scale, $h*$scale, $x1*$scale, $y1*$scale);
                            $img->resize(100);
                            $last_dot = strrpos($_POST['filename'], '.');
                            $thumb = substr($_POST['filename'], 0, $last_dot) . 'thumb' . substr($_POST['filename'], $last_dot);
                            $img->save($thumb);
                        }
                        if (empty($error)) {
                            $content = View::factory('/frontend/content/response/image-add')
                                    ->set('error', '')
                                    ->set('success', '1')
                                    ->set('step', '2')
                                    ->set('url', $image->url)
                                    ->set('thumb', $thumb)
                                    ->set('title', $image->title)
                                    ->set('image_id', $image->id)
                                    ;
                        }
                        else {
                            $content = View::factory('/frontend/content/response/image-add')
                                    ->set('error', $error)
                                    ->set('success', '0')
                                    ;
                        }
                        break;
                }
            }
        }
        $this->response->body($content);
    }
} // End Controller_Ajax_Image