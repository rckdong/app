webpackJsonp([6],{"0bZW":function(t,e){},"3pyw":function(t,e,a){var n,i;void 0===(i="function"==typeof(n=function(t,e,a){return function(t,e,a,n,i,r){function s(t){return"number"==typeof t&&!isNaN(t)}var o=this;if(o.version=function(){return"1.9.3"},o.options={useEasing:!0,useGrouping:!0,separator:",",decimal:".",easingFn:function(t,e,a,n){return a*(1-Math.pow(2,-10*t/n))*1024/1023+e},formattingFn:function(t){var e,a,n,i,r,s,l=t<0;if(t=Math.abs(t).toFixed(o.decimals),a=(e=(t+="").split("."))[0],n=e.length>1?o.options.decimal+e[1]:"",o.options.useGrouping){for(i="",r=0,s=a.length;r<s;++r)0!==r&&r%3==0&&(i=o.options.separator+i),i=a[s-r-1]+i;a=i}return o.options.numerals.length&&(a=a.replace(/[0-9]/g,function(t){return o.options.numerals[+t]}),n=n.replace(/[0-9]/g,function(t){return o.options.numerals[+t]})),(l?"-":"")+o.options.prefix+a+n+o.options.suffix},prefix:"",suffix:"",numerals:[]},r&&"object"==typeof r)for(var l in o.options)r.hasOwnProperty(l)&&null!==r[l]&&(o.options[l]=r[l]);""===o.options.separator?o.options.useGrouping=!1:o.options.separator=""+o.options.separator;for(var u=0,c=["webkit","moz","ms","o"],d=0;d<c.length&&!window.requestAnimationFrame;++d)window.requestAnimationFrame=window[c[d]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[c[d]+"CancelAnimationFrame"]||window[c[d]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(t,e){var a=(new Date).getTime(),n=Math.max(0,16-(a-u)),i=window.setTimeout(function(){t(a+n)},n);return u=a+n,i}),window.cancelAnimationFrame||(window.cancelAnimationFrame=function(t){clearTimeout(t)}),o.initialize=function(){return!(!o.initialized&&(o.error="",o.d="string"==typeof t?document.getElementById(t):t,o.d?(o.startVal=Number(e),o.endVal=Number(a),s(o.startVal)&&s(o.endVal)?(o.decimals=Math.max(0,n||0),o.dec=Math.pow(10,o.decimals),o.duration=1e3*Number(i)||2e3,o.countDown=o.startVal>o.endVal,o.frameVal=o.startVal,o.initialized=!0,0):(o.error="[CountUp] startVal ("+e+") or endVal ("+a+") is not a number",1)):(o.error="[CountUp] target is null or undefined",1)))},o.printValue=function(t){var e=o.options.formattingFn(t);"INPUT"===o.d.tagName?this.d.value=e:"text"===o.d.tagName||"tspan"===o.d.tagName?this.d.textContent=e:this.d.innerHTML=e},o.count=function(t){o.startTime||(o.startTime=t),o.timestamp=t;var e=t-o.startTime;o.remaining=o.duration-e,o.options.useEasing?o.countDown?o.frameVal=o.startVal-o.options.easingFn(e,0,o.startVal-o.endVal,o.duration):o.frameVal=o.options.easingFn(e,o.startVal,o.endVal-o.startVal,o.duration):o.countDown?o.frameVal=o.startVal-(o.startVal-o.endVal)*(e/o.duration):o.frameVal=o.startVal+(o.endVal-o.startVal)*(e/o.duration),o.countDown?o.frameVal=o.frameVal<o.endVal?o.endVal:o.frameVal:o.frameVal=o.frameVal>o.endVal?o.endVal:o.frameVal,o.frameVal=Math.round(o.frameVal*o.dec)/o.dec,o.printValue(o.frameVal),e<o.duration?o.rAF=requestAnimationFrame(o.count):o.callback&&o.callback()},o.start=function(t){o.initialize()&&(o.callback=t,o.rAF=requestAnimationFrame(o.count))},o.pauseResume=function(){o.paused?(o.paused=!1,delete o.startTime,o.duration=o.remaining,o.startVal=o.frameVal,requestAnimationFrame(o.count)):(o.paused=!0,cancelAnimationFrame(o.rAF))},o.reset=function(){o.paused=!1,delete o.startTime,o.initialized=!1,o.initialize()&&(cancelAnimationFrame(o.rAF),o.printValue(o.startVal))},o.update=function(t){if(o.initialize()){if(!s(t=Number(t)))return void(o.error="[CountUp] update() - new endVal is not a number: "+t);o.error="",t!==o.frameVal&&(cancelAnimationFrame(o.rAF),o.paused=!1,delete o.startTime,o.startVal=o.frameVal,o.endVal=t,o.countDown=o.startVal>o.endVal,o.rAF=requestAnimationFrame(o.count))}},o.initialize()&&o.printValue(o.startVal)}})?n.call(e,a,e,t):n)||(t.exports=i)},COzO:function(t,e,a){var n;"undefined"!=typeof self&&self,n=function(t){return function(t){function e(n){if(a[n])return a[n].exports;var i=a[n]={i:n,l:!1,exports:{}};return t[n].call(i.exports,i,i.exports,e),i.l=!0,i.exports}var a={};return e.m=t,e.c=a,e.d=function(t,a,n){e.o(t,a)||Object.defineProperty(t,a,{configurable:!1,enumerable:!0,get:n})},e.n=function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(a,"a",a),a},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=0)}([function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=a(1),i=a.n(n),r=a(4),s=a.n(r),o={__countup__:s.a,name:"ICountUp",props:{startVal:{type:Number,required:!1,default:0},endVal:{type:Number,required:!0},decimals:{type:Number,required:!1,default:0},duration:{type:Number,required:!1,default:2},options:{type:Object,required:!1}},data:function(){return{instance:null}},watch:{endVal:{handler:function(t){var e=this;e.instance&&i()(e.instance.update)&&e.instance.update(t)},deep:!1}},methods:{init:function(){var t=this;if(!t.instance){var e=t.$el,a=new s.a(e,t.startVal,t.endVal,t.decimals,t.duration,t.options);a.error||(a.start(function(){return t.$emit("ready",a,s.a)}),t.instance=a)}},uninit:function(){this.instance=null},start:function(t){var e=this;if(e.instance&&i()(e.instance.start)&&i()(t))return e.instance.start(t)},pauseResume:function(){var t=this;if(t.instance&&i()(t.instance.pauseResume))return t.instance.pauseResume()},reset:function(){var t=this;if(t.instance&&i()(t.instance.reset))return t.instance.reset()},update:function(t){var e=this;if(e.instance&&i()(e.instance.update))return e.instance.update(t)}},mounted:function(){this.init()},beforeDestroy:function(){this.uninit()},render:function(t){return t("span",{})}};e.default=o},function(t,e,a){var n=a(2),i=a(3),r="[object AsyncFunction]",s="[object Function]",o="[object GeneratorFunction]",l="[object Proxy]";t.exports=function(t){if(!i(t))return!1;var e=n(t);return e==s||e==o||e==r||e==l}},function(t,e){var a=Object.prototype.toString;t.exports=function(t){return a.call(t)}},function(t,e){t.exports=function(t){var e=typeof t;return null!=t&&("object"==e||"function"==e)}},function(e,a){e.exports=t}])},t.exports=n(a("3pyw"))},YP8J:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=a("KH7x"),i=a.n(n),r=a("rVsN"),s=a.n(r),o=a("aA9S"),l=a.n(o),u=(a("9rMa"),a("Du/2"),a("TVnW")),c=a("COzO"),d={name:"financeMain",components:{ICountUp:a.n(c).a},filters:l()({},u.a),data:function(){return{activeNames:["1"],init:!1,quarter:{startVal:0,endVal:0,decimals:0,duration:.5,options:{useEasing:!0,useGrouping:!0,separator:",",decimal:".",prefix:"",suffix:""}},month:{startVal:0,endVal:0,decimals:0,duration:.5,options:{useEasing:!0,useGrouping:!0,separator:",",decimal:".",prefix:"",suffix:""}},currentMonthData:{all_order_count:0,all_order_complete:0,team_order_count:0,team_order_complete:0}}},mounted:function(){var t=this;s.a.all([this.$HTTP.handlerTeamStatistics({}),this.$HTTP.handlerGetStatistics({})]).then(function(e){var a=i()(e,2),n=a[0],r=a[1],s=n.data.data,o=s.team_complete,l=s.team_target;t.quarter.endVal=l,t.month.endVal=o,t.init=!0,t.currentMonthData=r.data.data,console.log("=======>"),console.log(r.data.data)}).catch(function(t){})}},p={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticStyle:{background:"#FFF"}},[a("div",{staticClass:"financeContainer"},[a("el-breadcrumb",{attrs:{separator:"/"}},[a("el-breadcrumb-item",[t._v("销售经理人经理")]),t._v(" "),a("el-breadcrumb-item",[t._v("团队数据")])],1),t._v(" "),a("div",{staticClass:"item"},[a("div",{staticClass:"vo successBg"},[t._v("本月团队已完成")]),t._v(" "),a("div",{staticClass:"echarts"},[t.init?a("div",{staticClass:"dangerColor",staticStyle:{"padding-top":"70px","font-size":"38px","padding-left":"70px"}},[a("span",{staticStyle:{"font-size":"15px"}},[t._v("¥")]),t._v(" "),a("ICountUp",{attrs:{startVal:t.month.startVal,endVal:t.month.endVal,decimals:t.month.decimals,duration:t.month.duration,options:t.month.options}})],1):t._e()])]),t._v(" "),a("div",{staticClass:"item"},[a("div",{staticClass:"vo dangerBg"},[t._v("团队季度目标")]),t._v(" "),a("div",{staticClass:"echarts"},[t.init?a("div",{staticClass:"dangerColor",staticStyle:{"padding-top":"70px","font-size":"38px","padding-left":"70px"}},[a("span",{staticStyle:{"font-size":"15px"}},[t._v("¥")]),t._v(" "),a("ICountUp",{attrs:{startVal:t.quarter.startVal,endVal:t.quarter.endVal,decimals:t.quarter.decimals,duration:t.quarter.duration,options:t.quarter.options}})],1):t._e()])])],1),t._v(" "),a("div",{staticClass:"statistics"},[a("el-collapse",{attrs:{accordion:""},model:{value:t.activeNames,callback:function(e){t.activeNames=e},expression:"activeNames"}},[a("el-collapse-item",{attrs:{name:"1"}},[a("template",{slot:"title"},[a("h3",{staticClass:"pd8 defaultColor"},[t._v("  "),a("i",{staticClass:"header-icon el-icon-info"}),t._v(" 团队数据统计\n                    ")])]),t._v(" "),a("div",{staticClass:"pd8"},[t.init?a("ul",{staticClass:"stat"},[a("li",[a("span",[t._v("本月团队已完成:")]),t._v(" "),a("el-tag",[t._v(t._s(t.$options.filters.filterCNYFormatting(t.quarter.endVal)))]),t._v(" "),a("el-button",{attrs:{size:"mini",icon:"el-icon-view",round:""}},[t._v("查看")])],1),t._v(" "),a("li",[a("span",[t._v("团队季度目标:")]),t._v(" "),a("el-tag",{attrs:{type:"success"}},[t._v(t._s(t.$options.filters.filterCNYFormatting(t.quarter.endVal)))])],1)]):t._e()])],2)],1),t._v(" "),a("el-collapse",{staticStyle:{"margin-top":"20px"},attrs:{accordion:""},model:{value:t.activeNames,callback:function(e){t.activeNames=e},expression:"activeNames"}},[a("el-collapse-item",{attrs:{name:"1"}},[a("template",{slot:"title"},[a("h3",{staticClass:"pd8 defaultColor"},[t._v("  "),a("i",{staticClass:"header-icon el-icon-info"}),t._v(" 本月数据统计\n                    ")])]),t._v(" "),a("div",{staticClass:"pd8"},[t.init?a("ul",{staticClass:"stat"},[a("li",[a("span",[t._v("本月公司订单数量:")]),t._v(" "),a("el-tag",{attrs:{type:"success"}},[t._v(t._s(t.currentMonthData.all_order_count))])],1),t._v(" "),a("li",[a("span",[t._v("本月公司交车订单数量:")]),t._v(" "),a("el-tag",{attrs:{type:"success"}},[t._v(t._s(t.currentMonthData.all_order_complete))])],1),t._v(" "),a("li",[a("span",[t._v("本月团队订单数量:")]),t._v(" "),a("el-tag",{attrs:{type:"success"}},[t._v(t._s(t.currentMonthData.team_order_count))])],1),t._v(" "),a("li",[a("span",[t._v("本月团队交车数量:")]),t._v(" "),a("el-tag",{attrs:{type:"success"}},[t._v(t._s(t.currentMonthData.team_order_complete))])],1)]):t._e()])],2)],1)],1)])},staticRenderFns:[]};var m=a("vSla")(d,p,!1,function(t){a("0bZW")},"data-v-631c7882",null);e.default=m.exports}});