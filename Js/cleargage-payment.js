jQuery(document).ready(function ($) {
    // Access configuration parameters
    var apiKey = cleargage_params.api_key;

    // Add your JavaScript code to handle payment form interactions
    $('#cleargage-payment-form').submit(function (event) {
        // Prevent the form from submitting automatically
        event.preventDefault();

        // Perform client-side validation or customization
        var customField = $('#cleargage_custom_field').val();

        // Example: Check if the custom field is not empty
        if (customField.trim() === '') {
            alert('Please fill in the custom field.');
            return;
        }

        // If validation passes, proceed to submit the form to ClearGage API
        submitToClearGage(apiKey, customField);
    });

    // Function to submit the form to ClearGage API
    function submitToClearGage(apiKey, customField) {
        // Your logic to construct the request payload
        var payload = {
            apiKey: apiKey,
            customField: customField,
            // Add more parameters as needed
        };

        // Example: Use jQuery AJAX to send the request to ClearGage API
        $.ajax({
            url: 'https://cleargage-api.com/submit-payment',
            type: 'POST',
            data: payload,
            success: function (response) {
                // Handle the success response from ClearGage
                console.log('Payment successful:', response);
                // Optionally, redirect the user to a success page or update the UI
            },
            error: function (error) {
                // Handle the error response from ClearGage
                console.error('Payment failed:', error);
                // Optionally, display an error message to the user
            }
        });
    }
});
