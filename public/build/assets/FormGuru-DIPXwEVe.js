import"./el-popper-CSgOSG04.js";/* empty css                  *//* empty css                   */import{E as A,a as J}from"./el-form-item-CLi1Gt2n.js";import{E as O}from"./el-button-DBt5XuGS.js";import{E as M}from"./el-input-9yGDY5NS.js";/* empty css               */import{E as R,a as q}from"./el-select-Bdh-buAz.js";import"./el-scrollbar-DEwsvyBO.js";import{E as $,a as z}from"./el-col-Cd0wEX70.js";import{E as H}from"./el-divider-Bz5v_98_.js";import{E as Q}from"./el-avatar-CxTdv4pK.js";import{Q as W,h as X,r as f,z as Y,o as E,c as j,w as a,a as e,b as p,e as G,u as y,f as Z,F as ee,x as le,D as ae}from"./app-CXa1tqa2.js";import{a as te}from"./DashLayout-BmOUZM9s.js";import{E as B}from"./index-CQp_kVH7.js";import{E as oe}from"./index-fGPzLrYH.js";import"./index-D5RD23Xt.js";import"./castArray-Dh1HViVK.js";import"./use-form-item-THgkJ5sh.js";import"./isEqual-Dax5dt4i.js";import"./_initCloneObject-DuFWQ6ld.js";import"./typescript-Bp3YSIOJ.js";import"./event-HEVJa2N9.js";import"./index-DL5lWSoG.js";import"./index-BwSXwK79.js";import"./scroll-Ccz5mvb_.js";import"./debounce-BJCIde5Q.js";import"./index-DXeYCgt3.js";import"./el-popover-C-6kZ3_v.js";import"./iconify-DvK26OuJ.js";import"./use-dialog-Dy_TO4ms.js";const ue={class:"flex justify-center"},ne=["src"],re={class:"flex justify-center"},se=["src"],Ae={__name:"FormGuru",props:{open:Boolean,selectedGuru:Object},emits:["close"],setup(D,{emit:h}){const _=W(),v=D,P=h,U=X(()=>v.open),g=f(!1),T=f("/img/tutwuri.png"),b=f(null),V=f(null),k=f(null),t=f({nip:"1234556789",gelar_depan:"",nama:"Bejo",gelar_belakang:"S. Pd.",jk:"Laki-laki",alamat:"Malang",hp:"-",status:"PNS",email:"bejo@rmail.com",foto:"",agama:"Islam",pangkat:"III/B",jabatan:"guru_kelas"}),F=async()=>{g.value=!0;let n=new FormData;b.value!==null&&n.append("file",b.value),V.value!==null&&n.append("file_ttd",V.value),Object.keys(t.value).forEach(s=>{n.append(s,t.value[s])});let l=t.value.id?"dashboard.guru.update":"dashboard.guru.store";t.value.id&&n.append("_method","PUT"),ae.post(route(l),n,{onSuccess:s=>{B({title:"Info",message:s.props.flash.message,type:"success"}),g.value=!1,P("close")},onError:s=>{console.log(s),Object.keys(s).forEach(I=>{B({title:"Error",message:s[I],type:"error"})}),g.value=!1}})},N=n=>{const l=n.target.files[0];let s=URL.createObjectURL(l);b.value=l,T.value=s},K=n=>{const l=n.target.files[0];let s=URL.createObjectURL(l);V.value=l,k.value=s},C=()=>{g.value=!1,P("close")};return Y(()=>{v.selectedGuru!==null?(t.value=v.selectedGuru,t.value.sekolahs=v.selectedGuru.sekolahs.map(n=>n.id),k.value="/storage/images/ttd/"+v.selectedGuru.nip+".png"):_.props.auth.roles.includes("ops")&&(t.value.sekolahs=_.props.sekolahs.map(n=>n.id))}),(n,l)=>{const s=Q,I=H,r=$,m=M,d=A,c=z,u=R,i=q,S=O,w=J,L=oe;return E(),j(L,{modelValue:U.value,"onUpdate:modelValue":l[15]||(l[15]=o=>U.value=o),width:"800",title:"Formulir Data Guru",onClose:C,draggable:""},{default:a(()=>[e(c,{gutter:10},{default:a(()=>[e(r,{span:6,class:"border-r bg-slate-100 p-2"},{default:a(()=>[l[16]||(l[16]=p("h4",{class:"text-center mb-2"},[G("Foto Guru "),p("br"),p("small",null,"[Klik untuk mengganti]")],-1)),p("div",ue,[e(s,{src:t.value.foto,onError:n.onFotoError,onClick:l[0]||(l[0]=o=>n.$refs.fotoInput.click()),style:{margin:"0 auto",cursor:"pointer"},size:100},{default:a(()=>[p("img",{src:y(te)(t.value),class:"mx-auto"},null,8,ne)]),_:1},8,["src","onError"]),p("input",{type:"file",placeholder:"Pilih Foto Guru",ref:"fotoInput",onChange:N,class:"hidden",accept:".jpg,.JPG,.png,.PNG,.bmp,.BMP,.svg, .SVG,.jpeg, .JPEG, .webp"},null,544)]),e(I),l[17]||(l[17]=p("h4",{class:"text-center my-2"},[G("TTD Guru "),p("br"),p("small",null,"[Klik untuk mengganti]")],-1)),p("div",re,[p("img",{src:k.value,style:{cursor:"pointer"},onClick:l[1]||(l[1]=o=>n.$refs.inputTTD.click())},null,8,se),p("input",{type:"file",placeholder:"Pilih Foto Guru",ref:"inputTTD",onChange:K,class:"hidden",accept:".png,.PNG"},null,544)])]),_:1}),e(r,{span:18},{default:a(()=>[e(w,{"label-position":"top",size:"small"},{default:a(()=>[e(c,{gutter:6},{default:a(()=>[e(r,{span:6},{default:a(()=>[e(d,{label:"NIP"},{default:a(()=>[e(m,{modelValue:t.value.nip,"onUpdate:modelValue":l[2]||(l[2]=o=>t.value.nip=o),placeholder:"Masukkan NIP",required:""},null,8,["modelValue"])]),_:1})]),_:1}),e(r,{span:3},{default:a(()=>[e(d,{label:"Gelar Dpn"},{default:a(()=>[e(m,{modelValue:t.value.gelar_depan,"onUpdate:modelValue":l[3]||(l[3]=o=>t.value.gelar_depan=o),placeholder:"Gelar Dpn",required:""},null,8,["modelValue"])]),_:1})]),_:1}),e(r,{span:10},{default:a(()=>[e(d,{label:"Nama Guru"},{default:a(()=>[e(m,{modelValue:t.value.nama,"onUpdate:modelValue":l[4]||(l[4]=o=>t.value.nama=o),placeholder:"Nama Guru",required:""},null,8,["modelValue"])]),_:1})]),_:1}),e(r,{span:5},{default:a(()=>[e(d,{label:"Gelar Blk"},{default:a(()=>[e(m,{modelValue:t.value.gelar_belakang,"onUpdate:modelValue":l[5]||(l[5]=o=>t.value.gelar_belakang=o),placeholder:"Gelar Belakang",required:""},null,8,["modelValue"])]),_:1})]),_:1})]),_:1}),e(c,{gutter:6},{default:a(()=>[e(r,{span:6},{default:a(()=>[e(d,{label:"Jenis Kelamin"},{default:a(()=>[e(i,{modelValue:t.value.jk,"onUpdate:modelValue":l[6]||(l[6]=o=>t.value.jk=o),placeholder:"Jenis Kelamin"},{default:a(()=>[e(u,{value:"Laki-laki"}),e(u,{value:"Perempuan"})]),_:1},8,["modelValue"])]),_:1})]),_:1}),e(r,{span:6},{default:a(()=>[e(d,{label:"Agama"},{default:a(()=>[e(i,{modelValue:t.value.agama,"onUpdate:modelValue":l[7]||(l[7]=o=>t.value.agama=o),placeholder:"Agama"},{default:a(()=>[e(u,{value:"Islam"}),e(u,{value:"Kristen"}),e(u,{value:"Katolik"}),e(u,{value:"Hindu"}),e(u,{value:"Budha"}),e(u,{value:"Konghuchu"})]),_:1},8,["modelValue"])]),_:1})]),_:1}),e(r,{span:12},{default:a(()=>[e(d,{label:"Alamat"},{default:a(()=>[e(m,{type:"textarea",modelValue:t.value.alamat,"onUpdate:modelValue":l[8]||(l[8]=o=>t.value.alamat=o),placeholder:"Alamat"},null,8,["modelValue"])]),_:1})]),_:1})]),_:1}),e(c,{gutter:6},{default:a(()=>[e(r,{span:8},{default:a(()=>[e(d,{label:"HP"},{default:a(()=>[e(m,{modelValue:t.value.hp,"onUpdate:modelValue":l[9]||(l[9]=o=>t.value.hp=o),placeholder:"HP"},null,8,["modelValue"])]),_:1})]),_:1}),e(r,{span:4},{default:a(()=>[e(d,{label:"Status"},{default:a(()=>[e(i,{modelValue:t.value.status,"onUpdate:modelValue":l[10]||(l[10]=o=>t.value.status=o),placeholder:"Status"},{default:a(()=>[e(u,{value:"pns",label:"PNS"}),e(u,{value:"p3k",label:"P3K"}),e(u,{value:"gtt",label:"GTT"})]),_:1},8,["modelValue"])]),_:1})]),_:1}),e(r,{span:6},{default:a(()=>[e(d,{label:"Pangkat"},{default:a(()=>[e(i,{modelValue:t.value.pangkat,"onUpdate:modelValue":l[11]||(l[11]=o=>t.value.pangkat=o),placeholder:"Pangkat"},{default:a(()=>[e(u,{value:"-",label:"-"}),e(u,{value:"IIIa",label:"III/A"}),e(u,{value:"IIIb",label:"III/B"}),e(u,{value:"IIIc",label:"III/C"}),e(u,{value:"IIId",label:"III/D"}),e(u,{value:"IVa",label:"IV/A"}),e(u,{value:"IVb",label:"IV/B"}),e(u,{value:"IVc",label:"IV/C"}),e(u,{value:"IVd",label:"IV/D"})]),_:1},8,["modelValue"])]),_:1})]),_:1}),e(r,{span:6},{default:a(()=>[e(d,{label:"Jabatan"},{default:a(()=>[e(i,{modelValue:t.value.jabatan,"onUpdate:modelValue":l[12]||(l[12]=o=>t.value.jabatan=o),placeholder:"Jabatan"},{default:a(()=>[e(u,{value:"Kepala Sekolah"}),e(u,{value:"Guru Kelas"}),e(u,{value:"Guru Agama"}),e(u,{value:"Guru PJOK"}),e(u,{value:"Guru Inggris"})]),_:1},8,["modelValue"])]),_:1})]),_:1}),e(r,{span:14},{default:a(()=>[e(d,{label:"Email"},{default:a(()=>[e(m,{modelValue:t.value.email,"onUpdate:modelValue":l[13]||(l[13]=o=>t.value.email=o),placeholder:"Email"},null,8,["modelValue"])]),_:1})]),_:1}),e(r,{span:10},{default:a(()=>[e(d,{label:"Lembaga"},{default:a(()=>[e(i,{modelValue:t.value.sekolahs,"onUpdate:modelValue":l[14]||(l[14]=o=>t.value.sekolahs=o),placeholder:"Lembaga",multiple:"","collapse-tags":""},{default:a(()=>[(E(!0),Z(ee,null,le(y(_).props.sekolahs,(o,x)=>(E(),j(u,{key:x,value:o.id,label:o.nama},null,8,["value","label"]))),128))]),_:1},8,["modelValue"])]),_:1})]),_:1})]),_:1}),e(c,{justify:"center"},{default:a(()=>[e(S,{type:"primary",loading:g.value,onClick:F},{default:a(()=>l[18]||(l[18]=[G("Simpan")])),_:1},8,["loading"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])}}};export{Ae as default};