<?php
/*
Plugin Name: Your Plugin Name
Description: A brief description of your plugin.
Version: 1.0
Author: Your Name
*/

function display_weather_for_current_location() {
    $latitude = '';
    $longitude = '';

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        list($latitude, $longitude) = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    }

    if (empty($latitude) || empty($longitude)) {
        // Fallback to IP geolocation (you may need to use an IP geolocation service)
        $ipInfo = json_decode(file_get_contents("http://ip-api.com/json"), true);
        $latitude = $ipInfo['lat'];
        $longitude = $ipInfo['lon'];
    }

    $apiKey = 'b2e738ad4caf589cc360a975795bf027'; // Replace with your OpenWeatherMap API key
    $apiData = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$apiKey}"), true);

    if (isset($apiData['main']['temp'])) {
        $temperature = intval($apiData['main']['temp'] - 273.15, 2); // Convert to Celsius
        $description = $apiData['weather'][0]['description'];
        $locationName = $ipInfo['city'];

        $output = "<div class='weather-card' style='width: 440px; background: linear-gradient(0.25turn, #ecf7fc, #33acdd, #a9dcf1); border-radius: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);'>";
        $output .= "<h2 style='font-size: 21px; color:#197195; font-family: Courier, monospace; font-weight: bold; padding:20px 10px;'>Weather of Your Current Location</h2>";
        $output .= "<div style='width: 100%; background-repeat:no-repeat;
        box-shadow: 0px 10px 10px -3px #cae9f6, 0px -6px 10px -4px #cae9f6;
            height: 300px;
            background-image: url(https://cdn.pixabay.com/photo/2020/12/14/15/45/rain-5831237_1280.jpg);
            background-size: cover;'></div>";
        $output .= "<p style='font-size: 35px; line-height: 4px; text-align: start; padding-left: 30px; color: #197195; font-family: Courier, monospace;'>{$locationName}</p>";
        $output .= "<p style='font-size: 70px; line-height: 4px; text-align: center; font-weight: bold; color: #197195; line-spacing:-50px; mrgin-top:-30px; font-family: Courier, monospace;'>{$temperature}&deg;C</p>";
        $output .= "<p style='font-size: 24px; line-height: 4px; text-align: end; margin-right: 40px; padding-bottom: 30px; color: #197195; font-family: Courier, monospace;'>{$description}</p>";
        $output .= "</div>";

        return $output;
    } else {
        return "<p>Error fetching weather data.</p>";
    }
}

add_shortcode('current_location_weather', 'display_weather_for_current_location');
?>