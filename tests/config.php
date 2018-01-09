<?php
return  [
    'directory' => [
        [
            'rootPath' => '/home/vagrant/Code/ErpB2b/packages/wdda/laravel-finder-tests',
            'classes' => [
                'dir' => 'src',
                'dirExclude' => 'app/Modules/Erp/Models/test',
                'exclude' => [
                    'config.php'
                ],
                'methodsExclude' => [
                    '__construct'
                ]
            ],
            'tests' => [
                'dir' => 'tests',
                'dirExclude' => 'app/Modules/Erp/Models/test',
            ]
        ]
    ]
];
