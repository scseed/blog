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
				'car' => Jelly::field('BelongsTo', array(
					'foreign' => 'car'
				)),
                'url' => Jelly::field('String'),
                'ext' => Jelly::field('String'),
                'title' => Jelly::field('String'),
                'user' => Jelly::field('BelongsTo', array(
                    'foreign' => 'user'
                )),
			))->load_with(array('car'))
			;
	}
    
    /*public function get_thumb()
    {
        $last_dot = strrpos($this->url, '.');
        return substr($this->url, 0, $last_dot) . 'thumb' . substr($this->url, $last_dot);
    }*/

} // End Model_Image