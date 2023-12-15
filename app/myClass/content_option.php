<?php


namespace App\myClass;


class content_option
{
    public function option()
    {
        $static_field = [
            "value" => [
                "name" => "",
                "last_name" => "",
                "number" => "",
            ],
            "rules" => [
                "name" => "minmax:1,50|required",
                "last_name" => "",
                "number" => "standard",
            ],
            "fields_name" => [
                "name" => "*name",
                "last_name" => "last_name",
                "number" => "phone",
            ]
        ];
        $dynamic_field = [
            [
                "title" => "email",
                "fields" => [
                    [
                        "label" => "personal email",
                        "option" => [
                            "id" => 0,
                            "field_name" => "personal-email",
                            "rule" => "",
                            "value" => "",
                            "type" => "email"
                        ]
                    ],
                    [
                        "label" => "work email",
                        "option" => [
                            "id" => 0,
                            "field_name" => "work-email",
                            "rule" => "",
                            "value" => "",
                            "type" => "email",
                        ]
                    ],
                ],
            ],
            [
                "title" => "phone",
                "fields" => [
                    'personal_number' => [
                        "label" => "personal number",
                        "option" => [
                            "id" => 0,
                            "field_name" => "personal-number",
                            "rule" => "standard|required",
                            "value" => "",
                            "type" => "phone",
                        ]
                    ],
                    'home_number' => [
                        "label" => "home number",
                        "option" => [
                            "id" => 0,
                            "field_name" => "home-number",
                            "rule" => "",
                            "value" => "",
                            "type" => "phone",
                        ]
                    ],
                    'work_number' => [
                        "label" => "work number",
                        "option" => [
                            "id" => 0,
                            "field_name" => "work-number",
                            "rule" => "",
                            "value" => "",
                            "type" => "phone",
                        ]
                    ],
                ]
            ],
            [
                "title" => "other",
                "fields" => [
                    [
                        "label" => "other option",
                        "option" => [
                            "id" => 0,
                            "field_name" => "",
                            "rule" => "",
                            "value" => "",
                            "type" => "other",
                        ]
                    ]
                ]
            ],
        ];
        return ["static" => $static_field, "dynamic" => $dynamic_field];

    }

    public function sidebar()
    {
        return [
            [
                "icon" => "journals", //change to bootstrapvue
                "title" => "PhoneBook",
                "property" => [
                    ["icon" => "list-stars", "text" => 'All Contact', "method" => "showTable"]
                ]
            ],
            [
                "icon" => "ui-checks",
                "title" => "Todo",
                "property" => [
                    ["icon" => "list-check", "text" => 'All TODO', "method" => "showTodo"]
                ]
            ],
            [
                "icon" => "diagram3-fill",
                "title" => "Project",
                "property" => [
                    ["icon" => "list-task", "text" => 'All Project', "method" => "showProject"]
                ]
            ],
            [
                "icon" => "inbox-fill",
                "title" => "Crypto Currency",
                "property" => [
                    ["icon" => "cart3", "text" => 'your currency', "method" => "showMarket"]
                ]
            ]
        ];
    }

}
