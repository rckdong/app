webpackJsonp([27],{"50sq":function(t,e){},"8+FI":function(t,e,n){"use strict";var o=new(n("IvJb").default)({methods:{warningMessage:function(t){this.$message.warning({showClose:!0,message:t})},successMessage:function(t){this.$message.success({showClose:!0,message:t})},errorMessage:function(t){this.$message.error({showClose:!0,message:t})},loading:function(t){var e=this.$loading({lock:!0,text:"正在获取数据...",spinner:"el-icon-loading",background:"rgba(0, 0, 0, 0.7)"});return e},notify:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"success";this.$notify({title:"",message:t,type:e})}}});e.a=o},"Du/2":function(t,e,n){"use strict";n.d(e,"c",function(){return o}),n.d(e,"d",function(){return i}),n.d(e,"a",function(){return a}),n.d(e,"b",function(){return r}),n.d(e,"f",function(){return c}),n.d(e,"e",function(){return u});var o="PHOTO_PATH",i="RESPONSE_END",a="LOADING_BTN",r="NOTIFY",c="USER_SIGNIN",u="SET_AUTH"},FVuk:function(t,e){},MtYA:function(t,e,n){"use strict";n.d(e,"d",function(){return h}),n.d(e,"b",function(){return g}),n.d(e,"a",function(){return v});var o=n("rVsN"),i=n.n(o),a=n("4YfN"),r=n.n(a),c=n("ZLEe"),u=n.n(c),s=n("6sKG"),l=n.n(s),p=n("6iV/"),f=n.n(p),d=n("8+FI"),m=n("uAC3");n.n(m);n.d(e,"c",function(){return l.a});var h=(new Date).getTime(),g=function(){return localStorage.getItem("appToken")?localStorage.getItem("appToken"):""};"function"!=typeof String.prototype.startsWith&&(String.prototype.startsWith=function(t){return this.slice(0,t.length)===t});var v=function(t,e){var n=u()(e);return t=t.toLowerCase(),n.map(function(e){var n=e.toLowerCase();if(!n.startsWith(t))throw"API模块:"+t+"，接口变量:"+n+"不符合规范,正确命名应为:"+t+n}),e};l.a.interceptors.request.use(function(t){if("post"===t.method){var e=f.a.parse(t.data);if("[object FormData]"==t.data.toString())return t.data.append("m",h),t;t.data=f.a.stringify(r()({},e,{m:h,app_token:g()}))}else"get"===t.method&&(t.params=r()({},t.params,{m:h,app_token:g()}));return t},function(t){return i.a.reject(t)}),l.a.defaults.headers.post["Content-Type"]="application/x-www-form-urlencoded;charset=utf-8",l.a.interceptors.response.use(function(t){var e="";switch(t.data.status){case 400:case 1002:case 1003:case 1004:case 5001:case 5002:case 5003:case 5004:case 5005:case 5006:e=t.data.message}return e?(d.a.errorMessage(e),1002==t.data.status?(console.log(t.data.status),localStorage.removeItem("appToken"),localStorage.removeItem("user_id"),localStorage.removeItem("username"),localStorage.removeItem("auth"),localStorage.removeItem("authArr"),void setTimeout(function(){location.href="http://chexinyuan.com/vue/#/login"},300)):i.a.reject(e)):t},function(t){return t&&t.response?t.message="对不起，服务器出现错误，状态码:"+t.response.status:t.message="连接服务器失败",d.a.errorMessage(t.message),i.a.reject(t)})},NHnr:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n("IvJb"),i={render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"height",attrs:{id:"app"}},[e("transition",{attrs:{name:"router-fade",mode:"out-in"}},[e("router-view")],1)],1)},staticRenderFns:[]};var a=n("vSla")(null,i,!1,function(t){n("50sq")},null,null).exports,r=n("IHPB"),c=n.n(r),u=n("zO6J"),s=[{path:"/marketContractList",component:function(t){return Promise.all([n.e(0),n.e(2)]).then(function(){return t(n("4hbP"))}.bind(null,n)).catch(n.oe)},meta:{title:"已完成合同",isAuth:!0}},{path:"/marketContractUnfinished",component:function(t){return Promise.all([n.e(0),n.e(2)]).then(function(){return t(n("umi+"))}.bind(null,n)).catch(n.oe)},meta:{title:"未完成合同",isAuth:!0}},{path:"/enteringContract",component:function(t){return Promise.all([n.e(0),n.e(8)]).then(function(){return t(n("EyyQ"))}.bind(null,n)).catch(n.oe)},meta:{title:"录合同",keepAlive:!0,isAuth:!0}},{path:"/repertoryIndex",component:function(t){return Promise.all([n.e(0),n.e(20)]).then(function(){return t(n("qJgG"))}.bind(null,n)).catch(n.oe)},meta:{title:"库管主页",keepAlive:!1,isAuth:!0}},{path:"/main",component:function(t){return n.e(15).then(function(){return t(n("xGEr"))}.bind(null,n)).catch(n.oe)},meta:{title:"系统首页"}},{path:"/403",component:function(t){return n.e(7).then(function(){var e=[n("T6N0")];t.apply(null,e)}.bind(this)).catch(n.oe)},meta:{title:"无权限访问"}}],l=[{path:"/financeMain",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("yj1D"))}.bind(null,n)).catch(n.oe)},meta:{title:"财务主页",keepAlive:!1,isAuth:!0}},{path:"/businessIncome",component:function(t){return Promise.all([n.e(0),n.e(10)]).then(function(){return t(n("prm+"))}.bind(null,n)).catch(n.oe)},meta:{title:"营业收入",keepAlive:!1,isAuth:!0}},{path:"/businessExpenditure",component:function(t){return Promise.all([n.e(0),n.e(24)]).then(function(){return t(n("PxK9"))}.bind(null,n)).catch(n.oe)},meta:{title:"营业支出",keepAlive:!1,isAuth:!0}},{path:"/marketAccounts",component:function(t){return Promise.all([n.e(0),n.e(16)]).then(function(){return t(n("fl+A"))}.bind(null,n)).catch(n.oe)},meta:{title:"销售结算",keepAlive:!1,isAuth:!0}},{path:"/administrativeIncome",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("a4Z6"))}.bind(null,n)).catch(n.oe)},meta:{title:"行政收入",keepAlive:!1,isAuth:!0}},{path:"/administrativeExpend",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("cUg+"))}.bind(null,n)).catch(n.oe)},meta:{title:"行政支出",keepAlive:!1,isAuth:!0}},{path:"/withdrawMain",component:function(t){return Promise.all([n.e(0),n.e(13)]).then(function(){return t(n("v0Mu"))}.bind(null,n)).catch(n.oe)},meta:{title:"提现列表",keepAlive:!1,isAuth:!0}}],p=[{path:"/clerkMain",component:function(t){return Promise.all([n.e(0),n.e(12)]).then(function(){return t(n("DmsW"))}.bind(null,n)).catch(n.oe)},meta:{title:"文员主页",keepAlive:!0,isAuth:!0}},{path:"/datumUploadShow",component:function(t){return Promise.all([n.e(0),n.e(19)]).then(function(){return t(n("xWDR"))}.bind(null,n)).catch(n.oe)},meta:{title:"资料查看",keepAlive:!1,isAuth:!0}},{path:"/datumUpload",component:function(t){return Promise.all([n.e(0),n.e(21)]).then(function(){return t(n("X+P2"))}.bind(null,n)).catch(n.oe)},meta:{title:"资料上传",keepAlive:!1,isAuth:!0}},{path:"/MyRewardList",component:function(t){return Promise.all([n.e(0),n.e(14)]).then(function(){return t(n("7ojG"))}.bind(null,n)).catch(n.oe)},meta:{title:"账户",keepAlive:!1,isAuth:!0}},{path:"/mortgageList",component:function(t){return Promise.all([n.e(0),n.e(23)]).then(function(){return t(n("9V9O"))}.bind(null,n)).catch(n.oe)},meta:{title:"按揭主页",keepAlive:!1,isAuth:!0}}],f=[{path:"/boutiqueMain",component:function(t){return Promise.all([n.e(0),n.e(9)]).then(function(){return t(n("RGp3"))}.bind(null,n)).catch(n.oe)},meta:{title:"精品主页",keepAlive:!1,isAuth:!0}},{path:"/boutiqueList",component:function(t){return Promise.all([n.e(0),n.e(18)]).then(function(){return t(n("x/HJ"))}.bind(null,n)).catch(n.oe)},meta:{title:"精品列表",keepAlive:!1,isAuth:!0}},{path:"/boutiqueType",component:function(t){return Promise.all([n.e(0),n.e(25)]).then(function(){return t(n("VHr+"))}.bind(null,n)).catch(n.oe)},meta:{title:"精品分类",keepAlive:!1,isAuth:!0}}],d=[{path:"/teamData",component:function(t){return Promise.all([n.e(0),n.e(6)]).then(function(){return t(n("YP8J"))}.bind(null,n)).catch(n.oe)},meta:{title:"团队数据",keepAlive:!1,isAuth:!0}},{path:"/monthData",component:function(t){return Promise.all([n.e(0),n.e(11)]).then(function(){return t(n("6XSi"))}.bind(null,n)).catch(n.oe)},meta:{title:"本月团队数据",keepAlive:!1,isAuth:!0}},{path:"/teamAccount",component:function(t){return Promise.all([n.e(0),n.e(3)]).then(function(){return t(n("IeCl"))}.bind(null,n)).catch(n.oe)},meta:{title:"团队账户",keepAlive:!1,isAuth:!0}},{path:"/teamApproveList",component:function(t){return Promise.all([n.e(0),n.e(22)]).then(function(){return t(n("3rMV"))}.bind(null,n)).catch(n.oe)},meta:{title:"审批列表",keepAlive:!1,isAuth:!0}}],m=[{path:"/managerIndex",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("3uzR"))}.bind(null,n)).catch(n.oe)},meta:{title:"总经理主页",keepAlive:!1,isAuth:!0}},{path:"/managerApproveList",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("YWPe"))}.bind(null,n)).catch(n.oe)},meta:{title:"审批列表",keepAlive:!1,isAuth:!0}},{path:"/managerAccount",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("F+pw"))}.bind(null,n)).catch(n.oe)},meta:{title:"团队账户",keepAlive:!1,isAuth:!0}}],h=[{path:"/investorIndex",component:function(t){return Promise.all([n.e(0),n.e(1)]).then(function(){return t(n("3uzR"))}.bind(null,n)).catch(n.oe)},meta:{title:"总经理主页",keepAlive:!1,isAuth:!0}},{path:"/investorAccount",component:function(t){return Promise.all([n.e(0),n.e(3)]).then(function(){return t(n("IeCl"))}.bind(null,n)).catch(n.oe)},meta:{title:"团队账户",keepAlive:!1,isAuth:!0}}],g=[].concat(c()(s),c()(l),c()(p),c()(f),c()(d),c()(m),c()(h)),v=[{path:"/404",component:function(t){return n.e(4).then(function(){var e=[n("sv6B")];t.apply(null,e)}.bind(this)).catch(n.oe)},meta:{title:"页面走丢了"}},{path:"*",redirect:"/404"},{path:"/login",component:function(t){return Promise.all([n.e(0),n.e(17)]).then(function(){return t(n("fAfb"))}.bind(null,n)).catch(n.oe)}}];o.default.use(u.a);var b,_,A=new u.a({routes:[{path:"",redirect:"/main"},{path:"/",component:function(t){return n.e(5).then(function(){var e=[n("MpTN")];t.apply(null,e)}.bind(this)).catch(n.oe)},meta:{title:"自述文件"},children:g}].concat(c()(v))}),k=(n("6sKG"),n("9rMa")),y=n("a3Yh"),w=n.n(y),P=n("aA9S"),x=n.n(P),I=n("Du/2"),L=n("MtYA"),T={state:{photoPath:"",responseEnd:"",refreshTime:L.d,loadingBtn:"",notify:"",appToken:"",auth:[]},mutations:(b={},w()(b,I.c,function(t,e){x()(t,{photoPath:e})}),w()(b,I.d,function(t,e){var n={refreshTime:(new Date).getTime(),responseEnd:(new Date).getTime()};e.hasOwnProperty("cancelLoadingBtn")&&e.cancelLoadingBtn&&(n.loadingBtn=""),x()(t,n)}),w()(b,I.a,function(t,e){t.loadingBtn=e}),w()(b,I.b,function(t,e){x()(t,{notify:e})}),w()(b,I.f,function(t,e){t.appToken=e}),w()(b,I.e,function(t,e){t.auth.splice(0,1,e)}),b),actions:(_={},w()(_,I.d,function(t,e){(0,t.commit)(I.d,e)}),w()(_,I.a,function(t,e){(0,t.commit)(I.a,e)}),w()(_,I.b,function(t,e){(0,t.commit)(I.b,e)}),w()(_,I.e,function(t,e){(0,t.commit)(I.e,e)}),_)};o.default.use(k.a);var S=new k.a.Store({strict:!0,modules:{global:T}}),C=n("jPwE"),D=n.n(C),O=n("uaSg"),E=Object(L.a)("demo",{demo_list:function(t){return console.log(t),L.c.get(O.a+"/test.json?"+t,{})},demo_item:function(t){return L.c.get(O.a+"/item.json",t)},demo_item_update:function(t){return L.c.get(O.a+"/delete.json",t)},demo_item_delete:function(t){return L.c.get(O.a+"/delete.json?"+t,{})}}),j=O.a+"/plugs/upload",q=Object(L.a)("global",{globalCityList:function(t){return L.c.post(O.a+"/city/get_city_list",t)},globalGetUserByPhone:function(t){return L.c.post(O.a+"/user/get_user_by_phone",t)},globalUpload:function(t){return L.c.post(O.a+"/plugs/upload",t,{headers:{"Content-Type":"multipart/form-data"}})},globalUploadUrl:j,globalFileDelete:function(t){return L.c.post(O.a+"/plugs/del_file",t)},globalGetSeal:function(t){return L.c.post(O.a+"/systemuser/get_seal",t)},globalGetInfo:function(t){return L.c.post(O.a+"/systemuser/get_info",t)}}),M=Object(L.a)("market",{marketContractList:function(t){return L.c.post(O.a+"/contacts/index",t)},marketApplyWithdraw:function(t){return L.c.post(O.a+"/contacts/apply_withdraw",t)},marketSettlementCreate:function(t){return L.c.post(O.a+"/contacts/settlement_create",t)},marketCancelExitDesc:function(t){return L.c.post(O.a+"/contacts/get_cancel_desc",t)},marketSubmitCancelExit:function(t){return L.c.post(O.a+"/contacts/cancel_contract",t)},marketContactsCreate:function(t){return L.c.post(O.a+"/contacts/add\n",t)},marketContactsInfo:function(t){return L.c.post(O.a+"/contacts/contact_info",t)},marketContactsEdit:function(t){return L.c.post(O.a+"/contacts/edit",t)},marketHandRecord:function(t){return L.c.post(O.a+"/contacts/traffic_record",t)},marketApplyTraffic:function(t){return L.c.post(O.a+"/contacts/apply_traffic",t)},marketDoApply:function(t){return L.c.post(O.a+"/contacts/do_apply",t)},marketChangeContractType:function(t){return L.c.post(O.a+"/contacts/change_contract_type",t)},marketMyRewardList:function(t){return L.c.post(O.a+"/manager/my_reward_list",t)},marketDoWithdraw:function(t){return L.c.post(O.a+"/systemuser/do_withdraw",t)},marketGetContactsPdf:function(t){return L.c.post(O.a+"/contacts/get_contacts_pdf",t)},marketContactChangeFormal:function(t){return L.c.post(O.a+"/contacts/contact_change_formal",t)}}),U=Object(L.a)("car",{carBrandList:function(t){return L.c.post(O.a+"/car/get_brands",t)},carBoutiqueList:function(t){return L.c.post(O.a+"/car/get_products",t)},carInsurancesList:function(t){return L.c.post(O.a+"/car/get_insurances",t)}}),G=Object(L.a)("clerk",{clerkContactsList:function(t){return L.c.post(O.a+"/contacts/get_contacts_list_by_clerk",t)},clerkUploadInsurance:function(t){return L.c.post(O.a+"/contacts/upload_insurance",t)},clerkUploadProcess:function(t){return L.c.post(O.a+"/contacts/upload_process",t)},clerkUploadLogistics:function(t){return L.c.post(O.a+"/contacts/upload_logistics",t)},clerkUploadLicense:function(t){return L.c.post(O.a+"/contacts/upload_license",t)},clerkShowInsurance:function(t){return L.c.post(O.a+"/contacts/show_insurance",t)},clerkShowProcess:function(t){return L.c.post(O.a+"/contacts/show_process",t)},clerkShowLogistics:function(t){return L.c.post(O.a+"/contacts/show_logistics",t)},clerkShowLicense:function(t){return L.c.post(O.a+"/contacts/show_license",t)}}),W=Object(L.a)("mortgage",{mortgageList:function(t){return L.c.post(O.a+"/mortgage/index",t)},mortgageDoExamine:function(t){return L.c.post(O.a+"/mortgage/do_examine",t)}}),B=Object(L.a)("boutique",{boutiqueMain:function(t){return L.c.post(O.a+"/productcheck/index",t)},boutiqueTypeList:function(t){return L.c.post(O.a+"/productscate/index",t)},boutiqueTypeInsert:function(t){return L.c.post(O.a+"/productscate/add",t)},boutiqueTypeEdit:function(t){return L.c.post(O.a+"/productscate/edit",t)},boutiqueTypeDelete:function(t){return L.c.post(O.a+"/productscate/del",t)},boutiqueList:function(t){return L.c.post(O.a+"/products/index",t)},boutiqueInsert:function(t){return L.c.post(O.a+"/products/add",t)},boutiqueEdit:function(t){return L.c.post(O.a+"/products/edit",t)},boutiqueProject:function(t){return L.c.post(O.a+"/productcheck/product_detail",t)},boutiqueFinish:function(t){return L.c.post(O.a+"/productcheck/do_finish",t)},boutiqueProductsWithdraw:function(t){return L.c.post(O.a+"/productcheck/products_withdraw",t)}}),N=Object(L.a)("repertory",{repertoryList:function(t){return L.c.post(O.a+"/repertory/index",t)}}),F=Object(L.a)("employee",{employeeUpdatePwd:function(t){return L.c.post(O.a+"/systemuser/change_password",t)}}),R=Object(L.a)("finance",{financeMain:function(t){return L.c.post(O.a+"/finance/index",t)},financeBusinessList:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=O.a+"/finance/business_index";return e?n:L.c.post(n,t)},financeSelectOptions:function(t){return L.c.post(O.a+"/finance/get_business_options",t)},financeGetBusinessLog:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=O.a+"/finance/get_business_log";return e?n:L.c.post(n,t)},financeAddIncomeLog:function(t){return L.c.post(O.a+"/finance/add_income_log",t)},financeAddExincomeLog:function(t){return L.c.post(O.a+"/finance/add_exincome_log",t)},financeLogReport:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=O.a+"/finance/log_report";return e?n:L.c.post(n,t)},financeAdministrativeOptions:function(t){return L.c.post(O.a+"/administrative/get_options",t)},financeAdministrativeIndex:function(t){var e=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=O.a+"/administrative/index";return e?n:L.c.post(n,t)},financeAdministrativeAddLog:function(t){return L.c.post(O.a+"/administrative/add_log",t)},financeSettlement:function(t){return L.c.post(O.a+"/finance/settlement",t)},financeDoSettlement:function(t){return L.c.post(O.a+"/finance/do_settlement",t)},financeWithdrawToWalletExamine:function(t){return L.c.post(O.a+"/withdraw/examine_to_wallet",t)},financeWithdrawIndex:function(t){return L.c.post(O.a+"/withdraw/index",t)},financeWithdrawDoExamine:function(t){return L.c.post(O.a+"/withdraw/do_examine",t)}}),H=Object(L.a)("manager",{managerIndexMain:function(t){return L.c.post(O.a+"/touzi/index",t)},managerApprovalList:function(t){return L.c.post(O.a+"/touzi/approval_list",t)},managerDoApproval:function(t){return L.c.post(O.a+"/touzi/do_approval",t)}}),$={fileDirInsurance:{compulsory:"insurance/compulsory",commercial:"insurance/commercial",quality:"insurance/quality",compulsoryInvoice:"insurance/compulsory_invoice",commercialInvoice:"insurance/commercial_invoice",qualityInvoice:"insurance/quality_invoice"},fileDirProcedure:{certificate:"procedure/certificate",examination:"procedure/examination",agreement:"procedure/agreement",invoice:"procedure/invoice",environment:"procedure/environment"},fileDirLogistics:{ahead:"logistics/ahead",side:"logistics/side",back:"logistics/back",insideOne:"logistics/insideOne",insideTwo:"logistics/insideTwo",nameplate:"logistics/nameplate"},fileDirLicense:{license:"drivingLicense/license",registration:"drivingLicense/registration",tax:"drivingLicense/tax",receipt:"drivingLicense/receipt"},imagePath:"http://chexinyuan.com/",fileDirBusinessIncome:"business/certificate",rootUrl:"http://chexinyuan.com/"},z=Object(L.a)("handler",{handlerTeamData:function(t){return L.c.post(O.a+"/manager/reward_list",t)},handlerWithdraw:function(t){return L.c.post(O.a+"/manager/withdraw",t)},handlerGetStatistics:function(t){return L.c.post(O.a+"/manager/get_statistics",t)},handlerTeamStatistics:function(t){return L.c.post(O.a+"/manager/team_statistics",t)},handlerTeamCompleteStatistics:function(t){return L.c.post(O.a+"/manager/team_complete_statistics",t)},handlerApprovalList:function(t){return L.c.post(O.a+"/manager/approval_list",t)},handlerDoApproval:function(t){return L.c.post(O.a+"/manager/do_approval",t)},handlerPrintCommissionDetail:function(t){return $.rootUrl+"index.php/api/contacts/settlement.html?id="+t}}),J=Object(L.a)("budget",{budgetAdd:function(t){return L.c.post(O.a+"/budget/calculation",t)},budgetGetInfo:function(t){return L.c.post(O.a+"/budget/get_info",t)},budgetGetType:function(t){return L.c.post(O.a+"/budget/get_type",t)},budgetGetPriceByIds:function(t){return L.c.post(O.a+"/contacts/get_price_by_ids",t)}}),V=Object(L.a)("user",{userLogin:function(t){return L.c.post(O.a+"/login/login",t)},userLoginOut:function(t){return L.c.post(O.a+"/login/login_out",t)},userGetToken:function(t){return L.c.post(O.a+"/login/get_auth",t)},userWithdrawLog:function(t){return L.c.post(O.a+"/systemuser/withdraw_log",t)}}),Y=x()(E,q,M,U,G,W,B,N,F,R,H,z,J,V),K=(n("8+FI"),n("SsbU")),X=n.n(K);n("FVuk"),n("briU"),n("WEav");o.default.config.productionTip=!1,o.default.use(D.a,{size:"small"}),o.default.use(X.a),o.default.prototype.$HTTP=Y,o.default.prototype.$CONSTANT=$,A.beforeEach(function(t,e,n){if(localStorage.getItem("appToken")||"/login"===t.path)if(t.meta.isAuth){return void n()}else navigator.userAgent.indexOf("MSIE")>-1&&"/editor"===t.path?o.default.prototype.$alert("vue-quill-editor组件不兼容IE10及以下浏览器，请使用更高版本的浏览器查看","浏览器不兼容通知",{confirmButtonText:"确定"}):n();else n("/login")}),new o.default({router:A,store:S,render:function(t){return t(a)}}).$mount("#app")},WEav:function(t,e){},uaSg:function(t,e,n){"use strict";n.d(e,"a",function(){return o});var o="";o="http://chexinyuan.com:80/index.php/api"}},["NHnr"]);