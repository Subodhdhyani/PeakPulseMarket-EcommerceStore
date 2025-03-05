// Function to fetch cart count
function fetch_cart_nav_count() {
    $.ajax({
        url: "/fetch_cart_nav_count",
        type: "GET",
        success: function (response) {
            if (response.status === "success") {
                var totalQuantity = response.total_quantity;
                $("#cart-count").find("sup").text(totalQuantity);
            }
        },
        error: function () {
            console.error("Failed to fetch cart data.");
        },
    });
}
