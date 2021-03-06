<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * type Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Blog_Category extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blog_categories')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String', array(
                    'rules' => array(
					    array('not_empty'),
                    ))),
				'title' => Jelly::field('String', array(
                    'rules' => array(
					    array('not_empty'),
                    ))),
				'description' => Jelly::field('Text'),
                'is_common' => Jelly::field('Boolean'),
                'is_active' => Jelly::field('Boolean'),
				'blogs' => Jelly::field('HasMany'),

// TODO: car book belongs to car but not to user ???

                'user' => Jelly::field('BelongsTo', array(
                    'foreign' => 'user'
                )),
                'car' => Jelly::field('BelongsTo'),
			));
	}
} // End Model_Blog_Type