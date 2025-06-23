// blogs-----------------------------------------------------------------------------------
if(modules=='categories' && theme=='blog')
{
  var categories_table;
    $(document).ready(function() {

        //datatables
        categories_table = $('#categoriestable').DataTable({
            'ordering':false,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"blog/categories/categories_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


    });

    function add_categories()
    {
        $('[name="method"]').val('insert');
        $('#categories_form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Category'); // Set Title to Bootstrap modal title
       
            
    }

    function edit_categories(id)
    {
        $('[name="method"]').val('update');
        $('#categories_form')[0].reset(); // reset form on modals
        
        //Ajax Load data from ajax
        $.ajax({
             url : base_url+"blog/categories/categories_edit/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               
                $('[name="id"]').val(data.id);
                $('[name="category_name"]').val(data.category_name);
                $('[name="slug"]').val(data.slug);
                $('[name="description"]').val(data.description);
                $('[name="keywords"]').val(data.keywords);
                
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Category'); // Set title to Bootstrap modal title

            },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }
        });
    }

    function categories_reload_table()
    {
        categories_table.ajax.reload(null,false); //reload datatable ajax 
    }



    function delete_categories(id)
    {
        if(confirm('Are you sure delete this categories?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"blog/categories/categories_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#categories_form').modal('hide');
                    categories_reload_table();
                    toastr.success('Category deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                    }
            });

        }
    }


        $(document).ready(function (e){
            $("#categories_form").on('submit',(function(e){
                e.preventDefault();

                var category_name=$('#category_name').val();
                
                if(category_name==''){
                    toastr.error('Please enter category name');
                    return false;
                }

                             
                 $.ajax({
                    url: base_url+'blog/categories/create_categories',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btncategoriessave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btncategoriessave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btncategoriessave').html('Submit');
                      $('#btncategoriessave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            
                            toastr.success(obj.status);

                              $('#modal_form').modal('hide');
                              $('#categories_form')[0].reset();
                              
                              categories_table.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                           
                        }
                         else if(obj.result=='exe')
                        {
                            toastr.error(obj.status);
                        }
                        else
                        {
                            window.location.reload();
                        }
                    },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });
} 

if(modules=='subcategories' && theme=='blog')
{


  var subcategories_table;
    $(document).ready(function() {

       $.ajax({
        type: "GET",
        url: base_url+"ajax/get_category",
        data:{id:$(this).val()}, 
        beforeSend :function(){
      $('#category').find("option:eq(0)").html("Please wait..");
        },                         
        success: function (data) {
          /*get response as json */
           $('#category').find("option:eq(0)").html("Select Category");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#category').append(option);
         });  

          
        }
      });

        //datatables
        subcategories_table = $('#subcategoriestable').DataTable({
            'ordering':false,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"blog/subcategories/subcategories_list",
                "type": "POST",
                "data": function ( data ) {
                   
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


    });

    function add_subcategories()
    {
        $('[name="method"]').val('insert');
        $('#subcategories_form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Subcategory'); // Set Title to Bootstrap modal title
       
            
    }

    function edit_subcategories(id)
    {
        $('[name="method"]').val('update');
        $('#subcategories_form')[0].reset(); // reset form on modals
        
        //Ajax Load data from ajax
        $.ajax({
             url : base_url+"blog/subcategories/subcategories_edit/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               
                $('[name="id"]').val(data.id);
                $('[name="category"]').val(data.category);
                $('[name="subcategory_name"]').val(data.subcategory_name);
                $('[name="slug"]').val(data.slug);
                $('[name="description"]').val(data.description);
                $('[name="keywords"]').val(data.keywords);
                
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Subcategory'); // Set title to Bootstrap modal title

            },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }
        });
    }

    function subcategories_reload_table()
    {
        subcategories_table.ajax.reload(null,false); //reload datatable ajax 
    }



    function delete_subcategories(id)
    {
        if(confirm('Are you sure delete this subcategories?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"blog/subcategories/subcategories_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#subcategories_form').modal('hide');
                    subcategories_reload_table();
                    toastr.success('Subcategory deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                    }
            });

        }
    }


        $(document).ready(function (e){
            $("#subcategories_form").on('submit',(function(e){
                e.preventDefault();

                var category=$('#category').val();
                 var subcategory_name=$('#subcategory_name').val();
                
                if(category==''){
                    toastr.error('Please select category');
                    return false;
                }

                
                if(subcategory_name==''){
                    toastr.error('Please enter subcategory name');
                    return false;
                }

                             
                 $.ajax({
                    url: base_url+'blog/subcategories/create_subcategories',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend:function() { 

                        $('#btnsubcategoriessave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btnsubcategoriessave').attr('disabled',true);
                  
                    },
                    success: function(data){

                      $('#btnsubcategoriessave').html('Submit');
                      $('#btnsubcategoriessave').attr('disabled',false);
                              
                       var obj=jQuery.parseJSON(data);
                        if(obj.result=='true')
                        {
                            
                            toastr.success(obj.status);

                              $('#modal_form').modal('hide');
                              $('#subcategories_form')[0].reset();
                              
                              subcategories_table.ajax.reload(null,false);
                        }
                        else if(obj.result=='false')
                        {
                            toastr.error(obj.status);
                           
                        }
                         else if(obj.result=='exe')
                        {
                            toastr.error(obj.status);
                        }
                        else
                        {
                            window.location.reload();
                        }
                    },
                error:function(){
                      window.location.href=base_url+'admin/dashboard';
                    }

                });
            }));
        });
}

      
if(modules=='post' || modules=='pharmacy'){

if(pages=='index' || pages=='pending_post')
{
    var posts_table;
     //datatables
        posts_table = $('#posts_table').DataTable({
            'ordering':false,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy' :true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                 "url": base_url+"blog/post/posts_list",
                "type": "POST",
                "data": function ( data ) {
                   data.posts_type =$('#posts_type').val();
                },
                error:function(){
                      //window.location.href=base_url+'admin/dashboard';
                    }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],

        });


  

 function posts_reload_table()
    {
        posts_table.ajax.reload(null,false); //reload datatable ajax 
    }

    function change_status(id,type)
    {
        var stat= $('#status_'+id).prop('checked');

        if(stat==true)
        {
            var status=1;
        }
        else
        {
            var status=0;
        }
        $.post(base_url+"blog/post/change_status",{id:id,status:status,type:type},function(data){
            posts_reload_table();
        });

    }



    function delete_posts(id)
    {
        if(confirm('Are you sure delete this post?'))
        {
            // ajax delete data to database
            $.ajax({
                url : base_url+"blog/post/post_delete/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    posts_reload_table();
                    toastr.success('Post deleted successfully');
                },
                error:function(){
                     //window.location.href=base_url+'admin/dashboard';
                    }
            });

        }
    }
}
  
    if(pages=='add_post' || pages=='edit_post' || pages=='products'){

        $(document).ready(function(){
        $("#upload_image_btn").click(function(){        
            $("#avatar-image-modal").css('display','block');
            $("#avatar-image-modal").modal('show');
        });

        $.ajax({
        type: "GET",
        url: base_url+"ajax/get_category",
        data:{id:$(this).val()}, 
        beforeSend :function(){
      $('#category').find("option:eq(0)").html("Please wait..");
        },                         
        success: function (data) {
          /*get response as json */
           $('#category').find("option:eq(0)").html("Select Category");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#category').append(option);
         });  
          $('#category').val(category);
          
        }
      });

        $.ajax({
        type: "POST",
        url: base_url+"ajax/get_subcategory",
        data:{id:category}, 
        beforeSend :function(){
      $("#subcategory option:gt(0)").remove(); 
      $('#subcategory').find("option:eq(0)").html("Please wait..");

        },                         
        success: function (data) {
          /*get response as json */
           $('#subcategory').find("option:eq(0)").html("Select Subcategory");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#subcategory').append(option);
         });  

          $('#subcategory').val(subcategory);
          
        }
      });


         $('#category').change(function(){
      $.ajax({
        type: "POST",
        url: base_url+"ajax/get_subcategory",
        data:{id:$(this).val()}, 
        beforeSend :function(){
      $("#subcategory option:gt(0)").remove(); 
      $('#subcategory').find("option:eq(0)").html("Please wait..");

        },                         
        success: function (data) {
          /*get response as json */
           $('#subcategory').find("option:eq(0)").html("Select Subcategory");
          var obj=jQuery.parseJSON(data);
          $(obj).each(function()
          {
           var option = $('<option />');
           option.attr('value', this.value).text(this.label);           
           $('#subcategory').append(option);
         });  

          /*ends */
          
        }
      });
    });

         $("#add_post").validate({
            rules: {
                title: "required",
                category: "required",
                subcategory: "required"
            },
            messages: {
                title: "Please enter blog title",
                category: "Please select blog category",
                subcategory: "Please select blog subcategory"
             },
            submitHandler: function(form) {
                if($('#upload_image_url').val()==''){
                    $('#image-error').show();
                    $('#image-error').html('Please upload blog image');
                    return false;
                }
                 if($('#ck_editor_textarea_id').val()==''){
                    $('#content-error').show();
                    $('#content-error').html('Please enter blog content');
                    return false;
                }

                        $.ajax({
            url: base_url+'blog/post/create_post',
            data: $("#add_post").serialize(),
            type: "POST",
            beforeSend: function(){
                $('#post_btn').attr('disabled',true);
                $('#post_btn').html('<div class="spinner-border text-light" role="status"></div>');
               },
            success: function(res){
                $('#post_btn').attr('disabled',false);
                $('#post_btn').html('Post');
                    var obj = JSON.parse(res);
                    
                    if(obj.status===200)
                    {
                       window.location.href=base_url+'blog/pending-post';
                    }
                    else
                    {
                        toastr.error(obj.msg);
                    }   
            }
        });
                return false;
            }

        });


         $("#edit_post").validate({
            rules: {
                title: "required",
                category: "required",
                subcategory: "required"
            },
            messages: {
                title: "Please enter blog title",
                category: "Please select blog category",
                subcategory: "Please select blog subcategory"
             },
            submitHandler: function(form) {
                if($('#upload_image_url').val()==''){
                    $('#image-error').show();
                    $('#image-error').html('Please upload blog image');
                    return false;
                }
                 if($('#ck_editor_textarea_id').val()==''){
                    $('#content-error').show();
                    $('#content-error').html('Please enter blog content');
                    return false;
                }

                        $.ajax({
            url: base_url+'blog/post/update_post',
            data: $("#edit_post").serialize(),
            type: "POST",
            beforeSend: function(){
                $('#post_btn').attr('disabled',true);
                $('#post_btn').html('<div class="spinner-border text-light" role="status"></div>');
               },
            success: function(res){
                $('#post_btn').attr('disabled',false);
                $('#post_btn').html('Post');
                    var obj = JSON.parse(res);
                    
                    if(obj.status===200)
                    {
                       window.location.href=base_url+'blog/pending-post';
                    }
                    else
                    {
                        toastr.error(obj.msg);
                    }   
            }
        });
                return false;
            }

        });



        });

