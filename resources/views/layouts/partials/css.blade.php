<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
<link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
{{-- <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" /> --}}
<link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<style type="text/css">
    .audio {
        /* Set the background color */
        background-color: #f0f0f0;

        /* Set the color of the volume control slider */
        --webkit-slider-thumb-color: #3498db;
        --moz-range-thumb-color: #3498db;
        --ms-thumb-color: #3498db;
        --o-range-thumb-color: #3498db;
        --webkit-slider-runnable-track-color: #bdc3c7;
        --moz-range-track-color: #bdc3c7;
        --ms-track-color: #bdc3c7;
        --o-range-track-color: #bdc3c7;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    textarea {
        min-height: auto;
    }

    .error {
        color: #ff3d3d;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .spacer {
        height: 10px;
    }

    .saved-note {
        background-color: #f2f2f2;
        /* Gray background color */
        padding: 10px;
        /* Add some padding to make it visually appealing */
        margin-bottom: 10px;
        /* Add some space between containers */
    }
    h4 {
        font-size: 14px; /* Adjust the font size as needed */
        font-weight: bold; /* Make the text bold */
        background-color: #f4f4f4; /* Light gray background */
        padding: 5px; /* Padding for spacing */
        border-radius: 3px; /* Rounded corners */
        display: inline-block; /* Make it inline-block to allow text to wrap */
        margin-bottom: 5px; /* Add some bottom margin for spacing */
    }
    .custom-tomorrow-heading {
        font-size: 14px;
        font-weight: bold;
        background-color: #f4f4f4;
        padding: 5px;
        border-radius: 3px;
        display: inline-block;
        margin-bottom: 0; /* Adjusted to reduce bottom margin */
        margin-top: 0; /* Adjusted to reduce top margin */
    }
    .content {
        width: 100%;
        /* Adjust the width based on your content */
        white-space: nowrap;
        /* Prevent line breaks */
        overflow-x: auto;
        overflow-y: hidden;
        /* or overflow-y: scroll; */
    }
    .countdown {
            text-align: center;
            font-size: 1rem;
            padding: 6px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }


        .custom-audio-player {
        width: 200px;
        margin: 10px;
        background-color: ; /* Set the background color to black */
        color: black; /* Set the text color to white */
        border-radius: 5px; /* Optional: Add border radius for rounded corners */
    }

    /* Customize the audio player controls */
    .custom-audio-player audio {
        width: 100%;
    }

    /* Customize the play button color */
    .custom-audio-player audio::-webkit-media-controls-play-button {
        color: white;
    }

    .custom-audio-player audio::-webkit-media-controls-start-playback-button {
        color: white;
    }

    .custom-audio-player audio::-webkit-media-controls-pause-button {
        color: white;
    }

/* Add tick mark for the selected rows */
/* Add tick mark for the selected rows */
.dataTables tbody tr.selected td:first-child::before {
    content: '\2713'; /* Unicode character for checkmark */
    font-size: 1.2em;
    color: green; /* Change the color as desired */
    text-align: center;
    display: block;
}

   </style>
<style>
    .popup {
        display: none;
        position: fixed;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        width: 400px;
        /* Adjust the width as needed */
        height: 300px;
        /* Adjust the height as needed */
    }
</style>
<style>
    .popup {
        display: none;
    }

    .show {
        display: block;
    }
</style>
<style>
    .edit-field.error {
        background-color: #ffcccc;
        /* Change this to the desired shade of red */
    }
</style>


<style>
    .myDiv {
        display: none;
        padding: 10px;
        margin-top: 20px;
    }


    #showOther {}

    #showfollowup {}

    #showsitevisitScheduled {}
</style>


