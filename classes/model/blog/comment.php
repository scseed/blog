<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * comment Model for Jelly ORM
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Blog_Comment extends Jelly_Model_MPTT {

	protected $_directory = 'frontend/content/blog';

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blog_comments')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'blog' => Jelly::field('BelongsTo'),
				'author' => Jelly::field('BelongsTo', array(
					'foreign' => 'user',
					'default' => NULL,
					'allow_null' => TRUE,
				)),
				'date_create' => Jelly::field('Timestamp', array(
					'auto_now_create' => TRUE,
				)),
				'text' => Jelly::field('Text', array(
					'empty' => FALSE,
					'rules' => array(
						array('not_empty')
					),
				)),
				'is_active' => Jelly::field('Boolean', array(
					'default' => TRUE
				)),
			));

		parent::initialize($meta);
	}
} // End Model_Blog_Comment