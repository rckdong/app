webpackJsonp([19],{dd9r:function(a,e){},xWDR:function(a,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var t=i("KH7x"),s=i.n(t),l=i("rVsN"),c=i.n(l),o=i("aA9S"),n=i.n(o),r=i("4YfN"),u=i.n(r),d=i("8+FI"),p=i("9rMa"),g=i("Du/2"),_=i("zilM"),m=(i("IvJb"),i("TVnW")),v={name:"insuranceUpload",components:{singleUpload:_.a},route:{canReuse:!1},data:function(){return{options:[{value:"0",label:"非全包"},{value:"1",label:"4S店全包"}],uploadUrl:this.$HTTP.globalUploadUrl,imageUrl:"",formImageUrl:"",uploadDir:"test",activeNames:["1","2","3","4"],contactsId:-1,isSubmitIng:!1,ruleForm:{},insurance:{contacts_id:"",compulsory_insurance:"",commercial_insurance_one:"",commercial_insurance_two:"",commercial_insurance_three:"",quality_assurance:"",compulsory_image:"",commercial_image:"",quality_image:"",compulsory_invoice_image:"",commercial_invoice_image:"",quality_invoice_image:""},procedure:{contacts_id:"",is_quanbao:"0",invoice_money:"",certificate_image:"",examination_image:"",agreement_image:"",invoice_image:"",environment_image:""},logistics:{contacts_id:"",garage_number:"",ahead_image:"",side_image:"",back_image:"",inside_image_one:"",inside_image_two:"",nameplate_image:""},license:{contacts_id:"",license_image:"",registration_image:"",tax_image:"",receipt_image:""},rules:{}}},computed:u()({},Object(p.c)(["global"])),filters:n()({},m.a),mounted:function(){var a=this.$route.query.id;this.contactsId=a},watch:{$route:function(a,e){this.$router.go(0)}},created:function(){var a=this;this.contactsId=this.$route.query.id;var e={contacts_id:this.contactsId},i=d.a.loading();c.a.all([this.$HTTP.clerkShowInsurance(e),this.$HTTP.clerkShowProcess(e),this.$HTTP.clerkShowLogistics(e),this.$HTTP.clerkShowLicense(e)]).then(function(e){var t=s()(e,4),l=t[0],c=t[1],o=t[2],n=t[3];a.insurance=l.data.data,c.data.data.is_quanbao="0"==c.data.data.is_quanbao?"非全包":"4S店全包",a.procedure=c.data.data,a.logistics=o.data.data,a.license=n.data.data,i.close()}).catch(function(a){i.close()})},methods:u()({},Object(p.b)([g.a,g.d,g.b]),{uploadSuccess:function(a){var e=a.data,i=a.flag,t=(e.site_url,e.img_url),l=i.split("."),c=s()(l,2),o=c[0],n=c[1];this[o][n]=t},submit:function(a){var e=this;"非全包"==this.procedure.is_quanbao&&(this.procedure.is_quanbao=0),this.$refs.formElement.validate(function(a){e.LOADING_BTN("submitUpload"),e.insurance.contacts_id=e.contactsId,e.procedure.contacts_id=e.contactsId,e.logistics.contacts_id=e.contactsId,e.license.contacts_id=e.contactsId,c.a.all([e.$HTTP.clerkUploadInsurance(e.insurance),e.$HTTP.clerkUploadProcess(e.procedure),e.$HTTP.clerkUploadLogistics(e.logistics),e.$HTTP.clerkUploadLicense(e.license)]).then(function(a){var i=s()(a,4);i[0],i[1],i[2],i[3];e.LOADING_BTN(""),d.a.notify("数据提交成功")}).catch(function(a){e.LOADING_BTN("")})})}})},f={render:function(){var a=this,e=a.$createElement,i=a._self._c||e;return i("div",{staticStyle:{padding:"10px 0 50px 10px"}},[i("div",{ref:"marketDom"},[i("el-form",{ref:"formElement",attrs:{model:a.ruleForm,rules:a.rules,"label-width":"150px",inline:""}},[i("el-collapse",{attrs:{accordion:""},model:{value:a.activeNames,callback:function(e){a.activeNames=e},expression:"activeNames"}},[i("el-collapse-item",{attrs:{name:"1"}},[i("template",{slot:"title"},[i("p",{staticClass:"line defaultColor"},[i("i",{staticClass:"header-icon el-icon-upload defaultBg"}),a._v("  查看保险信息\n                        ")])]),a._v(" "),i("div",[i("el-form-item",{staticStyle:{"margin-left":"-40px"},attrs:{label:"交强车船税",prop:"compulsory_insurance"}},[i("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:a.$options.filters.filterCNYFormatting(a.insurance.compulsory_insurance),placement:"right"}},[i("el-input",{staticStyle:{width:"170px"},attrs:{clearable:!0,placeholder:"请输入交强车船税金额",disabled:!0},model:{value:a.insurance.compulsory_insurance,callback:function(e){a.$set(a.insurance,"compulsory_insurance",e)},expression:"insurance.compulsory_insurance"}})],1)],1),a._v(" "),i("el-form-item",{staticStyle:{"margin-left":"-12px"},attrs:{label:"商业险类别(三选一)",width:"150",prop:"insuranceMoney"}},[i("el-input",{staticStyle:{width:"200px"},attrs:{clearable:!0,placeholder:"请输入金额",disabled:!0},model:{value:a.insurance.commercial_insurance_one,callback:function(e){a.$set(a.insurance,"commercial_insurance_one",e)},expression:"insurance.commercial_insurance_one"}},[i("template",{slot:"prepend"},[a._v("65折")])],2),a._v(" "),i("el-input",{staticStyle:{width:"200px","margin-left":"-5px"},attrs:{clearable:!0,disabled:!0},model:{value:a.insurance.commercial_insurance_two,callback:function(e){a.$set(a.insurance,"commercial_insurance_two",e)},expression:"insurance.commercial_insurance_two"}},[i("template",{slot:"prepend"},[a._v("95折")])],2),a._v(" "),i("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:a.$options.filters.filterCNYFormatting(a.insurance.commercial_insurance_three),placement:"right"}},[i("el-input",{staticStyle:{width:"270px","margin-left":"-5px"},attrs:{clearable:!0,disabled:!0,placeholder:"请输入金额"},model:{value:a.insurance.commercial_insurance_three,callback:function(e){a.$set(a.insurance,"commercial_insurance_three",e)},expression:"insurance.commercial_insurance_three"}},[i("template",{slot:"prepend"},[a._v("店保金额")])],2)],1)],1),a._v(" "),i("el-form-item",{staticStyle:{"margin-left":"-56px"},attrs:{label:"质保金额",prop:"quality_assurance"}},[i("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:a.$options.filters.filterCNYFormatting(a.insurance.quality_assurance),placement:"right"}},[i("el-input",{staticStyle:{width:"170px"},attrs:{clearable:!0,placeholder:"请输入质保金额",disabled:!0},model:{value:a.insurance.quality_assurance,callback:function(e){a.$set(a.insurance,"quality_assurance",e)},expression:"insurance.quality_assurance"}})],1)],1),a._v(" "),i("ul",{staticClass:"uploadList"},[i("li",[i("div",{staticClass:"upTag"},[i("el-tag",[a._v("交强险")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirInsurance.compulsory,disabled:!0,path:a.insurance.compulsory_image,uploadFlag:"insurance.compulsory_image"},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",[a._v("商业险")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirInsurance.commercial,disabled:!0,uploadFlag:"insurance.commercial_image",path:a.insurance.commercial_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",[a._v("质保")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirInsurance.quality,disabled:!0,uploadFlag:"insurance.quality_image",path:a.insurance.quality_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",[a._v("交强险发票")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirInsurance.compulsoryInvoice,disabled:!0,uploadFlag:"insurance.compulsory_invoice_image",path:a.insurance.compulsory_invoice_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",[a._v("商业险发票")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirInsurance.commercialInvoice,disabled:!0,uploadFlag:"insurance.commercial_invoice_image",path:a.insurance.commercial_invoice_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",[a._v("质保发票")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirInsurance.qualityInvoice,disabled:!0,uploadFlag:"insurance.quality_invoice_image",path:a.insurance.quality_invoice_image},on:{uploadSuccess:a.uploadSuccess}})],1)])])],1)],2),a._v(" "),i("el-collapse-item",{attrs:{name:"2"}},[i("template",{slot:"title"},[i("p",{staticClass:"line successColor"},[i("i",{staticClass:"header-icon el-icon-upload successBg"}),a._v("  查看手续信息\n                        ")])]),a._v(" "),i("div",[i("el-form-item",{staticStyle:{"margin-left":"-40px"},attrs:{label:"发票金额",prop:"invoice_money"}},[i("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:a.$options.filters.filterCNYFormatting(a.procedure.invoice_money),placement:"right"}},[i("el-input",{staticStyle:{width:"170px"},attrs:{clearable:!0,placeholder:"请输入发票金额",disabled:!0},model:{value:a.procedure.invoice_money,callback:function(e){a.$set(a.procedure,"invoice_money",e)},expression:"procedure.invoice_money"}})],1)],1),a._v(" "),i("el-form-item",{staticStyle:{"margin-left":"-40px"},attrs:{label:"选项"}},[i("el-select",{attrs:{placeholder:"请选择",disabled:!0},model:{value:a.procedure.is_quanbao,callback:function(e){a.$set(a.procedure,"is_quanbao",e)},expression:"procedure.is_quanbao"}},a._l(a.options,function(a){return i("el-option",{key:a.value,attrs:{label:a.label,value:a.value}})}))],1),a._v(" "),i("ul",{staticClass:"uploadList"},[i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"success"}},[a._v("关单/合格证")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirProcedure.certificate,disabled:!0,uploadFlag:"procedure.certificate_image",path:a.procedure.certificate_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"success"}},[a._v("商检")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirProcedure.examination,disabled:!0,uploadFlag:"procedure.examination_image",path:a.procedure.examination_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"success"}},[a._v("一致证书")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirProcedure.agreement,disabled:!0,uploadFlag:"procedure.agreement_image",path:a.procedure.agreement_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"success"}},[a._v("发票")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirProcedure.invoice,disabled:!0,uploadFlag:"procedure.invoice_image",path:a.procedure.invoice_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"success"}},[a._v("环保证书")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirProcedure.environment,disabled:!0,uploadFlag:"procedure.environment_image",path:a.procedure.environment_image},on:{uploadSuccess:a.uploadSuccess}})],1)])])],1)],2),a._v(" "),i("el-collapse-item",{attrs:{name:"3"}},[i("template",{slot:"title"},[i("p",{staticClass:"line warningColor"},[i("i",{staticClass:"header-icon el-icon-upload warningBg"}),a._v("  查看物流信息\n                        ")])]),a._v(" "),i("div",[i("el-form-item",{staticStyle:{"margin-left":"-10px"},attrs:{label:"车架后7位编号",prop:"garage_number"}},[i("el-input",{staticStyle:{width:"370px"},attrs:{clearable:!0,placeholder:"请输入车架后7位编号",disabled:!0},model:{value:a.logistics.garage_number,callback:function(e){a.$set(a.logistics,"garage_number",e)},expression:"logistics.garage_number"}})],1),a._v(" "),i("ul",{staticClass:"uploadList"},[i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"warning"}},[a._v("车前方")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLogistics.ahead,disabled:!0,uploadFlag:"logistics.ahead_image",path:a.logistics.ahead_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"warning"}},[a._v("侧方图片")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLogistics.side,disabled:!0,uploadFlag:"logistics.side_image",path:a.logistics.side_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"warning"}},[a._v("后方")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLogistics.back,disabled:!0,uploadFlag:"logistics.back_image",path:a.logistics.back_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"warning"}},[a._v("内饰1")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLogistics.insideOne,disabled:!0,uploadFlag:"logistics.inside_image_one",path:a.logistics.inside_image_one},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"warning"}},[a._v("内饰2")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLogistics.insideTwo,disabled:!0,uploadFlag:"logistics.inside_image_two",path:a.logistics.inside_image_two},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"warning"}},[a._v("铭牌")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLogistics.nameplate,disabled:!0,uploadFlag:"logistics.nameplate_image",path:a.logistics.nameplate_image},on:{uploadSuccess:a.uploadSuccess}})],1)])])],1)],2),a._v(" "),i("el-collapse-item",{attrs:{name:"4"}},[i("template",{slot:"title"},[i("p",{staticClass:"line warningColor"},[i("i",{staticClass:"header-icon el-icon-upload warningBg"}),a._v("  查看物流信息\n                        ")])]),a._v(" "),i("div",[i("ul",{staticClass:"uploadList"},[i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"danger"}},[a._v("行驶证")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLicense.license,disabled:!0,uploadFlag:"license.license_image",path:a.license.license_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"danger"}},[a._v("登记证")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLicense.registration,disabled:!0,uploadFlag:"license.registration_image",path:a.license.registration_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"danger"}},[a._v("购置税")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLicense.tax,disabled:!0,uploadFlag:"license.tax_image",path:a.license.tax_image},on:{uploadSuccess:a.uploadSuccess}})],1)]),a._v(" "),i("li",[i("div",{staticClass:"upTag"},[i("el-tag",{attrs:{type:"danger"}},[a._v("购置税收据")])],1),a._v(" "),i("div",[i("single-upload",{attrs:{uploadData:a.$CONSTANT.fileDirLicense.receipt,disabled:!0,uploadFlag:"license.receipt_image",path:a.license.receipt_image},on:{uploadSuccess:a.uploadSuccess}})],1)])])])],2)],1)],1)],1),a._v(" "),i("div",{staticClass:"btnGroup"},[i("p",[i("el-button",{attrs:{size:"mini",round:"",icon:"el-icon-back"},on:{click:function(){a.$router.back(-1)}}},[a._v("返回上一级")])],1)])])},staticRenderFns:[]};var b=i("vSla")(v,f,!1,function(a){i("dd9r")},"data-v-34025abe",null);e.default=b.exports}});