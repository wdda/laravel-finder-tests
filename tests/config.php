<?php
return  [
    'directory' => [
        [
            'rootPath' => '.',
            'classes' => [
                'dir' => 'src',
                'exclude' => [
                    'config.php'
                ],
                'methodsExclude' => [
                    '__construct'
                ]
            ],
            'tests' => [
                'dir' => 'tests/unit',
            ]
        ]
    ]
];
