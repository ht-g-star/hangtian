;(function($,undefined){var prefix='',eventPrefix,endEventName,endAnimationName,vendors={Webkit:'webkit',Moz:'',O:'o'},document=window.document,testEl=document.createElement('div'),supportedTransforms=/^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i,transform,transitionProperty,transitionDuration,transitionTiming,transitionDelay,animationName,animationDuration,animationTiming,animationDelay,cssReset={}
function dasherize(str){return str.replace(/([a-z])([A-Z])/,'$1-$2').toLowerCase()}
function normalizeEvent(name){return eventPrefix?eventPrefix+name:name.toLowerCase()}
$.each(vendors,function(vendor,event){if(testEl.style[vendor+'TransitionProperty']!==undefined){prefix='-'+vendor.toLowerCase()+'-'
eventPrefix=event
return false}})
transform=prefix+'transform'
cssReset[transitionProperty=prefix+'transition-property']=cssReset[transitionDuration=prefix+'transition-duration']=cssReset[transitionDelay=prefix+'transition-delay']=cssReset[transitionTiming=prefix+'transition-timing-function']=cssReset[animationName=prefix+'animation-name']=cssReset[animationDuration=prefix+'animation-duration']=cssReset[animationDelay=prefix+'animation-delay']=cssReset[animationTiming=prefix+'animation-timing-function']=''
$.fx={off:(eventPrefix===undefined&&testEl.style.transitionProperty===undefined),speeds:{_default:400,fast:200,slow:600},cssPrefix:prefix,transitionEnd:normalizeEvent('TransitionEnd'),animationEnd:normalizeEvent('AnimationEnd')}
$.fn.animate=function(properties,duration,ease,callback,delay){if($.isFunction(duration))
callback=duration,ease=undefined,duration=undefined
if($.isFunction(ease))
callback=ease,ease=undefined
if($.isPlainObject(duration))
ease=duration.easing,callback=duration.complete,delay=duration.delay,duration=duration.duration
if(duration)duration=(typeof duration=='number'?duration:($.fx.speeds[duration]||$.fx.speeds._default))/1000
if(delay)delay=parseFloat(delay)/1000
return this.anim(properties,duration,ease,callback,delay)}
$.fn.anim=function(properties,duration,ease,callback,delay){var key,cssValues={},cssProperties,transforms='',that=this,wrappedCallback,endEvent=$.fx.transitionEnd,fired=false
if(duration===undefined)duration=$.fx.speeds._default/1000
if(delay===undefined)delay=0
if($.fx.off)duration=0
if(typeof properties=='string'){cssValues[animationName]=properties
cssValues[animationDuration]=duration+'s'
cssValues[animationDelay]=delay+'s'
cssValues[animationTiming]=(ease||'linear')
endEvent=$.fx.animationEnd}else{cssProperties=[]
for(key in properties)
if(supportedTransforms.test(key))transforms+=key+'('+properties[key]+') '
else cssValues[key]=properties[key],cssProperties.push(dasherize(key))
if(transforms)cssValues[transform]=transforms,cssProperties.push(transform)
if(duration>0&&typeof properties==='object'){cssValues[transitionProperty]=cssProperties.join(', ')
cssValues[transitionDuration]=duration+'s'
cssValues[transitionDelay]=delay+'s'
cssValues[transitionTiming]=(ease||'linear')}}
wrappedCallback=function(event){if(typeof event!=='undefined'){if(event.target!==event.currentTarget)return
$(event.target).unbind(endEvent,wrappedCallback)}else
$(this).unbind(endEvent,wrappedCallback)
fired=true
$(this).css(cssReset)
callback&&callback.call(this)}
if(duration>0){this.bind(endEvent,wrappedCallback)
setTimeout(function(){if(fired)return
wrappedCallback.call(that)},(duration*1000)+25)}
this.size()&&this.get(0).clientLeft
this.css(cssValues)
if(duration<=0)setTimeout(function(){that.each(function(){wrappedCallback.call(this)})},0)
return this}
testEl=null})(Zepto);(function($){$.fn.coffee=function(option){var __time_val=null;var __time_wind=null;var __flyFastSlow='cubic-bezier(.09,.64,.16,.94)';var $coffee=$(this);var opts=$.extend({},$.fn.coffee.defaults,option);var coffeeSteamBoxWidth=opts.steamWidth;var $coffeeSteamBox=$('<div class="coffee-steam-box"></div>').css({'height':opts.steamHeight,'width':opts.steamWidth,'left':60,'top':-50,'position':'absolute','overflow':'hidden','z-index':0}).appendTo($coffee);$.fn.coffee.stop=function(){clearInterval(__time_val);clearInterval(__time_wind);}
$.fn.coffee.start=function(){__time_val=setInterval(function(){steam();},rand(opts.steamInterval/2,opts.steamInterval*2));__time_wind=setInterval(function(){wind();},rand(100,1000)+rand(1000,3000))}
return $coffee;function steam(){var fontSize=rand(8,opts.steamMaxSize);var steamsFontFamily=randoms(1,opts.steamsFontFamily);var color='#'+randoms(6,'0123456789ABCDEF');var position=rand(0,44);var rotate=rand(-90,89);var scale=rand02(0.4,1);var transform=$.fx.cssPrefix+'transform';transform=transform+':rotate('+rotate+'deg) scale('+scale+');'
var $fly=$('<span class="coffee-steam">'+randoms(1,opts.steams)+'</span>');var left=rand(0,coffeeSteamBoxWidth-opts.steamWidth-fontSize);if(left>position)left=rand(0,position);$fly.css({'position':'absolute','left':position,'top':opts.steamHeight,'font-size:':fontSize+'px','color':color,'font-family':steamsFontFamily,'display':'block','opacity':1}).attr('style',$fly.attr('style')+transform).appendTo($coffeeSteamBox).animate({top:rand(opts.steamHeight/2,0),left:left,opacity:0},rand(opts.steamFlyTime/2,opts.steamFlyTime*1.2),__flyFastSlow,function(){$fly.remove();$fly=null;});};function wind(){var left=rand(-10,10);left+=parseInt($coffeeSteamBox.css('left'));if(left>=54)left=54;else if(left<=34)left=34;$coffeeSteamBox.animate({left:left},rand(1000,3000),__flyFastSlow);};function randoms(length,chars){length=length||1;var hash='';var maxNum=chars.length-1;var num=0;for(i=0;i<length;i++){num=rand(0,maxNum-1);hash+=chars.slice(num,num+1);}
return hash;};function rand(mi,ma){var range=ma-mi;var out=mi+Math.round(Math.random()*range);return parseInt(out);};function rand02(mi,ma){var range=ma-mi;var out=mi+Math.random()*range;return parseFloat(out);};};$.fn.coffee.defaults={steams:['jQuery','HTML5','HTML6','CSS2','CSS3','JS','$.fn()','char','short','if','float','else','type','case','function','travel','return','array()','empty()','eval','C++','JAVA','PHP','JSP','.NET','while','this','$.find();','float','$.ajax()','addClass','width','height','Click','each','animate','cookie','bug','Design','Julying','$(this)','i++','Chrome','Firefox','Firebug','IE6','Guitar','Music','攻城师','旅行','王子墨','啤酒'],steamsFontFamily:['Verdana','Geneva','Comic Sans MS','MS Serif','Lucida Sans Unicode','Times New Roman','Trebuchet MS','Arial','Courier New','Georgia'],steamFlyTime:5000,steamInterval:500,steamMaxSize:30,steamHeight:200,steamWidth:300};$.fn.coffee.version='2.0.0';})(Zepto);