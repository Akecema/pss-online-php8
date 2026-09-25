function MakeArrayday(size) {
	this.length = size;
	for(var i = 1; i <= size; i++) {
		this[i] = "";
	}//endfor
	return this;
}//endfunction
function MakeArraymonth(size) {
	this.length = size;
	for(var i = 1; i <= size; i++) {
		this[i] = "";
	}//endfor
	return this;
}
function funClock() {
	if (!document.layers && !document.all)
		return;
	var runTime = new Date();
	var year = runTime.getYear();
	var hours = runTime.getHours();
	var minutes = runTime.getMinutes();
	var seconds = runTime.getSeconds();
	var dn = "AM";
	var month = runTime.getMonth() + 1;
	var date = runTime.getDate();
	var day = runTime.getDay();
	
	
	//getDay
	switch(day){
		case 1: day = "Monday";
				break;
		case 2: day = "Tuesday";
				break;
		case 3: day = "Wednesday";
				break;
		case 4: day = "Thursday";
				break;
		case 5: day = "Friday";
				break;
		case 6: day = "Saturday";
				break;
		case 7: day = "Sunday";
				break;
		default: day = "Invalid day";
	}//endswitch
	
	//getMonth
	switch(month){
		case 1: month = "January";
				break;
		case 2: month = "February";
				break;
		case 3: month = "Mac";
				break;
		case 4: month = "April";
				break;
		case 5: month = "May";
				break;
		case 6: month = "June";
				break;
		case 7: month = "July";
				break;
		case 8: month = "August";
				break;
		case 9: month = "September";
				break;
		case 10: month = "October";
				break;
		case 11: month = "November";
				break;
		case 12: month = "December";
				break;
		default: day = "Invalid Month";
	}//endswitch
	
	if (hours >= 12) {
		dn = "PM";
		hours = hours - 12;
	}//endif
	if (hours == 0) {
		hours = 12;
	}//endif
	if (minutes <= 9) {
		minutes = "0" + minutes;
	}//endif
	if (seconds <= 9) {
		seconds = "0" + seconds;
	}//endif
	movingtime = "<b>"+day+", "+date+" "+month+" "+year+" | "+hours+ ":"+minutes+":"+seconds+" "+dn+"</b>";
	if (document.layers) {
		document.layers.clock.document.write(movingtime);
		document.layers.clock.document.close();
	}
	else if (document.all) {
		clock.innerHTML = movingtime;
	}//endif
	setTimeout(funClock, 1000)
}//endfunction
window.onload = funClock;
//  End -->