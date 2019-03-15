NaiaCode
========

De/Serialization text with meta from/to human readable text string.


    $str =
    "Any-Header: with value
    Meta: {"vendor":"zim","ver":"0.4"}
    Content-Type: text/x-zim-wiki


    And Lorem ipsum doler ist.";

    (new Decoder())->decode($str) == [
        "And Lorem ipsum doler ist.",
        [
            'any-header' => "with value",
            'meta' => (object) [
                'vendor' => 'zim',
                'ver' => '0.4',
            ],
            'content-type' => "text/x-zim-wiki",
        ]
    ]

And encoding similarly.
