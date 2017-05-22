function Toast(msg,duration){  
    duration=isNaN(duration)?1500:duration;  
    var m = document.createElement('div');  
    m.innerHTML = msg;  
    m.style.cssText="padding-left:8px;padding-right:8px;min-width:150px; background:#000; opacity:0.5; height:40px; color:#fff; line-height:40px; text-align:center; border-radius:5px; position:fixed; top:45%; left:45%; z-index:999999; font-weight:bold;";  
    document.body.appendChild(m);  
    setTimeout(function() {  
        var d = 0.5;  
        m.style.webkitTransition = '-webkit-transform ' + d + 's ease-in, opacity ' + d + 's ease-in';  
        m.style.opacity = '0';  
        setTimeout(function() { document.body.removeChild(m) }, d * 1000);  
    }, duration);  
} 