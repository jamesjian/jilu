<form action="<?php echo ADMIN_HTML_ROOT . 'chapter/create'; ?>" method="post">
    <fieldset>
        <legend>Create chapter</legend>
        <dl>
            <dt>Name:</dt><dd><input type="text" name="name" size="50" /></dd>
            <dt> Status:</dt><dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Abstract: </dt><dd><textarea cols="10" rows="30" name="abstract"></textarea></dd>
            <dt> Chapter:</dt>
            <dd>
                <select name='book_id'>
                    <?php
                    foreach ($books as $book) {
                        echo "<option value='" . $book['id'] . "'>" . $book['name'] . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt> </dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('abstract');
