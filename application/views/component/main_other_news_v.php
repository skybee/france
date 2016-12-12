<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="cat_listing">

<!--    <div class="header" style="margin-bottom: 40px;">
        <h1>
            ИНТЕРЕСНОЕ В СЕТИ
        </h1>
    </div>-->
    
    <!-- likeArticlesSlider -->
    
        <?php
            foreach($express_news as $expressData):
                
            $hostData   = $expressData['host_data'];
            $newsData   = $expressData['news'];
            $host       = $expressData['host'];
        ?>
    
        <div id="like-acle-slider" class="listing in-doc-listing" lang="<?=$hostData['lang'];?>">
            <div class="header">
                <h2 class="doc-cat-title"><?=$hostData['site_name_str'];?> (<?=$hostData['lang'];?>):</h2>
            </div>
            
            <div class="like-article-list">
                <?php
                    foreach ($newsData as $news):
                        $newsUrl    = 'http://'.$host.'/'.$news['full_uri'].'-'.$news['id'].'-'.$news['url_name'].'/';
                        $imgUrl     = 'http://'.$host.'/upload/images/small/'.$news['main_img'];
                ?>
                <div class="like-article-item">
                    <a href="<?=$newsUrl?>">
                        <img src="<?=$imgUrl?>" alt="<?=htmlspecialchars($news['title'])?>" />
                        <?=$news['title']?>
                    </a>
                </div>
                <?php
                    endforeach;
                ?>
            </div>
        </div>
        <?php endforeach; ?>
        <!-- /likeArticlesSlider -->

</div>



