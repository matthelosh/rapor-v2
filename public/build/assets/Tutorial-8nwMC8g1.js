import"./el-popper-CSgOSG04.js";/* empty css                  *//* empty css                   */import{E as d}from"./el-button-DBt5XuGS.js";import{I as c}from"./iconify-DvK26OuJ.js";import{E as u}from"./index-fGPzLrYH.js";import{o as _,c as f,w as s,b as t,a as r,u as w}from"./app-CXa1tqa2.js";import"./index-D5RD23Xt.js";import"./use-form-item-THgkJ5sh.js";import"./use-dialog-Dy_TO4ms.js";import"./event-HEVJa2N9.js";import"./scroll-Ccz5mvb_.js";const b={class:"toolbar flex items-start justify-between"},g={class:"dialog-bo"},x=["src"],S={__name:"Tutorial",props:{url:String,show:Boolean},emits:["close"],setup(i,{emit:a}){const e=i,n=a;return(V,o)=>{const m=d,p=u;return _(),f(p,{modelValue:e.show,"onUpdate:modelValue":o[1]||(o[1]=l=>e.show=l),"show-close":!1},{header:s(()=>[t("div",b,[o[2]||(o[2]=t("span",{class:"title text-lg font-bold text-slate-600"}," Video tutorial ",-1)),r(m,{type:"danger",onClick:o[0]||(o[0]=l=>n("close")),size:"small"},{default:s(()=>[r(w(c),{icon:"mdi:close"})]),_:1})])]),default:s(()=>[t("div",g,[t("video",{src:e.url,controls:""},null,8,x)])]),_:1},8,["modelValue"])}}};export{S as default};