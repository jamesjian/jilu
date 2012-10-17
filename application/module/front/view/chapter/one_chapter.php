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
        if ($chapter) {
            echo $book['author_name'], BR;
            ?>
        <a href="book link"><?php echo $book['name'];?></a>
            <article>
                <header>
                    <h1 class="zx-front-article-title">
                        <?php
                        echo $chapter['name'], BR;
                        ?>
                        <?php 
                        if ($previous_chapter) {
                        ?>                        
                        <a href="previous_chapter link">previous chapter <?php echo $previous_chapter['name'];?></a>
                        <?php       
                        }
                        ?>
                        <?php 
                        if ($next_chapter) {
                        ?>                                            
                        <a href="next chapter link">next chapter <?php echo $next_chapter['name'];?></a>
                        <?php
                        }
                        ?> 
                    </h1>
                </header>
                <section>
                    logo:<img src="<?php echo $chapter['image'];?>" />
                    <div class="zx-front-article-content">
                        <?php
                        echo $chapter['abstract'], BR;
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
        if ($sections) {
        ?>
        <ul>
        <?php
        foreach ($sections as $section) {
            $link = FRONT_HTML_ROOT . 'section/content/' .  $sections['id'];
        ?>
            <li><a href="<?php echo $link;?>"><?php echo $section['name'];?></a></li>
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
        <form action="chapter comment" method="post">
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