function remove_images(image_url,preview_image_url, row_id) {


                $('#remove_image_div_' + row_id).remove();
                    var total_array = $('#upload_image_url').val();
                    var arr = total_array.split(",");
                    var itemtoRemove = data.image_url;
                    arr.splice($.inArray(itemtoRemove, arr), 1);
                    $("#upload_image_url").val(arr);

                    var total_array1 = $('#upload_preview_image_url').val();
                    var arr1 = total_array1.split(",");
                    var itemtoRemove1 = data.image_url;
                    arr1.splice($.inArray(itemtoRemove1, arr1), 1);
                    $("#upload_preview_image_url").val(arr1);
               
} 

function remove_image(image_url,preview_image_url, row_id) {

    var url = base_url + 'blog/post/delete_image';

    $.ajax({
         type: 'post',
         url: url,
         dataType: 'json',

        data: {
            image_url: image_url,preview_image_url:preview_image_url
        },

        success: function(data) {
            if (data.html == 1) {
                $('#remove_image_div_' + row_id).remove();
                    var total_array = $('#upload_image_url').val();
                    var arr = total_array.split(",");
                    var itemtoRemove = data.image_url;
                    arr.splice($.inArray(itemtoRemove, arr), 1);
                    $("#upload_image_url").val(arr);

                    var total_array1 = $('#upload_preview_image_url').val();
                    var arr1 = total_array1.split(",");
                    var itemtoRemove1 = data.image_url;
                    arr1.splice($.inArray(itemtoRemove1, arr1), 1);
                    $("#upload_preview_image_url").val(arr1);
                 }
           }
   });
}

    }
}

