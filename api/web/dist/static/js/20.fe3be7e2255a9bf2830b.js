webpackJsonp([20],{"2kPu":function(t,e){},TAXh:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a={name:"addTask",data:function(){return{task:{order_id:"",problem:"",status:"",description:"",track_number:"",new_price:""},warehouseOptions:[{label:"国内仓",value:"1"},{label:"泰国仓",value:"2"}]}},methods:{submitTask:function(){var t="http://api.orkowms.me/problems",e={},s={};e.order_id=this.task.order_id,e.problem=this.task.problem,e.status=this.task.status,e.description=this.task.description,e.track_number=this.task.track_number,e.new_price=this.task.new_price,console.log("id:"+this.task.id);var a=this;a.task.id&&(s={headers:{}},t="http://api.orkowms.me/problems/"+this.task.id),this.$axios.put(t,e,s).then(function(t){a.$message({type:"success",message:"更新成功!"})}).catch(function(t){a.$message({type:"error",message:"新增失败!"})})}},created:function(){var t=this.$route.query;this.task.id=t.id,this.task.order_id=t.order_id,this.task.problem=t.problem,this.task.status=t.status,this.task.description=t.description,this.task.track_number=t.track_number,this.task.new_price=t.new_price}},r={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"container"},[s("el-row",[s("el-col",{attrs:{span:8}},[s("h1",{staticClass:"title"},[t._v("修改问题单")]),t._v(" "),s("label",{attrs:{for:""}},[t._v("订单ID")]),s("el-input",{attrs:{placeholder:"订单ID"},model:{value:t.task.order_id,callback:function(e){t.$set(t.task,"order_id",e)},expression:"task.order_id"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("problem")]),s("el-input",{attrs:{placeholder:"problem"},model:{value:t.task.problem,callback:function(e){t.$set(t.task,"problem",e)},expression:"task.problem"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("状态")]),s("el-input",{attrs:{placeholder:"状态"},model:{value:t.task.status,callback:function(e){t.$set(t.task,"status",e)},expression:"task.status"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("问题描述")]),s("el-input",{attrs:{placeholder:"问题描述"},model:{value:t.task.description,callback:function(e){t.$set(t.task,"description",e)},expression:"task.description"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("跟踪号")]),s("el-input",{attrs:{placeholder:"跟踪号"},model:{value:t.task.track_number,callback:function(e){t.$set(t.task,"track_number",e)},expression:"task.track_number"}}),t._v(" "),s("label",{attrs:{for:""}},[t._v("新价格")]),s("el-input",{attrs:{placeholder:"新价格"},model:{value:t.task.new_price,callback:function(e){t.$set(t.task,"new_price",e)},expression:"task.new_price"}})],1)],1),t._v(" "),s("div",{staticClass:"footerButton border-shadow"},[s("el-button",{staticStyle:{"vertical-align":"top"},attrs:{type:"primary"},on:{click:function(e){t.submitTask()}}},[t._v("提交")])],1)],1)},staticRenderFns:[]};var i=s("VU/8")(a,r,!1,function(t){s("2kPu")},"data-v-7ece93a8",null);e.default=i.exports}});
//# sourceMappingURL=20.fe3be7e2255a9bf2830b.js.map