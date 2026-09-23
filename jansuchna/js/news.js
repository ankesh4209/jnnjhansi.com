var message = new Array();
begintag = '<div style="padding: 5px 0;">';
message[0] = "<a href=''><b>To be released soon</b></a><br />We are premier Warehousing Agency in India, providing world class multi-modal";
message[1] = "<a href=''><b>Coming soon</b></a><br />We are premier Warehousing Agency in India, providing world class multi-modal";
message[2] = "<a href=''><b>Coming soon</b></a><br />We are premier Warehousing Agency in India, providing world class multi-modal";
closetag = '</div>';

var delay = 4000;
var maxsteps = 30;
var stepdelay = 40;
var startcolor = new Array(255,255,255);
var endcolor = new Array(0,0,0);
var a_endcolor = new Array(8,105,196);
var width = '170px';
var height = '160px';

var fadelinks = 1;

var ie4 = document.all && !document.getElementById;
var DOM2 = document.getElementById;
var faderdelay = 0;
var msg = message.length;
var index1 = 0;
var index2 = 0;

function changecontent() {
	if (index1 > msg - 2)
		index1 = 0;
		index2 = index1 + 2;
	if (index2 > msg - 1)
		index2 = 0;
	
	if (DOM2) {
		document.getElementById("scroller").style.color = "rgb("+startcolor[0]+", "+startcolor[1]+", "+startcolor[2]+")"
		document.getElementById("scroller").innerHTML = begintag+message[index1]+closetag+"<br />"+begintag+message[index2]+closetag;
		
		if (fadelinks)
			linkcolorchange(1);
			colorfade(1, 15);
			
	} else if (ie4)
		document.all.fscroller.innerHTML = begintag+message[index1]+closetag+"<br />"+begintag+message[index2]+closetag;
	
	index1++;
}

function linkcolorchange(step) {
	var obj = document.getElementById("scroller").getElementsByTagName("a");
	if (obj.length > 0) {
		for (i=0; i<obj.length; i++)
		obj[i].style.color = a_getstepcolor(step);
	}
}

var fadecounter;
function colorfade(step) {
	if(step <= maxsteps) {
		document.getElementById("scroller").style.color = getstepcolor(step);
		
		if (fadelinks)
		linkcolorchange(step);
		step++;
		
		fadecounter=setTimeout("colorfade("+step+")",stepdelay);
	} else {
		clearTimeout(fadecounter);
		document.getElementById("scroller").style.color = "rgb("+endcolor[0]+", "+endcolor[1]+", "+endcolor[2]+")";
		setTimeout("changecontent()", delay);
	}   
}

function getstepcolor(step) {
	var diff
	var newcolor = new Array(3);
	for(var i=0; i<3; i++) {
		diff = (startcolor[i] - endcolor[i]);
		
		if(diff > 0) {
			newcolor[i] = startcolor[i] - (Math.round((diff/maxsteps))*step);
		} else {
			newcolor[i] = startcolor[i] + (Math.round((Math.abs(diff)/maxsteps))*step);
		}
	}
	return ("rgb(" + newcolor[0] + ", " + newcolor[1] + ", " + newcolor[2] + ")");
}

function a_getstepcolor(step) {
	var diff
	var newcolor = new Array(3);
	for(var i=0; i<3; i++) {
		diff = (startcolor[i] - a_endcolor[i]);
		
		if(diff > 0) {
			newcolor[i] = startcolor[i] - (Math.round((diff/maxsteps))*step);
		} else {
			newcolor[i] = startcolor[i] + (Math.round((Math.abs(diff)/maxsteps))*step);
		}
	}
	return ("rgb(" + newcolor[0] + ", " + newcolor[1] + ", " + newcolor[2] + ")");
}

if (ie4 || DOM2)
	document.write('<div id="scroller" style="width:'+width+';height:'+height+'"></div>');
	
	if (window.addEventListener)
		window.addEventListener("load", changecontent, false)
	else if (window.attachEvent)
		window.attachEvent("onload", changecontent)
	else if (document.getElementById)
		window.onload = changecontent