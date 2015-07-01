/*
 *     Easy Slider 1.7 - jQuery plugin
 *    written by Alen Grakalic
 *    http://cssglobe.com/post/4004/easy-slider-15-the-easiest-jquery-plugin-for-sliding
 *
 *    Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *    Dual licensed under the MIT (MIT-LICENSE.txt)
 *    and GPL (GPL-LICENSE.txt) licenses.
 *
 *    Built for jQuery library
 *    http://jquery.com
 *
 * MODIFIED by mark@vanillaforums.com pretty heavily, 2011-11-09
 */

(function($) {

    $.fn.easySlider = function(options){

        // default configuration properties
        var defaults = {
            controlsContainer: '.SplashNav',
            speed:             800,
            auto:            false,
            pause:            2000,
            continuous:        false
        };

        var options = $.extend(defaults, options);

        this.each(function() {
            var obj = $(this);
            var s = $("li", obj).length;
            var w = $("li", obj).width();
            var h = $("li", obj).height();
            var clickable = true;
            obj.width(w);
            obj.height(h);
            obj.css("overflow","hidden");
            var ts = s-1;
            var t = 0;
            $("ul", obj).css('width',s*w);

            if(options.continuous){
                $("ul", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px"));
                $("ul", obj).append($("ul li:nth-child(2)", obj).clone());
                $("ul", obj).css('width',(s+1)*w);
            };

            $("li", obj).css('float','left');
            html = '<div class="SplashArrow SplashLeft"><a href="javascript:void(0);"></a></div>';
            html += '<div class="SplashDots"></div>';
            html += '<div class="SplashArrow SplashRight"><a href="javascript:void(0);"></a></div>';
            $(options.controlsContainer).html(html);

            for(var i=0;i<s;i++){
                $(document.createElement("a"))
                    .attr('id','Dot' + (i+1))
                    .attr('rel', i)
                    .attr('href', 'javascript:void(0);')
                    .appendTo($('.SplashDots'))
                    .click(function(){
                        animate($(this).attr('rel'),true);
                        return false;
                    });
            };
            $(".SplashRight").click(function(){
                animate("next",true);
            });
            $(".SplashLeft").click(function(){
                animate("prev",true);
            });

            function setCurrent(i){
                i = parseInt(i)+1;
                $(options.controlsContainer + " a").removeClass("Current");
                $("a#Dot" + i).addClass("Current");
            };

            function adjust(){
                if(t>ts) t=0;
                if(t<0) t=ts;
                if(!options.vertical) {
                    $("ul",obj).css("margin-left",(t*w*-1));
                } else {
                    $("ul",obj).css("margin-left",(t*h*-1));
                }
                clickable = true;
                if(options.numeric) setCurrent(t);
            };

            function animate(dir,clicked){
                if (clickable){
                    clickable = false;
                    var ot = t;
                    switch(dir){
                        case "next":
                            t = t*1; // bugfix math wasn't adding properly
                            t = (ot>=ts) ? (options.continuous ? t+1 : ts) : t+1;
                            break;
                        case "prev":
                            t = t*1; // bugfix math wasn't adding properly
                            t = (t<=0) ? (options.continuous ? t-1 : 0) : t-1;
                            break;
                        case "first":
                            t = 0;
                            break;
                        case "last":
                            t = ts;
                            break;
                        default:
                            t = dir;
                            break;
                    };
                    var diff = Math.abs(ot-t);
//                    console.log('ot: '+ot+'; t: '+t+'; ts: '+ts+'; diff: '+diff);
                    var speed = diff*options.speed;
                    p = (t*w*-1);
                    $("ul",obj).animate(
                        { marginLeft: p },
                        { queue:false, duration:speed, complete:adjust }
                    );
                    if(clicked) clearTimeout(timeout);
                    if(options.auto && dir=="next" && !clicked){;
                        timeout = setTimeout(function(){
                            animate("next",false);
                        },diff*options.speed+options.pause);
                    };

                };

            };
            // init
            var timeout;
            if(options.auto){;
                timeout = setTimeout(function(){
                    animate("next",false);
                },options.pause);
            };

            setCurrent(0);
        });

    };

})(jQuery);