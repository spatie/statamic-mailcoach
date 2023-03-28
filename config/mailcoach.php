<?php

return [
    'api_url' => env('MAILCOACH_API_URL'),

    'api_token' => env('MAILCOACH_API_TOKEN'),

    /*
     * If you want to add to your mailchimp audience when a user registers, set this to `true`
     */
    'add_new_users' => false,

    'users' => [
        /*
        * The uuid of your email list.
        */
        'email_list_uuid' => null,

        /*
        * To disable double opt-in, which is not reccommended
        */
        'disable_double_opt_in' => false,

        /*
        * Any extra tags you'd like to add to the subscriber
        */
        'tags' => [],

        /*
        * Extra attributes
         */
        'attributes' => [
            [
                /*
                * The attribute key
                */
                'key'=> null,

                /*
                * the blueprint field name to use for the merge field
                */
                'field_name' => null,
            ],
        ],

        /*
        * Define the handle for the email field to be used. Defaults to 'email'.
        */
        'primary_email_field' => 'email',
    ],

    /*
     * The form submissions to add to your Mailchimp Audiences
     */
    'forms' => [
        //[
        //    /*
        //    * handle of the form to listen for
        //    */
        //    'form' => null,
        //
        //    /*
        //    * The email list uuid
        //    */
        //    'email_list_uuid' => null,
        //
        //    'disable_double_opt_in' => false,
        //
        //    'tags' => [],
        //
        //    'attributes' => [
        //        [
        //            /*
        //            * The Mailcoach attribute key
        //            */
        //            'key'=> null,
        //
        //            /*
        //            * the blueprint field name to use for the merge field
        //            */
        //            'field_name' => null,
        //        ],
        //    ],
        //
        //    /*
        //    * Define the handle for the email field to be used. Defaults to 'email'.
        //    */
        //    'primary_email_field' => 'email',
        //]
    ],
];
