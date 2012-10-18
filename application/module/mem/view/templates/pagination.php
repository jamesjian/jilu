<?php

//$current_page = 1;
if ($current_page != 1) {
    //first
    $link = $link_prefix . '1' . $link_postfix;
    echo "<a href='$link'>First</a>";
    //previous
    $link = $link_prefix . ($current_page - 1) . $link_postfix;
    echo "<a href='$link'>Prev</a>";
}

//middle
if ($num_of_pages <= NUM_OF_ITEMS_IN_PAGINATION) {
    //display all page numbers
    for ($i = 1; $i <= $num_of_pages; $i++) {
        $link = $link_prefix . $i . $link_postfix;
        echo "<a href='$link'";
        if ($i == $current_page) {
            echo " class='zx-admin-current-page'";
        }
        echo ">$i</a>";
    }
} else {
    //display page numbers around current page
    $difference = intval((NUM_OF_ITEMS_IN_PAGINATION - 1) / 2);
    if (($current_page - $difference) < 0) {
        $start = 1;
    } else {
        $start = $current_page - $difference;
    }
    if (($current_page + $difference) > $num_of_pages) {
        $end = $num_of_pages;
    } else {
        $end = $current_page + $difference;
    }
    for ($i = $start; $i <= $end; $i++) {
        $link = $link_prefix . $i . $link_postfix;
        echo "<a href='$link'";
        if ($i == $current_page) {
            echo " class='zx-admin-current-page'";
        }
        echo ">$i</a>";
    }
}
if ($current_page != $num_of_pages) {
    //next
    $link = $link_prefix . ($current_page + 1) . $link_postfix;
    echo "<a href='$link'>Next</a>";
    //last
    $link = $link_prefix . $num_of_pages . $link_postfix;
    echo "<a href='$link'>Last</a>";
}
