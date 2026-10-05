// JavaScript Document
    function parentFunc(){
      alert('Hi In Parent Func');
    }
    
    function makeRequest(div,url) {
            var http_request = false;
            //alert(url);                   //Manoj kumar
			d = document.getElementById(div);
			//alert(d);
    		  //  d.innerHTML = "<table border='0' width='100%' cellspacing='2' bgcolo='#ff9900'><tr bgolor='#ffebd7' height=80><td valign=top><TABLE WIDTH='100%' valign=top border=0><TR><TD align=center><IMG SRC='/images/wait.gif' WDTH='90' HEGHT='20' BORDER='0' ALT=''><br>Please Wait</TD></TR></TABLE></TD></TR></TABLE>";
    		   //alert(d);
			  
		    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
                // See note below about this line
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        http_request.onreadystatechange = function() { alertContents(div,http_request); };
        http_request.open('GET', url, true);
        http_request.send(null);
		

    }
	function makeRequest_1(div,url) {
            var http_request = false;
            //alert(url);
           // d = document.getElementById(div);
    		//d.innerHTML = "<table border='0' width='100%' cellspacing='2' bgcolo='#ff9900'><tr bgolor='#ffebd7' height=80><td valign=top><TABLE WIDTH='100%' valign=top border=0><TR><TD align=center><IMG SRC='/images/lingoo-uploader.gif' WDTH='90' HEGHT='20' BORDER='0' ALT=''><br>Please Wait</TD></TR></TABLE></TD></TR></TABLE>";
    		    
		    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
                // See note below about this line
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        http_request.onreadystatechange = function() { alertContents(div,http_request); };
        http_request.open('GET', url, true);
        http_request.send(null);

    }

    function alertContents(div,http_request) {
	
      if (http_request.readyState == 4)
  		{
  			//alert(http_request.status);                 //Subhashish Mandal
        if (http_request.status == 200)
  			{
                  result=http_request.responseText;
  				//alert(result);
                  var divm = document.getElementById(div);
  				        divm.innerHTML = result;
  				//alert(divm.innerHTML);
        }
  			else
  			{
                alert('There was a problem with the request---.');
        }
      }

    }
 
 
 
    
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}