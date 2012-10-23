<form action="<?php echo ADMIN_HTML_ROOT . 'section/update'; ?>" method="post">
    <fieldset>
        <legend>Update section</legend>
        <dl>
            <dt>    
            Name:</dt><dd><input type="text" name="name" size="50" value="<?php echo $section['name']; ?>"/></dd>
            <dt>    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php echo $section['content']; ?></textarea></dd>
            <dt>    Chapter:</dt><dd><select name='chapter_id'>
                    <?php
                    foreach ($chapters as $chapter) {
                        echo "<option value='" . $chapter['id'] . "'";
                        if ($section['chapter_id'] == $chapter['id']) {
                            echo " selected";
                        }
                        echo ">" . $chapter['name'] . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt>    Status:</dt>
            <dd>
                <?php
                if ($section['status'] == '1') {
                    $active_checked = ' checked';
                    $inactive_checked = '';
                } else {
                    $inactive_checked = ' checked';
                    $active_checked = '';
                }
                ?>
                <input type="radio" name="status" value="1" <?php echo $active_checked; ?>/>Active    
                <input type="radio" name="status" value="0"  <?php echo $inactive_checked; ?>/>Inactive     
            </dd>
            <dt> <input type="hidden" name="id" value="<?php echo $section['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
