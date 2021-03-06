hostname = window.location.hostname;
HTMLROOT = '/';
ADMINHTMLROOT = '/mem/';
if (hostname == 'www' || hostname =='') {
    HTMLROOT = 'http://www./';
}
mem = {
    /**
     * template for all delete_XXX click event
     */
    confirm_delete_template: function(e){
        if (confirm('Are you sure to delete this ' + e.data.name + '?') ==  true)  {
            return true;
        } else {
            return false;
        }
    },
    /**
     * template for all view_XXX_link click event
     */
    show_template: function(e){
        //console.log(e.data.title);
        var url = $(this).attr('href');
        $('#dialog').dialog({
            title: e.data.title
        });
        $('#dialog').load(url);
        $('#dialog').dialog('open');
        return false;
    },
    /**
     * @param:  event has table name infomation
     * table_name is the table name in which status will be changed
     * such as blog, company, user, and so on
     */
    change_status: function(event){
        //console.log('aaaaaa');
        url = HTMLROOT + 'mem/'+ event.data.table_name + '/change_status';
        id = $(this) . attr('id');
        id = parseInt(id.substr(7));  //get line number 'status_XXX'
        status = $(this).val();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id, 
                status: status
            },
            dataType:  'json',
            success: function(data){
                //console.log(data.changed);
                if (data.changed == true){
                    //no action
                } else {
                }
            }
        });   
    },    
    clear_image: function(event)
    {
        var delete_image_id = $(this).attr('id');
        var url = $(this).attr('href');
        image_id = event.data.image_id;
        //console.log(image_id);
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            dataType:  'json',
            success: function(data){
                if (data.result) {
                    //remove image
                    $(image_id).css('display','none');
                    $(image_id).css('visibility','hidden');
                    //remove delete link as well
                    $('#'+delete_image_id).css('display','none');
                    $('#'+delete_image_id).css('visibility','hidden');
                }
                //transaction.get_buyer_location_list();
            }
        });   
        return false;
    },  
    /**
     * bulletin/discount/secondhand has 3 images, goods have 5 images, we will delete them seperately,
     * we need to pass image index(1,2,3,4, 5) to controller and view
     */
    clear_single_image: function()
    {
        var delete_image_id = $(this).attr('id');
        //console.log(delete_image_id);
        var image_index = parseInt(delete_image_id.substr(15)); //id="delete_image_id2"
        //console.log(image_index);
        var url = $(this).attr('href');
        image_id='#image'+image_index;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                image_index: image_index
            },
            dataType:  'json',
            success: function(data){
                if (data.result) {
                    //remove image
                    $(image_id).css('display','none');
                    $(image_id).css('visibility','hidden');
                    //remove delete link as well
                    $('#'+delete_image_id).css('display','none');
                    $('#'+delete_image_id).css('visibility','hidden');
                }
                //transaction.get_buyer_location_list();
            }
        });   
        return false;
    }, 
/**
     * when province changed, city options will be changed
     */
    get_city_options_by_province_id: function(){
        var province_id = $(this).val();
        var url = HTMLROOT + 'front//get_city_options_by_province_id/' + province_id;
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            dataType:  'html',
            success: function(data){
                city_id = '#city_id';
                $(city_id).html(data);
            }
        });
    
    },    
/**
     * when city changed, district options will be changed
     */    
    get_district_options_by_city_id: function(){
        var city_id = $(this).val();
        var url = HTMLROOT + 'front/region/get_district_options_by_city_id/' + city_id;
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            dataType:  'html',
            success: function(data){
                district_id = '#district_id';
                $(district_id).html(data);
            }
        });
    
    },    
    /**
     * when province_id changed, city  and district options will be changed 
     */
    get_city_and_district_options_by_province_id: function(){
        var province_id = $(this).val();
        var url = HTMLROOT + 'front/region/get_city_and_district_options_by_province_id/' + province_id;
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            dataType:  'json',
            success: function(data){
                city_id = '#city_id';
                district_id = '#district_id';
                $(city_id).html(data.city_options);
                $(district_id).html(data.district_options);
            }
        });
    
    },        
    /**
     * initialize all show event handlers
     */
    init_view_links: function(){
        
        $('.view_blog_link').bind('click', {
            title: 'Blog'
        }, mem.show_template);
       
        $('.view_article_link').bind('click', {
            title: 'Article'
        }, mem.show_template);
       
    },    
    init_clear_image_links: function(){
        $('.clear_article1_image').bind('click', {
            image_id:'#image'
        }, mem.clear_image);
         
        $('.clear_blog_image').bind('click', {
            image_id:'#image'
        }, mem.clear_image);        
    },
    /**
     * initialize all delete event handlers
     */
    init_delete_links: function(){
        $('.delete_article_cat').bind('click', {
            name: 'article category'
        }, mem.confirm_delete_template);
        $('.delete_article').bind('click', {
            name: 'article'
        }, mem.confirm_delete_template);
        $('.delete_page_cat').bind('click', {
            name: 'page category'
        }, mem.confirm_delete_template);
        $('.delete_page').bind('click', {
            name: 'page'
        }, mem.confirm_delete_template);
    },    
    init_change_status_links: function(){
        $('.category_status').bind('change', {
            table_name: 'category'
        },mem.change_status);
        $('.blog_status').bind('change', {
            table_name: 'blog'
        },mem.change_status);
    },	
    test: function(){
        var url = '/z2/public/mem/article/show';
        $.ajax({
            type: "POST",
            url: url,
            data: {id: 111},
            dataType:  'html',
            success: function(data){
                // index.open_action_dialog(data,title)
                $('#test_div').html(data);
            }
        });

    },
    bind_events: function(){
        mem.unbind_events();  
        //$('#delete_').click(mem.test);
        mem.init_view_links();
        //mem.init_clear_image_links();	  
        mem.init_delete_links();	  
        $('#province_id').bind('change', index.get_city_and_district_options_by_province_id);   
        $('#city_id').bind('change', index.get_district_options_by_city_id);   
    },
    unbind_events: function(){
        
    },
    init: function(){
        //console.log('aaa');
        mem.bind_events();
        $('tr:odd').css('background-color', '#ffffee');		
        /*
        $('#dialog').dialog({
            autoOpen: false,
            height: 500,
            width: 900,
            modal: true,
            buttons:{
                'close': function(){
                    $(this).dialog('close');
                }
            }		
        });
        */
    }
}
$(document).ready(mem.init);
