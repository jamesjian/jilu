<?php
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'section/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($chapter_list) {
$link_prefix = ADMIN_HTML_ROOT . "section/retrieve_by_chapter_id/$chapter_id/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_name = $link_prefix . 'name' . $link_postfix;
$link_chapter_name = $link_prefix . 'chapter_name' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_name;?>'>name</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_chapter_name;?>'>chapter</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($section_list as $section) {
	$section_id = $section['id'];
	$link_delete = ADMIN_HTML_ROOT . 'section/delete/' . $section_id;
	$link_update = ADMIN_HTML_ROOT . 'section/update/' . $section_id;
?>
<tr>
	<td><?php echo $section['id'];?></td>
	<td><?php echo $section['name'];?></td>
	<td><?php echo $section['chapter_name'];?></td>
        <td><?php echo $section['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_section">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'section/retrieve_by_chapter_id/' . $section_id;	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




