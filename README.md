# Chargily ePay Gateway PHP
Make your integration via Chargily ePay Gateway easier
- Chargily epay home page [Click here](https://epay.chargily.com.dz)
# Instalation
1. Via Composer (Recomended)
```bash
composer require medboubazine/chargily-checkout
```
2. Download as ZIP
We do not recommend this option. But be careful to download the updated versions every a while [Download](https://github.com/Medboubazine/Chargily-ePay-Gateway-PHP/releases/)
# Quick start

1. create redirect php file **redirect.php**

```php
use Medboubazine\Chargily\Chargily;
use Medboubazine\Chargily\Core\Configurations;

require './vendor/autoload.php';

$chargily = new Chargily(new Configurations([
                        //crenditionals
                        'api_key'=>'your-api-key',// you can you found it on your epay.chargily.com.dz Dashboard
                        'api_secret'=>'your-api-secret',// you can you found it on your epay.chargily.com.dz Dashboard
                        //urls
                        'urls'=>[
                            'back_url'=>"valid-url-to-redirect-after-payment",// this is where client redirected after payment processing
                            'webhook_url'=>"valid-url-to-process-after-payment-sucess",// this is where you recieve payment informations
                        ],
                        //mode
                        'mode'=>'EDAHABIA',//OR CIB
                        //payment details
                        'payment'=>[
                            'number'=>'payment-number-from-your-side',// Payment or order number
                            'client_name'=>'client name',// Client name
                            'client_email'=>'client_email@mail.com',// This is where client receive payment receipt after confirmation
                            'amount'=>75,//this the amount must be greater than or equal 75 
                            'discount'=>0,//this is discount percentage between 0 and 99.99
                            'description'=>'payment-description',// this is the payment description
                            
                        ],
                        //options
                        'options'=>[
                            'headers'=>[],
                            'timeout'=>20,
                        ],
                    ]));
// get redirect url
$redirectUrl = $chargily->getRedirectUrl();
//like : https://epay.chargily.com.dz/checkout/random_token_here
//
if($redirectUrl){
    //redirect
    header('Location: '.$redirectUrl);
}else{
    echo "We cant redirect to your payment now";
}
```
2. create process.php php file **process.php**

```php

use Medboubazine\Chargily\Chargily;
use Medboubazine\Chargily\Core\Configurations;

require './vendor/autoload.php';

$chargily = new Chargily(new Configurations([
                    //crenditionals
                    'api_key'=>'your-api-key',
                    'api_secret'=>'your-api-secret',
                ]));

if ($chargily->checkResponse()) {
    $response = $chargily->getResponseDetails();
    //@ToDo: Validate order status by $response['invoice']['invoice_number']. If it is not already approved, approve it.
    //something else
    /*
        Response like the follwing array
            "invoice"=>[
                "id" => 41441,
                "client" => "Client name",
                "invoice_number" => "100020",
                "due_date" => "2021-10-05 00:00:00",
                "status" => "paid",
                "amount" => 100,
                "fee" => 12.5,
                "discount" => 0,
                "comment" => "Payment description",
                "tos" => 1,
                "mode" => "EDAHABIA",
                "invoice_token" => "070229c5e7bd95ec17c76b02e5e209215776327b258796a6d1a3a89ff45a84c5",
                "due_amount" => 11250,
                "created_at" => "2021-10-05 06:10:38",
                "updated_at" => "2021-10-05 06:13:00",
                "back_url" => "https://www.dzecards.com/success",
                "new" => 1,
            ],
    */
    exit('OK');
}
exit;
```

# Configurations

- Avaialable Configurations

| key                   |  description                                                                                          | redirect url |  process url |
|-----------------------|-------------------------------------------------------------------------------------------------------|--------------|--------------|
| api_key               | must be string given by organization                                                                  |   required   |   required   |
| api_secret            | must be string given by organization                                                                  |   required   |   required   |
| urls                  | must be array                                                                                         |   required   | not required |
| urls[back_url]        | must be string and valid url                                                                          |   required   | not required |
| urls[process_url]     | must be string and valid url                                                                          |   required   | not required |
| mode                  | must be in **CIB**,**EDAHABIA**                                                                       |   required   | not required |
| payment[number]       | must be string or int                                                                                 |   required   | not required |
| payment[client_name]  | must be string                                                                                        |   required   | not required |
| payment[client_email] | must be string and valid email This is where client receive payment receipt after confirmation        |   required   | not required |
| payment[amount]       | must be numeric and greather or equal than  75                                                        |   required   | not required |
| payment[discount]     | must be numeric and between 0 and 99.99  (discount by percentage)                                     |   required   | not required |
| payment[description]  | must be string                                                                                        |   required   | not required |
| options               | must be array                                                                                         |   required   | not required |
| options[headers]      | must be array                                                                                         |   required   | not required |
| options[timeout]      | must be numeric                                                                                       |   required   | not required |

# Notice
- Good luck with the integration
- Open an issues if you faced a problem [Click here](https://github.com/Medboubazine/Chargily-ePay-Gateway-PHP/issues/new)
