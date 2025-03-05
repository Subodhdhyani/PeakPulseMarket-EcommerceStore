{{--Show spinner when data process in  pages--}}
<div class="spinner-container" style="display: none;">
    <div class="spinner"></div>
</div>

<style>
    /* Spinner Container */
    .spinner-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;

    }

    /* Spinner Styling */
    .spinner {
        border: 6px solid #f3f3f3;
        /* Light gray background color */
        border-top: 6px solid #e74c3c;
        /* Red color for the spinning section */
        border-radius: 50%;
        width: 60px;
        /* size of the spinner */
        height: 60px;
        animation: spin 1s linear infinite;
    }

    /* Spinner Animation */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>