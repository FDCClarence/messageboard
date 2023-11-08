<style>
    .container {
        display: flex;
        flex-direction: column;
    }

    .chat-bubble {
        display: flex;
        margin: 8px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .info-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
    }

    .chat-text {
        max-width: 70%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f0f0f0;
    }

    .chat-bubble.user1 {
        justify-content: flex-end;
        /* User1 bubbles on the right */
    }

    .chat-bubble.user2 {
        justify-content: flex-start;
        /* User2 bubbles on the left */
    }

    .datetime {
        font-size: 12px;
        color: gray;
    }

    .delete-button {
        font-size: 12px;
        background-color: #f97c6e;
        color: #fff;
        border: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        text-align: center;
        cursor: pointer;
        line-height: 1;
    }

    .delete-button:hover {
        background-color: #f9301a;
    }

    #searchButton:hover {
        color: #1f4a7c;
        /* Change the color to a lighter shade on hover */
        border: 1px solid #1f4a7c;
    }

    #userInfoDiv:hover {
        cursor: pointer;
    }

    .info-modal-body {
        text-align: center;
        /* Center the contents horizontally */
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Center the contents vertically */
        padding: 20px;
        /* Add some padding for spacing */
    }

    .info-avatar {
        max-width: 100%;
        height: auto;
        border-radius: 50%;
        /* Make the image round */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        /* Add a subtle shadow */
    }

    .row {
        margin: 10px 0;
    }

    span {
        font-weight: bold;
    }

    .message-content {
        overflow: hidden;
        /* Hide overflow content */
        word-wrap: break-word;
        /* Wrap long words to the next line */
    }

    .message-content:hover {
        cursor: pointer;
    }

    #show-more-div:hover {
        cursor: pointer;
    }
</style>

