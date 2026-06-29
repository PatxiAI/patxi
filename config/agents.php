<?php

return [

    'agents' => [
        'work' => [
            'title' => 'Work',
            'agents' => [
                'marketing' => [
                    'package' => 'patxiai/agent-marketing',
                    'name' => 'Marketing',
                    'description' => 'Plans campaigns, grows your audience and runs paid acquisition across channels.',
                    'icon' => 'megaphone',
                    'sub-agents' => ['CommunityManager', 'SEOExpert', 'SocialAds', 'ContentStrategy', 'Copywriter', 'DigitalAnalyst'],
                ],
                'development' => [
                    'package' => 'patxiai/agent-development',
                    'name' => 'Development',
                    'description' => 'Ships features, runs your infrastructure and keeps everything secure and online.',
                    'icon' => 'code-bracket',
                    'sub-agents' => ['Developer', 'DevOps', 'SysAdmin', 'Security'],
                ],
            ],
        ],
    ],

];
