<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * blog Model for Jelly ORM
 *
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Blog extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('blogs')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'type' => Jelly::field('BelongsTo', array(
					'foreign' => 'blog_type'
				)),
				'author' => Jelly::field('BelongsTo', array(
					'foreign' => 'user'
				)),
				'date_create' => Jelly::field('Timestamp', array(
					'auto_now_create' => TRUE,
				)),
				'date_update' => Jelly::field('Timestamp', array(
					'auto_now_update' => TRUE,
					'default' => NULL,
					'allow_null' => TRUE,
				)),
				'title' => Jelly::field('String'),
				'text' => Jelly::field('Text'),
				'is_active' => Jelly::field('Boolean', array(
					'default' => TRUE,
				)),
				'is_on_main' => Jelly::field('Boolean', array(
					'default' => FALSE,
				)),
				'score' => Jelly::field('Integer', array(
				)),
				'tags' => Jelly::field('ManyToMany', array(
					'trough' => 'blog_tags',
				)),
				'comments' => Jelly::field('HasMany', array(
					'foreign' => 'blog_comment'
				)),
			))
			->load_with(array('type', 'author'));

	}

	public function intro()
	{
		$cut = strstr($this->text, '~~~', TRUE);
		if($cut == FALSE)
		{
			$cut = $this->text;
		}

		return $cut;
	}

	public function count_comments()
	{
		return $this->get('comments')->where('level', '!=', 0)->active()->count();
	}
} // End Model_blog