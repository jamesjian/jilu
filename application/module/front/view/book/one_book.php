<?php
/**
 * left ads                     tag cloud
 *  article                        right ads
 * related articles                latest
 *                              hottest
 */
?>
<div class='zx-front-left'>			
    <div class='zx-front-left1'>
        <?php
        if ($book) {
            echo $book['author_name'], BR;
            ?>
            <article>
                <header>
                    <h1 class="zx-front-article-title">
                        <?php
                        echo $book['name'], BR;
                        ?>
                    </h1>
                </header>
                <section>
                    logo:<img src="<?php echo $book['image'];?>" />
                    Character:
                    Name: <?php echo $book['character_name'];?>
                    Birth: <?php echo $book['character_birthday'];?>
                    Relationship: <?php echo $book['character_relationship'];?>
                    Region: <?php echo $book['character_region_name'];?>
                    <div class="zx-front-article-content">
                        <?php
                        echo $book['abstract'], BR;
                        ?>
                    </div>
                </section>
            </article>
            <?php
        }
        ?>
    </div>
    <div class='zx-front-left2'>
        <?php
        if ($chapters) {
        ?>
        <ul>
        <?php
        foreach ($chapters as $chapter) {
            $link = FRONT_HTML_ROOT . 'chapter/content/' .  $chapter['id'];
        ?>
            <li><a href="<?php echo $link;?>"><?php echo $chapter['name'];?></a></li>
        <?php
        }//foreach
        ?>
        </ul>
        <?php
        } //if
        ?>
    </div>
    
    <div class='zx-front-left3'>
        <?php
        if ($comments) {
        ?>
        <ul>
        <?php
        foreach ($comments as $comment) {
            //list titles first, then can expand them because comment might be very long
        ?>
            <li><a href="<?php echo $comment['id'];?>"><?php echo $comment['title'];?></a></li>
            <li><?php echo $comment['content'];?></li>
        <?php
        }//foreach
        ?>
        </ul>
        <?php
        } //if
        ?>
    </div>
    <div class='zx-front-left4'>
        <form action="book comment" method="post">
            <label for="title">Title</label>
            <input type="text" name="title" />
            <label for="comment">Comment</label>
            <textarea name="comment" cols="10" rows="10"></textarea>
            <input type="submit" name="submit" value="submit" />
        </form>
    </div>
</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
    </div>	
    <div class="zx-front-right2">
    </div>    
    <div class='zx-front-right3'>
    </div>
    <div class='zx-front-right4'>
    </div>
    <div class='zx-front-right5'>
    </div>
</div>
