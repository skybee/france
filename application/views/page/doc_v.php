<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
    #fscreen-right-top-padding{ display: block;}
</style>


<span id="docId" docId="<?=$doc_data['id']?>" style="display: none" ></span>

<div class="single">
    <div class="active">
        <h1><i><?=$cat_ar['name']?></i><?=$doc_data['title']?></h1>
    </div>
    
    <div class="doc-date doc-date-top">
        
        <div class="social_btn social_btn_top">
            <script async type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareTheme="counter" data-yashareType="normal" data-yashareQuickServices="<?=$this->multidomaine['social_btn_list'];?>"></div> 
        </div>
        
        <div class="dd_left">
            <?php $dateAr =& $doc_data['date_ar']; ?>
            <span class="long_date_str">
            <?=$dateAr['time'].'&nbsp;&nbsp;'.$dateAr['day_nmbr'].'&nbsp;'.$dateAr['month_str'].'&nbsp; '.$dateAr['year_nmbr'];?>
            </span>
<!--            <span class="short_date_str">
                <?=$dateAr['time'].'&nbsp;&nbsp;'.$dateAr['day_nmbr'].'.'.$dateAr['month_nmbr'].'.'.$dateAr['year_short_nmbr'];?>
            </span>-->
        </div>
        
        <div class="dd_right">
            <span class="short_date_str">
            <?=$dateAr['time'].'&nbsp;&nbsp;'.$dateAr['day_nmbr'].'&nbsp;'.$dateAr['month_str'].'&nbsp; '.$dateAr['year_nmbr'];?>
            </span>
            <span class="long_date_str">
                <span id="source_str"><?=$this->multidomaine['source_str'];?></span>:&nbsp;&nbsp;
            <span class="doc-donor-link out-link" src="http://<?=$doc_data['d_host']?>/" <?=$donor_rel;?> target="_blank" style="background-image: url('/upload/donor-logo/<?=$doc_data['d_img']?>');">
                <?=$doc_data['d_name']?>
            </span>
            </span>
        </div>
    </div><!-- #date -->
   
    <style>
        #left div.single div.thumb-gAd .gad-bottom-respon{width: 616px;}
        @media(max-width: 980px){ #left div.single div.content-gAd-bottom .mobile-noimg{width: 336px; height: 280px;} }
        @media(max-width: 340px){ #left div.single div.content-gAd-bottom .mobile-noimg{width: 300px; height: 250px;} }
    </style>
    <?php if(0):?>
    <div class="content-gAd content-gAd-bottom" style="padding: 15px 0;border-color:#009ddb;" >
        <div class="content-gAd-center">
            <!--<span class="gAd" data="content bottom Netboard"></span>-->
<!--            <ins class="adsbygoogle mobile-noimg"
                style="display:block"
                data-ad-client="ca-pub-6096727633142370"
                data-ad-slot="8859464449"
                data-ad-format="rectangle"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>-->
        </div>
    </div>
    <?php endif;?>

    <div class="content copy-url">

<!--        
        <style>
            @media(max-width: 980px){ #left div.single div.thumb-gAd .mobile-noimg{width: 336px; height: 280px;} }
            @media(max-width: 340px){ #left div.single div.thumb-gAd .mobile-noimg{width: 300px; height: 250px;} }
        </style>
        
        <div class="thumb thumb-gAd">
            <span class="gAd" data="content noImg"></span>
        </div>
-->
        
    <?=$doc_data['text']?>
        
    </div><!-- #content -->
    
    <div class="doc-date doc-date-bottom">
        <div class="social_btn">
            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareTheme="counter" data-yashareType="normal" data-yashareQuickServices="<?=$this->multidomaine['social_btn_list'];?>"></div> 
        </div>
        <div class="dd_left long_date_str">
            &mdash; &nbsp; <?=$this->multidomaine['repost_news_str'];?>
        </div>
    </div>
    
    
    <style>
        #left div.single div.thumb-gAd .gad-bottom-respon{width: 616px;}
        @media(max-width: 980px){ #left div.single div.content-gAd-bottom .mobile-noimg{width: 336px; height: 280px;} }
        @media(max-width: 340px){ #left div.single div.content-gAd-bottom .mobile-noimg{width: 300px; height: 250px;} }
    </style>
    <div class="content-gAd content-gAd-bottom" style="padding: 15px 0;border-color:#009ddb;" >
        <div class="content-gAd-center">
            <span class="gAd" data="content bottom Netboard"></span>
        </div>
    </div>
    
<!--    <div class="content-gAd content-gAd-bottom" id="bottom-games" style="padding: 15px 0;margin:40px 0;border-color:#009ddb;" >
        <div style="height:32px;line-height:32px;font-size:16px;padding-left:37px;margin:5px 0px 5px 240px;display:inline-block;background-image:url('/img/slider/bx_loader.gif');background-repeat:no-repeat;">
            Game loading...
        </div>
    </div>-->
    

    

    <div id="video_holder" style="display:none;"></div>
    
    
    <?php if($like_video): ?>
    <div class="likevideo">
        <div class="listing in-doc-listing">
            <div class="header">
                <h2 class="doc-cat-title"><?=$this->multidomaine['like_video_str'];?>:</h2>
            </div>
            <div id="like-video-container">
                <?php foreach ($like_video as $lVideo): ?>
                <div class="like-video-item">
                    <div class="like-video-item-left">
                        <div class="respon_video">
                            <iframe width="auto" height="auto"  src="https://www.youtube.com/embed/<?=$lVideo['video_id']?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="like-video-item-right">
                        <h3><?=$lVideo['title']?></h3>
                        <p><?=$lVideo['description']?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    
    <div class="othernews">
        
        <!-- likeArticlesSlider -->
        <div id="like-acle-slider" class="listing in-doc-listing">
            <div class="header">
                <h2 class="doc-cat-title"><?=$this->multidomaine['like_news_str'];?>:</h2>
            </div><!-- #header -->
            
            <div class="like-article-list">
                <?php
                    foreach ($like_articles as $likeArts):
                        $newsUrl    = "/{$likeArts['full_uri']}-{$likeArts['id']}-{$likeArts['url_name']}/";
                        $imgUrl     = '/upload/images/small/' . $likeArts['main_img']
                ?>
                <div class="like-article-item">
                    <a href="<?=$newsUrl?>">
                        <img src="<?=$imgUrl?>" alt="<?=$likeArts['title']?>" />
                        <?=$likeArts['title']?>
                    </a>
                </div>
                <?php
                    endforeach;
                ?>
            </div>
        </div>
        <!-- /likeArticlesSlider -->
        
        
    </div><!-- #othernews -->
    
    <?php if(0): ?>
    <div class="doc-comments">
        <div class="listing in-doc-listing" style="margin-bottom:10px; margin-top: 15px;">
            <div class="header">
                <h2 class="doc-cat-title"><?=$this->multidomaine['comments_str'];?>:</h2>
            </div>
        </div>
        
        <!-- KAMENT -->
<!--        <div id="kament_comments"></div>
        <script type="text/javascript">
                /* * * НАСТРОЙКА * * */
                var kament_subdomain = 'odnako';

                /* * * НЕ МЕНЯЙТЕ НИЧЕГО НИЖЕ ЭТОЙ СТРОКИ * * */
                (function() {
                        var node = document.createElement('script'); node.type = 'text/javascript'; node.async = true;
                        node.src = 'http://' + kament_subdomain + '.svkament.ru/js/embed.js';
                        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(node);
                })();
        </script>
        <noscript>Для отображения комментариев нужно включить Javascript</noscript>-->
        <!-- /KAMENT -->
        
        <div id="hypercomments_widget"></div>
        <script type="text/javascript">
            _hcwp = window._hcwp || [];
            _hcwp.push({widget:"Stream", widget_id: 19400});
            (function() {
            if("HC_LOAD_INIT" in window)return;
            HC_LOAD_INIT = true;
            var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase();
            var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
            hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/19400/"+lang+"/widget.js";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hcc, s.nextSibling);
            })();
        </script>
        <a href="#" class="hc-link" title="comments widget">comments powered by HyperComments</a>
        
    </div>
    <?php endif; ?>
    


</div>
