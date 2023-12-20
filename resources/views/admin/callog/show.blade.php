@extends('layouts.admin')

@section('content')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-Gn5384xqQ1QSv5+76k8u6Z5PJR3pe6eg/9S2EmtA4IjToA1AZ8W8UqQ4lvSs9zFlk2Zl8S2GnjXjbhMz9DDs1Q==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <div class="container">
        <h1>Call Flow</h1>

        <div class="horizontal-call-flow">
            @foreach ($callFlow as $step)
                <div class="call-step-container">
                    <div class="call-step">
                        @if (array_key_exists('type', $step))
                            @if ($step['type'] == 'init')
                                <div class="icon-container init">
                                    <i class="fas fa-phone"></i>
                                </div>
                            @elseif ($step['type'] == 'Agent')
                                <div class="icon-container agent">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-container">
                                    @if (array_key_exists('name', $step) && array_key_exists('dialst', $step))
                                        <p>{{ $step['name'] }} ({{ $step['dialst'] }})</p>
                                        <p>{{ $callRecord['call_id_number'] }}</p>
                                    @endif
                                </div>
                            @elseif ($step['type'] == 'ClickToCall')
                                <div class="icon-container click-to-call">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-container">
                                    @if (array_key_exists('name', $step))
                                        <p>{{ $step['name'] }}</p>
                                    @endif
                                </div>
                            @elseif ($step['type'] == 'hangup')
                                <div class="icon-container hangup">
                                    <i class="fas fa-phone-slash"></i>
                                </div>
                            @endif

                            <div class="type-time-container">
                                <p>{{ $step['type'] }}</p>
                                <p>{{ $step['readableTime'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection


<style>
    .horizontal-call-flow {
        display: flex;
        /* Use flexbox to display elements in a row */
    }

    .call-step-container {
        display: flex;
        align-items: center;
        margin: 10px;
    }

    .call-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Arrange icon, type, and time horizontally */
        margin: 0 10px;
        /* Add some spacing between call steps */
    }

    .icon-container {
        border-radius: 50%;
        background-color: #ccc;
        padding: 10px;
        margin-right: 10px;
        /* Add some spacing between icon and type-time container */
    }

    .info-container {
        /* Add styling for the container that holds additional information for Agent and ClickToCall */
    }

    .type-time-container {
        display: flex;
        flex-direction: column;
        /* Arrange type and time vertically */
        text-align: center;
    }

    .icon-container i {
        font-size: 24px;
        /* Adjust the icon size */
    }

    .icon-container.init {
        /* Add specific styling for the init type */
    }

    .icon-container.agent {
        /* Add specific styling for the Agent type */
    }

    .icon-container.click-to-call {
        /* Add specific styling for the ClickToCall type */
    }

    .icon-container.hangup {
        /* Add specific styling for the hangup type */
    }
</style>
