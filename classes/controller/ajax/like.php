<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller ajax like
 *
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Ajax_Like extends Controller_Ajax_Template {

    public function before()
    {
        parent::before();
        $this->response->headers('Content-Type', 'application/json');
    }

	public function after()
	{
		if($this->_user == NULL)
		{
			$this->response->body('');
		}
		parent::after();
	}

    /**
     * makes like for current object id and type
     * @return void
     */
    public function action_like()
    {
        try
        {
            $object = (int) $this->request->param('object');
            $type_name = HTML::chars($this->request->param('type'));
            $type = Jelly::query('like_type')->where('name', '=', $type_name)->limit(1)->select();
            $user_id = $this->_user['member_id'];
            if ($type->loaded()) {
                $like = Jelly::factory('like');
                $like->type = $type->id;
                $like->object = $object;
                $like->author = $user_id;
                $like->save();
            }
        }
        catch (Exception $e) {
            $this->response->body('{"result": "BAD"}');
        }
        $this->response->body('{"result": "OK"}');
    }
} // End Controller_Blog_Filter