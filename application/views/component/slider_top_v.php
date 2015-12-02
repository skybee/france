<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- SLIDER -->

<div id="featured" >
    
    <ul class="ui-tabs-nav" id="bx-pager">
        <?php  $i = 0;
            foreach($articles as $article):
        ?>
        <li class="ui-tabs-nav-item ">
            <a href="" data-slide-index="<?=$i?>">
                <img data-src="/upload/images/small/<?=$article['main_img']?>" src="/img/default_news.jpg" alt="" onerror="imgError(this);" />
            </a>
        </li>
        <?php $i++; endforeach; ?>
    </ul>


<ul class="bxslider">    
<?php 
    $i=0;
    foreach($articles as $article):
        
    $newsUrl    = "/{$article['full_uri']}-{$article['id']}-{$article['url_name']}/"; 
    $dateAr     =& $article['date'];
    $dateStr    = $dateAr['day_str'].', '.$dateAr['day_nmbr'].' '.$dateAr['month_str'].' '.$dateAr['year_nmbr'];
?>
    <li>
    <!-- 1 Content -->
    <div id="fragment-<?=$i?>" class="ui-tabs-panel " style="margin-top:1px; background-color:transparent; float:left;">
        <a href="<?=$newsUrl?>" class="top_slide_main_img">
            <?php if($i==0): ?>
            <img src="/upload/images/medium/<?=$article['main_img']?>" alt="" border="0" onerror="imgError(this);" />
            <?php else: ?>
            <img data-src="/upload/images/medium/<?=$article['main_img']?>" src="/img/default_news.jpg" alt="" border="0" onerror="imgError(this);" />
            <?php endif;?>
        </a>
        <div class="info">
            <h2><a href="<?=$newsUrl?>" ><?=$article['title']?></a></h2>
            <span class="date"><?=$dateStr?></span>
            <p><?=$article['text']?> [&hellip;]</p>
        </div><!-- #info closer -->
    </div><!-- #fragment-1 closer -->
    </li>
<?php
    $i++;
    endforeach; 
?>
</ul>
    
</div><!-- #featured closer -->
<!-- SLIDER END -->