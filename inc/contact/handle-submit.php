<?php

use JetBrains\PhpStorm\NoReturn;

add_action('wp_ajax_submit_contact_form', 'submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'submit_contact_form');

function submit_contact_form()
{
    $reqName = $_POST["name"];
    $reqEmail = $_POST["email"];
    $reqMessage = $_POST["message"];
    $reqReCaptcha = $_POST["reCaptcha"];

    //SANITIZE
    if (isset($reqName) && !empty($reqName)) {
        $name = filter_var($reqName, FILTER_SANITIZE_STRING);
    } else {
        skil3e_sendResponse(400, "Bad request", "First name is required.");
    }

    if (isset($reqEmail) && !empty($reqEmail)) {
        $email = filter_var($reqEmail, FILTER_SANITIZE_EMAIL);
    } else {
        skil3e_sendResponse(400, "Bad request", "Email is required.");
    }

    if (isset($reqMessage) && !empty($reqMessage)) {
        $message = filter_var($reqMessage, FILTER_SANITIZE_STRING);
    } else {
        $message = "";
    }

    if (isset($reqReCaptcha) && !empty($reqReCaptcha)) {
        $captcha = filter_var($reqReCaptcha, FILTER_SANITIZE_STRING);
    } else {
        skil3e_sendResponse(400, "Bad request", "Failed to generate ReChaptcha.");
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode(RECAPTCHA_SECRET_KEY) . '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response, true);

    if ($responseKeys["success"]) {

        $template = get_stylesheet_directory() . "/inc/contact/template.php";

        if (file_exists($template)) {
            $HtmlBody = file_get_contents($template);
        } else {
            wp_die();
        }

        //Settings variables
        $mail_to = get_option("admin_email");
        $mail_from = get_option("admin_email");
        $mail_headers = array('From: ' . get_option("blogname") . '<' . $mail_from . '>', 'Content-Type: text/html; charset=UTF-8');
        $mail_subject = get_option("blogname") . " | Contact form submission";

        $swap_var = [
            "{SUBJECT}" => $mail_subject,
            "{NAME}" => $name,
            "{EMAIL}" => $email,
            "{MESSAGE}" => $message,
        ];
        foreach (array_keys($swap_var) as $key) {
            if (strlen($key) > 2 && trim($key) != "") {
                $HtmlBody = str_replace($key, $swap_var[$key], $HtmlBody);
            }
        }
        wp_mail($mail_to, $mail_subject, $HtmlBody, $mail_headers);
        skil3e_sendResponse(200, "Success", "Thank you for your message!");
    }

    skil3e_sendResponse(400, "Error", 'There was an unexpected error. :(');
}


function skil3e_sendResponse($code, $statusText, $message)
{
    header('HTTP/1.1 ' . $code . ' ' . $statusText);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(
        [
            "code" => $code,
            "message" => $message
        ]
    );
    wp_die();
}
