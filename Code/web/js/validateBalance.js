//input validation for out account creation and login form
// we wait for the DOM to load
$(document).ready(function() {
    //Checks the withdrawal amount
    var validator = $('#withdrawSnapCashForm').validate({
        rules: {
            withdraw_amount: {
                required: true,
                digits: true,
                min: 1
            }
        },
        messages: {
            withdraw_amount: {
                digits: "Please enter a valid number",
                required: "Please enter an amount to withdraw",
                min: "Please enter an amount greater than 0"
            }
        },
        submitHandler: function(form) {
            form.submit();
        },
        errorPlacement: function(error, element) {
            error.insertBefore(element);
        }
    });
});