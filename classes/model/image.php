<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * image Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Model_Image extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('images')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'blog' => Jelly::field('BelongsTo', array(
					'foreign' => 'blog'
				)),
                'url' => Jelly::field('String'),
                'title' => Jelly::field('String'),
			))
			;

	}

} // End Model_Score