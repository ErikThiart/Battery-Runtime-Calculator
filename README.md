# Battery-Runtime-Calculator
Calculate your battery runtime using Watts

# Live DEMO

https://erikthiart.com/tools/calculate-battery-runtime-using-watts.php

# Support

If you find this software helpful and would like to support the development of more free tools like this, please consider making a donation through PayPal at paypal.me/erikthiart. Your support is greatly appreciated and helps me to continue creating useful resources for the community. Thank you!

## Overview

This is a PHP script that calculates the run time of a battery using various inputs such as voltage, amp-hours, load in watts, inverter type, and battery type. 

The script first checks if the request method is POST, indicating that a form has been submitted. 

It then creates an array of form values to check and loops through each value, trimming it of leading and trailing whitespace and checking if it is empty or not set. 

If any of the values are empty or not set, an error message is added to the output variable. 

If all of the form values are set, the script calculates the run time of the battery based on the provided values and the specific efficiency and depth of discharge (DoD) for the given inverter type and battery type. 

The script then calculates the number of hours, minutes, and seconds based on the run time and adds a success message to the output variable with the run time in hours, minutes, and seconds. 

The script then displays the output variable, which will either be an error message or the run time message, within a Bootstrap alert box.

## Formula

Volts of the battery x Ah rating of the battery / Watts of the load / The battery’s depth of discharge (DoD) x The efficiency of the inverter

## TO DO

### Refactor

#### Switch Cases

To refactor these switch statements, one can use an array to map the values of `battery_type` and `inverter_type` to their corresponding depths of discharge and efficiencies, respectively.

```
// Map battery types to depths of discharge
$battery_type_to_dod = [
    'lead_acid' => 3,
    'lithium_lifepo_4' => 1.25,
];

// Set the depth of discharge (DoD) based on the battery type
$dod = $battery_type_to_dod[$form_values['battery_type']] ?? 3;

// Map inverter types to efficiencies
$inverter_type_to_efficiency = [
    'ups_apc' => 0.80,
    'ups_average' => 0.60,
    'inverter' => 0.90,
];

// Set the efficiency of the inverter based on the inverter type
$efficiency = $inverter_type_to_efficiency[$form_values['inverter_type']] ?? 0.65;

```

Using this approach, one can avoid the use of switch statements and make the code easier to read and maintain.

#### Run Time Calculator

One could consider creating a function to handle the calculations for the run time. This would make the code easier to read and maintain, as well as easier to reuse.

Here is an example of how this refactored code could look:

```
function calculateRunTime($voltage, $amp_hour, $load_watts, $dod, $efficiency) {
    // Calculate the run time using the formula - Volts of the battery x Ah rating of the battery / Watts of the load / The battery's depth of discharge (DoD) x The efficiency of the inverter
    $run_time = ($voltage * $amp_hour * $efficiency) / ($load_watts * $dod);

    // Calculate the number of hours
    $hours = floor($run_time);

    // Calculate the number of minutes and seconds
    $minutes_seconds = $run_time - $hours;
    $minutes_seconds *= 60;
    $minutes = floor($minutes_seconds);
    $seconds = round(($minutes_seconds - $minutes) * 60);

    // Return the run time as a string
    return "$hours hours $minutes minutes $seconds seconds";
}

```

To use this function, one would call it like this:

```
$run_time = calculateRunTime($form_values['voltage'], $form_values['amp_hour'], $form_values['load_watts'], $dod, $efficiency);
$output = '<div class="alert alert-success lead"><strong>Run time:</strong> ' . $run_time . '</div>';

```

