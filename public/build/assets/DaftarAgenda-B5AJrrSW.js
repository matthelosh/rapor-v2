const __vite__fileDeps=["assets/Footer-C4xbhwJF.js","assets/el-popper-CSgOSG04.js","assets/app-CXa1tqa2.js","assets/app.css","assets/index-D5RD23Xt.js","assets/el-popper.css","assets/iconify-DvK26OuJ.js","assets/Footer.css"],__vite__mapDeps=i=>i.map(i=>__vite__fileDeps[i]);
import{Q as i,r as s,h as l,B as _,o as d,f as u,a,u as n,Z as f,b as t,w as h,e as g,t as N,F as v,y as x,A as y,_ as w}from"./app-CXa1tqa2.js";import"./el-popper-CSgOSG04.js";import{_ as A}from"./Header-U3xbTFhP.js";import"./iconify-DvK26OuJ.js";import{c as B}from"./el-popover-C-6kZ3_v.js";import"./index-D5RD23Xt.js";import"./el-drawer-Y3EJc7XO.js";import"./use-dialog-Dy_TO4ms.js";import"./event-HEVJa2N9.js";import"./scroll-Ccz5mvb_.js";/* empty css                   */import"./el-affix-oq6hwXnZ.js";import"./el-col-Cd0wEX70.js";import"./typescript-Bp3YSIOJ.js";import"./index-5rUUBmBj.js";const C={class:"common-layout"},D={class:"wrapper"},E=["src"],$={__name:"DaftarAgenda",props:{agenda:Object,appName:String},setup(e){const c=x(()=>w(()=>import("./Footer-C4xbhwJF.js"),__vite__mapDeps([0,1,2,3,4,5,6,7])));i(),s(""),l(()=>route().params);const o=s(null),p=async()=>{y.get("/captcha/new").then(r=>{o.value=URL.createObjectURL(r.data)})};return _(()=>{p()}),(r,V)=>{const m=B;return d(),u(v,null,[a(n(f),{title:"Selamat Datang"}),t("div",C,[t("div",D,[a(A,{appName:e.appName},null,8,["appName"]),a(m,null,{default:h(()=>[g(N(e.agenda)+" ",1),t("img",{src:o.value,alt:"Captcha"},null,8,E)]),_:1}),a(n(c))])])],64)}}};export{$ as default};