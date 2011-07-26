<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * сфеупщкн Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Builder_Category extends Jelly_Builder {

	public function common($user_id = 0)
	{
		return $this->where('is_common', '=', TRUE)->or_where('user_id', '=', $user_id);
	}

} // End Model_Builder_Blog