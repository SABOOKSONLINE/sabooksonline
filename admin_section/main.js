	   //ADD CATEGORY TO DATABASE
       $("#category").on('submit',(function(e) {
        e.preventDefault();
        
                $("#results_add").html("Loading...");
            //showSwal('success-message');
            $.ajax({
                
                    url: "includes/backend/publish/category.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#results_add").html(data);
                },
            error: function(){}
        });
         
    }));


    	   //REMOVE CATEGORY FROM DATABASE
           $("#category_delete").on('submit',(function(e) {
            e.preventDefault();
            
                    $("#results_remove").html("Loading...");
                //showSwal('success-message');
                $.ajax({
                    
                        url: "includes/backend/delete/category.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                        cache: false,
                    processData:false,
                    success: function(data)
                {
                    $("#results_remove").html(data);
                    },
                error: function(){}
            });
             
        }));


            	   //ADD LOCATION TO DATABASE
                   $("#location").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results_add").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/publish/location.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results_add").html(data);
                            },
                        error: function(){}
                    });
                     
                }));


                
            	   //REMOVE LOCATION FROM DATABASE
                   $("#location_remove").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results_remove").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/delete/location.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results_remove").html(data);
                            },
                        error: function(){}
                    });
                     
                }));


                        
            	   //PUBLISH LISTING
                   $("#listing").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/publish/listing.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results").html(data);
                            },
                        error: function(){}
                    });
                     
                }));


                            
            	   //PUBLISH ads
                   $("#ads").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/publish/ads.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results").html(data);
                            },
                        error: function(){}
                    });
                     
                }));

                                 
            	   //PUBLISH ads
                   $("#sub").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/publish/category.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results").html(data);
                            },
                        error: function(){}
                    });
                     
                }));


                 	   //PUBLISH ads
                        $("#category_delete").on('submit',(function(e) {
                            e.preventDefault();
                            
                                    $("#results_remove").html("Loading...");
                                //showSwal('success-message');
                                $.ajax({
                                    
                                        url: "includes/backend/delete/category.php",
                                    type: "POST",
                                    data:  new FormData(this),
                                    contentType: false,
                                        cache: false,
                                    processData:false,
                                    success: function(data)
                                {
                                    $("#results_remove").html(data);
                                    },
                                error: function(){}
                            });
                             
                        }));




                                  
            	   //UPDATE LISTING
                   $("#edit").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/updates/edit.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results").html(data);
                            },
                        error: function(){}
                    });
                     
                }));

                                        
            	   //UPDATE USER PROFILE ADMIN
                   $("#edit-user-admin").on('submit',(function(e) {
                    e.preventDefault();
                    
                            $("#results").html("Loading...");
                        //showSwal('success-message');
                        $.ajax({
                            
                                url: "includes/backend/updates/edit-user-admin.php",
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                                cache: false,
                            processData:false,
                            success: function(data)
                        {
                            $("#results").html(data);
                            },
                        error: function(){}
                    });
                     
                }));


                       //UPDATE THE PROFILE
          $("#updatebook").on('submit',(function(e) {
            e.preventDefault();
    
                $("#results").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                
                //showSwal('success-message');
            $.ajax({
                    url: "includes/backend/updates/book.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#results").html(data);
                },
            error: function(){}
            });
    
            
            }));
                               
              //UPDATE THE EVENT
        $("#edit-event").on('submit',(function(e) {
            e.preventDefault();
    
                $("#listing_result").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                
                //showSwal('success-message');
            $.ajax({
                    url: "includes/backend/updates/edit-event.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#results").html(data);
                },
            error: function(){}
            });
    
            
            }));

            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 2000);