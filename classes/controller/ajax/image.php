<?php defined('SYSPATH') or die('No direct access allowed.');
/**
* Ajax Image Controller template
*
* @author Sergei Toporkov <stopkin0@gmail.com>
*/
class Controller_Ajax_Image extends Controller_Ajax_Template  {

    /// TODO: mb XML ????
    public function action_new()
    {

        $response = array();


        $car_id = (int) Arr::get($_GET, 'id', 0);
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
                @mkdir('i/photos/'.$user_id.$car_path, 0777, TRUE);
                $filename = Upload::save($_FILES['file'], NULL, 'i/photos/'.$user_id.$car_path);
                if ($filename) {
                    $image->url = 'i/photos/'.$user_id.$car_path.'/'.basename($filename);
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
                $response['error'] = '';
                $response['success'] = 1;
                $response['url'] = $image->url;
                $response['title'] = $image->title;
            }
            else {
                $response['error'] = $error;
                $response['success'] = 0;
            }
        }
        $this->response->body(json_encode($response));
    }
} // End Controller_Ajax_Image