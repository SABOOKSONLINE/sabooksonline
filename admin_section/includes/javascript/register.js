
        //publiish story upload code
        $("#register").on('submit',(function(e) {
        e.preventDefault();

            $("#reg_submits").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
            
            //showSwal('success-message');
        $.ajax({
                url: "includes/backend/register.php",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
                cache: false,
            processData:false,
            success: function(data)
        {
            $("#reg_submits").html(data);
            },
        error: function(){}
        });

        
        }));


        
        //login to account
        $("#login").on('submit',(function(e) {
            e.preventDefault();
    
                $("#log_submits").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                
                //showSwal('success-message');
            $.ajax({
                    url: "includes/backend/login.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#log_submits").html(data);
                },
            error: function(){}
            });
    
            
            }));
    

              //UPDATE THE PROFILE
        $("#bio").on('submit',(function(e) {
            e.preventDefault();
    
                $("#bio_result").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                
                //showSwal('success-message');
            $.ajax({
                    url: "includes/backend/updates/profile.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#bio_result").html(data);
                },
            error: function(){}
            });
    
            
            }));


            
              //UPDATE THE PROFILE
        $("#listing").on('submit',(function(e) {
            e.preventDefault();
    
                $("#listing_result").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                
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
                $("#listing_result").html(data);
                },
            error: function(){}
            });
    
            
            }));

                  //publiish story upload code
                  $("#delete").on('submit',(function(e) {
                    e.preventDefault();
            
                        $("#reg_submits").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                        
                        //showSwal('success-message');
                    $.ajax({
                            url: "includes/backend/delete/listing.php",
                        type: "POST",
                        data:  new FormData(this),
                        contentType: false,
                            cache: false,
                        processData:false,
                        success: function(data)
                    {
                        $("#reg_submits").html(data);
                        },
                    error: function(){}
                    });
            
                    
                    }));

                    


                 
              //UPDATE THE esit
        $("#edit").on('submit',(function(e) {
            e.preventDefault();
    
                $("#listing_result").html('<center><img src="img/loader/loader.jpg" width="40px"></center>');
                
                //showSwal('success-message');
            $.ajax({
                    url: "includes/backend/updates/listing.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#listing_result").html(data);
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
                $("#listing_result").html(data);
                },
            error: function(){}
            });
    
            
            }));

    
