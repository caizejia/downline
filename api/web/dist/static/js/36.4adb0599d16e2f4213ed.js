webpackJsonp([36],{dS3E:function(e,a,t){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var l={name:"updateTask",data:function(){return{task:{task_name:"",translation_language:"",urgency_degree:"",requirement:"",content_url:""},uploadParam:{file_field:"file",token:"",time:+new Date,format:"",file:null},languageOptions:[{label:"简体中文",value:"CN"},{label:"繁体中文-香港",value:"HK"},{label:"繁体中文-台湾",value:"TW"},{label:"英语",value:"EN"},{label:"印尼语",value:"ID"},{label:"泰语",value:"TH"},{label:"马来语",value:"MY"},{label:"阿拉伯语",value:"UA"}],degreeOptions:[{label:"高",value:"2"},{label:"中",value:"1"},{label:"低",value:"0"}]}},methods:{submitTask:function(){var e="/v1/translations/"+this.task.task_id,a={};a.title=this.task.task_name,a.uid=2,a.t_language=this.task.translation_language,a.level=this.task.urgency_degree,a.remark=this.task.requirement,a.fix_uid=2356,a.status=0,a.content_url=this.task.content_url,a.is_del=0;var t=this;this.$axios.put(e,a).then(function(e){t.$message({type:"success",message:"更新成功!"}),t.$refs.upload.clearFiles(),t.task.task_name="",t.task.translation_language="",t.task.urgency_degree="",t.task.requirement="",t.task.content_url=""}).catch(function(e){t.$message({type:"error",message:"更新失败!"})})},beforeUpload:function(e){this.uploadParam.token=this.$md5("salt001"+this.uploadParam.time.toString());var a=e.name;this.uploadParam.format=a.slice(a.lastIndexOf(".")+1),this.uploadParam.file=e},uploadSuccess:function(e,a,t){0==e.status?this.task.content_url="http://img.orkotech.com/"+e.path:this.$message.error(e.msg)},uploadFail:function(e,a,t){this.$message.error(e)}},created:function(){var e=this.$route.query;e.task_name&&(this.task.task_id=e.task_id),this.task.task_name=e.task_name;var a="";this.languageOptions.forEach(function(t){t.label===e.translation_language&&(a=t.value)}),this.task.translation_language=a;var t="";this.degreeOptions.forEach(function(a){a.label===e.urgency_degree&&(t=a.value)}),this.task.urgency_degree=t,this.task.requirement="",this.task.content_url=""}},s={render:function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"container"},[t("el-row",[t("el-col",{attrs:{span:8}},[t("h1",{staticClass:"title"},[e._v("修改翻译需求")]),e._v(" "),t("label",{attrs:{for:""}},[e._v("任务名")]),t("el-input",{attrs:{placeholder:"请输入任务名"},model:{value:e.task.task_name,callback:function(a){e.$set(e.task,"task_name",a)},expression:"task.task_name"}}),e._v(" "),t("label",{attrs:{for:""}},[e._v("翻译语言")]),e._v(" "),t("el-select",{attrs:{placeholder:"请选择"},model:{value:e.task.translation_language,callback:function(a){e.$set(e.task,"translation_language",a)},expression:"task.translation_language"}},e._l(e.languageOptions,function(e){return t("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})})),e._v(" "),t("label",{attrs:{for:""}},[e._v("紧急程度")]),e._v(" "),t("el-select",{attrs:{placeholder:"请选择"},model:{value:e.task.urgency_degree,callback:function(a){e.$set(e.task,"urgency_degree",a)},expression:"task.urgency_degree"}},e._l(e.degreeOptions,function(e){return t("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})})),e._v(" "),t("label",{attrs:{for:""}},[e._v("要求")]),e._v(" "),t("el-input",{attrs:{type:"textarea",rows:2,placeholder:"请输入内容"},model:{value:e.task.requirement,callback:function(a){e.$set(e.task,"requirement",a)},expression:"task.requirement"}}),e._v(" "),t("div",{staticClass:"button"},[t("el-upload",{ref:"upload",staticClass:"upload-img",attrs:{action:"http://img.orkotech.com/fdfs_upload.php",data:e.uploadParam,"before-upload":e.beforeUpload,"on-success":e.uploadSuccess,"on-error":e.uploadFail,multiple:""}},[t("el-button",{attrs:{size:"small",type:"primary"}},[e._v("上传附件")])],1)],1)],1)],1),e._v(" "),t("div",{staticClass:"footerButton border-shadow"},[t("el-button",{staticStyle:{"vertical-align":"top"},attrs:{type:"primary"},on:{click:function(a){e.submitTask()}}},[e._v("提交")])],1)],1)},staticRenderFns:[]};var n=t("VU/8")(l,s,!1,function(e){t("qSx/")},"data-v-6611ab5f",null);a.default=n.exports},"qSx/":function(e,a){}});
//# sourceMappingURL=36.4adb0599d16e2f4213ed.js.map