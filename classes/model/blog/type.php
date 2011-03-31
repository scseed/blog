<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * type Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Blog_Type extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blog_types')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String'),
				'description' => Jelly::field('Text'),
				'blogs' => Jelly::field('HasMany'),
			));
	}
} // End Model_Blog_Type