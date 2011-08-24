<?php defined('SYSPATH') or die('No direct access allowed.');
/**
* Ajax Image Controller template
*
* @author Sergei Toporkov <stopkin0@gmail.com>
*/
class Controller_Ajax_Image extends Controller_Ajax_Template  {

    private $_max_width = 1024;
    private $_max_height = 800;

    public function action_new($id = NULL)
    {
        $content = '';

        if($this->request->method() === HTTP_Request::POST)
        {
            $error = '';
            $car_id = (int)Arr::get($_POST, 'car');
            $avatar = (int)Arr::get($_POST, 'avatar');
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
                            ->rule('file', 'Upload::size', array(':value', '2.5M'))
                            ;

                        if ($validate->check())
                        {
                            try {
                                @mkdir('media/cars'.$car_path, 0777, TRUE);
                                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                                $filename = Upload::save($_FILES['file'], uniqid().".".$ext, 'media/cars'.$car_path);
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
                        if ($avatar OR ! $car->avatar) {
                            $car->avatar = $image->id;
                            $car->save();
                        }
                            
                        $w = (int)Arr::get($_POST, 'w', 100);
                        $h = (int)Arr::get($_POST, 'h', 73);
                        $x1 = (int)Arr::get($_POST, 'x1');
                        $y1 = (int)Arr::get($_POST, 'y1');

                        $img = Image::factory($_POST['filename']);
                        if ($img->width > $this->_max_width) {
                            $img->resize($this->_max_width, $this->_max_width);
                            $img->save();
                        }
                        elseif ($img->height > $this->_max_height) {
                            $img->resize($this->_max_height, $this->_max_height);
                            $img->save();
                        }
                        if ($w>0) {

                            $scale = $img->width/300;
                            $img->crop($w*$scale, $h*$scale, $x1*$scale, $y1*$scale);
                            $img->resize(100);
                            $thumb = Utils::get_thumb($_POST['filename']);
                            /*$last_dot = strrpos($_POST['filename'], '.');
                            $thumb = substr($_POST['filename'], 0, $last_dot) . 'thumb' . substr($_POST['filename'], $last_dot);*/
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
                                    ->set('car_id', $car->id)
                                    ->set('avatar', $avatar)
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