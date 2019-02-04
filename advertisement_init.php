<?php
function advertisement_init(){?>
<div style="margin-top:45px"></div>
<div class="container">
  <div class="row">
    <div class="col-sm-12">
    	<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="">
  <div class="form-group">
    <label class="control-label col-sm-2" for="title">Title:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="title" placeholder="Enter Title">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="content">Content:</label>
    <div class="col-sm-10">
    <textarea rows="4" cols="50" maxlength="50" name="content" class="form-control" placeholder="Enter text here..."></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="image">Upload Image:</label>
    <div class="col-sm-10">
    	<label class="btn btn-default btn-file">
    		<input id="image-url" type="text" name="image" />
  			<input id="upload-button" type="button" class="button" value="Upload Image" />
		</label>

    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="category">Select Category:</label>
    <div class="col-sm-10">
     <?php
    global $wpdb;
    $categories = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."advertisement_category" );
    ?>
    <select class="selectpicker" name="category">
    <option>Choose Option...</option>
    <?php foreach($categories as $cats): ?>
		  <option value="<?php echo $cats->id;?>"><?php echo $cats->category;?></option>
	<?php endforeach;?>	  
	</select>
    </div>
  </div>
  <?php 
  global $wpdb;
  $last = $wpdb->get_row("SHOW TABLE STATUS LIKE 'ibm_advertisement'");
  $increId = $last->Auto_increment;
  ?>
  <div class="form-group">
    <label class="control-label col-sm-2" for="shortcode">Shortcode:</label>
    <div class="col-sm-10">
      <input type='text' class='form-control' name='shortcode' value='[advertisement id="<?php echo $increId; ?>"]'?>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="submit" name="submit" value="Submit" class="btn btn-default">
    </div>
  </div>
</form>
    </div>
  </div>
</div>
<script>
jQuery(document).ready(function($){

  var mediaUploader;

  $('#upload-button').click(function(e) {
    e.preventDefault();
    // If the uploader object has already been created, reopen the dialog
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    // Extend the wp.media object
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });

    // When a file is selected, grab the URL and set it as the text field's value
    mediaUploader.on('select', function() {
      attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#image-url').val(attachment.url);
    });
    // Open the uploader dialog
    mediaUploader.open();
  });

});
</script>
<?php
  /* Add the media uploader script */
  function my_media_lib_uploader_enqueue() {
    wp_enqueue_media();
    wp_register_script( 'media-lib-uploader-js', plugins_url( 'media-lib-uploader.js' , __FILE__ ), array('jquery') );
    wp_enqueue_script( 'media-lib-uploader-js' );
  }
  add_action('admin_enqueue_scripts', 'my_media_lib_uploader_enqueue');
?>
<?php 
//post data
if($_POST['submit'])
{
	 $advTitle = $_POST['title'];
	 $advContent = $_POST['content'];
	 $advImage = $_POST['image'];
	 $advCategory = $_POST['category'];
	 $advShortcode = $_POST['shortcode'];
	 global $wpdb;
		$insert = "INSERT INTO `ibm_advertisement` (`id`,`title`,`content`,`image`,`shortcode`,`category`) values ('','".$advTitle."', '".$advContent."', '".$advImage."', '".$advShortcode."','".$advCategory."')";

	if($wpdb->query($insert)){echo "inserted successfully";echo "<script>location.reload();</script>";}
}
}