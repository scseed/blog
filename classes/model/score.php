<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * score Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Score extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('scores')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'blog' => Jelly::field('BelongsTo', array(
					'foreign' => 'blog'
				)),
                'user' => Jelly::field('BelongsTo', array(
                    'foreign' => 'user'
                )),
			))
			;

	}

} // End Model_Score