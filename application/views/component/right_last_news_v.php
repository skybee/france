<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h3 class="widget-title">
    <span class="title">TOP News</span>
</h3>

<div class="right-top-news">
    
    <?php
        $i=0;
        foreach($right_top as $key => $article):
            $newsUrl    = "/{$article['full_uri']}-{$article['id']}-{$article['url_name']}/";
        if($i==0):
    ?>
    <div class="big-rtn">
        <a href="<?=$newsUrl?>" >
            <img src="/upload/images/real/<?=$article['main_img']?>" alt="<?=$article['title']?>" onerror="imgError(this);" />
        </a>
        <div class="big-rtn-title">
            <a href="<?=$newsUrl?>" >
                <?=$article['title']?>
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="small-rtn">
        <div style="display: table;">
            <div style="display: table-cell; vertical-align: middle;">
                <a href="<?=$newsUrl?>" >
                    <img src="/upload/images/small/<?=$article['main_img']?>" alt="<?=$article['title']?>" onerror="imgError(this);" />
                </a>
            </div>
            <div style="display: table-cell; vertical-align: middle;">
                <a href="<?=$newsUrl?>" class="small-rtn-txt-link" ><?=$article['title']?></a>
            </div>
        </div>
    </div>
    <?php 
        endif;
        unset($right_top[$key]);
        $i++;
        if($i == 4 ){break;}
        endforeach; 
    ?>    
</div>




<div class="right_gad_block" style="margin-top: 30px;">
    <span class="gAd" data="right top"></span>
</div>



<!--
<div id="right-ajax-block">
</div>
-->




<h3 class="widget-title">
    <span class="title">TOP News</span>
</h3>

<div class="right-top-news">
    
    <?php
        $i=0;
        foreach($right_top as $article):
            $newsUrl    = "/{$article['full_uri']}-{$article['id']}-{$article['url_name']}/";
        if($i==0):
    ?>
    <div class="big-rtn">
        <a href="<?=$newsUrl?>" >
            <img src="/upload/images/real/<?=$article['main_img']?>" alt="<?=$article['title']?>" onerror="imgError(this);" />
        </a>
        <div class="big-rtn-title">
            <a href="<?=$newsUrl?>" >
                <?=$article['title']?>
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="small-rtn">
        <div style="display: table;">
            <div style="display: table-cell; vertical-align: middle;">
                <a href="<?=$newsUrl?>" >
                    <img src="/upload/images/small/<?=$article['main_img']?>" alt="<?=$article['title']?>" onerror="imgError(this);" />
                </a>
            </div>
            <div style="display: table-cell; vertical-align: middle;">
                <a href="<?=$newsUrl?>" class="small-rtn-txt-link" ><?=$article['title']?></a>
            </div>
        </div>
    </div>
    <?php 
        endif;
        $i++;
        endforeach; 
    ?>    
</div>



<h3 class="widget-title" style="margin-top: 30px;">
    <span class="title">Последние новости</span>
</h3>

<div class="last_news_list">
<?php
    foreach ($last_news['all'] as $lnews ):
?>
    <div class="lnl_news">
        <!--
        <span>
            <?#=$lnews['date_ar']['time']?> 
        </span>
        -->
        <a href="<?="/{$lnews['full_uri']}-{$lnews['id']}-{$lnews['url_name']}/"?>" class="right-last-news-item">
            <img src="/upload/donor-logo/<?=$lnews['d_img']?>" alt="<?=$lnews['d_name']?>" title="<?=$lnews['d_name']?>" />
            <?=$lnews['title']?>
        </a>    
    </div>
<?php
    endforeach;
?> 
</div>



<?php if( FALSE && isset($serp_list) && $serp_list != false): // ЗАБЛОКИРОВАННО! ?> 

<h3 class="widget-title" style="margin-top: 30px;">
    <span class="title">Похожее на других сайтах</span>
</h3>

<div class="serp_block">

    <?php foreach($serp_list as $serp): ?>
    <h4 rel="<?=$serp['url']?>">
        <?=$serp['title']?>
        <span>- <?=$serp['host']?></span>
    </h4>
    <p><?=$serp['text']?></p>
    <?php endforeach; ?>

</div>

<?php endif; ?>

