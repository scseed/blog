<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * tag Model for Jelly ORM
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Model_tag extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blog_tags')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String'),
				'blogs' => Jelly::field('ManyToMany')
			));
	}
} // End Model_tag