<style>
    .select2-container .select2-selection--single {
        height: 40px;
        /* Adjust the height as needed */
    }
</style>

<div class="alert alert-success alert-dismissible" role="alert" id="successAlertDiv">
    <div class="d-flex justify-content-between">
        <span id="successAlertText"></span>
        <button type="button" class="close btn btn-success" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-3 offset-md-1">
        <h1 class="text-primary" style="font-size: 24px; font-weight : bold;">Message List</h1>
    </div>
    <div class="col-md-2 offset-md-5">
        <button class="btn btn-primary" data-toggle="modal" data-target="#messageModal">+ New Message</button>
    </div>
</div>


<!--Samples-->
<div id="message-thread-container">

</div>




<div class="modal fade" id="messageModal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Change modal-lg for a larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send New Message</h5>
            </div>
            <div class="modal-body">
                <div class="">
                    <label for="userSearch">Send to:</label>
                    <select class="js-example-basic-single js-states form-control userSearchClass" id="userSearch" style="width: 100%; height : 42px;"></select>
                </div>
                <div class="form-group mt-4">
                    <label for="textField">Message:</label>
                    <textarea class="form-control" id="messageText" style="width: 100%; height: 100px;"></textarea> <!-- Use a <textarea> for a multiline text field -->
                </div>
                <div>
                    <span id="messageErrorText" style="color : red; font-style : italic">

                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeMessageBtn">Close</button>
                <button type="button" class="btn btn-primary" id="sendMessageBtn"> <i class="fas fa-paper-plane"></i> Send</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $("#successAlertDiv").hide();

        //select2 initialization and data collect
        $('.userSearchClass').select2({
            ajax: {
                url: '/cake2project/users/getUsers',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.users
                    };
                },
            },
            dropdownParent: $("#messageModal"),
            templateResult: formatOption,
            templateSelection: formatOption,
            placeholder: "Send to ...",
            minimumInputLength: 2,
            containerCssClass: "selectClass"
        });

        //formatting select2 options
        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            // Set fixed dimensions for the image and the option text
            var imageSize = '24px'; // Adjust the image size as needed
            var optionHeight = '40px'; // Adjust the option height as needed

            var imgSrc = "<?php echo $this->webroot; ?>" + ((option.img_url == '') ? '/img/profile-pictures/default.png' : option.img_url);

            var $option = $(
                '<span style="display: flex; align-items: center; height: ' + optionHeight + ';">' +
                '<img class="option-image" src="' + imgSrc + '" style="width: ' + imageSize + '; height: ' + imageSize + ';" />' +
                '<span style="margin-left: 10px; line-height: ' + optionHeight + ';">' + option.text + '</span>' +
                '</span>'
            );
            return $option;
        }

        //reset new message on close
        $('#messageModal').on('hidden.bs.modal', function() {
            $('#userSearch').val(null).trigger('change');
            $("#messageText").val("");
            $("#messageErrorText").text("");
        });

        $("#sendMessageBtn").click(function() {
            var selectedData = $("#userSearch").select2("data");
            var message_content = $("#messageText").val();

            if (selectedData.length == 0 || !message_content) {
                //$("#messageErrorText").text("Send To should not be empty.");
                if (selectedData.length == 0 && !message_content) {
                    $("#messageErrorText").text("Send To and Message fields should not be empty.");
                } else if (selectedData.length == 0) {
                    $("#messageErrorText").text("Send To field should not be empty.");
                } else if (!message_content) {
                    $("#messageErrorText").text("Message field should not be empty.");
                }
            }else{
                console.log("sending message");
                var sender_id = <?php echo $_SESSION['userData']['user_id'];?>;
                var receiver_id = selectedData[0].id;
                $("#messageErrorText").text("");
                $.ajax({
                    type : 'POST',
                    dataType : 'json',
                    data : {
                        sender_id : sender_id,
                        receiver_id : receiver_id,
                        message_content : message_content
                    },
                    url : '/cake2project/messages/sendMessage',
                    success : function(data){
                        console.log("Message Thread Created Successfully!");
                        $('#closeMessageBtn').click();
                        $("#successAlertText").text("Successfully sent the message.");
                        $("#successAlertDiv").show();
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'fast');
                    },
                    error : function(){
                        console.log("Send message error");
                    }
                });
            }
        });

        //generate initial data
        $.ajax({
            type : "POST",
            dataType : 'json',
            url : '/cake2project/messages/getMessageThreads',
            success : function(data){
                console.log("initial data");
                console.log(data);
                var messageThreadContainer = $("#message-thread-container");

                data.forEach(function (messageThread){
                    //check if latest message is from the logged user. Displays 'YOU' instead of full name if it is.
                    thread_name = ((<?php echo $_SESSION['userData']['user_id']; ?> == messageThread.Sender.user_id) ? 'You' : messageThread.Sender.name);
                    var card = `
                    <div class="row mt-4">
                        <div class="col">
                            <div class="card" style="width: 100%;">
                                <div class="d-flex flex-column h-100">
                                    <div class="row no-gutters">
                                        <div class=" col-md-2 d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <img src="<?php echo $this->webroot; ?>${messageThread.Sender.img_url}" alt="Image" class="card-img mt-1" style="width: auto; max-height: 90px; max-width: 90px;">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="card-body">
                                                <p class="card-text font-weight-bold" style="font-weight: bold;">${thread_name}</p>
                                                <p class="card-text mt-3" style="color: #666666;">${messageThread.LatestMessage.message_content}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-auto card-footer d-flex justify-content-between align-items-center">
                                        <small class="text-muted">${messageThread.LatestMessage.created}</small>
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;

                    messageThreadContainer.append(card);
                });

                

            },
            error : function(){
                console.log("Get message threads error.");
            }
        })




    });
</script>