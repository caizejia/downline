webpackJsonp([54],{"7OoB":function(e,t){},RNie:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var l={name:"submitDemand",data:function(){return{tableData:[],totalSize:0,currentPage:1,pageSize:100,statusValue:"-1",degreeValue:"-1",statusOptions:[{value:"-1",label:"所有状态"},{value:"0",label:"新增"},{value:"1",label:"翻译通过"},{value:"2",label:"翻译待审核"},{value:"3",label:"翻译不通过"},{value:"4",label:"翻译进行中"}],degreeOptions:[{value:"-1",label:"所有等级"},{value:"0",label:"低"},{value:"1",label:"中"},{value:"2",label:"高"}]}},methods:{degreeChange:function(e){e>=0?this.statusValue>=0?this.initTable(e,this.statusValue):this.initTable(e,null):this.statusValue>=0?this.initTable(null,this.statusValue):this.initTable()},statusChange:function(e){e>=0?this.degreeValue>=0?this.initTable(this.degreeValue,e):this.initTable(null,e):this.degreeValue>=0?this.initTable(this.degreeValue,null):this.initTable()},filterDegree:function(e,t,a){return t[a.property]===this.degreeOptions[parseInt(e)+1].label},filterStatus:function(e,t,a){return t[a.property]===this.statusOptions[parseInt(e)+1].label},handleSizeChange:function(e){this.pageSize=e,this.initTable()},handleCurrentChange:function(e){this.currentPage=e,this.initTable()},addCategory:function(){this.$router.push("/product/category/add")},handleEdit:function(e){this.$router.push({path:"/categoryManage/updatecategory",query:e})},handleDelete:function(e,t){var a=this,l="/v1/categories/"+e.id,n=this;this.$confirm("是否确认删除该条记录?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){n.$axios.delete(l).then(function(e){200==e.data.code?(n.tableData.splice(t,1),n.$message({type:"success",message:"删除成功!"})):a.$message({type:"error",message:"删除失败!"})}).catch(function(){a.$message({type:"error",message:"删除失败!"})})}).catch(function(){})},initTable:function(e,t){var a=[],l="/v1/categories?page="+this.currentPage+"&pagesize="+this.pageSize;e&&(l+="&level="+e),t&&(l+="&status="+t);var n=this;this.$axios.get(l).then(function(e){if(200==e.data.code){var t=e.data.data.list;n.totalSize=parseInt(e.data.data.page_total),console.log(t),t.forEach(function(e){var t={};t.id=e.id,t.name=e.name,t.parent_name=e.parent_name,t.create_time=e.create_time,a.push(t)})}n.tableData=a}).catch(function(e){})}},created:function(){this.initTable()}},n={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"container"},[a("el-breadcrumb",{attrs:{"separator-class":"el-icon-arrow-right"}},[a("el-breadcrumb-item",[e._v("首页")]),e._v(" "),a("el-breadcrumb-item",[e._v("分类列表")])],1),e._v(" "),a("div",{staticClass:"tools"},[a("el-button",{staticClass:"addItem",attrs:{type:"primary"},on:{click:function(t){e.addCategory()}}},[e._v("新增分类")])],1),e._v(" "),a("div",{staticClass:"tableContainer"},[a("el-table",{staticClass:"table",staticStyle:{width:"100%"},attrs:{data:e.tableData,height:"100%",border:""}},[a("el-table-column",{attrs:{type:"expand"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-form",{staticClass:"demo-table-expand",attrs:{"label-position":"left",inline:""}},[a("el-form-item",{attrs:{label:"分类id"}},[a("span",[e._v(e._s(t.row.id))])]),e._v(" "),a("el-form-item",{attrs:{label:"父分类"}},[a("span",[e._v(e._s(t.row.parent_name))])]),e._v(" "),a("el-form-item",{attrs:{label:"分类名"}},[a("span",[e._v(e._s(t.row.name))])]),e._v(" "),a("el-form-item",{attrs:{label:"创建时间"}},[a("span",{staticStyle:{color:"#00a0e9"}},[e._v(e._s(t.row.create_time))])])],1)]}}])}),e._v(" "),a("el-table-column",{attrs:{prop:"id",label:"分类id"}}),e._v(" "),a("el-table-column",{attrs:{prop:"parent_name",label:"父分类"}}),e._v(" "),a("el-table-column",{attrs:{prop:"name",label:"分类名"}}),e._v(" "),a("el-table-column",{attrs:{prop:"create_time",label:"创建时间"}}),e._v(" "),a("el-table-column",{attrs:{label:"操作"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){e.handleDelete(t.row,t.$index)}}},[e._v("删除")])]}}])})],1),e._v(" "),a("el-pagination",{attrs:{"current-page":e.currentPage,"page-sizes":[100,200,300,400],"page-size":e.pageSize,layout:"total, sizes, prev, pager, next, jumper",total:e.totalSize},on:{"size-change":e.handleSizeChange,"current-change":e.handleCurrentChange}})],1)],1)},staticRenderFns:[]};var i=a("VU/8")(l,n,!1,function(e){a("7OoB")},"data-v-3db1b014",null);t.default=i.exports}});
//# sourceMappingURL=54.99f749d1c7b60d81eac6.js.map