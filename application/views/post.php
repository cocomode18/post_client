<?php if(isset($urlError) && $urlError === true){?>
<div class="alert alert-error">
<button type="button" class="close" data-dismiss="alert">×</button>
<strong>Error!</strong> The Url you requested seems to be wrong or somehow not working :(
</div>
<?php }?>

<form action="<?php echo base_url('post/sendAction');?>" method="post" id="form" accept-charset="utf-8">
<div class="row">
<div class="span6">
<div class="input-prepend">
<span class="add-on">url</span>
<input class="span5" type="text" id="request_url" name="request_url" value="<?php echo $inputUrl;?>" placeholder="http://">
<button type="submit" class="btn btn-primary">Request</button>
<br/><br/>
<button class="btn" onClick="clearFormAll()">Clear</button>
</div>
</div>
<div class="span6">
<?php echo $inputKeyVal;?>
<br/>
</div>
</form>
</div>
<hr/>
<div class="row">
<div class="span12">
<?php if(!empty($requestContent)) echo $requestContent;?>
</div>
</div>
