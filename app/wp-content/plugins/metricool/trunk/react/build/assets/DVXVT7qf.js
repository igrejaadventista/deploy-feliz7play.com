import{ak as e,r as t,n as r,p as i,G as n,aa as o,U as a,J as s,g as l,q as d,i as c,m as u,Q as f,M as g,a6 as p,j as h,a7 as v,F as m,e as b,aj as $,ac as x,ag as y,K as w,c as C,X as k,ah as F,S as z,v as S}from"./J3XbFqv-.js";let I={data:""},D=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,B=/\/\*[^]*?\*\/|  +/g,E=/\n+/g,A=(e,t)=>{let r="",i="",n="";for(let o in e){let a=e[o];"@"==o[0]?"i"==o[1]?r=o+" "+a+";":i+="f"==o[1]?A(a,o):o+"{"+A(a,"k"==o[1]?"":t)+"}":"object"==typeof a?i+=A(a,t?t.replace(/([^,])+/g,e=>o.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,t=>/&/.test(t)?t.replace(/&/g,e):e?e+" "+t:t)):o):null!=a&&(o="-"==o[1]?o:o.replace(/[A-Z]/g,"-$&").toLowerCase(),n+=A.p?A.p(o,a):o+":"+a+";")}return r+(t&&n?t+"{"+n+"}":n)+i},R={},j=e=>{if("object"==typeof e){let t="";for(let r in e)t+=r+j(e[r]);return t}return e};function M(e){let t=this||{},r=e.call?e(t.p):e;return((e,t,r,i,n)=>{let o=j(e),a=R[o]||(R[o]=(e=>{let t=0,r=11;for(;t<e.length;)r=101*r+e.charCodeAt(t++)>>>0;return"go"+r})(o));if(!R[a]){let t=o!==e?e:(e=>{let t,r,i=[{}];for(;t=D.exec(e.replace(B,""));)t[4]?i.shift():t[3]?(r=t[3].replace(E," ").trim(),i.unshift(i[0][r]=i[0][r]||{})):i[0][t[1]]=t[2].replace(E," ").trim();return i[0]})(e);R[a]=A(n?{["@keyframes "+a]:t}:t,r?"":"."+a)}let s=r&&R.g;return r&&(R.g=R[a]),l=R[a],d=t,c=i,(u=s)?d.data=d.data.replace(u,l):-1===d.data.indexOf(l)&&(d.data=c?l+d.data:d.data+l),a;var l,d,c,u})(r.unshift?r.raw?((e,t,r)=>e.reduce((e,i,n)=>{let o=t[n];if(o&&o.call){let e=o(r),t=e&&e.props&&e.props.className||/^go/.test(e)&&e;o=t?"."+t:e&&"object"==typeof e?e.props?"":A(e,""):!1===e?"":e}return e+i+(null==o?"":o)},""))(r,[].slice.call(arguments,1),t.p):r.reduce((e,r)=>Object.assign(e,r&&r.call?r(t.p):r),{}):r,(e=>{if("object"==typeof window){let t=(e?e.querySelector("#_goober"):window._goober)||Object.assign(document.createElement("style"),{innerHTML:" ",id:"_goober"});return t.nonce=window.__nonce__,t.parentNode||(e||document.head).appendChild(t),t.firstChild}return e||I})(t.target),t.g,t.o,t.k)}M.bind({g:1}),M.bind({k:1});var T={colors:{inherit:"inherit",current:"currentColor",transparent:"transparent",black:"#000000",white:"#ffffff",neutral:{50:"#f9fafb",100:"#f2f4f7",200:"#eaecf0",300:"#d0d5dd",400:"#98a2b3",500:"#667085",600:"#475467",700:"#344054",800:"#1d2939",900:"#101828"},darkGray:{50:"#525c7a",100:"#49536e",200:"#414962",300:"#394056",400:"#313749",500:"#292e3d",600:"#212530",700:"#191c24",800:"#111318",900:"#0b0d10"},gray:{50:"#f9fafb",100:"#f2f4f7",200:"#eaecf0",300:"#d0d5dd",400:"#98a2b3",500:"#667085",600:"#475467",700:"#344054",800:"#1d2939",900:"#101828"},blue:{25:"#F5FAFF",50:"#EFF8FF",100:"#D1E9FF",200:"#B2DDFF",300:"#84CAFF",400:"#53B1FD",500:"#2E90FA",600:"#1570EF",700:"#175CD3",800:"#1849A9",900:"#194185"},green:{25:"#F6FEF9",50:"#ECFDF3",100:"#D1FADF",200:"#A6F4C5",300:"#6CE9A6",400:"#32D583",500:"#12B76A",600:"#039855",700:"#027A48",800:"#05603A",900:"#054F31"},red:{50:"#fef2f2",100:"#fee2e2",200:"#fecaca",300:"#fca5a5",400:"#f87171",500:"#ef4444",600:"#dc2626",700:"#b91c1c",800:"#991b1b",900:"#7f1d1d",950:"#450a0a"},yellow:{25:"#FFFCF5",50:"#FFFAEB",100:"#FEF0C7",200:"#FEDF89",300:"#FEC84B",400:"#FDB022",500:"#F79009",600:"#DC6803",700:"#B54708",800:"#93370D",900:"#7A2E0E"},purple:{25:"#FAFAFF",50:"#F4F3FF",100:"#EBE9FE",200:"#D9D6FE",300:"#BDB4FE",400:"#9B8AFB",500:"#7A5AF8",600:"#6938EF",700:"#5925DC",800:"#4A1FB8",900:"#3E1C96"},teal:{25:"#F6FEFC",50:"#F0FDF9",100:"#CCFBEF",200:"#99F6E0",300:"#5FE9D0",400:"#2ED3B7",500:"#15B79E",600:"#0E9384",700:"#107569",800:"#125D56",900:"#134E48"},pink:{25:"#fdf2f8",50:"#fce7f3",100:"#fbcfe8",200:"#f9a8d4",300:"#f472b6",400:"#ec4899",500:"#db2777",600:"#be185d",700:"#9d174d",800:"#831843",900:"#500724"},cyan:{25:"#ecfeff",50:"#cffafe",100:"#a5f3fc",200:"#67e8f9",300:"#22d3ee",400:"#06b6d4",500:"#0891b2",600:"#0e7490",700:"#155e75",800:"#164e63",900:"#083344"}},alpha:{90:"e5",70:"b3",20:"33"},font:{size:{"2xs":"calc(var(--tsrd-font-size) * 0.625)",xs:"calc(var(--tsrd-font-size) * 0.75)",sm:"calc(var(--tsrd-font-size) * 0.875)",md:"var(--tsrd-font-size)"},lineHeight:{xs:"calc(var(--tsrd-font-size) * 1)",sm:"calc(var(--tsrd-font-size) * 1.25)"},weight:{normal:"400",medium:"500",semibold:"600",bold:"700"},fontFamily:{sans:"ui-sans-serif, Inter, system-ui, sans-serif, sans-serif",mono:"ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace"}},border:{radius:{xs:"calc(var(--tsrd-font-size) * 0.125)",sm:"calc(var(--tsrd-font-size) * 0.25)",md:"calc(var(--tsrd-font-size) * 0.375)",full:"9999px"}},size:{0:"0px",.5:"calc(var(--tsrd-font-size) * 0.125)",1:"calc(var(--tsrd-font-size) * 0.25)",1.5:"calc(var(--tsrd-font-size) * 0.375)",2:"calc(var(--tsrd-font-size) * 0.5)",2.5:"calc(var(--tsrd-font-size) * 0.625)",3:"calc(var(--tsrd-font-size) * 0.75)",3.5:"calc(var(--tsrd-font-size) * 0.875)",4:"calc(var(--tsrd-font-size) * 1)",5:"calc(var(--tsrd-font-size) * 1.25)",8:"calc(var(--tsrd-font-size) * 2)"}};function O(){const[e]=t((e=>{const{colors:t,font:r,size:i,alpha:n,border:o}=T,{fontFamily:a,lineHeight:s,size:l}=r,d=e?M.bind({target:e}):M;return{devtoolsPanelContainer:d`
      direction: ltr;
      position: fixed;
      bottom: 0;
      right: 0;
      z-index: 99999;
      width: 100%;
      max-height: 90%;
      border-top: 1px solid ${t.gray[700]};
      transform-origin: top;
    `,devtoolsPanelContainerVisibility:e=>d`
        visibility: ${e?"visible":"hidden"};
      `,devtoolsPanelContainerResizing:e=>e()?d`
          transition: none;
        `:d`
        transition: all 0.4s ease;
      `,devtoolsPanelContainerAnimation:(e,t)=>e?d`
          pointer-events: auto;
          transform: translateY(0);
        `:d`
        pointer-events: none;
        transform: translateY(${t}px);
      `,logo:d`
      cursor: pointer;
      display: flex;
      flex-direction: column;
      background-color: transparent;
      border: none;
      font-family: ${a.sans};
      gap: ${T.size[.5]};
      padding: 0px;
      &:hover {
        opacity: 0.7;
      }
      &:focus-visible {
        outline-offset: 4px;
        border-radius: ${o.radius.xs};
        outline: 2px solid ${t.blue[800]};
      }
    `,tanstackLogo:d`
      font-size: ${r.size.md};
      font-weight: ${r.weight.bold};
      line-height: ${r.lineHeight.xs};
      white-space: nowrap;
      color: ${t.gray[300]};
    `,routerLogo:d`
      font-weight: ${r.weight.semibold};
      font-size: ${r.size.xs};
      background: linear-gradient(to right, #84cc16, #10b981);
      background-clip: text;
      -webkit-background-clip: text;
      line-height: 1;
      -webkit-text-fill-color: transparent;
      white-space: nowrap;
    `,devtoolsPanel:d`
      display: flex;
      font-size: ${l.sm};
      font-family: ${a.sans};
      background-color: ${t.darkGray[700]};
      color: ${t.gray[300]};

      @media (max-width: 700px) {
        flex-direction: column;
      }
      @media (max-width: 600px) {
        font-size: ${l.xs};
      }
    `,dragHandle:d`
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 4px;
      cursor: row-resize;
      z-index: 100000;
      &:hover {
        background-color: ${t.purple[400]}${n[90]};
      }
    `,firstContainer:d`
      flex: 1 1 500px;
      min-height: 40%;
      max-height: 100%;
      overflow: auto;
      border-right: 1px solid ${t.gray[700]};
      display: flex;
      flex-direction: column;
    `,routerExplorerContainer:d`
      overflow-y: auto;
      flex: 1;
    `,routerExplorer:d`
      padding: ${T.size[2]};
    `,row:d`
      display: flex;
      align-items: center;
      padding: ${T.size[2]} ${T.size[2.5]};
      gap: ${T.size[2.5]};
      border-bottom: ${t.darkGray[500]} 1px solid;
      align-items: center;
    `,detailsHeader:d`
      font-family: ui-sans-serif, Inter, system-ui, sans-serif, sans-serif;
      position: sticky;
      top: 0;
      z-index: 2;
      background-color: ${t.darkGray[600]};
      padding: 0px ${T.size[2]};
      font-weight: ${r.weight.medium};
      font-size: ${r.size.xs};
      min-height: ${T.size[8]};
      line-height: ${r.lineHeight.xs};
      text-align: left;
      display: flex;
      align-items: center;
    `,maskedBadge:d`
      background: ${t.yellow[900]}${n[70]};
      color: ${t.yellow[300]};
      display: inline-block;
      padding: ${T.size[0]} ${T.size[2.5]};
      border-radius: ${o.radius.full};
      font-size: ${r.size.xs};
      font-weight: ${r.weight.normal};
      border: 1px solid ${t.yellow[300]};
    `,maskedLocation:d`
      color: ${t.yellow[300]};
    `,detailsContent:d`
      padding: ${T.size[1.5]} ${T.size[2]};
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: ${r.size.xs};
    `,routeMatchesToggle:d`
      display: flex;
      align-items: center;
      border: 1px solid ${t.gray[500]};
      border-radius: ${o.radius.sm};
      overflow: hidden;
    `,routeMatchesToggleBtn:(e,i)=>{const o=[d`
        appearance: none;
        border: none;
        font-size: 12px;
        padding: 4px 8px;
        background: transparent;
        cursor: pointer;
        font-family: ${a.sans};
        font-weight: ${r.weight.medium};
      `];if(e){const e=d`
          background: ${t.darkGray[400]};
          color: ${t.gray[300]};
        `;o.push(e)}else{const e=d`
          color: ${t.gray[500]};
          background: ${t.darkGray[800]}${n[20]};
        `;o.push(e)}return i&&o.push(d`
          border-right: 1px solid ${T.colors.gray[500]};
        `),o},detailsHeaderInfo:d`
      flex: 1;
      justify-content: flex-end;
      display: flex;
      align-items: center;
      font-weight: ${r.weight.normal};
      color: ${t.gray[400]};
    `,matchRow:e=>{const r=[d`
        display: flex;
        border-bottom: 1px solid ${t.darkGray[400]};
        cursor: pointer;
        align-items: center;
        padding: ${i[1]} ${i[2]};
        gap: ${i[2]};
        font-size: ${l.xs};
        color: ${t.gray[300]};
      `];if(e){const e=d`
          background: ${t.darkGray[500]};
        `;r.push(e)}return r},matchIndicator:e=>{const r=[d`
        flex: 0 0 auto;
        width: ${i[3]};
        height: ${i[3]};
        background: ${t[e][900]};
        border: 1px solid ${t[e][500]};
        border-radius: ${o.radius.full};
        transition: all 0.25s ease-out;
        box-sizing: border-box;
      `];if("gray"===e){const e=d`
          background: ${t.gray[700]};
          border-color: ${t.gray[400]};
        `;r.push(e)}return r},matchID:d`
      flex: 1;
      line-height: ${s.xs};
    `,ageTicker:e=>{const r=[d`
        display: flex;
        gap: ${i[1]};
        font-size: ${l.xs};
        color: ${t.gray[400]};
        font-variant-numeric: tabular-nums;
        line-height: ${s.xs};
      `];if(e){const e=d`
          color: ${t.yellow[400]};
        `;r.push(e)}return r},secondContainer:d`
      flex: 1 1 500px;
      min-height: 40%;
      max-height: 100%;
      overflow: auto;
      border-right: 1px solid ${t.gray[700]};
      display: flex;
      flex-direction: column;
    `,thirdContainer:d`
      flex: 1 1 500px;
      overflow: auto;
      display: flex;
      flex-direction: column;
      height: 100%;
      border-right: 1px solid ${t.gray[700]};

      @media (max-width: 700px) {
        border-top: 2px solid ${t.gray[700]};
      }
    `,fourthContainer:d`
      flex: 1 1 500px;
      min-height: 40%;
      max-height: 100%;
      overflow: auto;
      display: flex;
      flex-direction: column;
    `,routesContainer:d`
      overflow-x: auto;
      overflow-y: visible;
    `,routesRowContainer:(e,r)=>{const n=[d`
        display: flex;
        border-bottom: 1px solid ${t.darkGray[400]};
        align-items: center;
        padding: ${i[1]} ${i[2]};
        gap: ${i[2]};
        font-size: ${l.xs};
        color: ${t.gray[300]};
        cursor: ${r?"pointer":"default"};
        line-height: ${s.xs};
      `];if(e){const e=d`
          background: ${t.darkGray[500]};
        `;n.push(e)}return n},routesRow:e=>{const r=[d`
        flex: 1 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: ${l.xs};
        line-height: ${s.xs};
      `];if(!e){const e=d`
          color: ${t.gray[400]};
        `;r.push(e)}return r},routesRowInner:d`
      display: 'flex';
      align-items: 'center';
      flex-grow: 1;
      min-width: 0;
    `,routeParamInfo:d`
      color: ${t.gray[400]};
      font-size: ${l.xs};
      line-height: ${s.xs};
    `,nestedRouteRow:e=>d`
        margin-left: ${e?0:i[3.5]};
        border-left: ${e?"":`solid 1px ${t.gray[700]}`};
      `,code:d`
      font-size: ${l.xs};
      line-height: ${s.xs};
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    `,matchesContainer:d`
      flex: 1 1 auto;
      overflow-y: auto;
    `,cachedMatchesContainer:d`
      flex: 1 1 auto;
      overflow-y: auto;
      max-height: 50%;
    `,historyContainer:d`
      display: flex;
      flex: 1 1 auto;
      overflow-y: auto;
      max-height: 50%;
    `,historyOverflowContainer:d`
      padding: ${i[1]} ${i[2]};
      font-size: ${T.font.size.xs};
    `,maskedBadgeContainer:d`
      flex: 1;
      justify-content: flex-end;
      display: flex;
    `,matchDetails:d`
      display: flex;
      flex-direction: column;
      padding: ${T.size[2]};
      font-size: ${T.font.size.xs};
      color: ${T.colors.gray[300]};
      line-height: ${T.font.lineHeight.sm};
    `,matchStatus:(e,t)=>{const r=t&&"success"===e?"beforeLoad"===t?"purple":"blue":{pending:"yellow",success:"green",error:"red",notFound:"purple",redirected:"gray"}[e];return d`
        display: flex;
        justify-content: center;
        align-items: center;
        height: 40px;
        border-radius: ${T.border.radius.sm};
        font-weight: ${T.font.weight.normal};
        background-color: ${T.colors[r][900]}${T.alpha[90]};
        color: ${T.colors[r][300]};
        border: 1px solid ${T.colors[r][600]};
        margin-bottom: ${T.size[2]};
        transition: all 0.25s ease-out;
      `},matchDetailsInfo:d`
      display: flex;
      justify-content: flex-end;
      flex: 1;
    `,matchDetailsInfoLabel:d`
      display: flex;
    `,mainCloseBtn:d`
      background: ${t.darkGray[700]};
      padding: ${i[1]} ${i[2]} ${i[1]} ${i[1.5]};
      border-radius: ${o.radius.md};
      position: fixed;
      z-index: 99999;
      display: inline-flex;
      width: fit-content;
      cursor: pointer;
      appearance: none;
      border: 0;
      gap: 8px;
      align-items: center;
      border: 1px solid ${t.gray[500]};
      font-size: ${r.size.xs};
      cursor: pointer;
      transition: all 0.25s ease-out;

      &:hover {
        background: ${t.darkGray[500]};
      }
    `,mainCloseBtnPosition:e=>d`
        ${"top-left"===e?`top: ${i[2]}; left: ${i[2]};`:""}
        ${"top-right"===e?`top: ${i[2]}; right: ${i[2]};`:""}
        ${"bottom-left"===e?`bottom: ${i[2]}; left: ${i[2]};`:""}
        ${"bottom-right"===e?`bottom: ${i[2]}; right: ${i[2]};`:""}
      `,mainCloseBtnAnimation:e=>e?d`
        opacity: 0;
        pointer-events: none;
        visibility: hidden;
      `:d`
          opacity: 1;
          pointer-events: auto;
          visibility: visible;
        `,routerLogoCloseButton:d`
      font-weight: ${r.weight.semibold};
      font-size: ${r.size.xs};
      background: linear-gradient(to right, #98f30c, #00f4a3);
      background-clip: text;
      -webkit-background-clip: text;
      line-height: 1;
      -webkit-text-fill-color: transparent;
      white-space: nowrap;
    `,mainCloseBtnDivider:d`
      width: 1px;
      background: ${T.colors.gray[600]};
      height: 100%;
      border-radius: 999999px;
      color: transparent;
    `,mainCloseBtnIconContainer:d`
      position: relative;
      width: ${i[5]};
      height: ${i[5]};
      background: pink;
      border-radius: 999999px;
      overflow: hidden;
    `,mainCloseBtnIconOuter:d`
      width: ${i[5]};
      height: ${i[5]};
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      filter: blur(3px) saturate(1.8) contrast(2);
    `,mainCloseBtnIconInner:d`
      width: ${i[4]};
      height: ${i[4]};
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    `,panelCloseBtn:d`
      position: absolute;
      cursor: pointer;
      z-index: 100001;
      display: flex;
      align-items: center;
      justify-content: center;
      outline: none;
      background-color: ${t.darkGray[700]};
      &:hover {
        background-color: ${t.darkGray[500]};
      }

      top: 0;
      right: ${i[2]};
      transform: translate(0, -100%);
      border-right: ${t.darkGray[300]} 1px solid;
      border-left: ${t.darkGray[300]} 1px solid;
      border-top: ${t.darkGray[300]} 1px solid;
      border-bottom: none;
      border-radius: ${o.radius.sm} ${o.radius.sm} 0px 0px;
      padding: ${i[1]} ${i[1.5]} ${i[.5]} ${i[1.5]};

      &::after {
        content: ' ';
        position: absolute;
        top: 100%;
        left: -${i[2.5]};
        height: ${i[1.5]};
        width: calc(100% + ${i[5]});
      }
    `,panelCloseBtnIcon:d`
      color: ${t.gray[400]};
      width: ${i[2]};
      height: ${i[2]};
    `,navigateButton:d`
      background: none;
      border: none;
      padding: 0 0 0 4px;
      margin: 0;
      color: ${t.gray[400]};
      font-size: ${l.md};
      cursor: pointer;
      line-height: 1;
      vertical-align: middle;
      margin-right: 0.5ch;
      flex-shrink: 0;
      &:hover {
        color: ${t.blue[300]};
      }
    `}})($(z)));return e}function L(e,i){const[n,o]=t();return r(()=>{const t=(e=>{try{const t=localStorage.getItem(e);return"string"==typeof t?JSON.parse(t):void 0}catch{return}})(e);o(null==t?"function"==typeof i?i():i:t)}),[n,t=>{o(r=>{let i=t;"function"==typeof t&&(i=t(r));try{localStorage.setItem(e,JSON.stringify(i))}catch{}return i})}]}var G="undefined"==typeof window;function P(e){return e.isFetching&&"success"===e.status?"beforeLoad"===e.isFetching?"purple":"blue":{pending:"yellow",success:"green",error:"red",notFound:"purple",redirected:"gray"}[e.status]}function H(){const[e,i]=t(!1);return(G?r:d)(()=>{i(!0)}),e}var N=Symbol.for("tanstack.rsc.stream"),J=Symbol.for("tanstack.rsc.renderable"),V=Symbol.for("tanstack.rsc.slotUsages");function _(e){let t=e.length;for(;t>0&&void 0===e[t-1];)t--;return 0===t||t===e.length?e:e.slice(0,t)}var K=e=>("object"==typeof e||"function"==typeof e)&&null!==e&&N in e,U=e=>K(e)?J in e&&!0===e[J]?"renderableValue":"compositeSource":null,q=e=>{if(!K(e))return[];const t=[];if(V in e){const r=e[V];if(Array.isArray(r))for(const e of r){const r=e?.slot;"string"!=typeof r||t.includes(r)||t.push(r)}}return t},Y=e=>{if("React element"===e)return"React element";const t=U(e);if("compositeSource"===t){const t=q(e);return t.length>0?`RSC composite source (${t.length} ${1===t.length?"slot":"slots"})`:"RSC composite source"}if("renderableValue"===t)return"RSC renderable value";const r=Object.getOwnPropertyNames(Object(e)),i="bigint"==typeof e?`${e.toString()}n`:e;try{return JSON.stringify(i,r)}catch{return"unable to stringify"}},Q=x('<span><svg xmlns=http://www.w3.org/2000/svg width=12 height=12 fill=none viewBox="0 0 24 24"><path stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 d="M9 18l6-6-6-6">'),W=x("<div>"),X=x("<button><span>:</span><span>"),Z=x("<div><span>slots</span><div>"),ee=x("<span>:"),te=x("<span>"),re=x("<button><span> "),ie=x("<div><div><button> [<!> ... <!>]"),ne=x("<button><span></span> 🔄 "),oe=({expanded:e,style:t={}})=>{const r=le();return i=Q(),n=i.firstChild,d(t=>{var o=r().expander,a=h(r().expanderIcon(e));return o!==t.e&&c(i,t.e=o),a!==t.t&&v(n,"class",t.t=a),t},{e:void 0,t:void 0}),i;var i,n};function ae({value:e,defaultExpanded:r,pageSize:n=100,filterSubEntries:o,...l}){const[g,p]=t(Boolean(r)),v=()=>p(e=>!e),m=i(()=>typeof e()),b=i(()=>{let t=[];const i=e=>{const t=!0===r?{[e.label]:!0}:r?.[e.label];return{...e,value:()=>e.value,defaultExpanded:t}};if(Array.isArray(e())&&2===e().length&&"React element"===e()[0]&&function(e){if(!e||"object"!=typeof e)return!1;const t=Object.getPrototypeOf(e);return t===Object.prototype||null===t}(e()[1])){const r=e();t=[i({label:"0",value:r[0]}),...Object.entries(r[1]).map(([e,t])=>i({label:e,value:t}))]}else Array.isArray(e())?t=e().map((e,t)=>i({label:t.toString(),value:e})):null!==e()&&"object"==typeof e()&&(n=e(),Symbol.iterator in n)&&"function"==typeof e()[Symbol.iterator]?t=Array.from(e(),(e,t)=>i({label:t.toString(),value:e})):"object"==typeof e()&&null!==e()&&(t=Object.entries(e()).map(([e,t])=>i({label:e,value:t})));var n;return o?o(t):t}),$=i(()=>function(e,t){if(t<1)return[];let r=0;const i=[];for(;r<e.length;)i.push(e.slice(r,r+t)),r+=t;return i}(b(),n)),[x,y]=t([]),[w,C]=t(void 0),k=le(),F=()=>{C(e()())},z=t=>u(ae,a({value:e,filterSubEntries:o},l,t)),S=i(()=>U(e())),I=i(()=>q(e())),D=i(()=>(e=>{const t=(e=>{if(!K(e))return[];if(!(V in e))return[];const t=e[V];return Array.isArray(t)?t.filter(e=>e&&"object"==typeof e&&"string"==typeof e.slot&&(void 0===e.args||Array.isArray(e.args))):[]})(e),r={};for(const i of t){const e=_(i.args??[]),t=r[i.slot]??(r[i.slot]={count:0,invocations:[]});t.count++,t.invocations.push(e)}return r})(e())),B=i(()=>"compositeSource"===S()&&I().length>0);return A=W(),s(A,(E=f(()=>null!==S()),()=>{return E()?f(()=>!!B())()?[(j=X(),M=j.firstChild,T=M.firstChild,O=M.nextSibling,j.$$click=()=>v(),s(j,u(oe,{get expanded(){return g()??!1}}),M),s(M,()=>l.label,T),s(O,()=>Y(e())),d(e=>{var t=k().expandButton,r=k().compositeComponent;return t!==e.e&&c(j,e.e=t),r!==e.t&&c(O,e.t=r),e},{e:void 0,t:void 0}),j),f(()=>{return f(()=>!!g())()?(e=Z(),t=e.firstChild,r=t.nextSibling,s(r,()=>I().map(e=>{const t=D()[e];return t?u(ae,{label:`${e}:`,value:()=>t.invocations.map(e=>1===e.length?e[0]:e)}):null})),d(i=>{var n=k().rscMetaRow,o=k().rscMetaLabel,a=k().subEntries;return n!==i.e&&c(e,i.e=n),o!==i.t&&c(t,i.t=o),a!==i.a&&c(r,i.a=a),i},{e:void 0,t:void 0,a:void 0}),e):null;var e,t,r})]:[(A=ee(),R=A.firstChild,s(A,()=>l.label,R),A)," ",(C=te(),s(C,()=>Y(e())),d(()=>c(C,"compositeSource"===S()?k().compositeComponent:k().renderableComponent)),C)]:f(()=>!!$().length)()?[(o=re(),a=o.firstChild,p=a.firstChild,o.$$click=()=>v(),s(o,u(oe,{get expanded(){return g()??!1}}),a),s(o,()=>l.label,a),s(a,()=>"iterable"===String(m).toLowerCase()?"(Iterable) ":"",p),s(a,()=>b().length,p),s(a,()=>b().length>1?"items":"item",null),d(e=>{var t=k().expandButton,r=k().info;return t!==e.e&&c(o,e.e=t),r!==e.t&&c(a,e.t=r),e},{e:void 0,t:void 0}),o),f(()=>{return f(()=>!!g())()?f(()=>1===$().length)()?(t=W(),s(t,()=>b().map((e,t)=>z(e))),d(()=>c(t,k().subEntries)),t):(e=W(),s(e,()=>$().map((e,t)=>{return i=ie(),o=i.firstChild,(p=(g=(l=(a=o.firstChild).firstChild).nextSibling).nextSibling.nextSibling).nextSibling,a.$$click=()=>y(e=>e.includes(t)?e.filter(e=>e!==t):[...e,t]),s(a,u(oe,{get expanded(){return x().includes(t)}}),l),s(a,t*n,g),s(a,t*n+n-1,p),s(o,(r=f(()=>!!x().includes(t)),()=>{return r()?(t=W(),s(t,()=>e.map(e=>z(e))),d(()=>c(t,k().subEntries)),t):null;var t}),null),d(e=>{var t=k().entry,r=h(k().labelButton,"labelButton");return t!==e.e&&c(o,e.e=t),r!==e.t&&c(a,e.t=r),e},{e:void 0,t:void 0}),i;var r,i,o,a,l,g,p})),d(()=>c(e,k().subEntries)),e):null;var e,t})]:f(()=>"function"===m())()?u(ae,{get label(){return t=(e=ne()).firstChild,e.$$click=F,s(t,()=>l.label),d(()=>c(e,k().refreshValueBtn)),e;var e,t},value:w,defaultExpanded:{}}):[(r=ee(),i=r.firstChild,s(r,()=>l.label,i),r)," ",(t=te(),s(t,()=>Y(e())),d(()=>c(t,k().value)),t)];var t,r,i,o,a,p,C,A,R,j,M,T,O})),d(()=>c(A,k().entry)),A;var E,A}var se=e=>{const{colors:t,font:r,size:i,border:n}=T,{fontFamily:o,lineHeight:a,size:s}=r,l=e?M.bind({target:e}):M;return{entry:l`
      font-family: ${o.mono};
      font-size: ${s.xs};
      line-height: ${a.sm};
      outline: none;
      word-break: break-word;
    `,labelButton:l`
      cursor: pointer;
      color: inherit;
      font: inherit;
      outline: inherit;
      background: transparent;
      border: none;
      padding: 0;
    `,expander:l`
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: ${i[3]};
      height: ${i[3]};
      padding-left: 3px;
      box-sizing: content-box;
    `,expanderIcon:e=>e?l`
          transform: rotate(90deg);
          transition: transform 0.1s ease;
        `:l`
        transform: rotate(0deg);
        transition: transform 0.1s ease;
      `,expandButton:l`
      display: flex;
      gap: ${i[1]};
      align-items: center;
      cursor: pointer;
      color: inherit;
      font: inherit;
      outline: inherit;
      background: transparent;
      border: none;
      padding: 0;
    `,value:l`
      color: ${t.purple[400]};
    `,compositeComponent:l`
      display: inline-flex;
      align-items: center;
      padding: 1px ${i[1]};
      border-radius: ${n.radius.full};
      border: 1px solid ${t.darkGray[500]};
      background: ${t.darkGray[700]};
      color: ${t.cyan[300]};
      font-style: normal;
      font-weight: ${r.weight.medium};
    `,renderableComponent:l`
      display: inline-flex;
      align-items: center;
      padding: 1px ${i[1]};
      border-radius: ${n.radius.full};
      border: 1px solid ${t.darkGray[500]};
      background: ${t.darkGray[700]};
      color: ${t.teal[300]};
      font-style: normal;
      font-weight: ${r.weight.medium};
    `,rscMetaRow:l`
      display: flex;
      gap: ${i[1]};
      align-items: flex-start;
      margin-left: calc(${i[3]} + ${i[1]});
      margin-top: ${i[.5]};
      flex-wrap: wrap;
    `,rscMetaLabel:l`
      color: ${t.gray[500]};
      font-size: ${s["2xs"]};
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding-top: 2px;
    `,rscChipRow:l`
      display: flex;
      gap: ${i[1]};
      flex-wrap: wrap;
    `,rscChip:l`
      display: inline-flex;
      align-items: center;
      gap: ${i[.5]};
      padding: 1px ${i[1]};
      border-radius: ${n.radius.full};
      border: 1px solid ${t.darkGray[500]};
      background: ${t.darkGray[800]};
      color: ${t.gray[200]};
      font-size: ${s["2xs"]};
      line-height: ${a.xs};
    `,rscChipName:l`
      color: ${t.gray[100]};
    `,rscChipMeta:l`
      color: ${t.gray[400]};
      font-size: ${s["2xs"]};
    `,subEntries:l`
      margin-left: ${i[2]};
      padding-left: ${i[2]};
      border-left: 2px solid ${t.darkGray[400]};
    `,info:l`
      color: ${t.gray[500]};
      font-size: ${s["2xs"]};
      padding-left: ${i[1]};
    `,refreshValueBtn:l`
      appearance: none;
      border: 0;
      cursor: pointer;
      background: transparent;
      color: inherit;
      padding: 0;
      font-family: ${o.mono};
      font-size: ${s.xs};
    `}};function le(){const[e]=t(se($(z)));return e}S(["click"]);var de=x("<div><div></div><div>/</div><div></div><div>/</div><div>");function ce(e){const t=[e/1e3,e/6e4,e/36e5,e/864e5];let r=0;for(let i=1;i<t.length&&!(t[i]<1);i++)r=i;return new Intl.NumberFormat(navigator.language,{compactDisplay:"short",notation:"compact",maximumFractionDigits:0}).format(t[r])+["s","min","h","d"][r]}function ue({match:e,router:t}){const r=O();if(!e)return null;const i=t().looseRoutesById[e.routeId];if(!i.options.loader)return null;const n=Date.now()-e.updatedAt,o=i.options.staleTime??t().options.defaultStaleTime??0,a=i.options.gcTime??t().options.defaultGcTime??18e5;return g=(f=(u=(l=de()).firstChild).nextSibling.nextSibling).nextSibling.nextSibling,s(u,()=>ce(n)),s(f,()=>ce(o)),s(g,()=>ce(a)),d(()=>c(l,h(r().ageTicker(n>o)))),l;var l,u,f,g}var fe=x("<button type=button>➔");function ge({to:e,params:t,search:r,router:i}){const n=O();return(o=fe()).$$click=n=>{n.stopPropagation(),i().navigate({to:e,params:t,search:r})},v(o,"title",`Navigate to ${e}`),d(()=>c(o,n().navigateButton)),o;var o}S(["click"]);var pe=x("<button><div>TANSTACK</div><div>TanStack Router v1"),he=x("<div style=display:flex;align-items:center;width:100%><div style=flex-grow:1;min-width:0>"),ve=x("<code> "),me=x("<code>"),be=x("<div><div role=button><div>"),$e=x("<div>"),xe=x("<div><ul>"),ye=x('<div><button><svg xmlns=http://www.w3.org/2000/svg width=10 height=6 fill=none viewBox="0 0 10 6"><path stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=1.667 d="M1 1l4 4 4-4"></path></svg></button><div><div></div><div><div></div></div></div><div><div><div><span>Pathname</span></div><div><code></code></div><div><div><button type=button>Routes</button><button type=button>Matches</button><button type=button>History</button></div><div><div>age / staleTime / gcTime</div></div></div><div>'),we=x("<div><span>masked"),Ce=x("<div role=button><div>"),ke=x("<li><div>"),Fe=x("<li>This panel displays the most recent 15 navigations."),ze=x("<div><div><div>Cached Matches</div><div>age / staleTime / gcTime</div></div><div>"),Se=x("<div><div>Match Details</div><div><div><div><div></div></div><div><div>ID:</div><div><code></code></div></div><div><div>State:</div><div></div></div><div><div>Last Updated:</div><div></div></div></div></div><div>Explorer</div><div>"),Ie=x("<div>Loader Data"),De=x("<div><div><span>Search Params</span></div><div>"),Be=x("<span style=margin-left:0.5rem>"),Ee=x('<button type=button aria-label="Copy value to clipboard"style=cursor:pointer>');function Ae(e){const{className:t,...r}=e,i=O();return n=pe(),s=n.firstChild,l=s.nextSibling,o(n,a(r,{get class(){return h(i().logo,t?t():"")}}),!1),d(e=>{var t=i().tanstackLogo,r=i().routerLogo;return t!==e.e&&c(s,e.e=t),r!==e.t&&c(l,e.t=r),e},{e:void 0,t:void 0}),n;var n,s,l}function Re(e){return r=(t=he()).firstChild,s(t,()=>e.left,r),s(r,()=>e.children),s(t,()=>e.right,null),d(()=>c(t,e.class)),t;var t,r}function je({routerState:e,pendingMatches:t,router:r,route:n,isRoot:o,activeId:a,setActiveId:l}){const g=O(),m=i(()=>t().length?t():e().matches),b=i(()=>e().matches.find(e=>e.routeId===n.id)),$=i(()=>{try{if(b()?.params){const e=b()?.params,t=n.path||y(n.id);if(t.startsWith("$")){const r=t.slice(1);if(e[r])return`(${e[r]})`}}return""}catch(e){return""}}),x=i(()=>{if(o)return;if(!n.path)return;const e=Object.assign({},...m().map(e=>e.params)),t=w({path:n.fullPath,params:e,decoder:r().pathParamsDecoder});return t.isMissingParams?void 0:t.interpolatedPath});return F=be(),z=F.firstChild,S=z.firstChild,z.$$click=()=>{b()&&l(a()===n.id?"":n.id)},s(z,u(Re,{get class(){return h(g().routesRow(!!b()))},get left(){return u(C,{get when(){return x()},children:e=>u(ge,{get to(){return e()},router:r})})},get right(){return u(ue,{get match(){return b()},router:r})},get children(){return[(t=ve(),r=t.firstChild,s(t,()=>o?p:n.path||y(n.id),r),d(()=>c(t,g().code)),t),(e=me(),s(e,$),d(()=>c(e,g().routeParamInfo)),e)];var e,t,r}}),null),s(F,(k=f(()=>!!n.children?.length),()=>{return k()?(i=$e(),s(i,()=>[...n.children].sort((e,t)=>e.rank-t.rank).map(i=>u(je,{routerState:e,pendingMatches:t,router:r,route:i,activeId:a,setActiveId:l}))),d(()=>c(i,g().nestedRouteRow(!!o))),i):null;var i}),null),d(e=>{var t=`Open match details for ${n.id}`,r=h(g().routesRowContainer(n.id===a(),!!b())),i=h(g().matchIndicator(function(e,t){const r=e.find(e=>e.routeId===t.id);return r?P(r):"gray"}(m(),n)));return t!==e.e&&v(z,"aria-label",e.e=t),r!==e.t&&c(z,e.t=r),i!==e.a&&c(S,e.a=i),e},{e:void 0,t:void 0,a:void 0}),F;var k,F,z,S}var Me=function({...$}){const{isOpen:x=!0,setIsOpen:y,handleDragStart:w,router:C,routerState:z,shadowDOMTarget:S,...I}=$,{onCloseClick:D}=e(),B=O(),{className:E,style:A,...R}=I,[j,M]=L("tanstackRouterDevtoolsActiveTab","routes"),[T,G]=L("tanstackRouterDevtoolsActiveRouteId",""),[H,N]=t([]),[J,V]=t(!1);let _,K;if("subscribe"in C().stores.pendingMatches){const[e,i]=t([]);_=e;const[n,o]=t([]);K=n,r(()=>{const e=C().stores.pendingMatches;i(e.get());const t=e.subscribe(()=>{i(e.get())});k(()=>t.unsubscribe())}),r(()=>{const e=C().stores.cachedMatches;o(e.get());const t=e.subscribe(()=>{o(e.get())});k(()=>t.unsubscribe())})}else _=()=>C().stores.pendingMatches.get(),K=()=>C().stores.cachedMatches.get();r(()=>{const e=z().matches,t=e[e.length-1];if(!t)return;const r=F(()=>H()),i=r[0],n=i&&i.pathname===t.pathname&&JSON.stringify(i.search??{})===JSON.stringify(t.search??{});i&&n||(r.length>=15&&V(!0),N(e=>{const r=[t,...e];return r.splice(15),r}))});const U=i(()=>[..._(),...z().matches,...K()].find(e=>e.routeId===T()||e.id===T())),q=i(()=>n(z().location.search)),Y=i(()=>({...C(),state:z()})),Q=i(()=>Object.fromEntries(function(e,t=[e=>e]){return e.map((e,t)=>[e,t]).sort(([e,r],[i,n])=>{for(const o of t){const t=o(e),r=o(i);if(void 0===t){if(void 0===r)continue;return 1}if(t!==r)return t>r?1:-1}return r-n}).map(([e])=>e)}(Object.keys(Y()),["state","routesById","routesByPath","options","manifest"].map(e=>t=>t!==e)).map(e=>[e,Y()[e]]).filter(e=>"function"!=typeof e[1]&&!["stores","basepath","injectedHtml","subscribers","latestLoadPromise","navigateTimeout","resetNextScroll","tempLocationKey","latestLocation","routeTree","history"].includes(e[0])))),W=i(()=>U()?.loaderData),X=i(()=>U()),Z=i(()=>z().location.search);return(()=>{var e=ye(),t=e.firstChild,r=t.firstChild,i=t.nextSibling,n=i.firstChild,$=n.nextSibling,x=$.firstChild,k=i.nextSibling,F=k.firstChild,S=F.firstChild;S.firstChild;var I,O,L,N,V,Y,ee=S.nextSibling,te=ee.firstChild,re=ee.nextSibling,ie=re.firstChild,ne=ie.firstChild,oe=ne.nextSibling,se=oe.nextSibling,le=ie.nextSibling,de=re.nextSibling;return o(e,a({get class(){return h(B().devtoolsPanel,"TanStackRouterDevtoolsPanel",E?E():"")},get style(){return A?A():""}},R),!1),s(e,w?(I=$e(),l(I,"mousedown",w,!0),d(()=>c(I,B().dragHandle)),I):null,t),t.$$click=e=>{y&&y(!1),D(e)},s(n,u(Ae,{"aria-hidden":!0,onClick:e=>{y&&y(!1),D(e)}})),s(x,u(ae,{label:"Router",value:Q,defaultExpanded:{state:{},context:{},options:{}},filterSubEntries:e=>e.filter(e=>"function"!=typeof e.value())})),s(S,(O=f(()=>!!z().location.maskedLocation),()=>{return O()?(e=we(),t=e.firstChild,d(r=>{var i=B().maskedBadgeContainer,n=B().maskedBadge;return i!==r.e&&c(e,r.e=i),n!==r.t&&c(t,r.t=n),r},{e:void 0,t:void 0}),e):null;var e,t}),null),s(te,()=>z().location.pathname),s(ee,(L=f(()=>!!z().location.maskedLocation),()=>{return L()?(e=me(),s(e,()=>z().location.maskedLocation?.pathname),d(()=>c(e,B().maskedLocation)),e):null;var e}),null),ne.$$click=()=>{M("routes")},oe.$$click=()=>{M("matches")},se.$$click=()=>{M("history")},s(de,u(b,{get children(){return[u(g,{get when(){return"routes"===j()},get children(){return u(je,{routerState:z,pendingMatches:_,router:C,get route(){return C().routeTree},isRoot:!0,activeId:T,setActiveId:G})}}),u(g,{get when(){return"matches"===j()},get children(){var e=$e();return s(e,()=>(_().length?_():z().matches).map((e,t)=>{return r=Ce(),i=r.firstChild,r.$$click=()=>G(T()===e.id?"":e.id),s(r,u(Re,{get left(){return u(ge,{get to(){return e.pathname},get params(){return e.params},get search(){return e.search},router:C})},get right(){return u(ue,{match:e,router:C})},get children(){var t=me();return s(t,()=>`${e.routeId===p?p:e.pathname}`),d(()=>c(t,B().matchID)),t}}),null),d(t=>{var n=`Open match details for ${e.id}`,o=h(B().matchRow(e===U())),a=h(B().matchIndicator(P(e)));return n!==t.e&&v(r,"aria-label",t.e=n),o!==t.t&&c(r,t.t=o),a!==t.a&&c(i,t.a=a),t},{e:void 0,t:void 0,a:void 0}),r;var r,i})),e}}),u(g,{get when(){return"history"===j()},get children(){var e,t=xe(),r=t.firstChild;return s(r,u(m,{get each(){return H()},children:(e,t)=>{return r=ke(),i=r.firstChild,s(r,u(Re,{get left(){return u(ge,{get to(){return e.pathname},get params(){return e.params},get search(){return e.search},router:C})},get right(){return u(ue,{match:e,router:C})},get children(){var t=me();return s(t,()=>`${e.routeId===p?p:e.pathname}`),d(()=>c(t,B().matchID)),t}}),null),d(n=>{var o=h(B().matchRow(e===U())),a=h(B().matchIndicator(0===t()?"green":"gray"));return o!==n.e&&c(r,n.e=o),a!==n.t&&c(i,n.t=a),n},{e:void 0,t:void 0}),r;var r,i}}),null),s(r,(e=f(()=>!!J()),()=>{return e()?(t=Fe(),d(()=>c(t,B().historyOverflowContainer)),t):null;var t}),null),t}})]}})),s(k,(N=f(()=>!!K().length),()=>{return N()?(e=ze(),t=e.firstChild,r=t.firstChild.nextSibling,i=t.nextSibling,s(i,()=>K().map(e=>{return t=Ce(),r=t.firstChild,t.$$click=()=>G(T()===e.id?"":e.id),s(t,u(Re,{get left(){return u(ge,{get to(){return e.pathname},get params(){return e.params},get search(){return e.search},router:C})},get right(){return u(ue,{match:e,router:C})},get children(){var t=me();return s(t,()=>`${e.id}`),d(()=>c(t,B().matchID)),t}}),null),d(i=>{var n=`Open match details for ${e.id}`,o=h(B().matchRow(e===U())),a=h(B().matchIndicator(P(e)));return n!==i.e&&v(t,"aria-label",i.e=n),o!==i.t&&c(t,i.t=o),a!==i.a&&c(r,i.a=a),i},{e:void 0,t:void 0,a:void 0}),t;var t,r})),d(i=>{var n=B().cachedMatchesContainer,o=B().detailsHeader,a=B().detailsHeaderInfo;return n!==i.e&&c(e,i.e=n),o!==i.t&&c(t,i.t=o),a!==i.a&&c(r,i.a=a),i},{e:void 0,t:void 0,a:void 0}),e):null;var e,t,r,i}),null),s(e,(V=f(()=>!(!U()||!U()?.status)),()=>{return V()?(n=Se(),a=(o=n.firstChild).nextSibling,l=a.firstChild,p=(g=l.firstChild).firstChild,h=g.nextSibling,m=(v=h.firstChild.nextSibling).firstChild,b=h.nextSibling,$=b.firstChild.nextSibling,x=b.nextSibling,y=x.firstChild.nextSibling,w=a.nextSibling,C=w.nextSibling,s(p,(e=f(()=>!("success"!==U()?.status||!U()?.isFetching)),()=>e()?"fetching":U()?.status)),s(m,()=>U()?.id),s($,(t=f(()=>!!_().find(e=>e.id===U()?.id)),()=>t()?"Pending":z().matches.find(e=>e.id===U()?.id)?"Active":"Cached")),s(y,(r=f(()=>!!U()?.updatedAt),()=>r()?new Date(U()?.updatedAt).toLocaleTimeString():"N/A")),s(n,(i=f(()=>!!W()),()=>{return i()?[(t=Ie(),d(()=>c(t,B().detailsHeader)),t),(e=$e(),s(e,u(ae,{label:"loaderData",value:W,defaultExpanded:{}})),d(()=>c(e,B().detailsContent)),e)]:null;var e,t}),w),s(C,u(ae,{label:"Match",value:X,defaultExpanded:{}})),d(e=>{var t=B().thirdContainer,r=B().detailsHeader,i=B().matchDetails,a=B().matchStatus(U()?.status,U()?.isFetching),s=B().matchDetailsInfoLabel,d=B().matchDetailsInfo,u=B().matchDetailsInfoLabel,f=B().matchDetailsInfo,p=B().matchDetailsInfoLabel,m=B().matchDetailsInfo,k=B().detailsHeader,F=B().detailsContent;return t!==e.e&&c(n,e.e=t),r!==e.t&&c(o,e.t=r),i!==e.a&&c(l,e.a=i),a!==e.o&&c(g,e.o=a),s!==e.i&&c(h,e.i=s),d!==e.n&&c(v,e.n=d),u!==e.s&&c(b,e.s=u),f!==e.h&&c($,e.h=f),p!==e.r&&c(x,e.r=p),m!==e.d&&c(y,e.d=m),k!==e.l&&c(w,e.l=k),F!==e.u&&c(C,e.u=F),e},{e:void 0,t:void 0,a:void 0,o:void 0,i:void 0,n:void 0,s:void 0,h:void 0,r:void 0,d:void 0,l:void 0,u:void 0}),n):null;var e,t,r,i,n,o,a,l,g,p,h,v,m,b,$,x,y,w,C}),null),s(e,(Y=f(()=>!!q()),()=>Y()?(()=>{var e=De(),t=e.firstChild;t.firstChild;var r,i=t.nextSibling;return s(t,"undefined"!=typeof navigator?(r=Be(),s(r,u(Te,{getValue:()=>{const e=z().location.search;return JSON.stringify(e)}})),r):null,null),s(i,u(ae,{value:Z,get defaultExpanded(){return Object.keys(z().location.search).reduce((e,t)=>(e[t]={},e),{})}})),d(r=>{var n=B().fourthContainer,o=B().detailsHeader,a=B().detailsContent;return n!==r.e&&c(e,r.e=n),o!==r.t&&c(t,r.t=o),a!==r.a&&c(i,r.a=a),r},{e:void 0,t:void 0,a:void 0}),e})():null),null),d(e=>{var o=B().panelCloseBtn,a=B().panelCloseBtnIcon,s=B().firstContainer,l=B().row,d=B().routerExplorerContainer,u=B().routerExplorer,f=B().secondContainer,g=B().matchesContainer,p=B().detailsHeader,m=B().detailsContent,b=B().detailsHeader,y=B().routeMatchesToggle,w="routes"===j(),C=h(B().routeMatchesToggleBtn("routes"===j(),!0)),z="matches"===j(),I=h(B().routeMatchesToggleBtn("matches"===j(),!0)),D="history"===j(),E=h(B().routeMatchesToggleBtn("history"===j(),!1)),A=B().detailsHeaderInfo,R=h(B().routesContainer);return o!==e.e&&c(t,e.e=o),a!==e.t&&v(r,"class",e.t=a),s!==e.a&&c(i,e.a=s),l!==e.o&&c(n,e.o=l),d!==e.i&&c($,e.i=d),u!==e.n&&c(x,e.n=u),f!==e.s&&c(k,e.s=f),g!==e.h&&c(F,e.h=g),p!==e.r&&c(S,e.r=p),m!==e.d&&c(ee,e.d=m),b!==e.l&&c(re,e.l=b),y!==e.u&&c(ie,e.u=y),w!==e.c&&(ne.disabled=e.c=w),C!==e.w&&c(ne,e.w=C),z!==e.m&&(oe.disabled=e.m=z),I!==e.f&&c(oe,e.f=I),D!==e.y&&(se.disabled=e.y=D),E!==e.g&&c(se,e.g=E),A!==e.p&&c(le,e.p=A),R!==e.b&&c(de,e.b=R),e},{e:void 0,t:void 0,a:void 0,o:void 0,i:void 0,n:void 0,s:void 0,h:void 0,r:void 0,d:void 0,l:void 0,u:void 0,c:void 0,w:void 0,m:void 0,f:void 0,y:void 0,g:void 0,p:void 0,b:void 0}),e})()};function Te({getValue:e}){const[r,i]=t(!1);let n=null;return k(()=>{n&&clearTimeout(n)}),(o=Ee()).$$click=async()=>{if("undefined"!=typeof navigator&&navigator.clipboard?.writeText)try{const t=e();await navigator.clipboard.writeText(t),i(!0),n&&clearTimeout(n),n=setTimeout(()=>i(!1),2500)}catch(t){console.error("TanStack Router Devtools: Failed to copy",t)}else console.warn("TanStack Router Devtools: Clipboard API unavailable")},s(o,()=>r()?"✅":"📋"),d(()=>v(o,"title",r()?"Copied!":"Copy")),o;var o}S(["click","mousedown"]);export{Me as BaseTanStackRouterDevtoolsPanel,Me as default,L as n,O as r,H as t};
