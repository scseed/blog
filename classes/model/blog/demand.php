<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * type Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Blog_Demand extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blog_demands')
			->fields(array(
                'blog' => Jelly::field('BelongsTo'),
                'category' => Jelly::field('BelongsTo', array(
					'foreign' => 'blog_category'
				)),
                'message' => Jelly::field('Text'),
                'is_done' => Jelly::field('Integer')
            ));
	}
} // End Model_Blog_Type
