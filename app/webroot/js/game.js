function seeCase(url, x, y, movable, message, submessage, attack) {
	$("#gamePopup").show();
	$("#gamePopupX").empty();
	$("#gamePopupY").empty();
	$("#gamePopupMessage").empty();
	$("#gamePopupSubmessage").empty();
	$("#gamePopupMoveTo").hide();
	$("#gamePopupAttack").hide();
	$("#gamePopupX").append("X = " + x);
	$("#gamePopupY").append("Y = " + y);
	$("#gamePopupMessage").append(message);
	$("#gamePopupSubmessage").append(submessage);
	$("#gamePopupMoveTo").attr('href', url + "/moveToX:" + x + "/moveToY:" + y);
	$("#gamePopupAttack").attr('href', url + "/attackX:" + x + "/attackY:" + y);
	if (attack) $("#gamePopupAttack").show();
	if (movable) $("#gamePopupMoveTo").show();
	
}

$(document).ready(function () {
	$("#gamePopup").click(function() {
		$("#gamePopup").hide();
	});
				
	$('#gamePopupContent').click(function(event){
		event.stopPropagation();
	});
});