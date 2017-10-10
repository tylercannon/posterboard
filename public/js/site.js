//set the maximum length of a post to 140 characters
var MAX_POST_LENGTH = 140;

//update the current number of characters the user has typed into
//the new post box (from the navbar)
function updateNumCharacters() {
    var contentNumChars = $("#postContent").val().length;
    $("#numCharacters").html(contentNumChars);

    if (contentNumChars > 0) {
        $("#postSubmitBtn").prop('disabled', false);
    }
    else {
        $("#postSubmitBtn").prop('disabled', true);
    }

    if (contentNumChars === MAX_POST_LENGTH) {
        $("#maxChars").css('color', 'red');
    }
    else {
        $("#maxChars").css('color', 'black');
    }
}

//update the current number of characters the user has typed into
//the new post box (from the board page)
function updateBoardNumCharacters() {
    var contentNumChars = $("#boardPostContent").val().length;
    $("#boardNumCharacters").html(contentNumChars);

    if (contentNumChars > 0) {
        $("#boardPostSubmit").prop('disabled', false);
    }
    else {
        $("#boardPostSubmit").prop('disabled', true);
    }

    if (contentNumChars === MAX_POST_LENGTH) {
        $("#boardMaxChars").css('color', 'red');
    }
    else {
        $("#boardMaxChars").css('color', 'black');
    }
}

//when the "what's happening" textarea on the board page is clicked into,
//expand the div to show more rows on the textarea, display the #
//of characters the user has currently typed in, and display the post button
$("#boardPostContent").focus(function() {
    $("#whatsHappening").css({'background-color': 'white', 'height': '175px'});
    $("#boardPostContent").prop('rows', 5);
    $("#boardMaxChars").prop('hidden', false);
    $("#boardPostSubmit").prop('hidden', false);
});

//When the user clicks out of the "what's happening" textarea on the board page,
//hide the expanded rows, # characters the user has typed in, and the post button
//(if applicable)
$("#boardPostContent").focusout(function() {
    //if there's anything in the post textarea, don't hide the post button
    if (this.value.length > 0) return;

    $("#whatsHappening").css({'background-color': 'transparent', 'height': 'auto'});
    $("#boardPostContent").prop('rows', 1);
    $("#boardMaxChars").prop('hidden', true);
    $("#boardPostSubmit").prop('hidden', true);
});

//set up a function delay configuration
//so we can wait x amount of ms before loading results
//when the user types in the search box on the navbar
var keypressDelay = (function() {
   var timer = 0;
   return function(callbackFn, timeOutInMs) {
       clearTimeout(timer);
       timer = setTimeout(callbackFn, timeOutInMs);
   };
})();

//hide the search results box if the search box is empty
$("#searchBox").focusout(function() {
    //don't hide the search results if the text is still in the search box
   if (this.value.length > 0) return;

   $("#searchResults").css('display', 'none');
});

//retrieve a list of users that match the user input from the search box
//Note: this has a 200 ms delay on it to prevent searching the database for
//      every letter the user has typed
function searchBoxKeyup() {
    //perform an ajax call every 200 ms after user keyup
    keypressDelay(function() {
        var searchVal = $("#searchBox").val();
        //reset search results
        $("#searchResults").html('');
        $("#searchResults").css('display', 'none');
        if (searchVal.length !== 0) {
            $.ajax({
                'url': '/search/' + searchVal,
                'method': 'GET',
                'success': function(data) {
                    $('#searchResults').css('display', 'block');
                    var test = data;
                    var htmlStr = '';
                    for(var i = 0; i < data.length; i++) {
                        var result = data[i];
                        htmlStr +=
                            '<div class="searchResult">' +
                                '<a href="/' + result["username"] + '"> ' + result["name"] + '</a>' +
                                '<span style="margin-left: 10px;"> @' + result["username"] + '</span>' +
                            '</div>';
                    }
                    $("#searchResults").html(htmlStr);
                },
                'error': function() {
                    $("#searchResults").html("No results found");
                }
            });
        }
    }, 200);
}