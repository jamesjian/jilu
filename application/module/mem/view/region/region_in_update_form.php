<?php
?>
<select name="province_id" id="province_id">
    <option value="0">No</option>
    <?php 
    foreach ($provinces as $province) {
    ?>
    <option value="<?php echo $province['id'];?>"<?php 
    if ($character_region['province_id'] == $province['id']) {
        echo ' selected';
    }
    ?>><?php echo $province['name'];?></option>
    <?php
    }
    ?>
</select>
<select name="city_id" id="city_id">
    <option value="0">No</option>
    <?php 
    foreach ($cities as $city) {
    ?>
    <option value="<?php echo $city['id'];?>"<?php 
    if ($character_region['city_id'] == $city['id']) {
        echo ' selected';
    }
    ?>><?php echo $city['name'];?></option>
    <?php
    }
    ?>
</select>
<select name="district_id" id="district_id">
    <option value="0">No</option>
    <?php 
    foreach ($districts as $district) {
    ?>
    <option value="<?php echo $district['id'];?>"<?php 
    if ($character_region['district_id'] == $district['id']) {
        echo ' selected';
    }
    ?>><?php echo $district['name'];?></option>
    <?php
    }
    ?>
</select>
   
