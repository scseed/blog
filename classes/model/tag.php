<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * tag Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Tag extends Model_Core_Tag {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->field('blogs', 'ManyToMany', array('through' => 'blogs_tags'));
		
		parent::initialize($meta);
	}
} // End Model_tag