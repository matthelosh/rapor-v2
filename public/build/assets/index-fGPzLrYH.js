import{am as W,B as G,aN as J,K as _,l as I,ao as q,h as L,o as k,f as X,b as A,k as y,n as v,u as e,t as Q,a as F,w as b,c as K,s as Z,g as U,q as O,aG as x,r as z,p as ee,i as oe,H as se,aU as te,ai as ae,ax as le,ay as ne}from"./app-CXa1tqa2.js";import{d as re,a as ie,b as de,c as ce,u as ue,E as fe,e as me}from"./use-dialog-Dy_TO4ms.js";import{N as H,T as ve,E as pe,_ as j,ah as ge,u as he,w as be}from"./index-D5RD23Xt.js";import{F as ye,e as Ce,m as Ee}from"./el-popper-CSgOSG04.js";const we=(...l)=>t=>{l.forEach(n=>{W(n)?n(t):n.value=t})},ke=(l,t,n,C)=>{let d={offsetX:0,offsetY:0};const f=m=>{const E=m.clientX,w=m.clientY,{offsetX:p,offsetY:g}=d,c=l.value.getBoundingClientRect(),a=c.left,h=c.top,T=c.width,R=c.height,D=document.documentElement.clientWidth,S=document.documentElement.clientHeight,B=-a+p,N=-h+g,Y=D-a-T+p,P=S-h-R+g,u=o=>{let r=p+o.clientX-E,i=g+o.clientY-w;C!=null&&C.value||(r=Math.min(Math.max(r,B),Y),i=Math.min(Math.max(i,N),P)),d={offsetX:r,offsetY:i},l.value&&(l.value.style.transform=`translate(${H(r)}, ${H(i)})`)},M=()=>{document.removeEventListener("mousemove",u),document.removeEventListener("mouseup",M)};document.addEventListener("mousemove",u),document.addEventListener("mouseup",M)},$=()=>{t.value&&l.value&&t.value.addEventListener("mousedown",f)},s=()=>{t.value&&l.value&&t.value.removeEventListener("mousedown",f)};G(()=>{J(()=>{n.value?$():s()})}),_(()=>{s()})},V=Symbol("dialogInjectionKey"),$e=["aria-level"],Le=["aria-label"],Te=["id"],De=I({name:"ElDialogContent"}),Me=I({...De,props:re,emits:ie,setup(l){const t=l,{t:n}=ve(),{Close:C}=ge,{dialogRef:d,headerRef:f,bodyId:$,ns:s,style:m}=q(V),{focusTrapRef:E}=q(ye),w=L(()=>[s.b(),s.is("fullscreen",t.fullscreen),s.is("draggable",t.draggable),s.is("align-center",t.alignCenter),{[s.m("center")]:t.center}]),p=we(E,d),g=L(()=>t.draggable),c=L(()=>t.overflow);return ke(d,f,g,c),(a,h)=>(k(),X("div",{ref:e(p),class:v(e(w)),style:O(e(m)),tabindex:"-1"},[A("header",{ref_key:"headerRef",ref:f,class:v([e(s).e("header"),{"show-close":a.showClose}])},[y(a.$slots,"header",{},()=>[A("span",{role:"heading","aria-level":a.ariaLevel,class:v(e(s).e("title"))},Q(a.title),11,$e)]),a.showClose?(k(),X("button",{key:0,"aria-label":e(n)("el.dialog.close"),class:v(e(s).e("headerbtn")),type:"button",onClick:h[0]||(h[0]=T=>a.$emit("close"))},[F(e(pe),{class:v(e(s).e("close"))},{default:b(()=>[(k(),K(Z(a.closeIcon||e(C))))]),_:1},8,["class"])],10,Le)):U("v-if",!0)],2),A("div",{id:e($),class:v(e(s).e("body"))},[y(a.$slots,"default")],10,Te),a.$slots.footer?(k(),X("footer",{key:0,class:v(e(s).e("footer"))},[y(a.$slots,"footer")],2)):U("v-if",!0)],6))}});var Ae=j(Me,[["__file","dialog-content.vue"]]);const Fe=["aria-label","aria-labelledby","aria-describedby"],Ie=I({name:"ElDialog",inheritAttrs:!1}),Re=I({...Ie,props:de,emits:ce,setup(l,{expose:t}){const n=l,C=x();Ce({scope:"el-dialog",from:"the title slot",replacement:"the header slot",version:"3.0.0",ref:"https://element-plus.org/en-US/component/dialog.html#slots"},L(()=>!!C.title));const d=he("dialog"),f=z(),$=z(),s=z(),{visible:m,titleId:E,bodyId:w,style:p,overlayDialogStyle:g,rendered:c,zIndex:a,afterEnter:h,afterLeave:T,beforeLeave:R,handleClose:D,onModalClick:S,onOpenAutoFocus:B,onCloseAutoFocus:N,onCloseRequested:Y,onFocusoutPrevented:P}=ue(n,f);ee(V,{dialogRef:f,headerRef:$,bodyId:w,ns:d,rendered:c,style:p});const u=me(S),M=L(()=>n.draggable&&!n.fullscreen);return t({visible:m,dialogContentRef:s}),(o,r)=>(k(),K(ne,{to:o.appendTo,disabled:o.appendTo!=="body"?!1:!o.appendToBody},[F(le,{name:"dialog-fade",onAfterEnter:e(h),onAfterLeave:e(T),onBeforeLeave:e(R),persisted:""},{default:b(()=>[oe(F(e(fe),{"custom-mask-event":"",mask:o.modal,"overlay-class":o.modalClass,"z-index":e(a)},{default:b(()=>[A("div",{role:"dialog","aria-modal":"true","aria-label":o.title||void 0,"aria-labelledby":o.title?void 0:e(E),"aria-describedby":e(w),class:v(`${e(d).namespace.value}-overlay-dialog`),style:O(e(g)),onClick:r[0]||(r[0]=(...i)=>e(u).onClick&&e(u).onClick(...i)),onMousedown:r[1]||(r[1]=(...i)=>e(u).onMousedown&&e(u).onMousedown(...i)),onMouseup:r[2]||(r[2]=(...i)=>e(u).onMouseup&&e(u).onMouseup(...i))},[F(e(Ee),{loop:"",trapped:e(m),"focus-start-el":"container",onFocusAfterTrapped:e(B),onFocusAfterReleased:e(N),onFocusoutPrevented:e(P),onReleaseRequested:e(Y)},{default:b(()=>[e(c)?(k(),K(Ae,se({key:0,ref_key:"dialogContentRef",ref:s},o.$attrs,{center:o.center,"align-center":o.alignCenter,"close-icon":o.closeIcon,draggable:e(M),overflow:o.overflow,fullscreen:o.fullscreen,"show-close":o.showClose,title:o.title,"aria-level":o.headerAriaLevel,onClose:e(D)}),te({header:b(()=>[o.$slots.title?y(o.$slots,"title",{key:1}):y(o.$slots,"header",{key:0,close:e(D),titleId:e(E),titleClass:e(d).e("title")})]),default:b(()=>[y(o.$slots,"default")]),_:2},[o.$slots.footer?{name:"footer",fn:b(()=>[y(o.$slots,"footer")])}:void 0]),1040,["center","align-center","close-icon","draggable","overflow","fullscreen","show-close","title","aria-level","onClose"])):U("v-if",!0)]),_:3},8,["trapped","onFocusAfterTrapped","onFocusAfterReleased","onFocusoutPrevented","onReleaseRequested"])],46,Fe)]),_:3},8,["mask","overlay-class","z-index"]),[[ae,e(m)]])]),_:3},8,["onAfterEnter","onAfterLeave","onBeforeLeave"])],8,["to","disabled"]))}});var Se=j(Re,[["__file","dialog.vue"]]);const Xe=be(Se);export{Xe as E,ke as u};