webpackJsonp([46],{"fo+7":function(t,e){},qmU6:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"container"},[a("el-row",[a("el-col",{attrs:{span:8}},[a("h1",{staticClass:"title"},[t._v(t._s(t.pageName)+"仓库")]),t._v(" "),a("label",{attrs:{for:""}},[t._v("仓库代码")]),a("el-input",{attrs:{placeholder:"请输入仓库代码"},model:{value:t.task.code,callback:function(e){t.$set(t.task,"code",e)},expression:"task.code"}}),t._v(" "),a("label",{attrs:{for:""}},[t._v("类型")]),t._v(" "),a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.task.type,callback:function(e){t.$set(t.task,"type",e)},expression:"task.type"}},t._l(t.typeOptions,function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})})),t._v(" "),a("label",{attrs:{for:""}},[t._v("仓库名称")]),a("el-input",{attrs:{placeholder:"请输入仓库名称"},model:{value:t.task.name,callback:function(e){t.$set(t.task,"name",e)},expression:"task.name"}}),t._v(" "),a("label",{attrs:{for:""}},[t._v("所在国家")]),a("el-input",{attrs:{placeholder:"请输入所在国家"},model:{value:t.task.country,callback:function(e){t.$set(t.task,"country",e)},expression:"task.country"}}),t._v(" "),a("label",{attrs:{for:""}},[t._v("联系电话")]),a("el-input",{attrs:{placeholder:"请输入仓库联系电话"},model:{value:t.task.mobile,callback:function(e){t.$set(t.task,"mobile",e)},expression:"task.mobile"}}),t._v(" "),a("label",{attrs:{for:""}},[t._v("仓库地址")]),a("el-input",{attrs:{placeholder:"请输入仓库地址"},model:{value:t.task.address,callback:function(e){t.$set(t.task,"address",e)},expression:"task.address"}})],1)],1),t._v(" "),a("div",{staticClass:"footerButton border-shadow"},[a("el-button",{staticStyle:{"vertical-align":"top"},attrs:{type:"primary"},on:{click:function(e){t.submitTask()}}},[t._v("提交")])],1)],1)},staticRenderFns:[]};var l=a("VU/8")({name:"addTask",data:function(){return{pageName:"添加",task:{code:"",name:"",country:"",mobile:"",address:"",type:""},typeOptions:[{label:"自建仓",value:"自建仓"},{label:"合作仓",value:"合作仓"},{label:"海外仓",value:"海外仓"}]}},methods:{submitTask:function(){var t="http://api.orkowms.me/warehouse",e={},a={};e.code=this.task.code,e.name=this.task.name,e.country=this.task.country,e.mobile=this.task.mobile,e.address=this.task.address,e.type=this.task.type;var s=this;s.task.id&&(a={headers:{}},t="http://api.orkowms.me/warehouse/"+this.task.id),this.$axios.put(t,e,a).then(function(t){s.$message({type:"success",message:"更新成功!"})}).catch(function(t){s.$message({type:"error",message:"新增失败!"})})}},created:function(){var t=this.$route.query;this.task.id=t.id,this.task.code=t.code,this.task.name=t.name,this.task.country=t.country,this.task.mobile=t.mobile,this.task.address=t.address,this.task.type=t.type}},s,!1,function(t){a("fo+7")},"data-v-4d40f452",null);e.default=l.exports}});
//# sourceMappingURL=46.31fa13ec3a688ead83a2.js.map