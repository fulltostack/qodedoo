document.getElementById("imgprv").onchange = function() {previewImg()};
document.getElementById("set_width").onchange = function() {previewImg()};
document.getElementById("set_height").onchange = function() {previewImg()};
document.getElementById("set_margin-top").onchange = function() {previewImg()};
document.getElementById("set_margin-left").onchange = function() {previewImg()};
document.getElementById("set_margin-right").onchange = function() {previewImg()};
document.getElementById("set_margin-bottom").onchange = function() {previewImg()};
document.getElementById("prvclrfl").onmouseup = function() {MyCLFbg()};

function previewImg()
{
	var imgprv = document.getElementById('imgprv').value;
	var img_width = document.getElementById('set_width').value;
	var img_height = document.getElementById('set_height').value;
	var img_margin_top = document.getElementById('set_margin-top').value;
	var img_margin_left = document.getElementById('set_margin-left').value;
	var img_margin_right = document.getElementById('set_margin-right').value;
	var img_margin_bottom = document.getElementById('set_margin-bottom').value;
	var chanimg = document.getElementById('previmg');

	chanimg.src = imgprv;
	chanimg.style.width = img_width;
	chanimg.style.height = img_height;
	chanimg.style.marginTop = img_margin_top;
	chanimg.style.marginLeft = img_margin_left;
	chanimg.style.marginRight = img_margin_right;
	chanimg.style.marginBottom = img_margin_bottom;
}


function MyCLFbg()
{
	document.getElementById('previewbg').style = document.getElementById('prvclrfl').value;
}