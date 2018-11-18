
// Display Message and Status of the Game
function farmGameAlert(msg, element) {
    $("#display-"+element).html(msg);
}

enterKeyFlag = true; // Flag to maintain enter key press for next turn

$(document).ready(function(){

    // This call is made at the start to get all game settings from server
    $.get("api/get-farm-game-details.php", function(data, status){
        /*
        These settings make sure you can change the game details
        only from one place i.e. server
        */
        farmEntities = data.farmEntities;
        totalturnCount = data.totalturnCount;
        farmerTitle = data.farmerTitle;
        // if Handle scenario via JS and display message
        // farmWinEntities = data.farmWinEntities;

        // Set the eaters config here
        eaters = {};
        for (var l in farmEntities) {
            eaters[farmEntities[l]] = 0;
        }

        turnCount = 0;

        // Dynamically set the table headers
        var tableHeaderHtml = '<tr ><th>Round</th>';
        for (var k in eaters) {
            // to set id so as to later on make cell red if dead
            str = k.replace(/\s+/g, '-');
            tableHeaderHtml = tableHeaderHtml + '<th id='+ str +'>' + k + '</th>';
        }
        tableHeaderHtml = tableHeaderHtml + '</tr>';
        $('#fed-chart').append(tableHeaderHtml);

        // Object to used to send as response
        farmDetails = {
            eaters
        };

        // Allow next turn only when ready
        $('#feed-anyone').prop('disabled', false);
    });


    $('#feed-anyone').on('click', function(){
        // Allow next turn only when ready
        $('#feed-anyone').prop('disabled', true);

        // Get the details after a turn 
        $.post(
            "api/next-turn.php",
            farmDetails,
            function(data, status){
                if (data['status'] == 'Invalid Input') {
                    alert('Invalid input. Kindly contact authorized technician.')
                }
                else {
                    farmDetails = data;

                    // Dynamically set the table rows here
                    var tableRowsHtml = '<tr><td>'+ farmDetails['turnCount']
                        +'</td>';
                    // Handle scenario via JS and display message
                    // var farmerDeadFlag = false;
                    for (var j in farmEntities) {
                        var cellValue = '';
                        var cellColor = '';
                        if (farmDetails['eaters'][farmEntities[j]] == 0) {
                            cellValue = 'Fed';
                        }
                        else if(farmDetails['eaters'][farmEntities[j]] == undefined)
                        {
                            if (farmEntities[j] == farmerTitle) {
                                // Handle scenario via JS and display message
                                // farmerDeadFlag = true;
                                enterKeyFlag = false;
                            }
                            cellValue = 'Dead';
                            cellColor = 'style="background-color:red"';
                            str = farmEntities[j].replace(/\s+/g, '-');
                            $('#'+str).css('background-color', 'red');
                        }
                        tableRowsHtml = tableRowsHtml + '<td '+ cellColor +'>'
                            + cellValue + '</td>';
                    }
                    tableRowsHtml = tableRowsHtml + '</tr>';
                    $('#fed-chart').append(tableRowsHtml);

                    // If you want to handle it in JS, kindly uncomment the Js code
                    // Handle scenario via JS and display message
                    /*if (farmerDeadFlag) {
                        farmGameAlert('Farmer Dead. Game Over', 'result');
                    }*/

                    if (farmDetails['turnCount'] == totalturnCount) {
                        enterKeyFlag = false;
                        // Handle scenario via JS and display message
                        /*for (var k in farmWinEntities) {
                            var winFlag = false;
                            for (var d in farmDetails['eaters']) {
                                if (d.indexOf(farmWinEntities[k]) > -1) {
                                    winFlag = true;
                                    break;
                                }
                            }
                            if (winFlag == false) {
                                break;
                            }
                        }
                        if (winFlag == true) {
                            farmGameAlert('You win.', 'win')
                        }*/

                        // Handle message via JS
                        // farmGameAlert('Game Over', 'result');
                        $('#feed-anyone').prop('disabled', true);
                    }
                    // Display messages from server
                    var message = '';
                    for (var b in farmDetails['message']['msg']) {
                        message = message + '<br/>'
                            + farmDetails['message']['msg'][b];
                    }
                    farmGameAlert(message, 'msg');

                    farmGameAlert(farmDetails['message']['status'], 'result');

                    // Allow next turn only when ready
                    if (farmDetails['message']['status'] == 'Play again') {
                        $('#feed-anyone').prop('disabled', false);
                    }
                }
            }
        );

        // Scroll down towards the button after every turn
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#feed-anyone").offset().top
        }, 2);

    });

    // Allow next turn on Enter Key Press
    $(document).keypress(function(e) {
        if(e.which == 13 && enterKeyFlag) {
            $('#feed-anyone').trigger("click");
        }
    });

    // Restart the game whenever required
    $('#restart-game').on('click', function() {
        window.location.reload();
    });
});
