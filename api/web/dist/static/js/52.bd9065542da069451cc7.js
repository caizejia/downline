webpackJsonp([52],{oaFy:function(t,e){},vaHM:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"container"},[s("el-row",[s("el-col",{attrs:{span:8}},[s("h1",{staticClass:"title"},[t._v("修改退货单")]),t._v(" "),s("label",{attrs:{for:""}},[t._v("退货数量")]),s("el-input",{attrs:{placeholder:"退货数量"},model:{value:t.task.rejection_goods_count,callback:function(e){t.$set(t.task,"rejection_goods_count",e)},expression:"task.rejection_goods_count"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("对应采购单")]),s("el-input",{attrs:{placeholder:"对应采购单"},model:{value:t.task.purchases_detail_id,callback:function(e){t.$set(t.task,"purchases_detail_id",e)},expression:"task.purchases_detail_id"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("仓库")]),s("el-input",{attrs:{placeholder:"仓库"},model:{value:t.task.warehouse_id,callback:function(e){t.$set(t.task,"warehouse_id",e)},expression:"task.warehouse_id"}})],1)],1),t._v(" "),s("div",{staticClass:"footerButton border-shadow"},[s("el-button",{staticStyle:{"vertical-align":"top"},attrs:{type:"primary"},on:{click:function(e){t.submitTask()}}},[t._v("提交")])],1)],1)},staticRenderFns:[]};var o=s("VU/8")({name:"addTask",data:function(){return{pageName:"添加",task:{rejection_goods_count:"",purchases_detail_id:"",warehouse_id:""},warehouseOptions:[{label:"国内仓",value:"1"},{label:"泰国仓",value:"2"}]}},methods:{submitTask:function(){var t="http://api.orkowms.me/pr-bill",e={};e.rejection_goods_count=this.task.rejection_goods_count,e.purchases_detail_id=this.task.purchases_detail_id,e.warehouse_id=this.task.warehouse_id;var s=this;s.task.id&&(t="http://api.orkowms.me/pr-bill/"+this.task.id),this.$axios.put(t,e).then(function(t){s.$message({type:"success",message:"更新成功!"})}).catch(function(t){s.$message({type:"error",message:"更新失败!"})})}},created:function(){var t=this.$route.query;this.task.id=t.id,this.task.rejection_goods_count=t.rejection_goods_count,this.task.purchases_detail_id=t.purchases_detail_id,this.task.warehouse_id=t.warehouse_id}},a,!1,function(t){s("oaFy")},"data-v-3e0c0507",null);e.default=o.exports}});
//# sourceMappingURL=52.bd9065542da069451cc7.js.map