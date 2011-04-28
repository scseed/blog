<?php defined('SYSPATH') or die('No direct access allowed.');?>
<span>Информация:</span>
<dl>
	<dt>Дата создания:</dt>
	<dd><?php echo date('r', $article->date_create)?></dd>
	<dt>Автор:</dt>
	<dd><?php echo $article->author->user_data->last_name?></dd>
</dl>