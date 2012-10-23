<form action="<?php echo ADMIN_HTML_ROOT . 'chapter/update'; ?>" method="post">
    <fieldset>
        <legend>Update chapter</legend>
        <dl>
            <dt>    
            Name:</dt><dd><input type="text" name="name" size="50" value="<?php echo $chapter['name']; ?>"/></dd>
            <dt>    Content: </dt><dd><textarea cols="10" rows="30" name="abstract"><?php echo $chapter['abstract']; ?></textarea></dd>
            <dt>    Abstract:</dt><dd><select name='book_id'>
                    <?php
                    foreach ($books as $book) {
                        echo "<option value='" . $book['id'] . "'";
                        if ($chapter['book_id'] == $book['id']) {
                            echo " selected";
                        }
                        echo ">" . $book['name'] . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt>    Status:</dt>
            <dd>
                <?php
                if ($chapter['status'] == '1') {
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
            <dt> <input type="hidden" name="id" value="<?php echo $chapter['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('abstract');
