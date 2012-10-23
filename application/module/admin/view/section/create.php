<form action="<?php echo ADMIN_HTML_ROOT . 'section/create'; ?>" method="post">
    <fieldset>
        <legend>Create section</legend>
        <dl>
            <dt>Name:</dt><dd><input type="text" name="name" size="50" /></dd>
            <dt> Status:</dt><dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="content"></textarea></dd>
            <dt> Chapter:</dt>
            <dd>
                <select name='chapter_id'>
                    <?php
                    foreach ($chapters as $chapter) {
                        echo "<option value='" . $chapter['id'] . "'>" . $chapter['name'] . '</option>';
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
echo CKEDITOR::ckReplaceEditor_Full('content');
