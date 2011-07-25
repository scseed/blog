<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id=demands">
<?php
foreach($demands as $demand):
?>
        <div class="demand-item">
            <?php echo HTML::anchor('articles/self/'.$demand->blog->author->id, $demand->blog->author->name); ?>
            <?php echo HTML::anchor('article/'.$demand->blog->id, $demand->blog->title); ?>
            <?php echo $demand->category->title; ?>
            <?php echo $demand->message; ?>
            <?php echo HTML::anchor('article/'.$demand->blog->id.'/moderate?action=allow', 'Одобрить перенос статьи'); ?>
            <?php echo HTML::anchor('article/'.$demand->blog->id.'/moderate?action=deny', 'Отклонить перенос статьи'); ?>
        </div>

<?php
endforeach;
echo $pager;
?>

</div>
