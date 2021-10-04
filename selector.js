// function ch
//**********************Event Dropdown*************************** */
$(document).on('click', '.dropdown-menu', function (e) {
e.stopPropagation();
});

// make it as accordion for smaller screens
if ($(window).width() < 992) {
$('.dropdown-menu a').click(function(e){
	e.preventDefault();
	if($(this).next('.submenu').length){
		$(this).next('.submenu').toggle();
	}
	$('.dropdown').on('hide.bs.dropdown', function () {
	$(this).find('.submenu').hide();
})
});
}
//********************Event Dropdown end*************************** */

$(".js-range-slider").ionRangeSlider({
	skin: "big",
	type: "double",
	grid: true,
	min: 820,
	max: 1910,
	from: 1000,
	to: 1700,
	postfix: " year",
	prettify_enabled: false,
	onChange: function (data) {
		document.getElementById("start").value = data.from;
		document.getElementById("end").value = data.to;
	}
});

function setEvent(code,eventName){
	document.getElementById("event").value = code;
	document.getElementById("category").innerHTML = eventName;
	//alert(document.getElementById("code").value);
}

function validateForm(){
	if(document.getElementById("event").value == ""){
		alert("請選擇Event");
	}
	else if(document.getElementById("provin").value == ""){
		alert("請選擇provin");
	}
	else{
		document.getElementById("provin").value = document.getElementById("provin").value.substring(1);
		document.getElementById("myform").submit();
	}
}

function Init(){
	DrawMap([112,45],700,'#69b3a2',true);
}