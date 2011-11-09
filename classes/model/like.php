<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * like Model for Jelly ORM
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Model_like extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('like')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String'),
			));
	}
} // End Model_like