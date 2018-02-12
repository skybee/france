function imgError(image){
    image.onerror = "";
//    image.src = "/img/default_news_error.jpg";
//    return true;
    
    
    
    imgNameAr = [
        'no_img_340x220-1.jpg',
        'no_img_340x220-2.jpg',
        'no_img_340x220-3.jpg',
        'no_img_340x220-4.jpg',
        'no_img_340x220-5.jpg',
        'no_img_340x220-6.jpg',
        'no_img_340x220-7.jpg',
        'no_img_340x220-8.jpg',
        'no_img_340x220-9.jpg'
    ];
    
    // использование Math.round() даст неравномерное распределение!
    function getRandomInt(min, max)
    {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }
   
    rndImgName = imgNameAr[getRandomInt(0,imgNameAr.length-1)];
    
    image.src   = "/img/no_img/"+rndImgName;
    image.style.transform = "none";  
    return true;
}

$( document ).ready(function(){
    
    $('#left .copy-url img, .like-article-list img, #out_window img').error( //удаление изображений 404
                function(){
                    $(this).remove();
                }
            );
    
    // <add url link to copy post>
    var source_link = '<p>Source: <a href="' + location.href + '">' + location.href + '</a></p>';
    $(
        function($)
        {
            if (window.getSelection) $('.copy-url').bind(
                'copy',
                function()
                {
                    var selection = window.getSelection();
                    var range = selection.getRangeAt(0);

                    var magic_div = $('<div>').css({ overflow : 'hidden', width: '1px', height : '1px', position : 'absolute', top: '-10000px', left : '-10000px' });
                    magic_div.append(range.cloneContents(), source_link);
                    $('body').append(magic_div);

                    var cloned_range = range.cloneRange();
                    selection.removeAllRanges();

                    var new_range = document.createRange();
                    new_range.selectNode(magic_div.get(0));
                    selection.addRange(new_range);

                    window.setTimeout(
                        function()
                        {
                            selection.removeAllRanges();
                            selection.addRange(cloned_range);
                            magic_div.remove();
                        }, 0
                    );
                }
            );
        }
    );
    // </add url link to copy post>
    
    
    setTimeout('setTop()', 15000);
    
    

    
    if(!$('#right').is(':visible')){
        ifMobile();
    }
    else{
        ifDesktop();
    }
    
    // <top adsense after image> //
    gAdsInContentHtml = '<div class="content-gAd content-gAd-bottom" style="padding: 15px 0; margin:30px 0px;border-color:#009ddb;" ><div class="content-gAd-center"><span class="gAd" data="content bottom Netboard"></span></div></div>';
    
    if( $('span.storyimage').length > 0 ){ //add after first(main) img
        $('span.storyimage:first').after(gAdsInContentHtml);
    }
    else if( $('ul.slideshow').length > 0){ //add in slider
        $('ul.slideshow:first li:first').after( '<li id="adsInSliderList"></li>');
        $(gAdsInContentHtml).appendTo('li#adsInSliderList');
    }
    
    if($('h2.look_more_hdn').length >= 3){ //add after like news block
        $('p.look_more_hdn:eq(2)').after(gAdsInContentHtml);
    }
    
    // </top adsense after image> //
    
    $('span.gAd').each(function(){
        blockName = $(this).attr('data');
        toWrite = loadGAd(blockName);
        $(this).replaceWith(toWrite);
    });
    
    
    // <Content Link>
    if($('span.out-link').length > 0)
    {
        $('span.out-link').each(function(){
            url     = $(this).attr('src');
            inner   = $(this).html();
            cls     = $(this).attr('class');
            stl     = $(this).attr('style');
            aHtml = '<a target="_blank" href="'+url+'" class="'+cls+'" style="'+stl+'" >'+inner+'</a>';
            $(this).replaceWith(aHtml);
        });
    }
    // <Content Link>
    
    
    // <Like Link in Text>
    if($('h2.look_more_hdn').length > 0)
    {
        $('h2.look_more_hdn').each(function(){
            likeInTxtLink = $(this).attr('rel');
            $(this).wrapInner('<a href="'+likeInTxtLink+'"></a>');
        });
    }
    // <Like Link in Text>
    
    
    // <show out window> //
    setTimeout('setOutWindow()', 5000);
    var cntShowOutWindow = 0;
    $('#top_hide_line').mouseover(function(){
        if(outWindow==1 && cntShowOutWindow < 2 && $("#out_window").length > 0){
            $('#ow_bg').css({'display':'block'}).animate({opacity:'0.7'},400,function(){
                $('#out_window').css({'display':'block'}).animate({top:'50px'},400);
            });
            cntShowOutWindow++;
            outWindow = 0;
            //setTimeout('setOutWindow()', 10000);
        }
    });
    $('#ow_close').click(function(){
        $('#out_window').animate({top:'-800px'},400,function(){
            $('#out_window').css({'display':'none'});
            $('#ow_bg').animate({opacity:'0'},400,function(){
                $('#ow_bg').css({'display':'none'});
            })
        });
        setTimeout('setOutWindow()', 5000);
    });
    // </show out window> //
    
    
    // <top adsense after image> //
//    if( $('span.storyimage').length > 0 ){
//        $('span.storyimage:first').after($('#left .content-gAd:first'));
//        $('#left .content-gAd:first').css({'margin-top':'30px', 'margin-bottom':'30px'});
//    }
//    else if( $('ul.slideshow').length > 0){
//        $('ul.slideshow:first li:first').after( '<li id="adsInSliderList"></li>');
//        $('#left .content-gAd:first').appendTo('li#adsInSliderList');
//        $('#left .content-gAd:first').css({'margin-top':'20px', 'margin-bottom':'40px'});
//    }
    // </top adsense after image> //
    
});

