window.onload = function(){ 
document.body.addEventListener("click", function(e){
	var elements = document.getElementsByTagName('*');
	for(var i = 0, len = elements.length; i < len; i++){
		elements[i].style.border = "";
	}
	e.target.style.border = "1px solid #FF0000";
})
}
