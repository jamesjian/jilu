<form action="<?php echo ADMIN_HTML_ROOT . 'character/create'; ?>" method="post">
    <fieldset>
        <legend>Create character</legend>
        <dl>
            <dt>Name:</dt><dd><input type="text" name="name" size="50" /></dd>
            <dt>Birthday:</dt><dd><input type="text" name="birthday" size="50" /></dd>
            <dt> Region:</dt>
            <dd>
                <select name='province_id'>
                    <?php
                    foreach ($provinces as $province) {
                        echo "<option value='" . $province['id'] . "'>" . $province['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name='city_id'>
                    <?php
                    foreach ($cities as $city) {
                        echo "<option value='" . $city['id'] . "'>" . $city['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name='district_id'>
                    <?php
                    foreach ($districts as $district) {
                        echo "<option value='" . $district['id'] . "'>" . $district['name'] . '</option>';
                    }
                    ?>
                </select>                
            </dd>
            
            <dt> </dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>

