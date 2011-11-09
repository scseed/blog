<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller like
 *
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Like extends Controller_Template {

	/**
	 * Shows likes for current object id and type in list
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_count()
	{
        if( ! $this->_ajax)
            throw new HTTP_Exception_404();
        $object = (int) $this->request->param('object');
        $type_name = HTML::chars($this->request->param('type'));
        $type = Jelly::query('like_type')->where('name', '=', $type_name)->limit(1)->select();
        if (! $type->loaded())
            throw new HTTP_Exception_404();
        $count = Jelly::query('like')->where('object', '=', $object)->and_where('type', '=', $type->id)->count();
        $this->template->content = View::factory('frontend/content/like/count')->set('count', $count);
	}

    /**
     * Shows likes for current object id and type in object
     *
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_show()
    {
        if( ! $this->_ajax)
            throw new HTTP_Exception_404();
        $object = (int) $this->request->param('object');
        $type_name = HTML::chars($this->request->param('type'));
        $type = Jelly::query('like_type')->where('name', '=', $type_name)->limit(1)->select();
        if (! $type->loaded())
            throw new HTTP_Exception_404();
        $user_id = $this->_user['member_id'];
        $count = Jelly::query('like')
                ->where('object', '=', $object)
                ->and_where('type', '=', $type->id)
                ->count();
        $user_count = Jelly::query('like')
                ->where('object', '=', $object)
                ->and_where('type', '=', $type->id)
                ->and_where('author', '=', $user_id)
                ->count();
        if ($user_count > 0)
            $content = View::factory('frontend/content/like/count')
                    ->set('count', $count);
        else
            $content = View::factory('frontend/content/like/show')
                    ->set('count', $count)
                    ->set('type', $type_name)
                    ->set('object', $object);
        $this->template->content = $content;
    }

} // End Controller_Blog_Filter