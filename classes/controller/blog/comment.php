<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_Comment extends Controller_Blog_Template {

	public function after()
	{
		if($this->_user == NULL)
		{
			$this->template->content = NULL;
		}
		parent::after();
	}

	/**
	 * Shows comments tree
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
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

		$place = 'next';
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
					'text' => '-'
				))->save();
			$comments_root->insert_as_new_root($scope);
			$place = 'inside';
		}
		else
		{
			$last_comment = ($comments_root->children(FALSE, 'DESC', 1)->loaded())
				? $comments_root->children(FALSE, 'DESC', 1)
				: $comments_root->children(TRUE, 'DESC', 1);

			if($last_comment->id == $comments_root->id)
				$place = 'inside';
		}

//		exit(Debug::vars($last_comment) . View::factory('profiler/stats'));

		$this->template->content = View::factory('frontend/content/blog/comments')
			->set('blog_comments', $comments_root->render_descendants('comments/list'))
			->bind('id', $last_comment->id)
			->bind('blog_id', $blog_id)
			->set('author_id', 1)
			->bind('place', $place)
			;
	}

	public function action_write()
	{
		$comment_root_id = $this->request->param('id', NULL);
		$comment_place   = $this->request->param('place', 'next');

		if( ! $comment_root_id)
			throw new HTTP_Exception_404();

		if($_POST)
		{
			$post    = Arr::extract($_POST, array('text', 'blog', 'author'));
			$post['text'] = trim(HTML::chars($post['text']));
			if( ! $post['text'])
				$this->request->redirect(Request::initial()->referrer().'#comment_'.$comment_root_id);

			$comment = Jelly::factory('blog_comment')
				->set($post);

			if( $comment_place == 'inside')
			{
				$comment->insert_as_last_child((int) $comment_root_id);
			}
			else
			{
				$comment->insert_as_next_sibling((int) $comment_root_id);
			}

			$this->request->redirect(Request::initial()->referrer().'#comment_'.$comment->id);
		}

		$this->template->content = View::factory('frontend/form/blog/comment');

	}
} // End Controller_Blog_Comment