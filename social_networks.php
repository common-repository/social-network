<?php
/*
Plugin Name: social Network And viewers
Plugin URI: http://twitter.com/walid_naceri
Description: Best and the simple way to add Automatically in each posts a social network Contact. Also it showes how many person seen your post.
Version: 1.0
Author: Walid Naceri
Author URI:http://coder-dz.com
*/


add_action('admin_menu','sociel_func');
function sociel_func(){
add_menu_page('Sociel Network Manager','Sociel Network','manage_options',__FILE__,'func_sociel');
}

function func_sociel($sociel_network){
global $wpdb;
$wpdb->get_results("CREATE TABLE IF NOT EXISTS sociel_network(Twitter varchar(300) not null,
		    Facebook varchar(300) not null,Email varchar(300) not null)");

$wpdb->get_results("CREATE TABLE IF NOT EXISTS views(id_post int(10) not null,
		    viewer int(10) not null)");

$make_sure = $wpdb->query("SELECT * FROM sociel_network");
if($make_sure==false){
$wpdb->get_results("INSERT INTO sociel_network(Twitter,Facebook,Email) values('http://twitter.com/walid_naceri','http://facebook.com','walid.naceri@yahoo.com')");
}

$get_information = $wpdb->get_results("SELECT * FROM sociel_network");

foreach($get_information as $info)
{
$info = array("Twitter"=>"$info->Twitter","Facebook"=>"$info->Facebook","Email"=>"$info->Email");
$info = wp_parse_args((array) $info,$info);
}
wp_enqueue_script('wiwi_mimi',plugins_url('includes/update.js',__FILE__),array('jquery'));
?>


<br/><br/><br/><br/><br/><br/>
<center>
Twitter (put your twitter account link)  : <input type="text" id="Twitter" name="Twitter" Value="<?php echo $info['Twitter']?>" size="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br/><br/><br/>
Facebook (put your twitter account link) : <input type="text" id="Facebook" name="Facebook" Value="<?php echo $info['Facebook']?>" size="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br/><br/><br/>
Email : <input type="text" id="Email" name="Email" Value="<?php echo $info['Email']?>" size="50">
<br/><br/>
<input type="button" value="Save"  class="button-primary">
</center>

</html>
<?php
if($_POST['Twitter'] || $_POST['Facebook'] || $_POST['Email'] ){
$Twitter = $_POST['Twitter'];
$Facebook = $_POST['Facebook'];
$Email = $_POST['Email'];
$wpdb->get_results("UPDATE sociel_network set Twitter='$Twitter',Facebook='$Facebook',Email='$Email'");
}
echo "
<br/><br/><br/><br/><br/><br/><br/><br/>
<b><font color=red>* How to Use?</font></b>
<br/><br/>
<b>- Just Complete the fields after that the Sociel Network icons shows up Automatically in all your posts.</b>
<br/>
<b>- If you want a particular incons hidden just leave the field empty.</b>
<br/>
<b>- If you have any problem please send me an email at walid.naceri@yahoo.com</b>
<br/>
<b>- For more free plugins and scripts Follow me on Twitter @walid_naceri</b>
<br/>
<b>- I programme for free for you :) if you want to donate Paypal: walid.naceri@yahoo.com</b>
";
}// Main Function

add_action('the_content','sociel_contact');

function sociel_contact($content){
global $wpdb;
$sociel = $wpdb->get_results("SELECT * FROM sociel_network");
foreach($sociel as $sociel_networks)
{
$sociel_networks = array("Twitter"=>"$sociel_networks->Twitter",
		         "Facebook"=>"$sociel_networks->Facebook",
			 "Email"=>"$sociel_networks->Email");
$sociel_networks = wp_parse_args((array) $sociel_networks,$sociel_networks);
}

if(is_single())
{
$id_post = get_the_id();
$get_id  = $wpdb->query("SELECT * FROM views where id_post='$id_post'");
if($get_id=='0'){
$wpdb->get_results("INSERT INTO views(id_post,viewer) values($id_post,1)");
}
else{
$wpdb->get_results("UPDATE views SET viewer=viewer+1 where id_post='$id_post'");
}

$get_viewer  = $wpdb->get_results("SELECT * FROM views where id_post='$id_post'");

foreach($get_viewer as $viewer){ 
$seen = $viewer->viewer;
}

echo $content;
echo "<b>You can Contact me on :</b>";
echo "<br/>";
if($sociel_networks[Facebook]==""){
echo "";
}else{
?>
<a href="<?php echo $sociel_networks[Facebook];?>" target='_blank'><img src='wp-content/plugins/social_networks/images/facebook.png'></img></a>
<?php
}if($sociel_networks[Twitter]==""){
echo "";
}else{
?>
<a href="<?php echo $sociel_networks[Twitter]; ?>"  target='_blank'><img src='wp-content/plugins/social_networks/images/twitter.png'></img></a>
<?php
}if($sociel_networks[Email]=="")
{
echo "";
}else{
?>
<a href='#' size="<?php echo $sociel_networks[Email];?>" id='email'><img src='wp-content/plugins/social_networks/images/email.png'></img></a>
<?php
}
?>
<br/><br>

<b>View : <font color='red'><?php echo $seen;?> </font>Person</b>

<?php

}
}//content FUnction

add_action('get_header','just_header');

function just_header(){
wp_enqueue_script('mimi',plugins_url('includes/get_email.js',__FILE__),array('jquery'));
}
add_action('delete_post', 'delete_post' );
function delete_post($postId) { 
global $wpdb;
$wpdb->query("DELETE FROM views where id_post='$postId'");
}
?>

