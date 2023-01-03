if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Form values to check
    $form_values = [
        'voltage' => intval($_POST['voltage']),
        'amp_hour' => intval($_POST['amp_hour']),
        'load_watts' => intval($_POST['load_watts']),
        'inverter_type' => $_POST['inverter_type'],
        'battery_type' => $_POST['battery_type']
    ];

    // Check the form values
    foreach ($form_values as $name => $value) {
        // Remove leading and trailing whitespace from the value
        $value = trim($value);
        // Check if the value is an empty string or if it is not set
        if (empty($value)) {
            $output .= '<div class="alert alert-danger lead"><strong>Error:</strong> ' . $name . ' must not be an empty string nor a letter, only numbers are allowed.</div>';
            break;
        }
    }
    // Check if the output variable is empty
    if (empty($output)) {

        // Set the depth of discharge (DoD) based on the battery type
        switch ($form_values['battery_type']) {
            case "lead_acid":
                $dod = 3; // 3 = 33.3%
                break;
            case "lithium_lifepo_4":
                $dod = 1.25; // 1.25 = 80%
                break;
            default:
                $dod = 3; // fallback
        }

        // Set the efficiency of the inverter based on the inverter type
        switch ($form_values['inverter_type']) {
            case "ups_average":
                $efficiency = 0.60; // Average UPS like Mecer or RCT
                break;
            case "modified_sinewave_inverter":
                $efficiency = 0.75; // Generic Modified Sinewave Inverters - Avg Efficiency
                break;
            case "ups_apc":
                $efficiency = 0.80; // Good UPS like APC or KStar
                break;
            case "trolly_inverter":
                $efficiency = 0.83; // Mecer Trolly Inverters
                break;
            case "pure_sinewave_inverter":
                $efficiency = 0.90; // Generic Modified Sinewave Inverters - Avg Efficiency
                break;
            case "inverter":
                $efficiency = 0.95; // High end Inverter like SunSynk, Kodak, Deye
                break;
            default:
                $efficiency = 0.65; // Fallback, use lowest efficiecy for safety ( under promise, over deliver )
        }

        // Calculate the run time using the formula - Volts of the battery x Ah rating of the battery / Watts of the load / The battery's depth of discharge (DoD) x The efficiency of the inverter
        $run_time = ($form_values['voltage'] * $form_values['amp_hour'] * $efficiency) / ($form_values['load_watts'] * $dod);

        // Calculate the number of hours
        $hours = floor($run_time);

        // Calculate the number of minutes and seconds
        $minutes_seconds = $run_time - $hours;
        $minutes_seconds *= 60;
        $minutes = floor($minutes_seconds);
        $seconds = round(($minutes_seconds - $minutes) * 60);
        // Output the result
        $output = '<div class="alert alert-success lead"><strong>Run time:</strong> ' . $hours . ' hours ' . $minutes . ' minutes ' . $seconds . ' seconds</div>';
    }
}
