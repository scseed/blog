<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php foreach($blogs as $blog_article):?>
<div class="blog_article">
	<h2><?php echo HTML::anchor(
		Request::current()->uri(array(
			'action' => 'show',
			'id' => $blog_article->id
		)),
		$blog_article->title)?></h2>
	<div class="intro">
		<?php echo $textile->TextileThis($blog_article->intro())?>
	</div>
	<div class="info">
		<div class="author"><?php echo $blog_article->author->user_data->last_name?></div>
		<div class="date"><?php echo date('d.m.Y', $blog_article->date_create)?></div>
		<div class="comments"><?php echo $blog_article->count_comments()?></div>
	</div>
</div>
<?php endforeach;?>