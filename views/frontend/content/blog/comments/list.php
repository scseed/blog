<?php defined('SYSPATH') or die('No direct access allowed.');?>
<ul>
<?php
$level = $nodes->current()->{$level_column};
$first = TRUE;

foreach ($nodes as $node)
{
	if ($node->{$level_column} > $level)
	{
	?>
		<ul>
	<?php
	}
	else if ($node->{$level_column} < $level)
	{
	?>
		</ul>
		</li>
	<?php
	}
	else if ( ! $first)
	{
	?>
		</li>
	<?php
	}
	?>
	<li>
		<div class="comment">
			<div class="author"><?php echo $node->author->user_data->last_name?></div>
			<div class="text"><?php echo $node->text?></div>
		</div>
	<?php
	$level = $node->{$level_column};
	$first = FALSE;
}
?>
</li>
</ul>