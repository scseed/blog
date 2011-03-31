<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Blog_Comment extends Controller_Blog_Template {

	public function action_tree()
	{
		if( ! $this->_ajax)
			throw new HTTP_Exception_404();

		$blog_id = (int) $this->request->param('id');

		if( ! $blog_id)
			throw new HTTP_Exception_404();

		$comments_root = Jelly::query('blog_comment')
			->where('blog', '=', $blog_id)
			->where('level', '=', 0)
			->limit(1)
			->select();

		if( ! $comments_root->loaded())
		{
			$scope = Jelly::query('blog_comment')->order_by('scope', 'DESC')->limit(1)->select();
			if( ! $scope->loaded())
			{
				$scope = 1;
			}
			else
			{
				$scope = $scope->scope + 1;
			}


			$comments_root = Jelly::factory('blog_comment')
				->set(array(
					'blog' => $blog_id,
					'text' => ' '
				))->save();
			$comments_root->insert_as_new_root($scope);
		}

		$this->template->content = View::factory('frontend/content/blog/comments')
			->set('blog_comments', $comments_root->render_descendants('comments/list'));
	}

} // End Controller_blog