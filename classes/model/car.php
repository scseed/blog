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
				'user' => Jelly::field('BelongsTo', array(
					'foreign' => 'user'
				)),
                'model' => Jelly::field('BelongsTo', array(
                    'foreign' => 'model'
                )),
                'description' => Jelly::field('Text'),
				'year' => Jelly::field('Integer'),
                'avatar' => Jelly::field('BelongsTo', array(
                    'foreign' => 'image',
	                'default' => NULL,
	                'convert_empty' => TRUE,
                )),
                'is_active' => Jelly::field('Boolean', array(
                    'default' => TRUE,
                )),
                'uniq' => Jelly::field('String'),
			))
			->load_with(array('model', 'user'));

	}
} // End Model_Blog