<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * сфеупщкн Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Builder_Category extends Jelly_Builder {

	public function common()
	{
		return $this->where('is_common', '=', TRUE);
	}

} // End Model_Builder_Blog