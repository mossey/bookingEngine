<?php
require_once('vendor/autoload.php');

$stripe = array(
    "secret_key"      => "sk_test_uANxJWuoYO5E73VVN4gu9UOK",
    "publishable_key" => "pk_test_Ka4YnAiHc9YhOmPJDSlYgRQt"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
