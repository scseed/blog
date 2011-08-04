<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * car Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Car extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('cars')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'owner' => Jelly::field('BelongsTo', array(
					'foreign' => 'user'
				)),
                'model' => Jelly::field('BelongsTo', array(
                    'foreign' => 'model'
                )),
				'year' => Jelly::field('Integer'),
                'is_active' => Jelly::field('Boolean', array(
                    'default' => TRUE,
                )),
			))
			->load_with(array('model', 'owner'));

	}
} // End Model_Blog