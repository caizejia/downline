webpackJsonp([24],{L8u2:function(t,e){},i2bN:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={name:"addTask",data:function(){return{task:{supplier_ref:"",price:"",count:"",status:"",memo:""},statusOptions:[{label:"待采购",value:"0"},{label:"已采购",value:"1"},{label:"缺货",value:"2"},{label:"取消单",value:"3"}]}},methods:{submitTask:function(){var t="http://api.orkowms.me/purchases-detail",e={},s={};e.supplier_ref=this.task.supplier_ref,e.price=this.task.price,e.count=this.task.count,e.status=this.task.status,e.memo=this.task.memo,console.log("id:"+this.task.id);var a=this;a.task.id&&(s={headers:{}},t="http://api.orkowms.me/purchases-detail/"+this.task.id),this.$axios.put(t,e,s).then(function(t){a.$message({type:"success",message:"修改成功!"})}).catch(function(t){a.$message({type:"error",message:"修改失败!"})})}},created:function(){var t=this.$route.query;this.task.id=t.id,this.task.supplier_ref=t.supplier_ref,this.task.price=t.price,this.task.count=t.count,this.task.status=t.status,this.task.memo=t.memo}},l={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"container"},[s("el-row",[s("el-col",{attrs:{span:8}},[s("h1",{staticClass:"title"},[t._v("修改采购详情")]),t._v(" "),s("label",{attrs:{for:""}},[t._v("平台单号")]),s("el-input",{attrs:{placeholder:"请输入平台单号"},model:{value:t.task.supplier_ref,callback:function(e){t.$set(t.task,"supplier_ref",e)},expression:"task.supplier_ref"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("采购价格")]),s("el-input",{attrs:{placeholder:"请输入采购价格"},model:{value:t.task.price,callback:function(e){t.$set(t.task,"price",e)},expression:"task.price"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("采购数量")]),s("el-input",{attrs:{placeholder:"请输入采购数量"},model:{value:t.task.count,callback:function(e){t.$set(t.task,"count",e)},expression:"task.count"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("状态")]),t._v(" "),s("el-select",{attrs:{placeholder:"请选择"},model:{value:t.task.status,callback:function(e){t.$set(t.task,"status",e)},expression:"task.status"}},t._l(t.statusOptions,function(t){return s("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})})),t._v(" "),s("label",{attrs:{for:""}},[t._v("备注")]),s("el-input",{attrs:{placeholder:"备注"},model:{value:t.task.memo,callback:function(e){t.$set(t.task,"memo",e)},expression:"task.memo"}})],1)],1),t._v(" "),s("div",{staticClass:"footerButton border-shadow"},[s("el-button",{staticStyle:{"vertical-align":"top"},attrs:{type:"primary"},on:{click:function(e){t.submitTask()}}},[t._v("提交")])],1)],1)},staticRenderFns:[]};var r=s("VU/8")(a,l,!1,function(t){s("L8u2")},"data-v-7b863c26",null);e.default=r.exports}});
//# sourceMappingURL=24.1f8352c0828a63bae11d.js.map