if(modules=='home')
{
  if(pages=='index')
  {

        get_blogs(0);
         function get_blogs(load_more){

        if(load_more == 0){
         $('#page_no_hidden').val(1);
       }

     var page = $('#page_no_hidden').val(); 
     var keywords = $('#keywords').val(); 
     var category = $('#category').val(); 
     var tags = $('#tags').val(); 
     



     $.ajax({
       url:  base_url +'blog/home/get_blogs',
       type: 'POST',
       data: {
        page : page,
        keywords : keywords,
        category : category,
        tags : tags,
        },
      beforeSend:function(){ 
       // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>'); 
      },
      success: function(response){
        //$('#doctor-list').html(''); 
        if(response){
          var obj = $.parseJSON(response);
          if(obj.data.length >=1) {
          var html = '';
          $(obj.data).each(function(){ 
    
          html +='<div class="col-md-6 col-sm-12">'+
                    '<div class="blog grid-blog">'+
                        '<div class="blog-image">'+
                            '<a href="'+base_url+'blog/blog-details/'+this.slug+'"><img width="308" height="206" class="img-fluid" src="'+this.post_image+'" alt="Post Image"></a>'+
                        '</div>'+
                        '<div class="blog-content">'+
                            '<ul class="entry-meta meta-item">'+
                                '<li>'+
                                    '<div class="post-author">'+
                                        '<a href="'+this.preview+'"><img src="'+this.profileimage+'" alt="Post Author"> <span>'+this.name+'</span></a>'+
                                    '</div>'+
                                '</li>'+
                                '<li><i class="far fa-clock"></i> '+this.created_date+'</li>'+
                            '</ul>'+
                            '<h3 class="blog-title"><a href="'+base_url+'blog/blog-details/'+this.slug+'">'+this.title+'</a></h3>'+
                            '<p class="mb-0">'+this.description+'</p>'+
                        '</div>'+
                    '</div>'+
                '</div>';
       
        });

          if(obj.current_page_no == 1){    
            $("#blog-list").html(html);    
          }else{
            $("#blog-list").append(html);    
          }  

        }
        else
        {

        var html ='<div class="col-md-6 col-sm-12">'+
                  '<div class="blog grid-blog">'+
                  '<p>No Blogs Found</p>'+
                  '</div>'+
                  '</div>';

                  $("#blog-list").html(html);    
        }

                 

          if(obj.count == 0){
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }


          if(obj.current_page_no == 1 && obj.count < 5){
            $('page_no_hidden').val(1);
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }



          if(obj.total_page > obj.current_page_no && obj.total_page !=0 ){                               
            $('#load_more_btn').removeClass('d-none');
            $('#no_more').addClass('d-none');
          }else{                                
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
          }                

        }                     
      }

    });
}


$('#load_more_btn').click(function(){    
 var page_no = $('#page_no_hidden').val(); 
 var current_page_no =0;

 if(page_no == 1){
  current_page_no = 2;
}else{
  current_page_no = Number(page_no) + 1;
}        
$('#page_no_hidden').val(current_page_no);
    get_blogs(1);
});

  }

  if(pages=='blog_details')
  {
        $(document).ready(function(){

      $('#add_comments').submit(function(e) {

    var form = $(this);

    e.preventDefault();
    if($('#comments').val()!='')
    {
    $.ajax({
        type: "POST",
        url: base_url+'blog/home/add_comments',
        data: form.serialize(), // <--- THIS IS THE CHANGE
        beforeSend: function(){
          $('#comments_btn').attr('disabled',true);
          $('#comments_btn').html('<div class="spinner-border text-light" role="status"></div>');
         },
       success: function(data){

        $('#comments_btn').attr('disabled',false);
        $('#comments_btn').html('Submit');
        var obj = JSON.parse(data);
         if(obj.status===200)
        {
            $('#add_comments')[0].reset();
            toastr.success(obj.msg);
            get_comments();
        }
        else
        {
            toastr.error(obj.msg);
        }   
                      
        },
        error: function() { alert("Error posting feed."); }
   });
}

});
    }); 


    function get_comments(load_more)
    {
         if(load_more == 0){
         $('#page_no_hidden').val(1);
       }

     var page = $('#page_no_hidden').val();
        var post_id=$('#post_id').val();
        $.post(base_url+'blog/home/get_comments',{post_id:post_id,page : page},function(data){
            var obj = JSON.parse(data);

            if(obj.current_page_no == 1){    
           $('#comments_list').html(obj.comments_list);
          }else{
            $('#comments_list').append(obj.comments_list);  
          }  
            
            $('.comments_count').html(obj.count);

            if(obj.count == 0){
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }


          if(obj.current_page_no == 1 && obj.count < 5){
            $('page_no_hidden').val(1);
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
            return false;
          }



          if(obj.total_page > obj.current_page_no && obj.total_page !=0 ){                               
            $('#load_more_btn').removeClass('d-none');
            $('#no_more').addClass('d-none');
          }else{                                
            $('#load_more_btn').addClass('d-none');
            $('#no_more').removeClass('d-none');
          } 
        });

    }

    $('#load_more_btn').click(function(){    
 var page_no = $('#page_no_hidden').val(); 
 var current_page_no =0;

 if(page_no == 1){
  current_page_no = 2;
}else{
  current_page_no = Number(page_no) + 1;
}        
$('#page_no_hidden').val(current_page_no);
    get_comments(1);
});
   get_comments(0);

   function add_reply(id)
   {
        $('.leave-reply-sub-body').hide();
        if ($('#reply_block_' + id).is(':visible')) {
            $('.leave-reply-sub-body').hide();
            $('.new-comment').hide();
        } else {
            $('#reply_block_' + id).show();
            $('.new-comment').hide();
        }
   }

   function create_reply(id)
   {
        var comment_id=$('#comment_id_'+id).val();
        var reply=$('#reply_text_'+id).val();
        if($('#reply').val()!='')
        {comment_id
            $.ajax({
                type: "POST",
                url: base_url+'blog/home/add_reply',
                data: {comment_id:comment_id,reply:reply}, // <--- THIS IS THE CHANGE
                beforeSend: function(){
                  $('#reply_btn_'+id).attr('disabled',true);
                  $('#reply_btn_'+id).html('<div class="spinner-border text-light" role="status"></div>');
                 },
               success: function(data){

                $('#reply_btn_'+id).attr('disabled',false);
                $('#reply_btn_'+id).html('Submit');
                var obj = JSON.parse(data);
                 if(obj.status===200)
                {
                    $('#reply_text_'+id).val('');
                    $('.leave-reply-sub-body').hide();
                    $('.new-comment').show();
                    toastr.success(obj.msg);
                    get_replies(id);
                }
                else
                {
                    toastr.error(obj.msg);
                }   
                              
                },
                error: function() { alert("Error posting feed."); }
           });
       }
   }

   function get_replies(comment_id)
    {
        $.post(base_url+'blog/home/get_replies',{comment_id:comment_id},function(data){
            var obj = JSON.parse(data);
            $('#reply_list_'+comment_id).html(obj.replies_list);
            
        });

    }

    function delete_comment_reply(id,comment_id,type)
    {
        if(type=='1')
        {
            var text='comment';
        }
        if(type=='2')
        {
            var text='reply';
        }     
         $.confirm({
        title: 'Warning!',
        content: 'Are you sure you want to delete this '+text+'?',
        buttons: {
            Delete: function () {
        $.post(base_url+'blog/home/delete_comment_reply',{id:id,type:type},function(data){
           if(type==1)
           {
             get_comments(0);
           }
           if(type==2)
           {
             get_replies(comment_id);
           }
        });
        },
            Cancel: function () {

            },
             }
    });

    }

    


  }
}

