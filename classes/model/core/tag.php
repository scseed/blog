<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * tag Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Core_Tag extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('tags')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String'),
			));
	}
} // End Model_tag