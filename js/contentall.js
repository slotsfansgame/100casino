$(document).on('click','.read-more',function(e){
	e.preventDefault();
	var id = $(this).data('id') || 0;
	// Вставляем полученный контент в HTML блок с id="contentall"
	$("#contentall").load("/assets/ajax.php",{action:"getContent", id:id});
});