<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller members
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Members extends Controller_Blog_Template {

    public function action_rand()
    {
        if( ! $this->_ajax)
            throw new HTTP_Exception_404();
        $members = DB::select('member_id', 'name')->from(array('forum_members','fm'))->distinct(TRUE)
                ->join(array('forum_profile_portal', 'fpp'))->on('fm.member_id', '=', 'fpp.pp_member_id')
                ->join(array('blogs',  'b'))->on('b.author_id', '=', 'fm.member_id')
                ->join(array('cars',  'c'))->on('c.user_id', '=', 'fm.member_id')
                ->where('fpp.avatar_type', '!=', '')->and_where('b.is_active', '=', 1)
                ->and_where('c.is_active', '=', 1)->order_by(DB::expr('rand()'))->limit(9)->execute();
        if ($members->count()<3)
            $this->template->content = '';
        else
            $this->template->content = View::factory('frontend/content/blog/members-block')
                ->set('members', $members);
    }

} // End Controller_Blog_Members