<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Post Client</title>
<link href="<?php echo base_url();?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
</style>
<link href="<?php echo base_url();?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<script type="text/javascript"><!--
function clearFormAll() {
	for (var i=0; i<document.forms.length; ++i) {
		clearForm(document.forms[i]);
	}
}
function clearForm(form) {
	for(var i=0; i<form.elements.length; ++i) {
		clearElement(form.elements[i]);
	}
}
function clearElement(element) {
	switch(element.type) {
		case "hidden":
			case "submit":
			case "reset":
			case "button":
			case "image":
			return;
		case "file":
			return;
		case "text":
			case "password":
			case "textarea":
			element.value = "";
		return;
		case "checkbox":
			case "radio":
			element.checked = false;
		return;
		case "select-one":
			case "select-multiple":
			element.selectedIndex = 0;
		return;
		default:
	}
}
// --></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
                <div class="container">
                        <a class="brand" href="<?php echo base_url();?>">PostClient</a>
                </div>
        </div>
</div>
<div class="container">
<?php echo (isset($mainContent))? $mainContent:'';?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
