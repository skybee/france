<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?= $meta['title'] ?></title>
        <link rel="shortcut icon" href="/img/favico.png" type="image/png" />
        
<!--        <link rel="stylesheet" type="text/css" href="/css/skin1/style.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/featured_long_style.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/featured_long.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/default.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/skin.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/font.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/magnific-popup.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/jquery.bxslider.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/mobile.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/media-queries.css" />
        <link rel="stylesheet" type="text/css" href="/css/skin1/mobile_gads.css" />-->
        
        <link rel="stylesheet" type="text/css" href="/css/all-style.min.css?v=21-11-02-07" />
        
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <?php if(isset($meta['og'])) echo $meta['og']; ?>
        
        <?php if(isset($meta['noindex']) && $meta['noindex'] == true ): ?>
            <meta name="robots" content="noindex, follow" />
        <?php endif; ?>

<!--        <script type='text/javascript' src='/js/skin1/jquery-1.11.3.min.js'></script>
        <script type='text/javascript' src='/js/skin1/jquery-ui.min-tabs.js'></script>
        <script type='text/javascript' src='/js/skin1/jquery.magnific-popup.min.js'></script>
        <script type='text/javascript' src='/js/skin1/jquery.bxslider.min.js'></script>
        <script type='text/javascript' src='/js/skin1/sb.js'></script>-->
            
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script async type='text/javascript' src='/js/all-files.min.js?v=21-11-01-35'></script>
        
    </head>

    <body>

        <?php  if( isset($this->catNameAr[0]) ): ?> <span style="display:none;" id="opt-tag-main-cat" ><?=$this->catNameAr[0]?></span> <?php endif; ?>
        <?php  if( isset($this->catNameAr[1]) ): ?> <span style="display:none;" id="opt-tag-sub-cat"  ><?=$this->catNameAr[1]?></span> <?php endif; ?>
        
        <div id="container">

            <div id="headernavigation">

                <div class="navigation">
                    <ul class="firstnav-menu">
                        <?php foreach ($main_menu_list as $main_link): ?>
                            <li class="page_item page-item-372" catname="<?=$main_link['url_name']?>">
                                <a href="/<?= $main_link['url_name'] ?>/"><?= $main_link['name'] ?></a>
                                <div class="firstnav-menu-arrow"></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>    
                </div><!-- #navigation closer -->
                
                <a href="/" title="Odnako.su" id="mobile_logo"></a>
                
                <!-- Mobile Menu -->
                <?=$mobile_menu;?>
                <!-- Mobile Menu -->
                
                
            </div><!-- #headernavigation closer -->
            <div id="content">
                <div id="white_space">
                    <div id="content_holder">
                        <div id="header">
                            <a href="/"><img src="/img/logo3.png" border="0" alt="Однако Logo" class="logo"  /></a>
                            <!--                            <div class="ad "></div> #ad 468x60 closer -->

                            <div class="search_top_block">
                                <form action="/search/1/" method="get" name="search" >
                                    <input type="text" name="q" value="" />
                                    <div id="top_search_submit" onclick="document.search.submit();"></div>
                                </form>
                            </div>
                        </div><!-- #header closer -->

                        <div id="categories">
                            <ul class="secondnav-menu">
                                <?php foreach ($second_menu_list as $second_menu_ar): ?>
                                    <li class="cat-item cat-item-<?= $second_menu_ar['id'] ?>" catname="<?=$second_menu_ar['url_name']?>">
                                        <a href="/<?= $second_menu_ar['full_uri'] ?>" ><?= $second_menu_ar['name'] ?></a>
                                        <div class="secondnav-menu-arrow"></div>
                                        
                                        <?php if( isset($second_menu_ar['sub_cat_list']) ): ?>
                                        <ul class="secondnav-drop-cat">
                                            <?php foreach($second_menu_ar['sub_cat_list'] as $third_menu_ar): ?>
                                            <li><a href="/<?= $third_menu_ar['full_uri'] ?>" ><?= $third_menu_ar['name'] ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div><!-- #categories closer -->

                        <div class="cat_line"></div>

                        <div class="top_gads">
                            <span class="gAd" data="under slider"></span>
                        </div>

                        <!-- !!! Top Slider Here-->

                        <div id="middle">
                            <div id="left">
                                <?= $content ?>
                            </div><!-- #left closer -->
                            <div id="right">
                                <?= $right; ?>
                            </div><!-- #right closer -->
                        </div><!-- #content_holder closer -->
                        
                        <?= $top_slider; ?>
                        
                    </div>
                </div><!-- #content closer -->
            </div>

            <div id="footer_widget" >
                <div class="inside">
                    <div id="footer_all_cat_block">
                        <?php
                        foreach ($footer_menu_list as $menuList):
                            ?>
                            <div class="footer_acb_main_cat">
                                <a href="/<?= $menuList['url_name'] ?>/" class="footer_main_cat_a"><?= $menuList['name'] ?></a><br />
                                <div class="footer_acb_sec_cat">
                                    <?php
                                    if ($menuList['s_cat'] != NULL):
                                        foreach ($menuList['s_cat'] as $sCat):
                                            ?>
                                            <a href="/<?= $menuList['url_name'] ?>/<?= $sCat['url_name'] ?>/"><?= $sCat['name'] ?></a>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="footer_contact">
                        <span>Контакты</span><br />
                        E-mail: <a href="mailto:mail@odnako.su">mail@odnako.su</a>
                    </div>
                </div><!-- #inside -->
            </div><!-- #footer_widget closer -->
  
            <div id="footer">
                <div class="inside">
                    <div class="left">&copy; 2013 Odnako.su. All Rights Reserved.</div><!-- #left -->
                    <div class="right"></div><!-- #right -->
                </div><!-- #inside -->
            </div><!-- #footer -->
        </div><!-- #container closer -->

        
        <div style="overflow: hidden; height: 1px; width: 1px; position: absolute; top: -100px;">
            <!--LiveInternet counter-->
            <script type="text/javascript"><!--
            document.write("<a href='http://www.liveinternet.ru/click' " +
                        "target=_blank><img src='//counter.yadro.ru/hit?t14.5;r" +
                        escape(document.referrer) + ((typeof (screen) == "undefined") ? "" :
                        ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                                screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
                        ";" + Math.random() +
                        "' alt='' title='LiveInternet: показано число просмотров за 24" +
                        " часа, посетителей за 24 часа и за сегодня' " +
                        "border='0' width='88' height='31'><\/a>")
                        //--></script>
            <!--/LiveInternet-->
            
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript">
                                (function(d, w, c) {
                                    (w[c] = w[c] || []).push(function() {
                                        try {
                                            w.yaCounter24764222 = new Ya.Metrika({id: 24764222,
                                                webvisor: true,
                                                clickmap: true,
                                                accurateTrackBounce: true});
                                        } catch (e) {
                                        }
                                    });

                                    var n = d.getElementsByTagName("script")[0],
                                            s = d.createElement("script"),
                                            f = function() {
                                                n.parentNode.insertBefore(s, n);
                                            };
                                    s.type = "text/javascript";
                                    s.async = true;
                                    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                                    if (w.opera == "[object Opera]") {
                                        d.addEventListener("DOMContentLoaded", f, false);
                                    } else {
                                        f();
                                    }
                                })(document, window, "yandex_metrika_callbacks");
            </script>
            <noscript><div><img src="//mc.yandex.ru/watch/24764222" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->            
        </div>
        
    </body>
</html>