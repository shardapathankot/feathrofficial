jQuery(document).ready(function($) {
    // Function to update quantity and total price
    function updateQuantityAndTotal() {
        var selectedValue = $('input[name="pofw_option[2]"]:checked').val(); // Get the value of the selected radio button
        var quantity = 1; // Default quantity
        var price = 10; // Example price per item (adjust as per your need)

        // Adjust quantity and price based on selected bulk option
        switch(selectedValue) {
            case '6':
                quantity = 5;
                break;
            case '7':
                quantity = 10;
                break;
            case '8':
                quantity = 25;
                break;
            default:
                quantity = 1; // Default to 1 if none of the specified cases match
        }

        // Update quantity input field (if you have one)
        $('input.qty').val(quantity);

        // Update total price display on product list page (if available)
        $('.total-price').text('$' + (quantity * price).toFixed(2));

        // Update total price display on product detail page (if available)
        $('.product-detail-total-price').text('$' + (quantity * price).toFixed(2));
    }

    // Ensure the first radio button is selected by default
    $('input[name="pofw_option[2]"]:first').prop('checked', true);

    // Initial call to update based on default checked option
    updateQuantityAndTotal();

    // Update on radio button change
    $('input[name="pofw_option[2]"]').change(function() {
        updateQuantityAndTotal();
    });
});

$(document).ready(function() {
    // Attach a click event handler to all radio buttons
    $(".pofw-option").on("click", function() {
        // Remove 'active' class from all choices
        $(".options-list .choice").removeClass("active");
        
        // Add 'active' class to the parent of the clicked radio button
        $(this).closest(".choice").addClass("active");
    });

    // Set the first choice as active on page load
    $(".options-list .choice:first").addClass("active");
});