<div class="container mt-4 messages-container" id="" style="min-height: 3em;
        border: 1px solid darkgray;
        padding: 1em;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-color : #93bff4;">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center" id="userInfoDiv">
            <?php
            if (isset($message_thread[0])) {
                if ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id']) {
                    $imgUrl = $message_thread[0]['user_2']['img_url'] ?: '/img/profile-pictures/default.png';
                } else {
                    $imgUrl = $message_thread[0]['user_1']['img_url'] ?: '/img/profile-pictures/default.png';
                }
            } else {
                $imgUrl = '/img/profile-pictures/default.png';
            }
            ?>
            <img src="<?php echo $this->webroot . $imgUrl; ?>" alt="Profile Picture" class="user-avatar">
            <span id="usernameContainer" class="font-weight-bold ml-5" style="font-weight: bold; font-size : 30px; margin-left: 1em;">
                <?php echo (isset($message_thread[0]) ? ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id'] ? $message_thread[0]['user_2']['name'] : $message_thread[0]['user_1']['name']) : 'User'); ?>
            </span>
            <span id="emailContainer" class="font-weight-bold ml-5" style="font-weight: bold; font-size : 20px; margin-left: 1em; color : #1f4a7c">
                (
                <?php echo (isset($message_thread[0]) ? ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id'] ? $message_thread[0]['user_2']['email'] : $message_thread[0]['user_1']['email']) : ''); ?>
                ) </span>
        </div>
        <!-- <button class="btn btn-primary align-self-center" id="replyButton">Reply</button> -->
        <div>
            <button class="btn btn-gray" data-toggle="modal" data-target="#searchModal" id="searchButton">
                <i class="fas fa-search"></i> Search in conversation
            </button>
            <button class="btn btn-primary" data-toggle="modal" data-target="#replyModal">Reply</button>
        </div>


    </div>
</div>


<!--MESSAGES CONTAINER-->

<div class="container messages-container" id="" style="height: 3em; border: 1px solid darkgray; padding: 1em; display: flex; align-items: center;">
    <div class="">
        <div class="col text-center" style="color : darkblue;" id="show-more-div">
            <span>show more</span>
        </div>
        <div class="col text-center" style="color : darkblue;" id="no-more-div">
            <span>no more messages to add</span>
        </div>
    </div>
</div>


<div class="container messages-container" id="messages-container" style="max-height: 600px; overflow-y: auto; border: 1px solid darkgray; padding: 1em; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
    <?php
    if (!isset($messages[0])) {
    ?>
        <div class="row">
            <div class="col text-center">No messages</div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col text-center" style="color : darkblue;" id="show-more-div">
        </div>
    </div>
</div>


<div class="container mt-4">
    <div style="text-align: right;">
        <button class="btn btn-danger" id="deleteConversation">Delete Conversation</button>
    </div>
</div>

<!--   REPLY MODAL     -->
<div class="modal fade" id="replyModal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Change modal-lg for a larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyTitle">Send a Reply</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mt-1">
                    <label for="textField">Message:</label>
                    <textarea class="form-control" id="replyText" style="width: 100%; height: 100px;"></textarea> <!-- Use a <textarea> for a multiline text field -->
                </div>
                <div>
                    <span id="replyErrorText" style="color : red; font-style : italic">

                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeReplyBtn">Close</button>
                <button type="button" class="btn btn-primary" id="sendReplyBtn"> <i class="fas fa-paper-plane"></i> Send</button>
            </div>
        </div>
    </div>
</div>

<!--    SEARCH MODAL     -->
<div class="modal fade" id="searchModal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Change modal-lg for a larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyTitle">Search in Conversation</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mt-1">
                    <label for="textField">Message:</label>
                    <input id="searchText" class="form-control" placeholder="Search..." />
                    <span id="searchErrorText" style="color : red; font-style : italic"></span>

                </div>
                <div id="messageInfo">
                    messageInfoDiv
                </div>


                <div id="noMessageInfo" class="container mt-4">
                    <div class="row p-3 border rounded bg-light">
                        <div class="col-12 text-center">
                            <span><strong>No Messages Found.</strong></span>
                        </div>
                    </div>
                </div>
                <div>
                    <span id="searchErrorText" style="color : red; font-style : italic">

                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeSearchBtn">Close</button>
                <button type="button" class="btn btn-primary" id="searchBtn"> <i class="fas fa-search"></i> Search</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userInfoModal" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md"> <!-- Change modal-lg for a larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userInfoTitle" style="font-weight : bold; font-size : 30px">
                    User Information
                </h5>
            </div>
            <div class="info-modal-body">
                <img src="<?php echo $this->webroot . $imgUrl; ?>" alt="Profile Picture" class="info-avatar">
                <div class="row">
                    <span>
                        Name:
                        <strong style="">
                            <?php echo (isset($message_thread[0]) ? ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id'] ? $message_thread[0]['user_2']['name'] : $message_thread[0]['user_1']['name']) : ''); ?>
                        </strong>
                    </span>
                </div>
                <div class="row">
                    <span>
                        Email:
                        <strong>
                            <?php echo (isset($message_thread[0]) ? ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id'] ? $message_thread[0]['user_2']['email'] : $message_thread[0]['user_1']['email']) : ''); ?>
                        </strong>
                    </span>
                </div>
                <div class="row">
                    <span>
                        Gender:
                        <strong>
                            <?php echo (isset($message_thread[0]) ? ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id'] ? $message_thread[0]['user_2']['gender'] : $message_thread[0]['user_1']['gender']) : ''); ?>
                        </strong>
                    </span>
                </div>
                <div class="row">
                    <span>
                        Hobby:
                        <strong>
                            <?php echo (isset($message_thread[0]) ? ($_SESSION['userData']['user_id'] == $message_thread[0]['user_1']['user_id'] ? $message_thread[0]['user_2']['hobby'] : $message_thread[0]['user_1']['hobby']) : ''); ?>
                        </strong>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeUserInfoBtn">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $("#no-more-div").hide();
        <?php
        $messagesJson = json_encode($messages);
        $messageThreadJson = json_encode($message_thread);
        $user_id = $_SESSION['userData']['user_id'];
        echo "var messages = ($messagesJson).reverse();";
        echo "var message_thread = ($messageThreadJson);";
        echo "var user_id = $user_id;";
        echo "var webroot = $this->webroot;";
        ?>


        messages.forEach(function(message) {
            bubbleType = (message['receiver']['user_id'] == user_id ? 'user2' : 'user1'); // user1 = shown on the left, user2 = shown on the right
            profilePicture = webroot + message['sender']['img_url'];

            // Shorten the message content if it's longer than 50 characters
            var messageContent = message['m']['message_content'];
            if (messageContent.length > 50) {
                messageContent = messageContent.slice(0, 50) + '...';
            }

            var chatBubble = `
                <div class="chat-bubble ${bubbleType}" title = "click to show more/show less">
                    <img src="${profilePicture}" alt="Profile Picture" class="user-avatar">
                    <div class="chat-text">
                        <p class="message-content">${messageContent}</p>
                        <span class="datetime">${message[0]['formatted_created_date']}</span>
                    </div>
                    ${bubbleType == 'user1' ? `<button class="delete-button" data-message-id="${message['m']['message_id']}">x</button>` : ''}
                </div>
            `;

            // Add a click event listener to toggle the message content
            var isShortened = true;
            chatBubble = $(chatBubble); // Convert chatBubble to a jQuery object
            chatBubble.find('.message-content').click(function() {
                if (isShortened) {
                    $(this).text(message['m']['message_content']);
                } else {
                    $(this).text(messageContent);
                }
                isShortened = !isShortened;
            });

            $("#messages-container").append(chatBubble);
        });

        $(document).on("click", ".delete-button", function(event) {
            var chatBubble = $(this).closest(".chat-bubble");
            message_id = event.target.getAttribute('data-message-id');
            if (confirm("Delete this message?")) {
                $.ajax({
                    type: 'POST',
                    url: '/cake2project/messages/deleteMessage',
                    data: {
                        message_id: message_id
                    },
                    success: function(data) {
                        chatBubble.remove();
                        console.log("Delete message success");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Delete message error:', errorThrown);
                    }
                });
            }
        });

        $("#replyButton").click(function() {
            console.log("reply button clicked");
        });

        $('#replyModal').on('hidden.bs.modal', function() {
            $("#replyText").val("");
            $("#replyErrorText").text("");
        });

        $("#sendReplyBtn").click(function() {
            var message_content = $("#replyText").val();
            var sender_id = <?php echo $_SESSION['userData']['user_id'] ?>;
            var receiver_id = (sender_id == message_thread[0]['user_1']['user_id']) ? message_thread[0]['user_2']['user_id'] : message_thread[0]['user_1']['user_id'];
            if (message_content == '') {
                $("#replyErrorText").text("Message should not be empty.");
            } else {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/cake2project/messages/sendMessage',
                    data: {
                        sender_id: sender_id,
                        receiver_id: receiver_id,
                        message_content: message_content
                    },
                    success: function(data) {
                        var profilePicture = '<?php echo $this->webroot . $_SESSION['userData']['img_url']; ?>';

                        // Shorten the message content if it's longer than 50 characters
                        var shortenedMessageContent = message_content;
                        if (message_content.length > 50) {
                            shortenedMessageContent = message_content.slice(0, 50) + '...';
                        }

                        var chatBubble = `
                                            <div class="chat-bubble user1" title="${message_content}">
                                                <img src="${profilePicture}" alt="Profile Picture" class="user-avatar">
                                                <div class="chat-text">
                                                    <p class="message-content">${shortenedMessageContent}</p>
                                                    <span class="datetime">${formatCurrentDateTime()}</span>
                                                </div>
                                                <button class="delete-button" data-message-id="${data['message_id']}">x</button>
                                            </div>
                                        `;

                        // Add the chat bubble to the messages container
                        var $chatBubble = $(chatBubble);
                        $("#messages-container").append($chatBubble);

                        // Add the click event listener to toggle the message content
                        var isShortened = true;
                        $chatBubble.find('.message-content').click(function() {
                            if (isShortened) {
                                $(this).text(message_content);
                            } else {
                                $(this).text(shortenedMessageContent);
                            }
                            isShortened = !isShortened;
                        });

                        $('#closeReplyBtn').click();
                    },
                    error: function() {
                        console.log("Send reply error.");
                    }
                });


            }
        });

        $('#searchModal').on('show.bs.modal', function(e) {
            $("#messageInfo").hide();
            $("#noMessageInfo").hide();
            $("#searchText").val("");
            $("#searchErrorText").text("");
            $("#messageInfo").empty();

        });

        $("#searchBtn").click(function() {
            var searchVal = $("#searchText").val();
            var message_thread_id = message_thread[0]['mt']['message_thread_id'];
            $("#messageInfo").empty();
            if (searchVal == '') {
                $("#searchErrorText").text("Message field should not be empty.");
            } else {
                $("#searchErrorText").text("");

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/cake2project/messages/searchMessage',
                    data: {
                        search: searchVal,
                        message_thread_id: message_thread_id
                    },
                    success: function(data) {
                        if (data.length == 0) {
                            $("#noMessageInfo").show();
                            $("#messageInfo").hide();
                        } else {
                            $("#messageInfo").show();
                            $("#noMessageInfo").hide();

                            data.forEach((message) => {
                                messageCard = `
                                        <div id="messageCard" class="container mt-4">
                                            <div class="row p-3 border rounded bg-light">
                                                <div class="col-12" style="max-width: 100%; word-wrap: break-word;">
                                                    <h4 class="font-weight-bold">Sent By: ${message['u1']['sender_name']}</h4>
                                                    <p class="font-italic text-muted">${message[0]['formatted_created_date']}</p>
                                                    <p>${message['m']['message_content']}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `
                                $("#messageInfo").append(messageCard);
                            });
                        }
                    },
                    error: function() {
                        console.log("Get search error.");
                    }
                })
            }
        });

        $("#deleteConversation").click(function() {
            var message_thread_id = message_thread[0]['mt']['message_thread_id'];
            if (confirm("Are you sure you want to delete all the messages in this conversation?")) {
                $.ajax({
                    type: 'POST',
                    url: '/cake2project/messages/deleteMessageThread',
                    data: {
                        message_thread_id: message_thread_id
                    },
                    success: function() {
                        window.location.href = '/cake2project/messages/';
                    },
                    error: function() {
                        console.log("Delete Conversation Error.");
                    }


                });
            }
        });

        $("#userInfoDiv").click(function() {
            console.log("user info clicked!");
            $('#userInfoModal').modal('show');

        });

        $("#closeUserInfoBtn").click(function() {
            $('#userInfoModal').modal('hide');

        });

        $("#show-more-div").click(function() {
            var numberOfBubbles = $("#messages-container").find(".chat-bubble").length;
            var message_thread_id = message_thread[0]['mt']['message_thread_id'];
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: '/cake2project/messages/showMoreMessages',
                data: {
                    numberOfBubbles: numberOfBubbles,
                    message_thread_id: message_thread_id
                },
                success: function(data) {
                    if(data.length > 0){

                    }else{
                        $("#show-more-div").hide();
                        $("#no-more-div").show();
                    }
                    // Assuming 'data.messages' is an array containing messages, similar to your original 'messages' array
                    data.forEach(function(message) {
                        var bubbleType = (message['receiver']['user_id'] == user_id ? 'user2' : 'user1');
                        var profilePicture = webroot + message['sender']['img_url'];

                        // Shorten the message content if it's longer than 50 characters
                        var messageContent = message['m']['message_content'];
                        if (messageContent.length > 50) {
                            messageContent = messageContent.slice(0, 50) + '...';
                        }

                        var chatBubble = `
                            <div class="chat-bubble ${bubbleType}" title="click to show more/show less">
                                <img src="${profilePicture}" alt="Profile Picture" class="user-avatar">
                                <div class="chat-text">
                                    <p class="message-content">${messageContent}</p>
                                    <span class="datetime">${message[0]['formatted_created_date']}</span>
                                </div>
                                ${bubbleType == 'user1' ? `<button class="delete-button" data-message-id="${message['m']['message_id']}">x</button>` : ''}
                            </div>
                        `;

                        // Add a click event listener to toggle the message content
                        var isShortened = true;
                        chatBubble = $(chatBubble); // Convert chatBubble to a jQuery object
                        chatBubble.find('.message-content').click(function() {
                            if (isShortened) {
                                $(this).text(message['m']['message_content']);
                            } else {
                                $(this).text(messageContent);
                            }
                            isShortened = !isShortened;
                        });

                        $("#messages-container").prepend(chatBubble);
                    });
                },
                error: function() {
                    console.log("Get new messages error.");
                }
            });

        });

        function formatCurrentDateTime() {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            };
            const formattedDate = new Date().toLocaleString(undefined, options);
            return formattedDate.replace("at ", "");
        }

    });
</script>