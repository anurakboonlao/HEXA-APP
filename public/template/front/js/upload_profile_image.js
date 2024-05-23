$(window).load(function(){   
    //** member profile upload image */
    $(".modal-profile .profile-left-area #wizard-picture").change(function(){
        uploadAvatar(this);
    });
})

// ================== SCRIPT MEMBER PROFILE UPLOAD IMAGE =================================//
    //** member profile upload image */
    function uploadAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }

        //$('.modal-profile .profile-left-area #upload-profile-avatar').submit();
    }
// ==================/. SCRIPT MEMBER PROFILE UPLOAD IMAGE =================================//