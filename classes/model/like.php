<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * likes Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Like extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('likes')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'type' => Jelly::field('BelongsTo', array(
					'foreign' => 'like_type'
				)),
                'object' => Jelly::field('Integer', array('column'=>'object_id')),
                'author' => Jelly::field('BelongsTo', array(
                    'foreign' => 'user'
                )),
                'date_create' => Jelly::field('Timestamp', array(
                    'auto_now_create' => TRUE,
                )),
			))
            ->load_with(array(
                    'type',
                    'author'))

			;

	}

} // End Model_Like