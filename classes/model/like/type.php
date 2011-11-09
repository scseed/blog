<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * like_type Model for Jelly ORM
 *
 * @author Sergei Toporkov <stopkin0@gmail.com>
 * @copyrignt
 */
class Model_Like_Type extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('like_types')
			->fields(array(
				'id'        => Jelly::field('Primary'),
				'name'      => Jelly::field('String'),
				'is_active' => Jelly::field('Boolean'),
			));
	}
} // End Model_type