var outWindow = 0;
function setOutWindow(){outWindow=1;}


function loadGAd( blockName ){
    //return false;
    width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    if( width <= 980 ){
        return loadGAdMobile(blockName);
    }
    else{
        return loadGAdDesctop(blockName);
    }  
}


function loadGAdMobile(blockName){
    toWrite = '<!-- No Ads -->';
    
    if( blockName == 'content noImg' || blockName == 'content bottom Netboard' ){
        /* Grey in Text */
        toWrite = "<!-- mobile -->\
                    <ins class=\"adsbygoogle mobile-noimg\"\
                         style=\"display:block\"\
                         data-ad-client=\"ca-pub-6096727633142370\"\
                         data-ad-slot=\"8859464449\"\
                         data-ad-format=\"rectangle\"></ins>\
                    <script>\
                    (adsbygoogle = window.adsbygoogle || []).push({});\
                    </script>";
    }
    
    if(blockName == 'mobile greyInTxt'){
        /* Grey in Text */
        toWrite = " <div class=\"mobile-in-txt\"> \n\
                        <!-- Mobile In Text -->\
                        <ins class=\"adsbygoogle mobile-intxt-grey\"\
                             style=\"display:block\"\
                             data-ad-client=\"ca-pub-6096727633142370\"\
                             data-ad-slot=\"8410309242\"\
                             data-ad-format=\"horizontal\"></ins>\
                        <script>\
                        (adsbygoogle = window.adsbygoogle || []).push({});\
                        </script> \n\
                    </div> ";
    }
    
//    document.write(toWrite);
    return toWrite;
}


