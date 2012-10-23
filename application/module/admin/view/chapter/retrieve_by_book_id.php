<?php
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'chapter/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($book_list) {
$link_prefix = ADMIN_HTML_ROOT . "chapter/retrieve_by_book_id/$book_id/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_name = $link_prefix . 'name' . $link_postfix;
$link_book_name = $link_prefix . 'book_name' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_name;?>'>name</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_book_name;?>'>book</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($chapter_list as $chapter) {
	$chapter_id = $chapter['id'];
	$link_delete = ADMIN_HTML_ROOT . 'chapter/delete/' . $chapter_id;
	$link_update = ADMIN_HTML_ROOT . 'chapter/update/' . $chapter_id;
?>
<tr>
	<td><?php echo $chapter['id'];?></td>
	<td><?php echo $chapter['name'];?></td>
	<td><?php echo $chapter['book_name'];?></td>
        <td><?php echo $chapter['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_chapter">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'chapter/retrieve_by_book_id/' . $chapter_id;	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




