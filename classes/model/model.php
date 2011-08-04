<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Model extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('models')
			->fields(array(
				'id' => Jelly::field('Primary'),
                'name' => Jelly::field('String'),
			))
			;
	}

} // End Model_Model