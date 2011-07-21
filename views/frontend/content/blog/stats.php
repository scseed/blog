<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!--<span>Информация:</span>-->
<dl>
	<dt>Дата создания:</dt>
	<dd><?php echo date('d.m.Y H:i', $article->date_create)?></dd>
	<dt>Автор:</dt>
	<dd><?php print_r( $article->author->name)?></dd>
</dl>