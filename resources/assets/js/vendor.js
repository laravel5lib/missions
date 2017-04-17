// easyScroller v0.1 jQuery plugin
// Written by: Jason Valle
// Date Modified: 3/28/2013
//
// Instructions and code maintained at https://github.com/jvalle/easyScroller

(function($){
    $.fn.easyScroller = function(options) {

        var settings = $.extend({
            backToTop: false,
            scrollDownSpeed: 800,
            scrollUpSpeed: 600,
            topOffset: 0
        }, options);

        $(this).click(function(event) {
            var $this = $(this),
                sectionId = $this.attr('href');

            $('html, body').animate({
                scrollTop: $(sectionId).offset().top - settings.topOffset
            }, settings.scrollDownSpeed);

            return false;
        });

        if (settings.backToTop) {
            $(settings.backToTop).click(function () {
                $('html, body').animate({
                    scrollTop: 0
                }, settings.scrollUpSpeed);

                return false;
            });
        }
    };
})(jQuery);

// VideoJs Vimeo plugin
!function e(t,n,o){function r(a,u){if(!n[a]){if(!t[a]){var s="function"==typeof require&&require;if(!u&&s)return s(a,!0);if(i)return i(a,!0);var c=new Error("Cannot find module '"+a+"'");throw c.code="MODULE_NOT_FOUND",c}var f=n[a]={exports:{}};t[a][0].call(f.exports,function(e){var n=t[a][1][e];return r(n?n:e)},f,f.exports,e,t,n,o)}return n[a].exports}for(var i="function"==typeof require&&require,a=0;a<o.length;a++)r(o[a]);return r}({1:[function(e,t,n){(function(e){!function(e,o){"object"==typeof n&&"undefined"!=typeof t?t.exports=o():"function"==typeof define&&define.amd?define(o):(e.Vimeo=e.Vimeo||{},e.Vimeo.Player=o())}(this,function(){"use strict";function t(e,t){return t={exports:{}},e(t,t.exports),t.exports}function n(e,t,n){var o=E.get(e.element)||{};t in o||(o[t]=[]),o[t].push(n),E.set(e.element,o)}function o(e,t){var n=E.get(e.element)||{};return n[t]||[]}function r(e,t,n){var o=E.get(e.element)||{};if(!o[t])return!0;if(!n)return o[t]=[],E.set(e.element,o),!0;var r=o[t].indexOf(n);return r!==-1&&o[t].splice(r,1),E.set(e.element,o),o[t]&&0===o[t].length}function i(e,t){var n=E.get(e);E.set(t,n),E.delete(e)}function a(e,t){return 0===e.indexOf(t.toLowerCase())?e:""+t.toLowerCase()+e.substr(0,1).toUpperCase()+e.substr(1)}function u(e){return e instanceof window.HTMLElement}function s(e){return!isNaN(parseFloat(e))&&isFinite(e)&&Math.floor(e)==e}function c(e){return/^(https?:)?\/\/(player.)?vimeo.com(?=$|\/)/.test(e)}function f(){var e=arguments.length<=0||void 0===arguments[0]?{}:arguments[0],t=e.id,n=e.url,o=t||n;if(!o)throw new Error("An id or url must be passed, either in an options object or as a data-vimeo-id or data-vimeo-url attribute.");if(s(o))return"https://vimeo.com/"+o;if(c(o))return o.replace("http:","https:");if(t)throw new TypeError("“"+t+"” is not a valid video id.");throw new TypeError("“"+o+"” is not a vimeo.com url.")}function l(e){for(var t=arguments.length<=1||void 0===arguments[1]?{}:arguments[1],n=S,o=Array.isArray(n),r=0,n=o?n:n[Symbol.iterator]();;){var i;if(o){if(r>=n.length)break;i=n[r++]}else{if(r=n.next(),r.done)break;i=r.value}var a=i,u=e.getAttribute("data-vimeo-"+a);(u||""===u)&&(t[a]=""===u?1:u)}return t}function p(e){var t=arguments.length<=1||void 0===arguments[1]?{}:arguments[1];return new Promise(function(n,o){if(!c(e))throw new TypeError("“"+e+"” is not a vimeo.com url.");var r="https://vimeo.com/api/oembed.json?url="+encodeURIComponent(e);for(var i in t)t.hasOwnProperty(i)&&(r+="&"+i+"="+encodeURIComponent(t[i]));var a="XDomainRequest"in window?new XDomainRequest:new XMLHttpRequest;a.open("GET",r,!0),a.onload=function(){if(404===a.status)return void o(new Error("“"+e+"” was not found."));if(403===a.status)return void o(new Error("“"+e+"” is not embeddable."));try{var t=JSON.parse(a.responseText);n(t)}catch(e){o(e)}},a.onerror=function(){var e=a.status?" ("+a.status+")":"";o(new Error("There was an error fetching the embed code from Vimeo"+e+"."))},a.send()})}function h(e,t){var n=e.html;if(!t)throw new TypeError("An element must be provided");if(null!==t.getAttribute("data-vimeo-initialized"))return t.querySelector("iframe");var o=document.createElement("div");return o.innerHTML=n,t.appendChild(o.firstChild),t.setAttribute("data-vimeo-initialized","true"),t.querySelector("iframe")}function d(){var e=arguments.length<=0||void 0===arguments[0]?document:arguments[0],t=[].slice.call(e.querySelectorAll("[data-vimeo-id], [data-vimeo-url]")),n=function(e){"console"in window&&console.error&&console.error("There was an error creating an embed: "+e)},o=function(){if(i){if(a>=r.length)return"break";u=r[a++]}else{if(a=r.next(),a.done)return"break";u=a.value}var e=u;try{if(null!==e.getAttribute("data-vimeo-defer"))return"continue";var t=l(e),o=f(t);p(o,t).then(function(t){return h(t,e)}).catch(n)}catch(e){n(e)}};e:for(var r=t,i=Array.isArray(r),a=0,r=i?r:r[Symbol.iterator]();;){var u,s=o();switch(s){case"break":break e;case"continue":continue}}}function y(e){return"string"==typeof e&&(e=JSON.parse(e)),e}function v(e,t,n){if(e.element.contentWindow.postMessage){var o={method:t};void 0!==n&&(o.value=n);var r=parseFloat(navigator.userAgent.toLowerCase().replace(/^.*msie (\d+).*$/,"$1"));r>=8&&r<10&&(o=JSON.stringify(o)),e.element.contentWindow.postMessage(o,e.origin)}}function m(e,t){t=y(t);var n=[],i=void 0;if(t.event){if("error"===t.event)for(var a=o(e,t.data.method),u=a,s=Array.isArray(u),c=0,u=s?u:u[Symbol.iterator]();;){var f;if(s){if(c>=u.length)break;f=u[c++]}else{if(c=u.next(),c.done)break;f=c.value}var l=f,p=new Error(t.data.message);p.name=t.data.name,l.reject(p),r(e,t.data.method,l)}n=o(e,"event:"+t.event),i=t.data}else t.method&&(n=o(e,t.method),i=t.value,r(e,t.method));for(var h=n,d=Array.isArray(h),v=0,h=d?h:h[Symbol.iterator]();;){var m;if(d){if(v>=h.length)break;m=h[v++]}else{if(v=h.next(),v.done)break;m=v.value}var g=m;try{if("function"==typeof g){g.call(e,i);continue}g.resolve(i)}catch(e){}}}var g="undefined"!=typeof Array.prototype.indexOf,w="undefined"!=typeof window.postMessage;if(!g||!w)throw new Error("Sorry, the Vimeo Player API is not available in this browser.");var _="undefined"!=typeof window?window:"undefined"!=typeof e?e:"undefined"!=typeof self?self:{},b=(t(function(e,t){!function(e){function t(e,t){function o(e){return this&&this.constructor===o?(this._keys=[],this._values=[],this._itp=[],this.objectOnly=t,void(e&&n.call(this,e))):new o(e)}return t||w(e,"size",{get:v}),e.constructor=o,o.prototype=e,o}function n(e){this.add?e.forEach(this.add,this):e.forEach(function(e){this.set(e[0],e[1])},this)}function o(e){return this.has(e)&&(this._keys.splice(g,1),this._values.splice(g,1),this._itp.forEach(function(e){g<e[0]&&e[0]--})),-1<g}function r(e){return this.has(e)?this._values[g]:void 0}function i(e,t){if(this.objectOnly&&t!==Object(t))throw new TypeError("Invalid value used as weak collection key");if(t!=t||0===t)for(g=e.length;g--&&!_(e[g],t););else g=e.indexOf(t);return-1<g}function a(e){return i.call(this,this._values,e)}function u(e){return i.call(this,this._keys,e)}function s(e,t){return this.has(e)?this._values[g]=t:this._values[this._keys.push(e)-1]=t,this}function c(e){return this.has(e)||this._values.push(e),this}function f(){(this._keys||0).length=this._values.length=0}function l(){return y(this._itp,this._keys)}function p(){return y(this._itp,this._values)}function h(){return y(this._itp,this._keys,this._values)}function d(){return y(this._itp,this._values,this._values)}function y(e,t,n){var o=[0],r=!1;return e.push(o),{next:function(){var i,a=o[0];return!r&&a<t.length?(i=n?[t[a],n[a]]:t[a],o[0]++):(r=!0,e.splice(e.indexOf(o),1)),{done:r,value:i}}}}function v(){return this._values.length}function m(e,t){for(var n=this.entries();;){var o=n.next();if(o.done)break;e.call(t,o.value[1],o.value[0],this)}}var g,w=Object.defineProperty,_=function(e,t){return e===t||e!==e&&t!==t};"undefined"==typeof WeakMap&&(e.WeakMap=t({delete:o,clear:f,get:r,has:u,set:s},!0)),"undefined"!=typeof Map&&"function"==typeof(new Map).values&&(new Map).values().next||(e.Map=t({delete:o,has:u,get:r,set:s,keys:l,values:p,entries:h,forEach:m,clear:f})),"undefined"!=typeof Set&&"function"==typeof(new Set).values&&(new Set).values().next||(e.Set=t({has:a,add:c,delete:o,clear:f,keys:p,values:p,entries:d,forEach:m})),"undefined"==typeof WeakSet&&(e.WeakSet=t({delete:o,add:c,clear:f,has:a},!0))}("undefined"!=typeof t&&"undefined"!=typeof _?_:window)}),t(function(e){!function(t,n,o){n[t]=n[t]||o(),"undefined"!=typeof e&&e.exports?e.exports=n[t]:"function"==typeof define&&define.amd&&define(function(){return n[t]})}("Promise","undefined"!=typeof _?_:_,function(){function e(e,t){p.add(e,t),l||(l=d(p.drain))}function t(e){var t,n=typeof e;return null==e||"object"!=n&&"function"!=n||(t=e.then),"function"==typeof t&&t}function n(){for(var e=0;e<this.chain.length;e++)o(this,1===this.state?this.chain[e].success:this.chain[e].failure,this.chain[e]);this.chain.length=0}function o(e,n,o){var r,i;try{n===!1?o.reject(e.msg):(r=n===!0?e.msg:n.call(void 0,e.msg),r===o.promise?o.reject(TypeError("Promise-chain cycle")):(i=t(r))?i.call(r,o.resolve,o.reject):o.resolve(r))}catch(e){o.reject(e)}}function r(o){var a,s=this;if(!s.triggered){s.triggered=!0,s.def&&(s=s.def);try{(a=t(o))?e(function(){var e=new u(s);try{a.call(o,function(){r.apply(e,arguments)},function(){i.apply(e,arguments)})}catch(t){i.call(e,t)}}):(s.msg=o,s.state=1,s.chain.length>0&&e(n,s))}catch(e){i.call(new u(s),e)}}}function i(t){var o=this;o.triggered||(o.triggered=!0,o.def&&(o=o.def),o.msg=t,o.state=2,o.chain.length>0&&e(n,o))}function a(e,t,n,o){for(var r=0;r<t.length;r++)!function(r){e.resolve(t[r]).then(function(e){n(r,e)},o)}(r)}function u(e){this.def=e,this.triggered=!1}function s(e){this.promise=e,this.state=0,this.triggered=!1,this.chain=[],this.msg=void 0}function c(t){if("function"!=typeof t)throw TypeError("Not a function");if(0!==this.__NPO__)throw TypeError("Not a promise");this.__NPO__=1;var o=new s(this);this.then=function(t,r){var i={success:"function"!=typeof t||t,failure:"function"==typeof r&&r};return i.promise=new this.constructor(function(e,t){if("function"!=typeof e||"function"!=typeof t)throw TypeError("Not a function");i.resolve=e,i.reject=t}),o.chain.push(i),0!==o.state&&e(n,o),i.promise},this.catch=function(e){return this.then(void 0,e)};try{t.call(void 0,function(e){r.call(o,e)},function(e){i.call(o,e)})}catch(e){i.call(o,e)}}var f,l,p,h=Object.prototype.toString,d="undefined"!=typeof setImmediate?function(e){return setImmediate(e)}:setTimeout;try{Object.defineProperty({},"x",{}),f=function(e,t,n,o){return Object.defineProperty(e,t,{value:n,writable:!0,configurable:o!==!1})}}catch(e){f=function(e,t,n){return e[t]=n,e}}p=function(){function e(e,t){this.fn=e,this.self=t,this.next=void 0}var t,n,o;return{add:function(r,i){o=new e(r,i),n?n.next=o:t=o,n=o,o=void 0},drain:function(){var e=t;for(t=n=l=void 0;e;)e.fn.call(e.self),e=e.next}}}();var y=f({},"constructor",c,!1);return c.prototype=y,f(y,"__NPO__",0,!1),f(c,"resolve",function(e){var t=this;return e&&"object"==typeof e&&1===e.__NPO__?e:new t(function(t,n){if("function"!=typeof t||"function"!=typeof n)throw TypeError("Not a function");t(e)})}),f(c,"reject",function(e){return new this(function(t,n){if("function"!=typeof t||"function"!=typeof n)throw TypeError("Not a function");n(e)})}),f(c,"all",function(e){var t=this;return"[object Array]"!=h.call(e)?t.reject(TypeError("Not an array")):0===e.length?t.resolve([]):new t(function(n,o){if("function"!=typeof n||"function"!=typeof o)throw TypeError("Not a function");var r=e.length,i=Array(r),u=0;a(t,e,function(e,t){i[e]=t,++u===r&&n(i)},o)})}),f(c,"race",function(e){var t=this;return"[object Array]"!=h.call(e)?t.reject(TypeError("Not an array")):new t(function(n,o){if("function"!=typeof n||"function"!=typeof o)throw TypeError("Not a function");a(t,e,function(e,t){n(t)},o)})}),c})})),T=b&&"object"==typeof b&&"default"in b?b.default:b,E=new WeakMap,S=["id","url","width","maxwidth","height","maxheight","portrait","title","byline","color","autoplay","autopause","loop","responsive"],x=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")},k=new WeakMap,j=new WeakMap,M=function(){function e(t){var n=this,o=arguments.length<=1||void 0===arguments[1]?{}:arguments[1];if(x(this,e),window.jQuery&&t instanceof jQuery&&(t.length>1&&window.console&&console.warn&&console.warn("A jQuery object with multiple elements was passed, using the first element."),t=t[0]),"string"==typeof t&&(t=document.getElementById(t)),!u(t))throw new TypeError("You must pass either a valid element or a valid id.");if("IFRAME"!==t.nodeName){var r=t.querySelector("iframe");r&&(t=r)}if("IFRAME"===t.nodeName&&!c(t.getAttribute("src")||""))throw new Error("The player element passed isn’t a Vimeo embed.");if(k.has(t))return k.get(t);this.element=t,this.origin="*";var a=new T(function(e,r){var a=function(t){if(c(t.origin)&&n.element.contentWindow===t.source){"*"===n.origin&&(n.origin=t.origin);var o=y(t.data),r="event"in o&&"ready"===o.event,i="method"in o&&"ping"===o.method;return r||i?(n.element.setAttribute("data-ready","true"),void e()):void m(n,o)}};if(window.addEventListener?window.addEventListener("message",a,!1):window.attachEvent&&window.attachEvent("onmessage",a),"IFRAME"!==n.element.nodeName){var u=l(t,o),s=f(u);p(s,u).then(function(e){var o=h(e,t);return n.element=o,i(t,o),e}).catch(function(e){return r(e)})}});return j.set(this,a),k.set(this.element,this),"IFRAME"===this.element.nodeName&&v(this,"ping"),this}return e.prototype.then=function(e){var t=arguments.length<=1||void 0===arguments[1]?function(){}:arguments[1];return this.ready().then(e,t)},e.prototype.callMethod=function(e){var t=this,o=arguments.length<=1||void 0===arguments[1]?{}:arguments[1];return new T(function(r,i){return t.ready().then(function(){n(t,e,{resolve:r,reject:i}),v(t,e,o)})})},e.prototype.get=function(e){var t=this;return new T(function(o,r){return e=a(e,"get"),t.ready().then(function(){n(t,e,{resolve:o,reject:r}),v(t,e)})})},e.prototype.set=function(e,t){var o=this;return T.resolve(t).then(function(t){if(e=a(e,"set"),void 0===t||null===t)throw new TypeError("There must be a value to set.");return o.ready().then(function(){return new T(function(r,i){n(o,e,{resolve:r,reject:i}),v(o,e,t)})})})},e.prototype.on=function(e,t){if(!e)throw new TypeError("You must pass an event name.");if(!t)throw new TypeError("You must pass a callback function.");if("function"!=typeof t)throw new TypeError("The callback must be a function.");var r=o(this,"event:"+e);0===r.length&&this.callMethod("addEventListener",e).catch(function(){}),n(this,"event:"+e,t)},e.prototype.off=function(e,t){if(!e)throw new TypeError("You must pass an event name.");if(t&&"function"!=typeof t)throw new TypeError("The callback must be a function.");var n=r(this,"event:"+e,t);n&&this.callMethod("removeEventListener",e).catch(function(e){})},e.prototype.loadVideo=function(e){return this.callMethod("loadVideo",e)},e.prototype.ready=function(){var e=j.get(this);return T.resolve(e)},e.prototype.enableTextTrack=function(e,t){if(!e)throw new TypeError("You must pass a language.");return this.callMethod("enableTextTrack",{language:e,kind:t})},e.prototype.disableTextTrack=function(){return this.callMethod("disableTextTrack")},e.prototype.pause=function(){return this.callMethod("pause")},e.prototype.play=function(){return this.callMethod("play")},e.prototype.unload=function(){return this.callMethod("unload")},e.prototype.getAutopause=function(){return this.get("autopause")},e.prototype.setAutopause=function(e){return this.set("autopause",e)},e.prototype.getColor=function(){return this.get("color")},e.prototype.setColor=function(e){return this.set("color",e)},e.prototype.getCurrentTime=function(){return this.get("currentTime")},e.prototype.setCurrentTime=function(e){return this.set("currentTime",e)},e.prototype.getDuration=function(){return this.get("duration")},e.prototype.getEnded=function(){return this.get("ended")},e.prototype.getLoop=function(){return this.get("loop")},e.prototype.setLoop=function(e){return this.set("loop",e)},e.prototype.getPaused=function(){return this.get("paused")},e.prototype.getTextTracks=function(){return this.get("textTracks")},e.prototype.getVideoEmbedCode=function(){return this.get("videoEmbedCode")},e.prototype.getVideoId=function(){return this.get("videoId")},e.prototype.getVideoTitle=function(){return this.get("videoTitle")},e.prototype.getVideoWidth=function(){return this.get("videoWidth")},e.prototype.getVideoHeight=function(){return this.get("videoHeight")},e.prototype.getVideoUrl=function(){return this.get("videoUrl")},e.prototype.getVolume=function(){return this.get("volume")},e.prototype.setVolume=function(e){return this.set("volume",e)},e}();return d(),M})}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{})},{}],2:[function(e,t,n){(function(t){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function a(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}function u(){if(!d){d=!0;var e="\n      .vjs-vimeo iframe {\n        position: absolute;\n        top: 0;\n        left: 0;\n        width: 100%;\n        height: 100%;\n      }\n    ",t=document.head||document.getElementsByTagName("head")[0],n=document.createElement("style");n.type="text/css",n.styleSheet?n.styleSheet.cssText=e:n.appendChild(document.createTextNode(e)),t.appendChild(n)}}n.__esModule=!0;var s="undefined"!=typeof window?window.videojs:"undefined"!=typeof t?t.videojs:null,c=o(s),f=e(1),l=o(f),p=c.default.getComponent("Component"),h=c.default.getComponent("Tech"),d=!1,y=function(e){function t(n,o){r(this,t);var a=i(this,e.call(this,n,o));return u(),a.setPoster(n.poster),a.initVimeoPlayer(),a}return a(t,e),t.prototype.initVimeoPlayer=function(){var e=this,t={url:this.options_.source.src,byline:!1,portrait:!1,title:!1};this.options_.autoplay&&(t.autoplay=!0),this.options_.height&&(t.height=this.options_.height),this.options_.width&&(t.width=this.options_.width),this.options_.maxheight&&(t.maxheight=this.options_.maxheight),this.options_.maxwidth&&(t.maxwidth=this.options_.maxwidth),this.options_.loop&&(t.loop=this.options_.loop),this._player=new l.default(this.el(),t),this.initVimeoState(),["play","pause","ended","timeupdate","progress","seeked"].forEach(function(t){e._player.on(t,function(n){e._vimeoState.progress.duration!=n.duration&&e.trigger("durationchange"),e._vimeoState.progress=n,e.trigger(t)})}),this._player.on("pause",function(){return e._vimeoState.playing=!1}),this._player.on("play",function(){e._vimeoState.playing=!0,e._vimeoState.ended=!1}),this._player.on("ended",function(){e._vimeoState.playing=!1,e._vimeoState.ended=!0}),this._player.on("volumechange",function(t){return e._vimeoState.volume=t}),this._player.on("error",function(t){return e.trigger("error",t)}),this.triggerReady()},t.prototype.initVimeoState=function(){var e=this._vimeoState={ended:!1,playing:!1,volume:0,progress:{seconds:0,percent:0,duration:0}};this._player.getCurrentTime().then(function(t){return e.progress.seconds=t}),this._player.getDuration().then(function(t){return e.progress.duration=t}),this._player.getPaused().then(function(t){return e.playing=!t}),this._player.getVolume().then(function(t){return e.volume=t})},t.prototype.createEl=function(){var e=c.default.createEl("div",{id:this.options_.techId});return e.style.cssText="width:100%;height:100%;top:0;left:0;position:absolute",e.className="vjs-vimeo",e},t.prototype.controls=function(){return!0},t.prototype.supportsFullScreen=function(){return!0},t.prototype.src=function(){return this.options_.source},t.prototype.currentSrc=function(){return this.options_.source.src},t.prototype.currentTime=function(){return this._vimeoState.progress.seconds},t.prototype.setCurrentTime=function(e){this._player.setCurrentTime(e)},t.prototype.volume=function(){return this._vimeoState.volume},t.prototype.setVolume=function(e){return this._player.setVolume(volume)},t.prototype.duration=function(){return this._vimeoState.progress.duration},t.prototype.buffered=function(){var e=this._vimeoState.progress;return c.default.createTimeRange(0,e.percent*e.duration)},t.prototype.paused=function(){return!this._vimeoState.playing},t.prototype.pause=function(){this._player.pause()},t.prototype.play=function(){this._player.play()},t.prototype.muted=function(){return 0===this._vimeoState.volume},t.prototype.ended=function(){return this._vimeoState.ended},t}(h);y.prototype.featuresTimeupdateEvents=!0,y.isSupported=function(){return!0},h.withSourceHandlers(y),y.nativeSourceHandler={},y.nativeSourceHandler.canPlayType=function(e){return"video/vimeo"===e?"maybe":""},y.nativeSourceHandler.canHandleSource=function(e){return e.type?y.nativeSourceHandler.canPlayType(e.type):e.src?y.nativeSourceHandler.canPlayType(e.src):""},y.nativeSourceHandler.handleSource=function(e,t){t.src(e.src)},y.nativeSourceHandler.dispose=function(){},y.registerSourceHandler(y.nativeSourceHandler),p.registerComponent("Vimeo",y),h.registerTech("Vimeo",y),y.VERSION="0.0.1",n.default=y}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{})},{}]},{},[2]);

/*
 * JavaScript Canvas to Blob
 * https://github.com/blueimp/JavaScript-Canvas-to-Blob
 *
 * Copyright 2012, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 *
 * Based on stackoverflow user Stoive's code snippet:
 * http://stackoverflow.com/q/4998908
 */

/* global atob, Blob, define */

;(function (window) {
    'use strict'

    var CanvasPrototype = window.HTMLCanvasElement &&
        window.HTMLCanvasElement.prototype
    var hasBlobConstructor = window.Blob && (function () {
            try {
                return Boolean(new Blob())
            } catch (e) {
                return false
            }
        }())
    var hasArrayBufferViewSupport = hasBlobConstructor && window.Uint8Array &&
        (function () {
            try {
                return new Blob([new Uint8Array(100)]).size === 100
            } catch (e) {
                return false
            }
        }())
    var BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder ||
        window.MozBlobBuilder || window.MSBlobBuilder
    var dataURIPattern = /^data:((.*?)(;charset=.*?)?)(;base64)?,/
    var dataURLtoBlob = (hasBlobConstructor || BlobBuilder) && window.atob &&
        window.ArrayBuffer && window.Uint8Array &&
        function (dataURI) {
            var matches,
                mediaType,
                isBase64,
                dataString,
                byteString,
                arrayBuffer,
                intArray,
                i,
                bb
            // Parse the dataURI components as per RFC 2397
            matches = dataURI.match(dataURIPattern)
            if (!matches) {
                throw new Error('invalid data URI')
            }
            // Default to text/plain;charset=US-ASCII
            mediaType = matches[2]
                ? matches[1]
                : 'text/plain' + (matches[3] || ';charset=US-ASCII')
            isBase64 = !!matches[4]
            dataString = dataURI.slice(matches[0].length)
            if (isBase64) {
                // Convert base64 to raw binary data held in a string:
                byteString = atob(dataString)
            } else {
                // Convert base64/URLEncoded data component to raw binary:
                byteString = decodeURIComponent(dataString)
            }
            // Write the bytes of the string to an ArrayBuffer:
            arrayBuffer = new ArrayBuffer(byteString.length)
            intArray = new Uint8Array(arrayBuffer)
            for (i = 0; i < byteString.length; i += 1) {
                intArray[i] = byteString.charCodeAt(i)
            }
            // Write the ArrayBuffer (or ArrayBufferView) to a blob:
            if (hasBlobConstructor) {
                return new Blob(
                    [hasArrayBufferViewSupport ? intArray : arrayBuffer],
                    {type: mediaType}
                )
            }
            bb = new BlobBuilder()
            bb.append(arrayBuffer)
            return bb.getBlob(mediaType)
        }
    if (window.HTMLCanvasElement && !CanvasPrototype.toBlob) {
        if (CanvasPrototype.mozGetAsFile) {
            CanvasPrototype.toBlob = function (callback, type, quality) {
                if (quality && CanvasPrototype.toDataURL && dataURLtoBlob) {
                    callback(dataURLtoBlob(this.toDataURL(type, quality)))
                } else {
                    callback(this.mozGetAsFile('blob', type))
                }
            }
        } else if (CanvasPrototype.toDataURL && dataURLtoBlob) {
            CanvasPrototype.toBlob = function (callback, type, quality) {
                callback(dataURLtoBlob(this.toDataURL(type, quality)))
            }
        }
    }
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return dataURLtoBlob
        })
    } else if (typeof module === 'object' && module.exports) {
        module.exports = dataURLtoBlob
    } else {
        window.dataURLtoBlob = dataURLtoBlob
    }
}(window));