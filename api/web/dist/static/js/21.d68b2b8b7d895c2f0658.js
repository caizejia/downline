webpackJsonp([21],{NMW2:function(e,t){},nRW8:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a={render:function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"container"},[s("el-row",[s("el-col",{attrs:{span:8}},[s("h1",{staticClass:"title"},[e._v("新增退货单")]),e._v(" "),s("label",{attrs:{for:""}},[e._v("退货数量")]),s("el-input",{attrs:{placeholder:"退货数量"},model:{value:e.task.rejection_goods_count,callback:function(t){e.$set(e.task,"rejection_goods_count",t)},expression:"task.rejection_goods_count"}}),e._v(" "),s("label",{attrs:{for:""}},[e._v("对应采购单")]),s("el-input",{attrs:{placeholder:"对应采购单"},model:{value:e.task.purchases_detail_id,callback:function(t){e.$set(e.task,"purchases_detail_id",t)},expression:"task.purchases_detail_id"}}),e._v(" "),s("label",{attrs:{for:""}},[e._v("仓库")]),s("el-input",{attrs:{placeholder:"仓库"},model:{value:e.task.warehouse_id,callback:function(t){e.$set(e.task,"warehouse_id",t)},expression:"task.warehouse_id"}})],1)],1),e._v(" "),s("div",{staticClass:"footerButton border-shadow"},[s("el-button",{staticStyle:{"vertical-align":"top"},attrs:{type:"primary"},on:{click:function(t){e.submitTask()}}},[e._v("提交")])],1)],1)},staticRenderFns:[]};var o=s("VU/8")({name:"addTask",data:function(){return{pageName:"添加",task:{rejection_goods_count:"",purchases_detail_id:"",warehouse_id:""},warehouseOptions:[{label:"国内仓",value:"1"},{label:"泰国仓",value:"2"}]}},methods:{submitTask:function(){var e={};e.rejection_goods_count=this.task.rejection_goods_count,e.purchases_detail_id=this.task.purchases_detail_id,e.warehouse_id=this.task.warehouse_id;var t=this;this.$axios.post("http://api.orkowms.me/pr-bill",e).then(function(e){t.$message({type:"success",message:"新增成功!"})}).catch(function(e){t.$message({type:"error",message:"新增失败!"})})}},created:function(){}},a,!1,function(e){s("NMW2")},"data-v-7d09b253",null);t.default=o.exports}});
//# sourceMappingURL=21.d68b2b8b7d895c2f0658.js.map