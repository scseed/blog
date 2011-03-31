<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * blog Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Builder_Blog extends Jelly_Builder {

	public function show_articles($type = NULL)
	{
		switch($type)
		{
			case 'mainpage':
				return $this
					->active()
					->where('is_on_main', '=', TRUE)
					->where('score', '>', 10)
				;
				break;
		}
	}
} // End Model_Builder_Blog