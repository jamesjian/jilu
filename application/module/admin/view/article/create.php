<form action="<?php echo ADMIN_HTML_ROOT . 'article/create';?>" method="post">
    Title:<input type="text" name="title" size="50" />
    Description: <textarea cols="10" rows="30" name="content"></textarea>
    Category:<select name='cat_id'>
        <?php 
        foreach ($cats as $cat) {
            echo "<option value='" . $cat['id'] . "'>" . $cat['title'] . '</option>';
        }
        ?>
    </select>
    <input type="submit" name="submit" value="create" />
</form>
<a href="<?php echo \Zx\Transaction\Tool::get_current_admin_page();?>" />Cancel</a>
<?php 
	include_once(DOCROOT . 'js/ckeditor/j_ckedit.class.php');
	echo CKEDITOR::ckHeader();
	echo CKEDITOR::ckReplaceEditor_Full('content');   
