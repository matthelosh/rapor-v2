import{t as y}from"./el-popper-CSgOSG04.js";import{I as w}from"./iconify-DvK26OuJ.js";import{an as h,r as E,B as N,l as b,h as k,o as p,c as T,w as _,u as e,f as g,q as B,n as v,d as C,k as S,a as m,g as I,ax as M,b as u,e as $,t as V}from"./app-CXa1tqa2.js";import{aH as F,a3 as H,u as P,E as q,aN as z,_ as D,w as K}from"./index-D5RD23Xt.js";const O={visibilityHeight:{type:Number,default:200},target:{type:String,default:""},right:{type:Number,default:40},bottom:{type:Number,default:40}},j={click:a=>a instanceof MouseEvent},A=(a,n,s)=>{const t=h(),o=h(),r=E(!1),c=()=>{t.value&&(r.value=t.value.scrollTop>=a.visibilityHeight)},d=l=>{var f;(f=t.value)==null||f.scrollTo({top:0,behavior:"smooth"}),n("click",l)},i=F(c,300,!0);return H(o,"scroll",i),N(()=>{var l;o.value=document,t.value=document.documentElement,a.target&&(t.value=(l=document.querySelector(a.target))!=null?l:void 0,t.value||y(s,`target does not exist: ${a.target}`),o.value=t.value),c()}),{visible:r,handleClick:d}},x="ElBacktop",G=b({name:x}),L=b({...G,props:O,emits:j,setup(a,{emit:n}){const s=a,t=P("backtop"),{handleClick:o,visible:r}=A(s,n,x),c=k(()=>({right:`${s.right}px`,bottom:`${s.bottom}px`}));return(d,i)=>(p(),T(M,{name:`${e(t).namespace.value}-fade-in`},{default:_(()=>[e(r)?(p(),g("div",{key:0,style:B(e(c)),class:v(e(t).b()),onClick:i[0]||(i[0]=C((...l)=>e(o)&&e(o)(...l),["stop"]))},[S(d.$slots,"default",{},()=>[m(e(q),{class:v(e(t).e("icon"))},{default:_(()=>[m(e(z))]),_:1},8,["class"])])],6)):I("v-if",!0)]),_:3},8,["name"]))}});var R=D(L,[["__file","backtop.vue"]]);const W=K(R),Y={class:"bg-sky-500 max-w-screen"},J={class:"footer p-4 flex justify-between items-center"},Q={class:"text-white"},U={href:"https://github.com/matthelosh/rapor-v2",target:"_blank"},at={__name:"Footer",setup(a){const n=k(()=>new Date().getFullYear());return(s,t)=>{const o=W;return p(),g("footer",Y,[m(o,{right:50,bottom:50}),u("div",J,[u("p",Q,[$(" © "+V(n.value)+" ",1),t[0]||(t[0]=u("a",{href:"mailto:matthelosh@gmail.com",target:"_blank",class:"text-sky-100 hover:underline hover:text-sky-50"},"TIM IT PKG Kec. Wagir",-1))]),u("a",U,[m(e(w),{icon:"mdi:github",class:"text-2xl text-white"})])])])}}};export{at as default};