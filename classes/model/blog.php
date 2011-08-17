<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * blog Model for Jelly ORM
 *
 * @package SCSeed
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
				'category' => Jelly::field('BelongsTo', array(
					'foreign' => 'blog_category'
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
				'title' => Jelly::field('String', array('rules' => array('not_empty'=>NULL))),
				'text' => Jelly::field('Text'),
				'is_active' => Jelly::field('Boolean', array(
					'default' => TRUE,
				)),
				'is_on_main' => Jelly::field('Boolean', array(
					'default' => FALSE,
				)),
				'score' => Jelly::field('Integer', array(
					'default' => 0,
				)),
				'tags' => Jelly::field('ManyToMany', array(
					'trough' => 'blogs_tags',
				)),
				'comments' => Jelly::field('HasMany', array(
					'foreign' => 'comment.object_id'
				)),
			))
			->load_with(array('category', 'author'));

	}

	/**
	 * Gets article intro by its cut tag
	 *
	 * @return string
	 */
	public function intro()
	{
        $cut_pos = mb_strpos($this->text, '~~~');
        if ($cut_pos === FALSE) {
            $cut_pos = mb_strpos($this->text, '</p>');
        }
        if ($cut_pos === FALSE) {
            $cut = mb_substr($this->text, 0, 70);
        }
        else {
            $cut = mb_substr($this->text, 0, $cut_pos);
        }

		return $cut;
	}

	/**
	 * Counts blog comments
	 *
	 * @return integer
	 */
	public function count_comments()
	{
		return $this->get('comments')->where('level', '!=', 0)->active()->count();
	}
} // End Model_Blog