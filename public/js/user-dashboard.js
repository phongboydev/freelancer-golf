$(document).ready(function() {
    $('.btn_edit').on('click', function(){
        $('.btn_update').addClass('active');
        $('.btn_cancel').addClass('active');
        $('.btn_edit_form').addClass('active');
        $('.avatar-wrapper').addClass('active');
        $('.avatar_user').addClass('active');
        $('.avatar-upload .avatar-edit').addClass('active');
        //remove attr
        $('.edit_profile input[name="avatar_upload"]').removeAttr("disabled");
        $('.edit_profile input[name="full_name"]').removeAttr("disabled");
        $('.edit_profile input[name="phone"]').removeAttr("disabled");
        $('.edit_profile input[name="last_name"]').removeAttr("disabled");
        $('.edit_profile input[name="address"]').removeAttr("disabled");
        $('.edit_profile textarea[name="about_me"]').removeAttr("disabled");
        $('.edit_profile select').removeAttr("disabled");
    });

    $('.btn_cancel input[type="button"]').on('click', function(){
        location.reload();
    });
});
