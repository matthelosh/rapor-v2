import{m as $e,u as ce}from"./el-popper-CSgOSG04.js";import{E as Ae}from"./el-drawer-Y3EJc7XO.js";/* empty css                   *//* empty css                  */import{E as Le}from"./el-checkbox-BMdlwYn5.js";import{E as Oe,a as _e}from"./el-col-Cd0wEX70.js";/* empty css                        *//* empty css                */import{E as we}from"./el-input-9yGDY5NS.js";import{E as Re}from"./el-divider-Bz5v_98_.js";import{E as ze}from"./el-affix-oq6hwXnZ.js";import{E as Se,a as Ue}from"./el-button-DBt5XuGS.js";import{au as W,l as De,h as A,r as w,J as Be,E as fe,B as Pe,K as He,aC as Ne,ah as D,o as c,c as S,w as r,i as X,a as d,b as u,n as y,q as me,d as se,f as E,s as ne,g as T,t as k,aL as te,k as je,e as $,ai as ue,ax as Me,av as Te,aT as Ie,aR as Ve,aQ as pe,ar as Fe,am as ve,Q as Ke,z as qe,u as P,F as H,x as G,a$ as ge,D as be}from"./app-CXa1tqa2.js";import{I as N}from"./iconify-DvK26OuJ.js";import{d as oe}from"./dayjs.min-BScCNWd_.js";import"./id-7wJc4GV2.js";import{_ as Je}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{E as Ge,f as We,e as Xe}from"./use-dialog-Dy_TO4ms.js";import{K as Qe,v as Ye,_ as Ze,E as xe,ay as es,D as ss,ag as ye,n as ns,Y as ts,z as he}from"./index-D5RD23Xt.js";import{u as os,E as as}from"./index-fGPzLrYH.js";import{E as ls}from"./index-CQp_kVH7.js";import{E as is}from"./index-C-oZYRc2.js";import"./event-HEVJa2N9.js";import"./use-form-item-THgkJ5sh.js";import"./isEqual-Dax5dt4i.js";import"./typescript-Bp3YSIOJ.js";import"./index-DL5lWSoG.js";import"./scroll-Ccz5mvb_.js";const rs='a[href],button:not([disabled]),button:not([hidden]),:not([tabindex="-1"]),input:not([disabled]),input:not([type="hidden"]),select:not([disabled]),textarea:not([disabled])',us=e=>getComputedStyle(e).position==="fixed"?!1:e.offsetParent!==null,Ce=e=>Array.from(e.querySelectorAll(rs)).filter(s=>ds(s)&&us(s)),ds=e=>{if(e.tabIndex>0||e.tabIndex===0&&e.getAttribute("tabIndex")!==null)return!0;if(e.disabled)return!1;switch(e.nodeName){case"A":return!!e.href&&e.rel!=="ignore";case"INPUT":return!(e.type==="hidden"||e.type==="file");case"BUTTON":case"SELECT":case"TEXTAREA":return!0;default:return!1}},cs=e=>["",...Qe].includes(e),de="_trap-focus-children",z=[],Ee=e=>{if(z.length===0)return;const s=z[z.length-1][de];if(s.length>0&&e.code===Ye.tab){if(s.length===1){e.preventDefault(),document.activeElement!==s[0]&&s[0].focus();return}const v=e.shiftKey,o=e.target===s[0],g=e.target===s[s.length-1];o&&v&&(e.preventDefault(),s[s.length-1].focus()),g&&!v&&(e.preventDefault(),s[0].focus())}},fs={beforeMount(e){e[de]=Ce(e),z.push(e),z.length<=1&&document.addEventListener("keydown",Ee)},updated(e){W(()=>{e[de]=Ce(e)})},unmounted(){z.shift(),z.length===0&&document.removeEventListener("keydown",Ee)}},ms=De({name:"ElMessageBox",directives:{TrapFocus:fs},components:{ElButton:Se,ElFocusTrap:$e,ElInput:we,ElOverlay:Ge,ElIcon:xe,...es},inheritAttrs:!1,props:{buttonSize:{type:String,validator:cs},modal:{type:Boolean,default:!0},lockScroll:{type:Boolean,default:!0},showClose:{type:Boolean,default:!0},closeOnClickModal:{type:Boolean,default:!0},closeOnPressEscape:{type:Boolean,default:!0},closeOnHashChange:{type:Boolean,default:!0},center:Boolean,draggable:Boolean,overflow:Boolean,roundButton:{default:!1,type:Boolean},container:{type:String,default:"body"},boxType:{type:String,default:""}},emits:["vanish","action"],setup(e,{emit:s}){const{locale:v,zIndex:o,ns:g,size:i}=ss("message-box",A(()=>e.buttonSize)),{t:C}=v,{nextZIndex:h}=o,M=w(!1),a=Be({autofocus:!0,beforeClose:null,callback:null,cancelButtonText:"",cancelButtonClass:"",confirmButtonText:"",confirmButtonClass:"",customClass:"",customStyle:{},dangerouslyUseHTMLString:!1,distinguishCancelAndClose:!1,icon:"",inputPattern:null,inputPlaceholder:"",inputType:"text",inputValue:null,inputValidator:null,inputErrorMessage:"",message:null,modalFade:!0,modalClass:"",showCancelButton:!1,showConfirmButton:!0,type:"",title:void 0,showInput:!1,action:"",confirmButtonLoading:!1,cancelButtonLoading:!1,confirmButtonDisabled:!1,editorErrorMessage:"",validateError:!1,zIndex:h()}),F=A(()=>{const t=a.type;return{[g.bm("icon",t)]:t&&ye[t]}}),U=ce(),l=ce(),ae=A(()=>a.icon||ye[a.type]||""),le=A(()=>!!a.message),p=w(),K=w(),O=w(),_=w(),m=w(),n=A(()=>a.confirmButtonClass);fe(()=>a.inputValue,async t=>{await W(),e.boxType==="prompt"&&t!==null&&x()},{immediate:!0}),fe(()=>M.value,t=>{var b,B;t&&(e.boxType!=="prompt"&&(a.autofocus?O.value=(B=(b=m.value)==null?void 0:b.$el)!=null?B:p.value:O.value=p.value),a.zIndex=h()),e.boxType==="prompt"&&(t?W().then(()=>{var J;_.value&&_.value.$el&&(a.autofocus?O.value=(J=ie())!=null?J:p.value:O.value=p.value)}):(a.editorErrorMessage="",a.validateError=!1))});const f=A(()=>e.draggable),Y=A(()=>e.overflow);os(p,K,f,Y),Pe(async()=>{await W(),e.closeOnHashChange&&window.addEventListener("hashchange",I)}),He(()=>{e.closeOnHashChange&&window.removeEventListener("hashchange",I)});function I(){M.value&&(M.value=!1,W(()=>{a.action&&s("action",a.action)}))}const V=()=>{e.closeOnClickModal&&R(a.distinguishCancelAndClose?"close":"cancel")},q=Xe(V),Z=t=>{if(a.inputType!=="textarea")return t.preventDefault(),R("confirm")},R=t=>{var b;e.boxType==="prompt"&&t==="confirm"&&!x()||(a.action=t,a.beforeClose?(b=a.beforeClose)==null||b.call(a,t,a,I):I())},x=()=>{if(e.boxType==="prompt"){const t=a.inputPattern;if(t&&!t.test(a.inputValue||""))return a.editorErrorMessage=a.inputErrorMessage||C("el.messagebox.error"),a.validateError=!0,!1;const b=a.inputValidator;if(typeof b=="function"){const B=b(a.inputValue);if(B===!1)return a.editorErrorMessage=a.inputErrorMessage||C("el.messagebox.error"),a.validateError=!0,!1;if(typeof B=="string")return a.editorErrorMessage=B,a.validateError=!0,!1}}return a.editorErrorMessage="",a.validateError=!1,!0},ie=()=>{const t=_.value.$refs;return t.input||t.textarea},ee=()=>{R("close")},re=()=>{e.closeOnPressEscape&&ee()};return e.lockScroll&&We(M),{...Ne(a),ns:g,overlayEvent:q,visible:M,hasMessage:le,typeClass:F,contentId:U,inputId:l,btnSize:i,iconComponent:ae,confirmButtonClasses:n,rootRef:p,focusStartRef:O,headerRef:K,inputRef:_,confirmRef:m,doClose:I,handleClose:ee,onCloseRequested:re,handleWrapperClick:V,handleInputEnter:Z,handleAction:R,t:C}}}),ps=["aria-label","aria-describedby"],vs=["aria-label"],gs=["id"];function bs(e,s,v,o,g,i){const C=D("el-icon"),h=D("close"),M=D("el-input"),a=D("el-button"),F=D("el-focus-trap"),U=D("el-overlay");return c(),S(Me,{name:"fade-in-linear",onAfterLeave:s[11]||(s[11]=l=>e.$emit("vanish")),persisted:""},{default:r(()=>[X(d(U,{"z-index":e.zIndex,"overlay-class":[e.ns.is("message-box"),e.modalClass],mask:e.modal},{default:r(()=>[u("div",{role:"dialog","aria-label":e.title,"aria-modal":"true","aria-describedby":e.showInput?void 0:e.contentId,class:y(`${e.ns.namespace.value}-overlay-message-box`),onClick:s[8]||(s[8]=(...l)=>e.overlayEvent.onClick&&e.overlayEvent.onClick(...l)),onMousedown:s[9]||(s[9]=(...l)=>e.overlayEvent.onMousedown&&e.overlayEvent.onMousedown(...l)),onMouseup:s[10]||(s[10]=(...l)=>e.overlayEvent.onMouseup&&e.overlayEvent.onMouseup(...l))},[d(F,{loop:"",trapped:e.visible,"focus-trap-el":e.rootRef,"focus-start-el":e.focusStartRef,onReleaseRequested:e.onCloseRequested},{default:r(()=>[u("div",{ref:"rootRef",class:y([e.ns.b(),e.customClass,e.ns.is("draggable",e.draggable),{[e.ns.m("center")]:e.center}]),style:me(e.customStyle),tabindex:"-1",onClick:s[7]||(s[7]=se(()=>{},["stop"]))},[e.title!==null&&e.title!==void 0?(c(),E("div",{key:0,ref:"headerRef",class:y([e.ns.e("header"),{"show-close":e.showClose}])},[u("div",{class:y(e.ns.e("title"))},[e.iconComponent&&e.center?(c(),S(C,{key:0,class:y([e.ns.e("status"),e.typeClass])},{default:r(()=>[(c(),S(ne(e.iconComponent)))]),_:1},8,["class"])):T("v-if",!0),u("span",null,k(e.title),1)],2),e.showClose?(c(),E("button",{key:0,type:"button",class:y(e.ns.e("headerbtn")),"aria-label":e.t("el.messagebox.close"),onClick:s[0]||(s[0]=l=>e.handleAction(e.distinguishCancelAndClose?"close":"cancel")),onKeydown:s[1]||(s[1]=te(se(l=>e.handleAction(e.distinguishCancelAndClose?"close":"cancel"),["prevent"]),["enter"]))},[d(C,{class:y(e.ns.e("close"))},{default:r(()=>[d(h)]),_:1},8,["class"])],42,vs)):T("v-if",!0)],2)):T("v-if",!0),u("div",{id:e.contentId,class:y(e.ns.e("content"))},[u("div",{class:y(e.ns.e("container"))},[e.iconComponent&&!e.center&&e.hasMessage?(c(),S(C,{key:0,class:y([e.ns.e("status"),e.typeClass])},{default:r(()=>[(c(),S(ne(e.iconComponent)))]),_:1},8,["class"])):T("v-if",!0),e.hasMessage?(c(),E("div",{key:1,class:y(e.ns.e("message"))},[je(e.$slots,"default",{},()=>[e.dangerouslyUseHTMLString?(c(),S(ne(e.showInput?"label":"p"),{key:1,for:e.showInput?e.inputId:void 0,innerHTML:e.message},null,8,["for","innerHTML"])):(c(),S(ne(e.showInput?"label":"p"),{key:0,for:e.showInput?e.inputId:void 0},{default:r(()=>[$(k(e.dangerouslyUseHTMLString?"":e.message),1)]),_:1},8,["for"]))])],2)):T("v-if",!0)],2),X(u("div",{class:y(e.ns.e("input"))},[d(M,{id:e.inputId,ref:"inputRef",modelValue:e.inputValue,"onUpdate:modelValue":s[2]||(s[2]=l=>e.inputValue=l),type:e.inputType,placeholder:e.inputPlaceholder,"aria-invalid":e.validateError,class:y({invalid:e.validateError}),onKeydown:te(e.handleInputEnter,["enter"])},null,8,["id","modelValue","type","placeholder","aria-invalid","class","onKeydown"]),u("div",{class:y(e.ns.e("errormsg")),style:me({visibility:e.editorErrorMessage?"visible":"hidden"})},k(e.editorErrorMessage),7)],2),[[ue,e.showInput]])],10,gs),u("div",{class:y(e.ns.e("btns"))},[e.showCancelButton?(c(),S(a,{key:0,loading:e.cancelButtonLoading,class:y([e.cancelButtonClass]),round:e.roundButton,size:e.btnSize,onClick:s[3]||(s[3]=l=>e.handleAction("cancel")),onKeydown:s[4]||(s[4]=te(se(l=>e.handleAction("cancel"),["prevent"]),["enter"]))},{default:r(()=>[$(k(e.cancelButtonText||e.t("el.messagebox.cancel")),1)]),_:1},8,["loading","class","round","size"])):T("v-if",!0),X(d(a,{ref:"confirmRef",type:"primary",loading:e.confirmButtonLoading,class:y([e.confirmButtonClasses]),round:e.roundButton,disabled:e.confirmButtonDisabled,size:e.btnSize,onClick:s[5]||(s[5]=l=>e.handleAction("confirm")),onKeydown:s[6]||(s[6]=te(se(l=>e.handleAction("confirm"),["prevent"]),["enter"]))},{default:r(()=>[$(k(e.confirmButtonText||e.t("el.messagebox.confirm")),1)]),_:1},8,["loading","class","round","disabled","size"]),[[ue,e.showConfirmButton]])],2)],6)]),_:3},8,["trapped","focus-trap-el","focus-start-el","onReleaseRequested"])],42,ps)]),_:3},8,["z-index","overlay-class","mask"]),[[ue,e.visible]])]),_:3})}var ys=Ze(ms,[["render",bs],["__file","index.vue"]]);const Q=new Map,hs=e=>{let s=document.body;return e.appendTo&&(Te(e.appendTo)&&(s=document.querySelector(e.appendTo)),he(e.appendTo)&&(s=e.appendTo),he(s)||(s=document.body)),s},Cs=(e,s,v=null)=>{const o=d(ys,e,ve(e.message)||Ie(e.message)?{default:ve(e.message)?e.message:()=>e.message}:null);return o.appContext=v,Ve(o,s),hs(e).appendChild(s.firstElementChild),o.component},Es=()=>document.createElement("div"),ks=(e,s)=>{const v=Es();e.onVanish=()=>{Ve(null,v),Q.delete(g)},e.onAction=i=>{const C=Q.get(g);let h;e.showInput?h={value:g.inputValue,action:i}:h=i,e.callback?e.callback(h,o.proxy):i==="cancel"||i==="close"?e.distinguishCancelAndClose&&i!=="cancel"?C.reject("close"):C.reject("cancel"):C.resolve(h)};const o=Cs(e,v,s),g=o.proxy;for(const i in e)pe(e,i)&&!pe(g.$props,i)&&(g[i]=e[i]);return g.visible=!0,g};function j(e,s=null){if(!ns)return Promise.reject();let v;return Te(e)||Ie(e)?e={message:e}:v=e.callback,new Promise((o,g)=>{const i=ks(e,s??j._context);Q.set(i,{options:e,callback:v,resolve:o,reject:g})})}const ws=["alert","confirm","prompt"],Ss={alert:{closeOnPressEscape:!1,closeOnClickModal:!1},confirm:{showCancelButton:!0},prompt:{showCancelButton:!0,showInput:!0}};ws.forEach(e=>{j[e]=Bs(e)});function Bs(e){return(s,v,o,g)=>{let i="";return Fe(v)?(o=v,i=""):ts(v)?i="":i=v,j(Object.assign({title:i,message:s,type:"",...Ss[e]},o,{boxType:e}),g)}}j.close=()=>{Q.forEach((e,s)=>{s.doClose()}),Q.clear()};j._context=null;const L=j;L.install=e=>{L._context=e._context,e.config.globalProperties.$msgbox=L,e.config.globalProperties.$messageBox=L,e.config.globalProperties.$alert=L.alert,e.config.globalProperties.$confirm=L.confirm,e.config.globalProperties.$prompt=L.prompt};const ke=L,Ms={class:"flex items-center justify-between"},Ts={class:"toolbar flex items-center justify-between py-4 bg-white"},Is={class:"flex items-center"},Vs={class:"font-bold"},$s={key:0},As={class:"list-[numeric] pl-6"},Ls={class:"mb-4"},Os=["innerHTML"],_s={key:0,class:"flex flex-wrap gap-2"},Rs={class:"flex items-center gap-2 justify-between m-2"},zs={class:"flex gap-1 items-center"},Us=["onUpdate:modelValue","value","onChange"],Ds=["innerHTML"],Ps={key:1},Hs={class:"font-bold text-sky-700 text-lg text-center border border-sky-700 py-1 px-4"},Ns=["innerHTML"],js={key:0,class:"mt-4"},Fs={class:"flex items-center gap-2 justify-between m-2"},Ks={class:"flex gap-1 items-center"},qs=["value"],Js=["innerHTML"],Gs={class:"flex justify-between"},Ws={class:"flex justify-between"},Xs={__name:"SoalAsesmen",props:{asesmen:Object,siswa:Object,show:Boolean,proses:Object},emits:["close"],setup(e,{emit:s}){oe.locale("id");const v=Ke(),o=e,g=s,i=w([]),C=w(!1),h=w("list"),M=w(0),a=w(!1),F=A(()=>{let m=0;return o.asesmen.soals.forEach(n=>{m+=n.level=="hot"?3:n.level=="mot"?2:1}),m});w(1),w(60);const U=()=>{setInterval(()=>{l.seconds<=0&&(l.minutes===0&&l.seconds===0&&(l.hours>0?(l.hours--,l.minutes=60):l.hours===0&&l.minutes===0&&l.seconds===0&&localStorage.getItem("durasi")&&le()),l.minutes>0&&(l.minutes--,l.seconds=60)),l.seconds>0&&(l.seconds--,localStorage.setItem("durasi",JSON.stringify(l)))},1e3)},l=Be({totalMinutes:0,hours:0,minutes:0,seconds:0}),ae=async()=>{var m,n;if(localStorage.getItem("durasi")){const f=JSON.parse(localStorage.getItem("durasi"));l.hours=f.hours,l.minutes=f.minutes,l.seconds=f.seconds}else{const f=((n=(m=o.asesmen.proses)==null?void 0:m.jawabans)==null?void 0:n.length)>0?oe(o.asesmen.proses.updated_at).locale("Asia/Jakarta"):oe(o.asesmen.mulai);let V=oe(o.asesmen.selesai).diff(f)/1e3/60;l.totalMinutes=V,l.hours=Math.floor(V/60),localStorage.setItem("hours",l.hours),l.minutes=Math.floor(V%60)}U()},le=()=>{clearInterval(U),localStorage.removeItem("durasi"),ke.alert("Maaf! Waktu habis. Jawaban kamu akan dikirim,","Info",{confirmButtonText:"Ya",callback:m=>{K()}})},p=w(0),K=async()=>{i.value.forEach((m,n)=>{let f=m.teks==o.asesmen.soals[n].kunci;m.is_benar=f,M.value+=f?o.asesmen.soals[n].level=="hot"?3:o.asesmen.soals[n].level=="mot"?2:1:0}),ke.alert("Selamat Skor Ananda "+M.value/F.value*100,"Skor")},O=m=>{const n=document.querySelector(".toolbar");m?n.classList.add("sticky"):n.classList.remove("sticky")},_=m=>{const n=o.asesmen.soals.find(f=>f.id===m.soal_id);m.is_benar=n.tipe=="pilihan"?m.teks==n.kunci:!1,be.post(route("asesmen.siswa.jawaban.savetemp"),m,{onSuccess:()=>{be.reload(),ls({title:"Info",message:v.props.flash.message,type:"success"})},onError:f=>console.log(f)})};return qe(()=>{o.asesmen.soals.forEach(m=>{var n;return i.value.push({asesmen_id:o.asesmen.kode,siswa_id:o.siswa.nisn,soal_id:m.id,teks:(n=o.asesmen.proses[0])!=null&&n.jawabans&&o.asesmen.proses[0].jawabans.find(f=>f.soal_id==m.id)?o.asesmen.proses[0].jawabans.find(f=>f.soal_id==m.id).teks:"",is_benar:!1,proses_id:o.proses.id})}),ae()}),(m,n)=>{const f=Se,Y=ze,I=Re,V=we,q=is,Z=Oe,R=Ue,x=_e,ie=Le,ee=as,re=Ae;return c(),E(H,null,[d(ee,{fullscreen:"","show-close":!1,modelValue:o.show,"onUpdate:modelValue":n[9]||(n[9]=t=>o.show=t)},{header:r(()=>[u("div",Ms,[u("h3",null,"Asesmen: "+k(o.asesmen.nama),1),d(f,{onClick:n[0]||(n[0]=t=>g("close")),type:"danger"},{default:r(()=>[d(P(N),{icon:"mdi:close"})]),_:1})]),d(Y,{offset:0,onChange:O},{default:r(()=>[u("div",Ts,[h.value=="card"?(c(),S(P(N),{key:0,icon:"mdi:list-box-outline",class:"text-xl text-black",onClick:n[1]||(n[1]=t=>h.value="list")})):T("",!0),h.value=="list"?(c(),S(P(N),{key:1,icon:"mdi:card-bulleted-outline",class:"text-xl text-black",onClick:n[2]||(n[2]=t=>h.value="card")})):T("",!0),u("div",Is,[u("h3",null,[$(" Proses : "+k(o.proses.id)+" | Jumlah Soal: "+k(o.asesmen.soals.length)+" | Waktu: ",1),u("span",Vs,k(l.hours)+":"+k(l.minutes)+":"+k(l.seconds),1)])]),d(P(N),{icon:"hugeicons:menu-11",class:"text-xl text-black hidden-md-and-up",onClick:n[3]||(n[3]=t=>a.value=!0)})])]),_:1})]),default:r(()=>[d(q,{class:"border rounded",shadow:"never","body-style":{minHeight:"70vh"}},{footer:r(()=>[u("div",Ws,[d(ie,{modelValue:C.value,"onUpdate:modelValue":n[8]||(n[8]=t=>C.value=t)},{default:r(()=>n[14]||(n[14]=[$("Sudah saya teliti")])),_:1},8,["modelValue"]),d(f,{type:"primary",disabled:!C.value,onClick:K},{default:r(()=>n[15]||(n[15]=[$("Kirim")])),_:1},8,["disabled"])])]),default:r(()=>[d(x,{gutter:20},{default:r(()=>[d(Z,{span:18,xs:24},{default:r(()=>[h.value=="list"?(c(),E("div",$s,[d(I,null,{default:r(()=>n[11]||(n[11]=[$("Pilihan Ganda")])),_:1}),n[12]||(n[12]=u("h3",{class:"font-bold text-sky-700 mb-4"},"Pilih jawaban yang tepat!",-1)),u("ul",As,[(c(!0),E(H,null,G(o.asesmen.soals,(t,b)=>(c(),E("li",Ls,[u("div",{innerHTML:t.pertanyaan},null,8,Os),t.tipe=="pilihan"?(c(),E("ul",_s,[(c(),E(H,null,G(["a","b","c","d"],B=>u("li",Rs,[u("label",zs,[X(u("input",{type:"radio","onUpdate:modelValue":J=>i.value[b].teks=J,value:B,onChange:J=>_(i.value[b])},null,40,Us),[[ge,i.value[b].teks]]),u("span",{innerHTML:t[B]},null,8,Ds)])])),64))])):(c(),S(V,{key:1,autosize:"",type:"textarea",placeholder:"Isikan jawabanmu",modelValue:i.value[b].teks,"onUpdate:modelValue":B=>i.value[b].teks=B,onChange:B=>_(i.value[b])},null,8,["modelValue","onUpdate:modelValue","onChange"]))]))),256))])])):T("",!0),h.value=="card"?(c(),E("div",Ps,[d(Me,{name:"soal","ease-in-out":""},{default:r(()=>[d(q,null,{footer:r(()=>[u("div",Gs,[d(f,{onClick:n[6]||(n[6]=t=>p.value--),disabled:p.value===0},{default:r(()=>[d(P(N),{icon:"mdi:chevron-left",class:"text-xl"})]),_:1},8,["disabled"]),d(f,{onClick:n[7]||(n[7]=t=>p.value++),disabled:p.value===o.asesmen.soals.length-1},{default:r(()=>[d(P(N),{icon:"mdi:chevron-right",class:"text-xl"})]),_:1},8,["disabled"])])]),default:r(()=>[d(I,null,{default:r(()=>[u("span",Hs," No. "+k(p.value+1),1)]),_:1}),u("p",{class:"my-4 pt-4",innerHTML:o.asesmen.soals[p.value].pertanyaan},null,8,Ns),u("span",null,[o.asesmen.soals[p.value].tipe=="pilihan"?(c(),E("ul",js,[(c(),E(H,null,G(["a","b","c","d"],t=>u("li",Fs,[u("label",Ks,[X(u("input",{type:"radio","onUpdate:modelValue":n[4]||(n[4]=b=>i.value[p.value].teks=b),value:t},null,8,qs),[[ge,i.value[p.value].teks]]),u("span",{innerHTML:o.asesmen.soals[p.value][t]},null,8,Js)])])),64))])):(c(),S(V,{key:1,autosize:"",type:"textarea",placeholder:"Isikan jawabanmu",modelValue:i.value[p.value].teks,"onUpdate:modelValue":n[5]||(n[5]=t=>i.value[p.value].teks=t)},null,8,["modelValue"]))])]),_:1})]),_:1})])):T("",!0)]),_:1}),d(Z,{span:6,class:"hidden-sm-and-down"},{default:r(()=>[d(q,null,{default:r(()=>[n[13]||(n[13]=u("h3",{class:"font-bold text-sky-700 mb-1"},"Nomor Soal:",-1)),(c(!0),E(H,null,G(o.asesmen.soals.length,t=>(c(),S(R,null,{default:r(()=>[d(f,{type:i.value[t-1].teks!==""?"primary":"",onClick:b=>p.value=t-1},{default:r(()=>[$(k(t),1)]),_:2},1032,["type","onClick"])]),_:2},1024))),256))]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"]),d(re,{modelValue:a.value,"onUpdate:modelValue":n[10]||(n[10]=t=>a.value=t),size:"60%",class:"hidden-md-and-up"},{default:r(()=>[n[16]||(n[16]=u("h3",{class:"font-bold text-sky-700 mb-1"},"Nomor Soal:",-1)),(c(!0),E(H,null,G(o.asesmen.soals.length,t=>(c(),S(R,null,{default:r(()=>[d(f,{type:i.value[t-1].teks!==""?"primary":"",onClick:b=>p.value=t-1},{default:r(()=>[$(k(t),1)]),_:2},1032,["type","onClick"])]),_:2},1024))),256))]),_:1},8,["modelValue"])],64)}}},Bn=Je(Xs,[["__scopeId","data-v-7ed9c6fd"]]);export{Bn as default};