import"./el-popper-CSgOSG04.js";/* empty css                */import{E as b,a as y}from"./el-col-Cd0wEX70.js";import{E,a as k}from"./el-table-column-D21P0-60.js";import"./el-checkbox-BMdlwYn5.js";import"./el-scrollbar-DEwsvyBO.js";/* empty css               */import{E as x}from"./el-button-DBt5XuGS.js";import{Q as w,h as I,o as T,f as B,a as e,u as p,Z as C,w as s,e as m,b as a,F as v,D as S}from"./app-CXa1tqa2.js";import{I as j}from"./iconify-DvK26OuJ.js";import{E as D,h as N}from"./html2canvas.esm-CyX_zrq-.js";import{_ as P}from"./DashLayout-BmOUZM9s.js";import{E as c}from"./index-CQp_kVH7.js";import{E as z}from"./index-C-oZYRc2.js";import"./index-D5RD23Xt.js";import"./typescript-Bp3YSIOJ.js";import"./index-DXeYCgt3.js";import"./isEqual-Dax5dt4i.js";import"./debounce-BJCIde5Q.js";import"./_initCloneObject-DuFWQ6ld.js";import"./use-form-item-THgkJ5sh.js";import"./event-HEVJa2N9.js";import"./el-popover-C-6kZ3_v.js";import"./el-divider-Bz5v_98_.js";const R={class:"toolbar flex items-center justify-between"},F={class:"flex items-center gap-1"},H={class:"toolbar-items flex justify-end items-ceter"},U={class:"card-body"},ct={__name:"Backup",setup(V){const r=w(),d=I(()=>r.props.auth.roles[0]),u=async()=>{const n=d=="ops"?r.props.sekolahs[0].npsn:"";S.post(route("dashboard.backup.store"),{sekolahId:n},{onSuccess:t=>{c({title:"Info",message:t.props.flash.message,type:"success"})},onError:t=>{Object.keys(t).forEach(o=>{setTimeout(()=>{c({title:"Error",message:t[o],type:"error"})},500)})}})},f=async()=>{const n=document.querySelector(".pdf");`${r.props.app_env=="local"?"http://localhost:5173/resources/css/app.css":"/build/assets/app.css"}${n.outerHTML}`;const o=new D({orientation:"p",unit:"mm",format:[210,330],hotfixes:["px_scaling"]});N(n,{width:o.internal.pageSize.getWidth(),height:o.internal.pageSize.getHeight()}).then(l=>{const i=l.toDataURL("image/png");o.addImage(i,"PNG",140,10,o.internal.pageSize.getWidth(),o.internal.pageSize.getHeight()),o.save("Tetsing.pdf")})};return(n,t)=>{const o=x,l=E,i=k,_=b,g=y,h=z;return T(),B(v,null,[e(p(C),{title:"Pengaturan Bakcup dan Restore Data"}),e(P,null,{header:s(()=>t[0]||(t[0]=[m("Admin")])),default:s(()=>[e(h,{class:"pdf"},{header:s(()=>[a("div",R,[a("h3",F,[e(p(j),{icon:"mdi:hdd"}),t[1]||(t[1]=a("span",{class:"font-bold text-slate-600 text-lg"},"Backup & Restore",-1))]),a("div",H,[e(o,{type:"warning",onClick:f},{default:s(()=>t[2]||(t[2]=[m("Tes PDF")])),_:1}),e(o,{type:"primary",onClick:u},{default:s(()=>t[3]||(t[3]=[m("Cadangkan")])),_:1})])])]),default:s(()=>[a("div",U,[e(g,null,{default:s(()=>[e(_,null,{default:s(()=>[t[5]||(t[5]=a("h3",{class:"text-lg font-bold text-sky-700"},"Peran dan Ijin",-1)),e(i,{data:p(r).props.roles},{default:s(()=>[e(l,{label:"#",type:"index"}),e(l,{label:"Peran",prop:"name"}),e(l,{label:"Ijin"},{default:s($=>t[4]||(t[4]=[])),_:1})]),_:1},8,["data"])]),_:1})]),_:1})])]),_:1})]),_:1})],64)}}};export{ct as default};