<?php
\Zx\Message\Message::show_message();
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'character/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($character_list) {
$link_prefix = ADMIN_HTML_ROOT . "character/retrieve/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_name = $link_prefix . 'name' . $link_postfix;
$link_province_name = $link_prefix . 'province_name' . $link_postfix;
$link_city_name = $link_prefix . 'city_name' . $link_postfix;
$link_district_name = $link_prefix . 'district_name' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>ID</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_name;?>'>Name</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_province_name;?>'>Province</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_city_name;?>'>City</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_district_name;?>'>District</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($character_list as $character) {
	$character_id = $character['id'];
	$link_delete = ADMIN_HTML_ROOT . 'character/delete/' . $character_id;
	$link_update = ADMIN_HTML_ROOT . 'character/update/' . $character_id;
?>
<tr>
	<td><?php echo $character['id'];?></td>
	<td><?php echo $character['name'];?></td>
	<td><?php echo $character['province_name'];?></td>
	<td><?php echo $character['city_name'];?></td>
	<td><?php echo $character['district_name'];?></td>
        <td><?php echo $character['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_character">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'character/retrieve/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




