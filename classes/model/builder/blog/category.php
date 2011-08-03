<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * blog_category Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Builder_Blog_Category extends Jelly_Builder {

	public function common($user_id = 0)
	{
		return $this->where('is_common', '=', TRUE)->or_where('user_id', '=', $user_id);
	}

    public function admin($user_id = 0)
    {
        return $this->where('user_id', '=', $user_id)->or_where('user_id', '=', 0);
    }

} // End Model_Builder_Blog