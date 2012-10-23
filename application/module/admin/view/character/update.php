<form action="<?php echo ADMIN_HTML_ROOT . 'character/update'; ?>" method="post">
    <fieldset>
        <legend>Update character</legend>
        <dl>
            <dt>    
            Name:</dt><dd><input type="text" name="name" size="50" value="<?php echo $character['name']; ?>"/></dd>
            <dt>Birthday:</dt><dd><input type="text" name="birthday" size="50" value="<?php echo $character['birthday']; ?>"/></dd>
            <dt>    Region:</dt>
            <dd>
                <select name='province_id'>
                    <?php
                    foreach ($provinces as $province) {
                        echo "<option value='" . $province['id'] . "'";
                        if ($character['province_id'] == $province['id']) {
                            echo " selected";
                        }
                        echo ">" . $province['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name='city_id'>
                    <?php
                    foreach ($cities as $city) {
                        echo "<option value='" . $city['id'] . "'";
                        if ($character['city_id'] == $city['id']) {
                            echo " selected";
                        }
                        echo ">" . $city['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name='district_id'>
                    <?php
                    foreach ($districts as $district) {
                        echo "<option value='" . $district['id'] . "'";
                        if ($character['district_id'] == $district['id']) {
                            echo " selected";
                        }
                        echo ">" . $district['name'] . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt>    Status:</dt>
            <dd>
                <?php
                if ($character['status'] == '1') {
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
            <dt> <input type="hidden" name="id" value="<?php echo $character['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
