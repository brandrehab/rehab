!function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/dist/",n(n.s=156)}({156:function(t,e,n){window.drupalSettings=n(157).module,n(158),n(161),n(165)},157:function(t,e){e.module=document.getElementById("drupalSettings")?JSON.parse(document.getElementById("drupalSettings").innerHTML):null},158:function(t,e,n){window.m={lazy:n(159).module,footer:n(160).module}},159:function(t,e){var n,o,r;e.module=(n=function(){return"lazy"},o=!1,r=function(){o=!0;var t=$(".lazy");if(0===t.length)return $(document).off("scroll.lazy"),$(window).off("resize.lazy"),void $(window).off("orientationchange.lazy");var e=[],n=$(window).scrollTop(),r=n+$(window).innerHeight();$(t).each((function(){var t=$(this).parent(),o=t.offset().top;o+t.height()<=r&&o>=n&&e.push(this)})),$(e).each((function(){$(this).data("lazy-srcset")&&$(this).attr("srcset",$(this).data("lazy-srcset")).removeClass("lazy"),$(this).data("lazy")&&$(this).attr("src",$(this).data("lazy")).removeClass("lazy")})),o=!1},void $((function(){($(".lazy-trigger").length>0||$("img.lazy").length>0)&&($(".lazy-trigger").on("click.lazy",(function(t){0==o&&r()})),$(document).on("scroll.lazy",(function(t){0==o&&r()})),$(window).on("resize.lazy",(function(t){0==o&&r()})),$(window).on("orientationchange.lazy",(function(t){0==o&&r()})),r())})),{getName:n})},160:function(t,e){var n;e.module=(n=function(){return"footer"},$((function(){$("#copyright-year").length>0&&$("#copyright-year").html((new Date).getFullYear())})),{getName:n})},161:function(t,e,n){"use strict";n.r(e);n(162)},162:function(t,e,n){},165:function(t,e,n){"use strict";n.r(e)}});