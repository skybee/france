<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="out_window">
    <div id="ow_title"><?=$this->multidomaine['outwindow_str'];?></div>
    <div id="ow_body">
        <?php 
            $i=0;
            foreach($like_articles as $lArticle):
            $i++;
            if($i > 3){ break; }
            $url = '/'.$lArticle['full_uri'].'-'.$lArticle['id'].'-'.$lArticle['url_name'].'/';
        ?>
        <div class="ow_item">
            <a href="<?=$url;?>">
                <img src="/upload/images/real/<?=$lArticle['main_img'];?>" />
            </a>
            <a href="<?=$url;?>" class="ow_item_title"><?=$lArticle['title'];?></a>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="ow_close"></div>
</div>