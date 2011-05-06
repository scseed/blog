<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_Article extends Controller_Blog_Template {

	public function before()
	{
		switch($this->request->action())
		{
			case 'new':
			case 'edit':
				$this->_auth_required = TRUE;
				break;
		}

		parent::before();
	}

	/**
	 * Shows blog article
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$id = (int) $this->request->param('id');

		if( ! $id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $id)->active()->select();

		if( ! $article->loaded())
			throw new HTTP_Exception_404();
		$comments = Request::factory(Route::get('blog_comment')->uri(array(
				'action' => 'tree',
				'id' => $article->id,
				'visibility' => ($article->allow_comments) ? 'show' : 'hide'
			)))->execute()->body();

		$this->template->title = $article->title;
		$this->template->content = View::factory('frontend/content/blog/article')
			->bind('article', $article)
			->bind('comments', $comments)
		;
	}

	public function action_new()
	{
		$types = Jelly::query('blog_type')->select();

		$post = array(
			'type'  => NULL,
			'title' => NULL,
			'text'  => NULL,
			'tags'  => NULL,
		);
		$errors = NULL;

		if($_POST)
		{
			$post = Arr::extract($_POST, array_keys($post));

			$post['text'] = HTML::chars($post['text']);

			$post['author'] = $this->_user->id;

			$article = Jelly::factory('blog');

			unset($post['tags']);

			$article->set($post);

			try
			{
				$article->save();
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}

			$_tags = explode(',', Arr::get($_POST, 'tags'));

			if(is_string($_tags))
			{
				$_tags[] = $_tags;
			}

			$tags = array();
			foreach($_tags as $_tag)
			{
				$_tag = HTML::chars(trim($_tag));

				$tag = Jelly::query('tag')->where('name', '=', $_tag)->limit(1)->select();

				if( ! $tag->loaded())
				{
					$tag = Jelly::factory('tag');
					$tag->name = $_tag;

					try
					{
						$tag->save();
					}
					catch(Jelly_Validation_Exception $e)
					{
						break;
					}
				}

				$tags[] = $tag;
			}

			if($article->loaded())
			{

				$article->add('tags', $tags);
				$article->save();
			}

			if(! $errors)
			{
				$this->request->redirect(Route::url('blog_article', array('action' => 'show', 'id' => $article->id)));
			}

			$post['tags'] = implode(',', $_tags);
		}

		StaticCss::instance()
			->addCss('/js/libs/markitup/markitup/skins/markitup/style.css')
			->addCss('/js/libs/textile/style.css');
		StaticJs::instance()
			->addJs('/js/libs/markitup/markitup/jquery.markitup.js')
			->addJs('/js/libs/textile/set.js');

		$this->template->page_title = __('New Blog Article');
		$this->template->content = View::factory('frontend/form/blog/new')
			->bind('types', $types)
			->bind('post', $post)
		;
	}

	public function action_edit()
	{
		$id = (int) $this->request->param('id');

		if( ! $id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $id)->active()->select();

		if( ! $article->loaded())
			throw new HTTP_Exception_404();

		if($this->_user->id != $article->author->id)
			throw new HTTP_Exception_401();

		$types = Jelly::query('blog_type')->select();

		$post = array(
			'type'  => $article->type->id,
			'title' => $article->title,
			'text'  => $article->text,
			'tags'  => implode(', ', Arr::pluck($article->tags->as_array('name'), 'name')),
		);
		$errors = NULL;

		if($_POST)
		{
			$post = Arr::extract($_POST, array_keys($post));
			unset($post['tags']);

			$article->set($post);

			try
			{
				$article->save();
				$this->request->redirect(Route::url('blog_article', array('action' => 'show', 'id' => $article->id)));
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}
		}

		StaticCss::instance()
			->addCss('/js/libs/markitup/markitup/skins/markitup/style.css')
			->addCss('/js/libs/textile/style.css');
		StaticJs::instance()
			->addJs('/js/libs/markitup/markitup/jquery.markitup.js')
			->addJs('/js/libs/textile/set.js');

		$this->template->content = View::factory('frontend/form/blog/new')
			->bind('types', $types)
			->bind('post', $post)
		;
	}

} // End Controller_Blog_Article