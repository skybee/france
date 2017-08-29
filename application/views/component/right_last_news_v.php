<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="fscreen-right-top-padding"></div>

<h3 class="widget-title">
    <span class="title"><?=$this->multidomaine['top_news_str'];?></span>
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
        if($i == 6 ){break;}
        endforeach; 
    ?>    
</div>


<h3 class="widget-title" style="margin-bottom: -10px; margin-top: 30px;">
    <span class="title">Ads</span>
</h3>


<div class="right_gad_block" style="margin-top: 30px;">
    <span class="gAd" data="right top"></span>
</div>



<!--
<div id="right-ajax-block">
</div>
-->




<h3 class="widget-title">
    <span class="title"><?=$this->multidomaine['top_news_str'];?></span>
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
    <span class="title"><?=$this->multidomaine['last_news_str'];?></span>
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



<?php if(  isset($serp_list) && $serp_list != false):  ?> 

<h3 class="widget-title" style="margin-top: 30px;">
    <span class="title"><?=$this->multidomaine['serp_news_str'];?></span>
</h3>


    
    
    <?php #if($_SERVER['HTTP_HOST'] == 'francais-express.com' || $_SERVER['HTTP_HOST'] == 'smiexpress.ru'): ?>
        <div class="serp_block">
            <?php $i=0; ?>
            <?php foreach($serp_list as $serp): ?>
            <span class="out-link" src="<?=$serp['url']?>" rel="nofollow" target="_blank">
                <?=$serp['title']?>
                <span>- <?=$serp['host']?></span>
            </span>
            <p><?=$serp['text']?></p>

            <?php
                $i++;
                if($i>=10){ break; }
                endforeach; 
            ?>
        </div>
    <?php /*else:*/ if(0): ?>
        <div class="serp_block">
            <?php $i=0; ?>
            <?php foreach($serp_list as $serp): ?>
            <a href="<?=$serp['url']?>" rel="nofollow" target="_blank">
                <?=$serp['title']?>
                <span>- <?=$serp['host']?></span>
            </a>
            <p><?=$serp['text']?></p>

            <?php
                $i++;
                if($i>=10){ break; }
                endforeach; 
            ?>

            <?php if(!empty($sape_donor_link)):?>
                <p><?=$sape_donor_link?></p>
            <?php endif;?>
        </div>
    <?php endif;?>

<?php endif; ?>

