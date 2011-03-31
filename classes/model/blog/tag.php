<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * tag Model for Jelly ORM
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Model_Blog_Tag extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blogs_tags')
			->fields(array(
				'blog' => Jelly::field('BelongsTo'),
				'tag' => Jelly::field('BelongsTo'),
			))
			->load_with(array('blog', 'tag'));
	}
} // End Model_tag