/**
 * GLOBAL variables
 */
var msg_success = "Success!";
var msg_ip_duplicate =
    "IP address you've entered is already in the database, please check the IP address before submiting";
var msg_name_duplicate =
    "Name you've entered is already in the database, please check the name before submiting";
var msg_undefined_err = "Unknown error has occured, please try again or contact your system administrator";
var msg_id_duplicate = "ID you've entered is already in the database, please check the ID before submiting";
var msg_err_no_data = "Error, failed to retrieve data from the database";
/**
 * jQuery functions
 * make sure jQuery library is imported before jQuery code or it won't work
 */
$(function() {
    /**
     * This function handles addNewStation form
     * 
     * @param {String}  event       Helps to control the event called by the HTML form
     * 
     * @return void
     */
    $("#addClientForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields

        // Send the POST method
        $.post(url, post_data, function(o) {

            /**
             * Client controller checks if there are no duplicates and returns either 
             * 1 for succsessful insertion, 2 for name duplicate, 3 for IP duplicate or 0 for undefined error
             * If station has been added, alert about success or error and redirect back to the previous page
             */
            if (o.result == 1) {
                alert(msg_success);
                window.location.href = base_url + "dashboard/manage_stations";
            } else if (o.result == 2) {
                alert(msg_name_duplicate);
            } else if (o.result == 3) {
                alert(msg_ip_duplicate);
            } else {
                alert(msg_undefined_err);
            }
        }, 'json');

    });

    /**
     * This function handles addNewServer form
     * 
     * @param {String}  event       Helps to control the event called by the HTML form
     * 
     * @return void
     */
    $("#addServerForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields

        // Send the POST method
        $.post(url, post_data, function(o) {

            /**
             * Server controller checks if there are no duplicates and returns either 
             * 1 for succsessful insertion, 2 for ID duplicate, 3 for name duplicate, 4 for IP duplicate or 0 for undefined error
             * If station has been added, alert about success or error and redirect back to the previous page
             */
            if (o.result == 1) {
                alert(msg_success);
                window.location.href = base_url + "dashboard/manage_stations";
            } else if (o.result == 2) {
                alert(msg_id_duplicate);
            } else if (o.result == 3) {
                alert(msg_name_duplicate);
            } else if (o.result == 4) {
                alert(msg_ip_duplicate);
            } else {
                alert(msg_undefined_err);
            }
        }, 'json');

    });

    /**
     * This function handles editStation form
     * 
     * @param {String}  event       Helps to control the event called by the HTML form
     * 
     * @return void
     */
    $("#editStationForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields
        // Send the POST method
        $.post(url, post_data, function(o) {

            /**
             * Client controller checks if there are no duplicates and returns either 
             * 1 for succsessful insertion, 2 for name duplicate, 3 for IP duplicate or 0 for undefined error
             * If station has been added, alert about success or error and redirect back to the previous page
             */
            if (o.result == 1) {
                alert(msg_success);
                window.location.href = base_url + "dashboard/manage_stations";
            } else if (o.result == 2) {
                alert(msg_name_duplicate);
            } else if (o.result == 3) {
                alert(msg_ip_duplicate);
            } else {
                alert(msg_undefined_err);
            }
        }, 'json');

    });

    /**
     * This function handles editServer form
     * 
     * @param {String}  event       Helps to control the event called by the HTML form
     * 
     * @return void
     */
    $("#editServerForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields
        // Send the POST method
        $.post(url, post_data, function(o) {

            /**
             * Server controller checks if there are no duplicates and returns either 
             * 1 for succsessful insertion, 2 for ID duplicate, 3 for name duplicate, 4 for IP duplicate or 0 for undefined error
             * If station has been added, alert about success or error and redirect back to the previous page
             */
            if (o.result == 1) {
                alert(msg_success);
                window.location.href = base_url + "dashboard/manage_stations";
            } else if (o.result == 2) {
                alert(msg_id_duplicate);
            } else if (o.result == 3) {
                alert(msg_name_duplicate);
            } else if (o.result == 4) {
                alert(msg_ip_duplicate);
            } else {
                alert(msg_undefined_err);
            }
        }, 'json');
        
    });
});

/**
 * AJAX functions to display dynamic modal content
 */
$(document).ready(function() {

    // Opent modal to edit station
    $('.openEditStationModal').click(function() {
        var id = $(this).attr('relid'); // Get attribute value
        var url = base_url + "client/get/"; // Define action URL 
        $.ajax({
            url: url,
            data: {
                id: id // Data that goes in to the GET request
            },
            method: 'GET', // Define request method
            dataType: 'json',
            success: function(response) {
                // If response is not empty
                if (response.result[0]) {
                    // Set input field values to the response values
                    document.getElementById("stationNameInput").value = response.result[0]
                        .name;
                    document.getElementById("stationIpInput").value = response.result[0].ip;
                    document.getElementById("stationIdInput").value = response.result[0].id;
                } else {
                    // Set the error message
                    document.getElementById("databaseErrorMessage").innerHTML =
                        msg_err_no_data;
                }
            },
            error: function() {
                document.getElementById("databaseErrorMessage").innerHTML =
                    msg_err_no_data;
            }
        });
    });

    // Open modal to edit server
    $('.openEditServerModal').click(function() {
        var id = $(this).attr('relid'); // Get attribute value
        var url = base_url + "server/get/"; // Define action URL 
        $.ajax({
            url: url,
            data: {
                id: id // Data that goes in to the GET request
            },
            method: 'GET', // Define request method
            dataType: 'json',
            success: function(response) {
                // If response is not empty
                if (response.result[0]) {
                    // Set input field values to the response values
                    document.getElementById("serverNewIdInput").value = response.result[0].id;
                    document.getElementById("serverIdInput").value = response.result[0].id;
                    document.getElementById("serverNameInput").value = response.result[0]
                        .name;
                    document.getElementById("serverIpInput").value = response.result[0].ip;
                    document.getElementById("serverDomainNameInput").value = response.result[0].domain_name;
                } else {
                    // Set the error message
                    document.getElementById("databaseErrorMessage").innerHTML =
                        msg_err_no_data;
                }
            },
            error: function() {
                document.getElementById("databaseErrorMessage").innerHTML =
                    msg_err_no_data;
            }
        });
    });
});