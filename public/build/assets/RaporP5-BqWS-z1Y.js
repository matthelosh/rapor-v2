import"./el-popper-CSgOSG04.js";/* empty css                  *//* empty css                   */import{E as K,a as H}from"./el-col-Cd0wEX70.js";import{E as L}from"./el-affix-oq6hwXnZ.js";import{E as F,a as U}from"./el-table-column-D21P0-60.js";import{E as $}from"./el-checkbox-BMdlwYn5.js";import"./el-scrollbar-DEwsvyBO.js";/* empty css               */import{E as q}from"./el-divider-Bz5v_98_.js";import{E as G}from"./el-image-viewer-SYZoVNZO.js";import{E as J}from"./el-button-DBt5XuGS.js";import{Q,r as f,z as W,o as r,c as Y,w as d,b as t,a as o,u as m,n as X,f as i,g as b,F as x,x as E,e as k,t as l}from"./app-CXa1tqa2.js";import{I as Z}from"./iconify-DvK26OuJ.js";import{E as tt}from"./index-fGPzLrYH.js";import"./index-D5RD23Xt.js";import"./typescript-Bp3YSIOJ.js";import"./event-HEVJa2N9.js";import"./scroll-Ccz5mvb_.js";import"./index-DXeYCgt3.js";import"./isEqual-Dax5dt4i.js";import"./debounce-BJCIde5Q.js";import"./_initCloneObject-DuFWQ6ld.js";import"./use-form-item-THgkJ5sh.js";import"./index-DL5lWSoG.js";import"./use-dialog-Dy_TO4ms.js";const et={class:"flex items-center justify-between z-40"},lt={key:0,class:"text-xl font-bold text-sky-800"},st={class:"page h-[330mm] print:h-[330mm] w-[216mm] bg-white mx-auto break-after-page shadow-md print:shadow-none my-6 cover p-6 relative overflow-hidden"},ot={class:"content h-[95%] border border-4 border-black border-dashed flex flex-col justify-center gap-8 m-8"},at={class:"uppercase font-black text-center text-black mb-20 text-xl"},rt={class:"py-20"},nt={class:"p-1 border border-black w-[50%] mx-auto text-center text-xl uppercase font-bold"},dt={class:"p-1 border border-black w-[50%] mx-auto text-center text-xl uppercase font-bold"},it={class:"text-center mt-24 text-xl font-black text-black font-[arial]"},pt={class:"page w-[216mm] bg-white mx-auto break-after-page shadow-md print:shadow-none my-6 proyek p-4 relative"},mt={class:"content h-full text-black"},ut={class:"w-full border-b border-double border-10 border-black"},bt={class:"judul-proyek mt-8"},kt={class:"border border-black p-2 text-justify"},ct={class:"page w-[216mm] bg-white mx-auto break-after-page shadow-md print:shadow-none my-6 nilai p-8 text-black"},gt={class:"w-full border border-black"},ft={class:"border border-black uppercase"},xt={colspan:"5",class:"border border-black bg-slate-100 p-2 font-black"},yt={class:"border border-black p-2"},wt={class:"list-disc pl-6"},ht={class:"pl-6"},vt={class:"border border-black px-2 w-14 text-center"},_t={key:0},Bt={class:"border border-black px-2 w-14 text-center"},St={key:0},Et={class:"border border-black px-2 w-14 text-center"},Nt={key:0},Pt={class:"border border-black px-2 w-14 text-center"},Tt={key:0},At={class:"border border-black p-2 text-justify"},Rt={class:"capitalize fotn-bold"},jt={class:"grid grid-cols-3 gap-8 mt-16"},Ct={class:"relative"},It={key:0,src:"/storage/images/ttd/196804222005011004.png",alt:"TTD Kepsek",class:"absolute w-[100px] translate-x-[15px]"},Vt={class:"mt-14 underline font-bold uppercase"},Mt={class:"relative"},Dt={class:"mt-14 underline font-bold uppercase"},Ot={class:"list"},zt={key:0,class:"toolbox flex items-center justify-between"},be={__name:"RaporP5",props:{proyek:Object},emits:["close"],setup(N,{emit:P}){const p=Q(),s=N,T=P,_=f(!1),y=f(!1),w=f(!1),B=f([]),A=async()=>{_.value=!0,axios.post(route("dashboard.p5.nilai.index",{_query:{rombel:s.proyek.rombel_id,proyek_id:s.proyek.id}})).then(u=>{B.value=u.data.nilais,_.value=!1}).catch(u=>console.log(u))},g=f([]),R=u=>{g.value=u},j=async()=>{const u=document.querySelector(".cetak"),e=p.props.app_env=="local"?"https://localhost:5173/resources/css/app.css":"/assets/css/app.css";let c=window.open("","_blank","width=800,height=1024"),h=`
        <!doctype html>
        <html>
            <head>
                <title>Rapor P5: ${s.proyek.nama}</title>
                <link rel="stylesheet" href="${e}" />
            </head>
            <body>
                ${u.outerHTML}
            </body>
        </html>
    `;c.document.write(h),setTimeout(()=>{c.print(),c.close()},1e3)};return W(()=>{A(),y.value=s.value!==null}),(u,e)=>{const c=J,h=G,C=q,S=K,I=$,v=F,V=U,M=L,D=H,O=tt;return r(),Y(O,{modelValue:y.value,"onUpdate:modelValue":e[2]||(e[2]=a=>y.value=a),fullscreen:"","show-close":!1},{header:d(()=>[t("div",et,[e[3]||(e[3]=t("h3",null,"Rapor P5",-1)),o(c,{circle:"",type:"danger",onClick:e[0]||(e[0]=a=>T("close"))},{default:d(()=>[o(m(Z),{icon:"mdi:close"})]),_:1})])]),default:d(()=>[o(D,{gutter:20},{default:d(()=>[o(S,{span:16},{default:d(()=>[t("div",{class:X(["cetak bg-slate-300 p-4 print:p-0 print:bg-white min-h-[80vh] z-30",g.value.length<1?"flex items-center justify-center":""])},[g.value.length<1?(r(),i("h3",lt," Pilih Siswa di samping ")):b("",!0),(r(!0),i(x,null,E(g.value,(a,z)=>(r(),i(x,{key:z},[t("div",st,[e[12]||(e[12]=t("img",{src:"/img/corner-tr-1.svg",alt:"Ornamen-TR",class:"absolute w-[50%] -right-28 -top-12"},null,-1)),e[13]||(e[13]=t("img",{src:"/img/corner-bl-1.svg",alt:"Ornamen-TR",class:"absolute w-[50%] -left-16 -bottom-10"},null,-1)),t("div",ot,[e[11]||(e[11]=t("img",{src:"/img/tutwuri.png",width:"100",class:"mx-auto"},null,-1)),t("h3",at,[e[4]||(e[4]=k("RAPOR ")),e[5]||(e[5]=t("br",null,null,-1)),e[6]||(e[6]=k(" PROYEK PENGUATAN PROFIL PELAJAR PANCASILA ")),e[7]||(e[7]=t("br",null,null,-1)),k(" "+l(s.proyek.rombel.sekolah.nama),1)]),o(h,{src:m(p).props.sekolahs[0].logo,style:{width:"200px",margin:"0 auto"}},{error:d(()=>e[8]||(e[8]=[t("img",{src:"https://pusatinformasi.kolaborasi.kemdikbud.go.id/hc/article_attachments/30787241285145",width:"200",class:"mx-auto"},null,-1)])),_:1},8,["src"]),t("div",rt,[e[9]||(e[9]=t("p",{class:"text-center text-lg"},"Nama Peserta Didik",-1)),t("h3",nt,l(a.nama),1),e[10]||(e[10]=t("p",{class:"text-center text-lg"},"NISN",-1)),t("h3",dt,l(a.siswa_id),1)]),t("h3",it,"TA. "+l(m(p).props.periode.tapel.label),1)])]),t("div",pt,[t("div",mt,[e[28]||(e[28]=t("h3",{class:"font-bold text-lg text-center mb-4 uppercase"},"Rapor Proyek Penguatan Profil Pelajar pancasila",-1)),t("table",ut,[t("tr",null,[e[14]||(e[14]=t("td",null,"Nama Sekolah",-1)),e[15]||(e[15]=t("td",null,":",-1)),t("td",null,l(s.proyek.rombel.sekolah.nama),1),e[16]||(e[16]=t("td",null,"Kelas",-1)),e[17]||(e[17]=t("td",null,":",-1)),t("td",null,l(s.proyek.rombel.label),1)]),t("tr",null,[e[18]||(e[18]=t("td",null,"Alamat Sekolah",-1)),e[19]||(e[19]=t("td",null,":",-1)),t("td",null,l(s.proyek.rombel.sekolah.alamat)+", "+l(s.proyek.rombel.sekolah.desa),1),e[20]||(e[20]=t("td",null,"Fase",-1)),e[21]||(e[21]=t("td",null,":",-1)),t("td",null,l(s.proyek.rombel.fase),1)]),t("tr",null,[e[22]||(e[22]=t("td",null,"Nama Siswa",-1)),e[23]||(e[23]=t("td",null,":",-1)),t("td",null,l(a.nama),1),e[24]||(e[24]=t("td",null,"Tahun Pelajaran",-1)),e[25]||(e[25]=t("td",null,":",-1)),t("td",null,l(s.proyek.tapel.label),1)]),t("tr",null,[e[26]||(e[26]=t("td",null,"NISN",-1)),e[27]||(e[27]=t("td",null,":",-1)),t("td",null,l(a.siswa_id),1)])]),t("h3",bt,"Proyek: "+l(s.proyek.id)+" | "+l(s.proyek.nama),1),t("article",kt,l(s.proyek.deskripsi),1),e[29]||(e[29]=t("h3",{class:"mt-8 text-lg mb-2"},"Keterangan:",-1)),e[30]||(e[30]=t("table",{class:"border"},[t("thead",null,[t("tr",null,[t("th",{class:"border border-black p-2"},"BB (Belum Berkembang)"),t("th",{class:"border border-black p-2"},"MB (Mulai Berkembang)"),t("th",{class:"border border-black p-2"},"BSH (Berkembang Sesuai Harapan)"),t("th",{class:"border border-black p-2"},"SB (Sangat Berkembang)")])]),t("tbody",null,[t("tr",null,[t("td",{class:"p-2 border border-black"},"Siswa masih membutuhkan bimbingan dalam mengembangkan kemampuan"),t("td",{class:"p-2 border border-black"},"Siswa mulai mengembangkan kemampuan namun masih belum ajeg"),t("td",{class:"p-2 border border-black"},"Siswa telah mengembangkan kemampuan hingga berada dalam tahap ajeg"),t("td",{class:"p-2 border border-black"},"Siswa mengembangkan kemampuannya melampaui harapan")])])],-1)),o(C)])]),t("div",ct,[t("table",gt,[t("thead",null,[t("tr",null,[t("th",ft,l(s.proyek.nama),1),e[31]||(e[31]=t("th",{class:"border border-black"},"BB",-1)),e[32]||(e[32]=t("th",{class:"border border-black"},"MB",-1)),e[33]||(e[33]=t("th",{class:"border border-black"},"BSH",-1)),e[34]||(e[34]=t("th",{class:"border border-black"},"SB",-1))])]),t("tbody",null,[(r(!0),i(x,null,E(a.nilais,(n,Kt)=>(r(),i(x,null,[t("tr",null,[t("td",xt,l(n.apd.elemen.dimensi.dimensi),1)]),t("tr",null,[t("td",yt,[t("ul",wt,[t("li",null,l(n.apd.elemen.teks),1)]),t("p",ht,l(n.apd.teks),1)]),t("td",vt,[n.skor=="BB"?(r(),i("div",_t,l(n.skor),1)):b("",!0)]),t("td",Bt,[n.skor=="MB"?(r(),i("div",St,l(n.skor),1)):b("",!0)]),t("td",Et,[n.skor=="BSH"?(r(),i("div",Nt,l(n.skor),1)):b("",!0)]),t("td",Pt,[n.skor=="SB"?(r(),i("div",Tt,l(n.skor),1)):b("",!0)])])],64))),256))])]),e[41]||(e[41]=t("h3",{class:"font-bold"},"Catatan proses:",-1)),t("p",At,[e[35]||(e[35]=k(" Ananda ")),t("span",Rt,l(a.nama),1),k(" "+l(a.proses),1)]),t("div",jt,[e[40]||(e[40]=t("div",{class:"relative"},[t("p",null,"Mengetahui:"),t("p",null,"Orang Tua / Wali,"),t("p",{class:"mt-14 underline font-bold uppercase border-b border-black border-dotted"}," ")],-1)),t("div",Ct,[e[36]||(e[36]=t("p",null," ",-1)),e[37]||(e[37]=t("p",null,"Kepala Sekolah,",-1)),w.value?(r(),i("img",It)):b("",!0),t("p",Vt,l(m(p).props.sekolahs[0].ks.nama)+", "+l(m(p).props.sekolahs[0].ks.gelar_belakang),1),t("p",null,"NIP. "+l(m(p).props.sekolahs[0].ks.nip),1)]),t("div",Mt,[e[38]||(e[38]=t("p",{contenteditable:"true",class:"bg-yellow-100 print:bg-white"},"Malang, 17 Agustus 2024",-1)),e[39]||(e[39]=t("p",null,"Guru Kelas,",-1)),t("p",Dt,l(m(p).props.auth.user.userable.nama)+", "+l(m(p).props.auth.user.userable.gelar_belakang),1),t("p",null,"NIP. "+l(m(p).props.auth.user.userable.nip),1)])])])],64))),128))],2)]),_:1}),o(S,{span:8},{default:d(()=>[t("div",Ot,[o(M,{offset:50},{default:d(()=>[g.value.length>0?(r(),i("div",zt,[o(I,{modelValue:w.value,"onUpdate:modelValue":e[1]||(e[1]=a=>w.value=a)},{default:d(()=>e[42]||(e[42]=[k("Tampilkan TTD")])),_:1},8,["modelValue"]),o(c,{size:"large",type:"primary",onClick:j},{default:d(()=>e[43]||(e[43]=[k("Cetak")])),_:1})])):b("",!0),e[44]||(e[44]=t("h4",{class:"text-lg text-sky-700 font-bold mt-8"},"Data Siswa",-1)),o(V,{data:B.value,onSelectionChange:R},{default:d(()=>[o(v,{label:"#",type:"selection"}),o(v,{label:"NISN",prop:"siswa_id"}),o(v,{label:"Nama",prop:"nama"})]),_:1},8,["data"])]),_:1})])]),_:1})]),_:1})]),_:1},8,["modelValue"])}}};export{be as default};