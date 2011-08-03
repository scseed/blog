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
					    'not_empty' => NULL,
                    ))),
				'title' => Jelly::field('String', array(
                    'rules' => array(
					    'not_empty' => NULL,
                    ))),
				'description' => Jelly::field('Text'),
                'is_common' => Jelly::field('Boolean'),
                'is_active' => Jelly::field('Boolean'),
				'blogs' => Jelly::field('HasMany'),
                'user' => Jelly::field('BelongsTo', array(
                    'foreign' => 'user'
                )),
			));
	}
} // End Model_Blog_Type