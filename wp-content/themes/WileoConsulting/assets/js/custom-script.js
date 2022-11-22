jQuery(document).ready(function ($){ 
    if($("#profile-settings form.chane_pass_form").length > 0){
        $("form.chane_pass_form button.btn-submit").text("Save")
        $("body #chane_pass_form").on("submit", function (event){
           
            event.preventDefault();
            event.stopPropagation();
            var form   = $(event.currentTarget),
                button = form.find('.btn-submit');

            let data = {
                old_password: $("#old_password").val(),
                new_password: $("#new_password").val(),
                renew_password: $("#renew_password").val(),
                do: 'update',
                ID: AE.globals.user_ID,
                action: 'ae-sync-user',
                method: 'update'
            }
            $.ajax({
                beforeSend: function() {
                    button.attr('disabled');
                    form.addClass('processing');
                },
                url: AE.globals.ajaxURL,
                type: 'POST',
                data: data,
                success: function (status){
                    button.removeAttr('disabled');
                    form.removeClass('processing');
                    if(status.success){
                        AE.pubsub.trigger('ae:notification', {
                            msg : status.msg,
                            notice_type: 'success'
                        });
                        form.trigger('reset');
                        //window.location.href = ae_globals.homeURL;
                    } else {
                        AE.pubsub.trigger('ae:notification', {
                            msg : status.msg,
                            notice_type: 'error'
                        });
                    }
                }
            })
        })
    }

$("body #profile_visible_action").on("submit", function (event){
   
        event.preventDefault();
            event.stopPropagation();
            var form   = $(event.currentTarget),
            button = form.find('.btn-submit');
        if ($("#profile_visible").is(':checked')) {
            

            let data = {
                is_checked_yes: 'yes',
               
                do: 'post',
                ID: AE.globals.user_ID,
                action: 'ae-sync-profile-visibility',
                method: 'post'
            }
            $.ajax({
                 beforeSend: function() {
                    button.attr('disabled');
                    form.addClass('processing');
                },
                url: AE.globals.ajaxURL,
                type: 'POST',
                data: data,
                success: function (status){
                    button.removeAttr('disabled');
                    form.removeClass('processing');
                    
                        AE.pubsub.trigger('ae:notification', {
                            msg : 'Your profile is visible for other users',
                            notice_type: 'success'
                        });
                        $('#profile_visible').attr('checked', true);
                        form.trigger('reset');
                        //window.location.href = ae_globals.homeURL;
                   
                }
            })
        
        }
        else {
             
             
            let data = {
                is_checked_yes: 'no',
               
                do: 'post',
                ID: AE.globals.user_ID,
                action: 'ae-sync-profile-visibility',
                method: 'post'
            }
            $.ajax({
              beforeSend: function() {
                    button.attr('disabled');
                    form.addClass('processing');
                },
                url: AE.globals.ajaxURL,
                type: 'POST',
                data: data,
                success: function (status){
                    button.removeAttr('disabled');
                    form.removeClass('processing');
                   
                        AE.pubsub.trigger('ae:notification', {
                            msg : 'Your profile is invisible for other users',
                            notice_type: 'success'
                        });
                        $('#profile_visible').removeAttr('checked');
                        form.trigger('reset');
                        //window.location.href = ae_globals.homeURL;
                   
                }
            })
        
        
        }
    });
})
