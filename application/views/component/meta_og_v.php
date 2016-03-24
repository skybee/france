<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<meta property="og:title" content="<?=$doc_data['title']?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>"/>
<meta property="og:site_name" content="СМИ Express"/>
<meta property="og:description" content="<?= mb_substr( strip_tags($doc_data['text']), 0, 200)?>..."/>

<?php if( !empty($doc_data['main_img']) ): ?>
<!--medium--><meta property="og:image" content="http://<?=$_SERVER['HTTP_HOST']?>/upload/images/real/<?=$doc_data['main_img']?>"/>
<?php endif; ?>

<meta name="description" content="<?=$doc_data['title']?>: <?= mb_substr( strip_tags($doc_data['text']), 0, 100)?>..." />    