function loadGAdDesctop(blockName){
    
    function getRandomInt(min, max){
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    
    toWrite         = '<!-- No Ads -->';
    writeCheck      = false;
    responsive      = false;
    blockClass      = '';
    dataFormat      = 'auto';
    dataAdClient    = "ca-pub-6096727633142370";

    if( blockName == 'content noImg' ){
        /* Content NoImg Block */
        adStyle         = "display:inline-block;width:300px;height:250px";
        dataAdSlot      = "7412278844";
        writeCheck      = true;
    }
    if( blockName == 'content bottom Netboard' ){
        /* Bottom Content Netboard */
//        adStyle         = "display:inline-block;width:580px;height:400px";
//        dataAdSlot      = "5547096043";
//        writeCheck      = true;

        /* Content Bottom Netboard Block - 2 "Respon" */
        responsive      = true;
        adStyle         = "display:block";
        dataAdSlot      = "8216029246";
        dataFormat      = 'rectangle';
        blockClass      = 'gad-bottom-respon';
        writeCheck      = true;
    }
    if( blockName == 'right top' ){
        adStyle         = "display:inline-block;width:300px;height:600px";
        dataAdSlot      = "4927119649";
        writeCheck      = true;
    }
    if( blockName == 'under slider'){
        /* Under Slider */
        adStyle         = "display:inline-block;width:956px;height:120px";
        dataAdSlot      = "7088605248";
        writeCheck      = true;
    }
    if( blockName == 'content greyInTxt'){
        /* Grey in Text */        
        adStyle         = "display:inline-block;width:468px;height:60px";
        dataAdSlot      = "2811858046";
        writeCheck      = true;
    }

    if( writeCheck == true ){
        toWrite = '<ins class="adsbygoogle" \
                         style="'+adStyle+'" \
                         data-ad-client="'+dataAdClient+'" \
                         data-ad-slot="'+dataAdSlot+'" \
                    </ins> \
                    <script> \
                    (adsbygoogle = window.adsbygoogle || []).push({}); \
                    </script>';
        
        if(responsive == true){
            toWrite = '<ins class="adsbygoogle '+blockClass+'" \
                         style="'+adStyle+'" \
                         data-ad-client="'+dataAdClient+'" \
                         data-ad-slot="'+dataAdSlot+'" \
                         data-ad-format="'+dataFormat+'" \
                    </ins> \
                    <script> \
                    (adsbygoogle = window.adsbygoogle || []).push({}); \
                    </script>';
        }
    }
    
    return toWrite;
}





function setTop(){
    var docId = $('#docId').attr('docId');
    if( !docId ){ return; }
    
    $.post( '/ajax/background/set_top/', {docId: docId, ref: document.referrer} );
}

//===================== <if Mobile> =====================//
function ifMobile(){
    
    $('#mobile_menu_tabs').tabs();
    
    $('#mobile_nav_btn').click(function(){
            $('#mobile_menu').slideToggle();
        }
    );
    
    //change mobile menu link
    $('#mobile_menu li span').each(function(){
        spanUrl     = $(this).attr('data-url');
        spanAnchor  = $(this).attr('data-anchor');
        $(this).replaceWith('<a href="'+spanUrl+'">'+spanAnchor+'</a>');
    });
}
//===================== </if Mobile> =====================//


//===================== <if Desktop> =====================//
function ifDesktop(){
    //========== <Sliders> ==========//
    function lazySliderBefore(thisBlock){
        thisImg = $('img', thisBlock);
        newSrc = thisImg.attr('data-src');
        if(newSrc !== undefined){
            thisImg.attr('src',newSrc);
            thisImg.removeAttr('data-src');
        }
    }

    function loadSliderPagerImg(){
        imges = $('#bx-pager li img');
        $('#bx-pager li img').each(function(){
            newSrc = $(this).attr('data-src');
            $(this).attr('src',newSrc);
        });
    }
    loadSliderPagerImg();

//    $('.slider-block').bxSlider(
//        {
//            speed: 1000,
//            pause: 6000,
//            auto: true,
//            //randomStart: true,
//            pager: false
//        });
        
        
        // Slider in content
//    $('ul.slideshow').bxSlider({ 
//        mode: 'fade',
//        pager: false
//    });    

//    $('#right-top-news-slider').bxSlider({
//        speed: 1000,
//        pause: 6000,
//        auto: true,
//        //randomStart: true,
//        pager: false,
//        onSlideBefore: lazySliderBefore
//    });    

    $('.bxslider').bxSlider({
        mode: 'fade',
        pagerCustom: '#bx-pager',
        controls: false,
        auto: true,
        speed: 600,
        pause: 6000,
        onSlideBefore: lazySliderBefore
    });    
    //========== </Sliders> ==========//


    // <zoom img>
    $('.image-popup-no-margins').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                closeBtnInside: false,
                fixedContentPos: true,
                mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
                image: {
                        verticalFit: true
                },
                zoom: {
                        enabled: true,
                        duration: 300 // don't foget to change the duration also in CSS
                }
        });
    // </zoom img>

    // <show this cat>
    if( $('span').is('#opt-tag-main-cat') ){ 
        $mainCatName = $('#opt-tag-main-cat').text();
        $('.firstnav-menu li[catname='+$mainCatName+']').addClass('main-nav-cat-active');
    }
     if( $('span').is('#opt-tag-sub-cat') ){ 
        $subCatName = $('#opt-tag-sub-cat').text();
        $('.secondnav-menu li[catname='+$subCatName+']').addClass('sub-nav-cat-active');
    }
    // </show this cat>

//    $('#right-ajax-block').load('/ajax/background/get_right_hc/');

    // <serp result add link>
    if($('.serp_block').length > 0){
        var serp_h4 = $('.serp_block h4');
        var serp_length = serp_h4.length;

        for(i=0; i<serp_length; i++)
        {
            $('.serp_block h4').eq(i).wrap('<a href="'+$('.serp_block h4').eq(i).attr('rel')+'" target="_blank"></a>')
        }
    }
    // <serp result add link>
    
    //Load Games
//    setTimeout('$("#bottom-games").load("/html/bottom-games.html");', 10000);
}
//===================== <if Desktop> =====================//