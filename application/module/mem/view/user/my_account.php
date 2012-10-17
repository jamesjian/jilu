<?php
//this is splash page of the account
?>
<!--Start Right Side1-->
<div class="grid right-side1" >
    <?php
    if (isset($errors)) {
        echo "<div  class='errormessage'>";
        foreach ($errors as $key => $message) {
            echo $message, BR;
        }
        echo "</div>";
    }
    App_Session::display_message();
    ?>
    <div class="mod_title">
        <h4>账户管理中心</h4>
        <div class="top_right_message">
<a class="big_title" href="<?php echo MEMHTMLROOT . 'contactus/ad_inquiry'; ?>">付费广告咨询</a>    
        </div>
    </div>
    <div style="clear:both; height:0;"></div>
    <div class="mod_content my-account-body">
        <table>
            <tr>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'company/list'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-business.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'company/list'; ?>">生意<?php if ($user->num_of_company > 0) echo '(' . $user->num_of_company . ')'; ?>
                    </a>
                </td>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'goods/list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-blog.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'goods/list/page/1'; ?>">商品或服务<?php if ($user->num_of_blog > 0) echo '(' . $user->num_of_blog . ')'; ?></a>            
                </td>
            </tr>
            <tr>
                <td class="title_small"><span class="small_title">您可以在这里创建多个生意</span></td>
                <td class="title_small"><span class="small_title">您可以在这里发布您的商品或服务</span></td>
            </tr>
            <tr>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'bulletin/list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-ad.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">    
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'bulletin/list/page/1'; ?>">广告<?php if ($user->num_of_bulletin > 0) echo '(' . $user->num_of_bulletin . ')'; ?></a>            
                </td>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'message/my_inbound_message_list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-message.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">    
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'message/my_inbound_message_list/page/1'; ?>">短信<?php if ($user->num_of_message > 0) echo '(' . $user->num_of_message . ')'; ?></a>            
                </td>
            </tr>
            <tr>
                <td class="title_small"><span class="small_title">您可以在这里发布广告信息</span></td>
                <td class="title_small"><span class="small_title">您可以在这里查看客户发给您的短信并回复</span></td>
            </tr>
            <tr>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'discount/list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-discount.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'discount/list/page/1'; ?>">特价<?php if ($user->num_of_discount > 0) echo '(' . $user->num_of_discount . ')'; ?></a>            
                </td>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'secondhand/list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-secondhand.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'secondhand/list/page/1'; ?>">二手产品<?php if ($user->num_of_secondhand > 0) echo '(' . $user->num_of_secondhand . ')'; ?></a>            
                </td>                
            </tr>
            <tr>
                <td class="title_small"><span class="small_title">您可以在这里发布特价信息</span></td>
                <td class="title_small"><span class="small_title">您可以在这里发布二手产品信息</span></td>
            </tr>
            <tr>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'requirement/list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-request.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'requirement/list/page/1'; ?>">我的需求<?php if ($user->num_of_requirement > 0) echo '(' . $user->num_of_requirement . ')'; ?></a>            
                </td>
                <td rowspan="2" class="icon">
                    <a href="<?php echo MEMHTMLROOT . 'blog/my_blog_list/page/1'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-account.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'blog/list/page/1'; ?>">博客<?php if ($user->num_of_blog > 0) echo '(' . $user->num_of_blog . ')'; ?>
                </td>
            </tr>
            <tr>
                <td class="title_small"><span class="small_title">您可以在这里发布您的需求信息</span></td>
                <td class="title_small"><span class="small_title">您可以在这里发布博客</span></td>
            </tr>
            <tr>
                <td rowspan="2" class="icon title_small_no_border">
                    <a href="<?php echo MEMHTMLROOT . 'user/change_profile'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-request.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'user/change_profile'; ?>">更新账户</a>            
                </td>
                <td rowspan="2" class="icon title_small_no_border">
                    <a href="<?php echo MEMHTMLROOT . 'contactus/ad_inquiry'; ?>">
                        <img src="<?php echo HTMLIMAGEROOT . 'fyl_images/icon-request.png'; ?>" />
                    </a>
                </td>
                <td class="title_big">
                    <a class="big_title" href="<?php echo MEMHTMLROOT . 'contactus/ad_inquiry'; ?>">付费广告咨询</a>            
                </td>
            </tr>
            <tr>
                <td class="title_small_no_border"><span class="small_title">如果您的联系方式有所变化， 请及时更新</span></td>
                <td class="title_small_no_border"><span class="small_title">您在这里可以咨询我们的付费广告业务</span></td>
            </tr>

        </table>



        <span>
            <!--
            说明：<br />
            1. 生意可以是任何商业实体（pty ltd, sole trader, partnership), 生意一经创建， 将永久有效。 您可以删除或“隐藏”您的生意。<br />
            2. 广告、优惠信息、需求、二手信息和博客可以属于您创建的某一个生意， 也可以不属于任何生意。 如果属于您创建的某个生意， 
            浏览者将可以通过更多相关链接查看您提供的所有信息。 <br />
            3. 广告、优惠信息、需求和二手信息一经发布即可在网站上查到您输入的信息。 每条信息的有效期为<?php echo ACTIVE_DAYS_OF_THREAD; ?>天， 有效期内， 如果您想终止
            显示这条信息， 可以删除信息或“隐藏”信息。<?php echo ACTIVE_DAYS_OF_THREAD; ?>天后， 如果您希望继续显示该条信息， 可以“延长”<?php echo ACTIVE_DAYS_OF_THREAD; ?>天有效期， 
            延长次数不限。<br />
            4.  广告、优惠信息、需求和二手信息的可以保留的信息总条数为100条， 请您删除过期的信息， 以便增加新的信息。 如果您想加入更多的信息， 
            请与客户服务部门联系。<br /> 
            5. 您的博客和短信条数不限。 <br />
            -->
        </span>    
    </div>
</div>
<!--End Right Side1-->