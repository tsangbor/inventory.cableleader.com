!function(){var e=document.getElementById("slider");noUiSlider.create(e,{start:[20,80],connect:!0,range:{min:0,max:100}});var n=document.getElementById("result"),t=document.getElementsByClassName("sliders"),i=[0,0,0];[].slice.call(t).forEach((function(e,t){noUiSlider.create(e,{start:127,connect:[!0,!1],orientation:"vertical",range:{min:0,max:255},format:wNumb({decimals:0})}),e.noUiSlider.on("update",(function(){i[t]=e.noUiSlider.get();var o="rgb("+i.join(",")+")";n.style.background=o,n.style.color=o}))}));for(var o=document.getElementById("input-select"),r=-20;r<=40;r++){var a=document.createElement("option");a.text=r,a.value=r,o.appendChild(a)}var l=document.getElementById("html5");noUiSlider.create(l,{start:[10,30],connect:!0,range:{min:-20,max:40}});var d=document.getElementById("input-number");l.noUiSlider.on("update",(function(e,n){e=e[n],n?d.value=e:o.value=Math.round(e)})),o.addEventListener("change",(function(){l.noUiSlider.set([this.value,null])})),d.addEventListener("change",(function(){l.noUiSlider.set([null,this.value])}));var c=document.getElementById("nonlinear");noUiSlider.create(c,{connect:!0,behaviour:"tap",start:[500,4e3],range:{min:[0],"10%":[500,500],"50%":[4e3,1e3],max:[1e4]}});var m=[document.getElementById("lower-value"),document.getElementById("upper-value")];c.noUiSlider.on("update",(function(e,n,t,i,o){m[n].innerHTML=e[n]+", "+o[n].toFixed(2)+"%"}));var u=!1,s=[60,80],g=document.getElementById("slider1"),v=document.getElementById("slider2"),S=document.getElementById("lockbutton"),U=document.getElementById("slider1-span"),f=document.getElementById("slider2-span");function E(e,n){var t;u&&(e-=s[(t=g===n?0:1)?0:1]-s[t],n.noUiSlider.set(e))}function y(){s=[Number(g.noUiSlider.get()),Number(v.noUiSlider.get())]}S.addEventListener("click",(function(){u=!u,this.textContent=u?"unlock":"lock"})),noUiSlider.create(g,{start:60,animate:!1,range:{min:50,max:100}}),noUiSlider.create(v,{start:80,animate:!1,range:{min:50,max:100}}),g.noUiSlider.on("update",(function(e,n){U.innerHTML=e[n]})),v.noUiSlider.on("update",(function(e,n){f.innerHTML=e[n]})),g.noUiSlider.on("change",y),v.noUiSlider.on("change",y),g.noUiSlider.on("slide",(function(e,n){E(e[n],v)})),v.noUiSlider.on("slide",(function(e,n){E(e[n],g)}));var p=document.getElementById("slider-hide");noUiSlider.create(p,{range:{min:0,max:100},start:[20,80],tooltips:!0}),e=document.getElementById("slider-color"),noUiSlider.create(e,{start:[4e3,8e3,12e3,16e3],connect:[!1,!0,!0,!0,!0],range:{min:[2e3],max:[2e4]}});var B=e.querySelectorAll(".noUi-connect"),I=["c-1-color","c-2-color","c-3-color","c-4-color","c-5-color"];for(r=0;r<B.length;r++)B[r].classList.add(I[r]);var h=document.getElementById("slider-toggle");noUiSlider.create(h,{orientation:"vertical",start:0,range:{min:[0,1],max:1},format:wNumb({decimals:0})}),h.noUiSlider.on("update",(function(e,n){"1"===e[n]?h.classList.add("off"):h.classList.remove("off")}));var x=document.getElementById("soft");noUiSlider.create(x,{start:50,range:{min:0,max:100},pips:{mode:"values",values:[20,80],density:4}}),x.noUiSlider.on("change",(function(e,n){e[n]<20?x.noUiSlider.set(20):80<e[n]&&x.noUiSlider.set(80)}))}();