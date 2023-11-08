<style>
    .edit-image {
        width: 100px;
        /* Adjust the image width as needed */
        height: 100px;
        /* Adjust the image height as needed */
        border: 1px solid #ddd;
        cursor: pointer;
        position: relative;
    }

    .edit-image:hover::after {
        content: "Click to Edit";
        position: absolute;
        top: 50%;
        /* Adjust the top position as needed */
        left: 50%;
        /* Adjust the left position as needed */
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        opacity: 1;
        z-index: 1;
    }

    .user-info-form {
        display: inline-block;
        margin: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 300px;
    }

    .user-info-form input {
        display: block;
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .user-info-form button {
        width: 100%;
        padding: 5px;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
    }

    /* Create a two-column layout */
    .form-row {
        display: flex;
    }

    .form-group {
        flex: 1;
        margin-right: 10px;
    }

    /* Style your form fields as needed */
    .form-control {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    form div {
        margin-bottom: 0;
        padding: 0;
    }

    .error-border {
        border: 1px solid red;
    }

    .password-modal-row {
        margin-top: 1em;
    }

    .edit-image-container {
        position: relative;
    }

    .image-label {
        cursor: pointer;
    }

    .change-image-text {
        display: none;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        text-align: center;
        padding: 5px;
    }

    .image-label:hover .change-image-text {
        display: block;
    }

    .edit-image-container {
        text-align: center;
    }

    .image-label {
        display: block;
        text-align: center;
    }
</style>




<div class="container mt-3">
    <!-- <div class="mb-3">
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>" class="btn btn-primary">Return to Home</a>
    </div> -->

    <div class="alert alert-success alert-dismissible" role="alert" id="successAlertDiv">
        <div class="d-flex justify-content-between">
            <span id="successAlertText"></span>
            <button type="button" class="close btn btn-success" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <div class="alert alert-danger alert-dismissible" role="alert" id="errorAlertDiv">
        <div class="d-flex justify-content-between">
            <span id="errorAlertText"></span>
            <button type="button" class="close btn btn-danger" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>


    <form id="user-info-form">
        <div class="row">
            <!-- <div class="form-group col-md-6 border">
                <div class="d-flex align-items-center">
                    <div class="edit-image-container ml-3">
                        <label for="image-input" class="image-label">
                            <img id="user-image-preview" src="<?php echo $this->webroot . $_SESSION['userData']['img_url']; ?>" alt="User Image" class="edit-image">
                            <span class="change-image-text">Change Image</span>
                        </label>
                        <form enctype="multipart/form-data" id="image-form">
                            <input type="file" id="image-input" accept="image/*" style="display: none;" name="image">
                            <input type="hidden" id="image-url-input" name="image_url">
                        </form>

                    </div>
                </div>
            </div> -->

            <div class="form-group col-md-6 ">
                <div class="d-flex align-items-center">
                    <div class="edit-image-container mx-auto"> <!-- Add mx-auto class for horizontal centering -->
                        <label for="image-input" class="image-label">
                            <img id="user-image-preview" src="<?php echo $this->webroot . $_SESSION['userData']['img_url']; ?>" alt="User Image" class="edit-image">
                            <span class="change-image-text">Change Image</span>
                        </label>
                        <form enctype="multipart/form-data" id="image-form">
                            <input type="file" id="image-input" accept="image/*" style="display: none;" name="image">
                            <input type="hidden" id="image-url-input" name="image_url">
                        </form>
                    </div>
                </div>
            </div>


            <div class="form-group col-md-6" style="display: flex; align-items: center;">
                <label style="margin-right: 10px;">Gender:</label>
                <label class="editable" style="display: flex; align-items: center; margin-right: 20px;">
                    <input type="radio" name="gender" value="Male" id="maleRadio" class="editable" <?php echo ($_SESSION['userData']['gender'] == 'Male') ? 'checked' : ''; ?> disabled style="width: 20px; height: 20px; margin-right: 5px;">
                    Male
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="radio" name="gender" value="Female" id="femaleRadio" class="editable" <?php echo ($_SESSION['userData']['gender'] == 'Female') ? 'checked' : ''; ?> disabled style="width: 20px; height: 20px; margin-right: 5px;">
                    Female
                </label>
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="user_id">User ID:</label>
                <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo !empty($_SESSION['userData']['user_id']) ? $_SESSION['userData']['user_id'] : 'No Value Set'; ?>" disabled>
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email:</label>
                <div class="input-group" style="padding : 0">
                    <input type="text" class="form-control" id="email" name="email" style="height: 2.5em;" value="<?php echo !empty($_SESSION['userData']['email']) ? $_SESSION['userData']['email'] : 'No Value Set'; ?>" disabled>
                    <div class="input-group-append input-group-prepend" style="padding:0; margin-left : 1em">
                        <button type="button" class="btn btn-primary" id="change-email-button" data-toggle="modal" data-target="#emailModal">&#9998;</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="name">Name:</label>
                <input type="text" class="form-control editable" id="name" name="name" value="<?php echo !empty($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : 'No Value Set'; ?>" disabled>
            </div>

            <div class="form-group col-md-6">
                <label for="birthdate">Birthdate:</label>
                <?php
                if (!empty($_SESSION['userData']['birthdate'])) {
                    $birthdateTimestamp = strtotime($_SESSION['userData']['birthdate']); // Convert the datetime string to a timestamp
                    $formattedBirthdate = date('F j, Y', $birthdateTimestamp); // Format the timestamp
                    echo '<input type="text" class="form-control datepicker editable" id="birthdate" name="birthdate" value="' . $formattedBirthdate . '" disabled>';
                } else {
                    echo '<input type="text" class="form-control datepicker editable" id="birthdate" name="birthdate" value="No Value Set" disabled>';
                }
                ?>
            </div>

        </div>

        <div class="form-group" style="margin-bottom: 2em;">
            <label for="hobby">Hobby:</label>
            <textarea id="hobby" name="hobby" class="form-control editable" rows="2" disabled><?php echo $_SESSION['userData']['hobby'] ?? ''; ?></textarea>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-primary" id="edit-button">Edit Profile</button>
            <button type="button" class="btn btn-secondary" id="password-button" data-toggle="modal" data-target="#passwordModal">Change Password</button>
            <button type="button" class="btn btn-success" id="save-button" style="display: none;">Save Changes</button>
            <button type="button" class="btn btn-dark" id="cancel-button" style="display: none;">Cancel</button>
        </div>
    </form>

    <!--CHANGE PASSWORD MODAL-->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Change Password</h5>

                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group password-modal-row">
                            <label for="password" style="font-weight: bold;">Current Password:</label>
                            <input type="password" class="form-control" id="currentPassword" placeholder="Enter password">
                        </div>
                        <div class="form-group password-modal-row">
                            <label for="password" style="font-weight: bold;">New Password:</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password">
                        </div>
                        <div class="form-group password-modal-row mb-3">
                            <label for="confirmPassword" style="font-weight: bold;">Confirm New Password:</label>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password">
                            <span style="font-style: italic; color : red;" class="mt-2" id="passwordErrorText"></span>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary editPasswordClose" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePassword">Save Password</button>
                </div>
            </div>
        </div>
    </div>
    <!--CHANGE PASSWORD MODAL END-->

    <!--CHANGE EMAIL MODAL-->
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Change Email</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="changeEmail">Email</label>
                            <input type="changeEmail" class="form-control" id="changeEmail" placeholder="Enter Email">
                            <span style="font-style: italic; color : red;" class="mt-2" id="emailErrorText"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary editModalClose" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-email">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!--CHANGE EMAIL MODAL END-->
</div>



<script>
    $(document).ready(function() {
        $("#successAlertDiv").hide();
        $("#errorAlertDiv").hide();

        $(".datepicker").datepicker({
            dateFormat: 'MM dd, yy', // Adjust the date format as needed
            changeMonth: true,
            changeYear: true,
            yearRange: '1900:2030', // Adjust the year range as needed
        });

        //edit button function
        $('#edit-button').click(function() {
            $('.editable').prop('disabled', false);
            $('#edit-button').hide();
            $("#password-button").hide();
            $('#save-button, #cancel-button').show();
        });

        //cancel button
        $('#cancel-button').click(function() {
            $('.editable').prop('disabled', true);
            $('#edit-button').show();
            $("#password-button").show();
            $('#save-button, #cancel-button').hide();

            //editable values
            var formattedBirthdate = 'No Value Set';
            if ('<?php echo $_SESSION['userData']['birthdate'] ?? ''; ?>') {
                var birthdateTimestamp = new Date('<?php echo $_SESSION['userData']['birthdate']; ?>');
                formattedBirthdate = birthdateTimestamp.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                });
            }
            $("#birthdate").val(formattedBirthdate);
            $("#name").val('<?php echo $_SESSION['userData']['name'] ?? 'No Value Set'; ?>');
            $("#email").val('<?php echo $_SESSION['userData']['email'] ?? 'No Value Set'; ?>');
            $("#gender").val('<?php echo $_SESSION['userData']['gender'] ?? ''; ?>');
            $("#hobby").val('<?php echo $_SESSION['userData']['hobby'] ?? ''; ?>');
        });

        //save button 
        $('#save-button').click(function() {
            var errorList = {
                nameError: false,
                emailError: false,
                birthdayError: false,
                genderError: false,
                hobbyError: false
            };

            //birthdate input value
            var birthdateInput = $("#birthdate").val();
            //checking if it is a valid date (checking formatting, if it is not in the future, if it does not exceed 122 years old)
            var formattedBirthday = validateAndFormatBirthdate(birthdateInput);
            errorList["birthdayError"] = (formattedBirthday == "error") ? true : false;

            //name input value
            var name = $("#name").val();
            //check if name is 5-20 characters
            errorList["nameError"] = (name.length >= 5 && name.length <= 20) ? false : true
            //errorList["nameError"] = !(validateName(name));

            //hobby input value 
            var hobby = $("#hobby").val();
            //check if hobby is empty
            errorList["hobbyError"] = (hobby == '') ? true : false;
            //errorList["hobbyError"] = !(validateHobby(hobby));

            //gender input value
            var gender = $('input[name="gender"]:checked').val();
            //check if user has chosen an option
            errorList["genderError"] = (typeof gender === 'undefined') ? true : false;


            var userData = {
                user_id: $("#user_id").val(),
                name: $("#name").val(),
                birthdate: formattedBirthday,
                gender: $('input[name="gender"]:checked').val(),
                hobby: $("#hobby").val()
            };

            var hasErrors = Object.values(errorList).includes(true);
            if (hasErrors) {
                $("#errorAlertDiv").show();

                const errorMessages = Object.entries(errorList)
                    .filter(([key, value]) => value)
                    .map(([key]) => {
                        switch (key) {

                            case 'hobbyError':
                                return 'Hobby';
                            case 'genderError':
                                return 'Gender';
                            case 'birthdayError':
                                return 'Birthdate';
                            case 'nameError':
                                return 'Name (5 to 20 characters)';
                            default:
                                return key;
                        }
                    })
                    .join(", ");

                const errorMessage = errorMessages.length > 0 ?
                    "These field values are empty or invalid: " + errorMessages + "." :
                    "";

                $("#errorAlertText").text(errorMessage);
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
            } else {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: userData,
                    url: '/cake2project/users/updateUser',
                    success: function(data) {
                        console.log("update was a success");
                        $("#successAlertDiv").show();
                        $("#successAlertText").text("Update Successful!");
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                        $('.editable').prop('disabled', true);
                        $('#edit-button').show();
                        $("#password-button").show();
                        $('#save-button, #cancel-button').hide();
                    },
                    error: function() {
                        console.log("update error ");
                    }
                })
                $("#errorAlertDiv").hide();
            }


        });

        //birthday validation
        function validateAndFormatBirthdate(birthdateInput) {

            const timeZoneOffset = 0; // UTC

            const parts = birthdateInput.split(' ');
            const monthNames = [
                "January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"
            ];
            const month = monthNames.indexOf(parts[0]);
            const day = parseInt(parts[1].replace(',', ''), 10);
            const year = parseInt(parts[2], 10);

            if (isNaN(year) || isNaN(month) || isNaN(day)) {
                return 'error'; // Invalid date
            }

            const birthdate = new Date(Date.UTC(year, month, day) - (timeZoneOffset * 60000));

            if (birthdate > new Date()) {
                return 'error';
            }

            const maxAgeDate = new Date();
            maxAgeDate.setFullYear(maxAgeDate.getFullYear() - 122);

            if (birthdate < maxAgeDate) {
                return 'error';
            }

            const formattedBirthday = birthdate.toISOString().split('T')[0];
            console.log("output");
            console.log(formattedBirthday);
            return formattedBirthday;
        }


        //edit email modal click
        $("#change-email-button").click(function() {
            //$("#changeEmail").val('<?php echo !empty($_SESSION['userData']['email']) ? $_SESSION['userData']['email'] : 'No Value Set'; ?>');
            $("#changeEmail").val($("#email").val());
            $("#emailErrorText").text("");
        });

        //save email change click
        $("#save-email").click(function() {
            var email = $("#changeEmail").val();
            var oldEmail = $("#email").val();
            if (isValidEmail(email)) {
                $("#emailErrorText").text("");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '/cake2project/users/checkEmailUnique',
                    data: {
                        email: email
                    },
                    success: function(data) {
                        if (data["unique"]) {
                            console.log("changing email");
                            $("#emailErrorText").text("");
                            //changing email ajax
                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: '/cake2project/users/updateEmail',
                                data: {
                                    email: email,
                                    oldEmail: oldEmail
                                },
                                async: true,
                                success: function(data) {
                                    console.log("Email Change Success");
                                    console.log(data);
                                    $("#successAlertDiv").show();
                                    $("#successAlertText").text("Email Updated Successfully");
                                    $("#email").val(data.newEmail);
                                    $("#emailModal .editModalClose").click()
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.log("Error: ");
                                    console.log(jqXHR);

                                    // You can access specific error details from the jqXHR object
                                    console.log("Status: " + textStatus);
                                    console.log("Error: " + errorThrown);
                                }
                            });
                            //changing email end
                        } else {
                            console.log("invalid email");
                            $("#emailErrorText").text("This email has been taken.");
                        }
                    },
                    error: function() {
                        console.log("Validate Email Error.");
                    }
                });
            } else {
                $("#emailErrorText").text("Enter a valid email. (ex. johnsmith@email.com)");
            }


        })

        //email checker function
        function isValidEmail(email) {
            var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
            if (emailRegex.test(email)) {
                return true;
            }
            return false;
        }

        //password modal click
        $("#password-button").click(function() {
            $('#passwordErrorText').text(''); // Clear any previous error messages.
            $('#password').removeClass('error-border');
            $('#confirmPassword').removeClass('error-border');
            $('#currentPassword').removeClass('error-border');

            $('#password').val('');
            $('#confirmPassword').val('');
            $('#currentPassword').val('');
        });

        //save password button
        $("#savePassword").click(function() {
            if (validatePasswordFields()) {
                var newPassword = $('#password');
                var confirmPassword = $('#confirmPassword');
                var currentPassword = $('#currentPassword');
                var passwordErrorText = $('#passwordErrorText');
                var userId = $("#user_id").val();

                passwordErrorText.text(''); // Clear any previous error messages.
                newPassword.removeClass('error-border');
                confirmPassword.removeClass('error-border');
                currentPassword.removeClass('error-border');

                var newPasswordVal = newPassword.val();
                var confirmPasswordVal = confirmPassword.val();
                var currentPasswordVal = currentPassword.val();

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/cake2project/users/passwordCheck',
                    data: {
                        user_id: userId,
                        password: currentPasswordVal
                    },
                    success: function(data) {
                        if (data.valid) {

                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: '/cake2project/users/passwordChange',
                                data: {
                                    user_id: userId,
                                    password: newPasswordVal
                                },
                                async: true,
                                success: function(data) {
                                    $("#passwordModal .editPasswordClose").click()
                                    $("#successAlertDiv").show();
                                    $("#successAlertText").text("Password changed successfully!");
                                    $('html, body').animate({
                                        scrollTop: 0
                                    }, 'fast');

                                },
                                error: function() {
                                    console.log("Password Change Error");
                                }
                            });

                        } else {
                            console.log("Incorrect password");
                            $('#passwordErrorText').text("Current password field is incorrect.")
                        }

                    },
                    error: function() {
                        console.log("Save Password Error.");
                    }
                })

            }
        });

        //validate passwords 
        function validatePasswordFields() {
            var newPassword = $('#password');
            var confirmPassword = $('#confirmPassword');
            var currentPassword = $('#currentPassword');
            var passwordErrorText = $('#passwordErrorText');

            passwordErrorText.text(''); // Clear any previous error messages.
            newPassword.removeClass('error-border');
            confirmPassword.removeClass('error-border');
            currentPassword.removeClass('error-border');

            var newPasswordVal = newPassword.val();
            var confirmPasswordVal = confirmPassword.val();
            var currentPasswordVal = currentPassword.val();

            if (newPasswordVal === '' && confirmPasswordVal === '' && currentPasswordVal === '') {
                passwordErrorText.text('All password fields are required.');
                newPassword.addClass('error-border');
                confirmPassword.addClass('error-border');
                currentPassword.addClass('error-border');
                return false;
            } else if (currentPasswordVal !== '' && (newPasswordVal === '' || confirmPasswordVal === '')) {
                passwordErrorText.text('New password and confirm password are required.');
                newPassword.addClass('error-border');
                confirmPassword.addClass('error-border');
                return false;
            } else if (newPasswordVal !== confirmPasswordVal) {
                passwordErrorText.text('Passwords do not match.');
                newPassword.addClass('error-border');
                confirmPassword.addClass('error-border');
                return false;
            }
            return true;
        }

        //image

        $('#image-input').change(function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {

                    if (confirm("Replace current profile picture?")) {
                        var formData = new FormData();
                        var imageInput = document.getElementById('image-input');
                        formData.append('image', imageInput.files[0]);
                        $.ajax({
                            url: '/cake2project/users/saveImage',
                            type: 'POST',
                            data: formData, // Send the formData object, not the 'file' object
                            processData: false, // Prevent jQuery from processing data
                            contentType: false,
                            async: true,
                            success: function(data) {
                                $('#user-image-preview').attr('src', e.target.result);

                            },
                            error: function(xhr, status, error) {
                                console.log("save image error.");
                            }
                        });
                    } else {
                        // User canceled the action, reset the input field and image
                        $('#image-input').val('');
                        $('#user-image-preview').attr('src', '<?php echo $this->webroot . $_SESSION['userData']['img_url']; ?>');
                    }
                };
                reader.readAsDataURL(file);
            }
        });







    });
</script>