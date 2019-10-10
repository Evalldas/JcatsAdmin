/**
 * Displaying and closing modals
 * @param {String} modalId Defines modal's that will be displayed or closed Id
 */

 /**
  * Function for displaying modals
  */
function displayModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

/**
 * Function for closing modals
 */
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}