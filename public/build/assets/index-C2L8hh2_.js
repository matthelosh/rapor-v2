import{l as b,aG as N,r as D,h as p,o as a,c as l,w as f,i as I,b as k,n as o,u as e,s as V,g as n,f as i,k as g,e as h,t as u,F as $,a as A,ai as F,ax as M}from"./app-CXa1tqa2.js";import{b as P,ax as z,ag as w,u as G,E as C,_ as O,ay as _,w as j}from"./index-D5RD23Xt.js";const q=["light","dark"],H=P({title:{type:String,default:""},description:{type:String,default:""},type:{type:String,values:z(w),default:"info"},closable:{type:Boolean,default:!0},closeText:{type:String,default:""},showIcon:Boolean,center:Boolean,effect:{type:String,values:q,default:"light"}}),J={close:r=>r instanceof MouseEvent},K=b({name:"ElAlert"}),L=b({...K,props:H,emits:J,setup(r,{emit:E}){const c=r,{Close:S}=_,d=N(),t=G("alert"),m=D(!0),y=p(()=>w[c.type]),B=p(()=>[t.e("icon"),{[t.is("big")]:!!c.description||!!d.default}]),T=p(()=>({"with-description":c.description||d.default})),v=s=>{m.value=!1,E("close",s)};return(s,R)=>(a(),l(M,{name:e(t).b("fade"),persisted:""},{default:f(()=>[I(k("div",{class:o([e(t).b(),e(t).m(s.type),e(t).is("center",s.center),e(t).is(s.effect)]),role:"alert"},[s.showIcon&&e(y)?(a(),l(e(C),{key:0,class:o(e(B))},{default:f(()=>[(a(),l(V(e(y))))]),_:1},8,["class"])):n("v-if",!0),k("div",{class:o(e(t).e("content"))},[s.title||s.$slots.title?(a(),i("span",{key:0,class:o([e(t).e("title"),e(T)])},[g(s.$slots,"title",{},()=>[h(u(s.title),1)])],2)):n("v-if",!0),s.$slots.default||s.description?(a(),i("p",{key:1,class:o(e(t).e("description"))},[g(s.$slots,"default",{},()=>[h(u(s.description),1)])],2)):n("v-if",!0),s.closable?(a(),i($,{key:2},[s.closeText?(a(),i("div",{key:0,class:o([e(t).e("close-btn"),e(t).is("customed")]),onClick:v},u(s.closeText),3)):(a(),l(e(C),{key:1,class:o(e(t).e("close-btn")),onClick:v},{default:f(()=>[A(e(S))]),_:1},8,["class"]))],64)):n("v-if",!0)],2)],2),[[F,m.value]])]),_:3},8,["name"]))}});var Q=O(L,[["__file","alert.vue"]]);const X=j(Q);export{X as E};