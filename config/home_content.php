<?php

/*
 * Every editable text on the public home page.
 * type: text | textarea (English + Dari) | shared (one value for both languages)
 *       | link (URL, empty = default page) | toggle (show / hide a whole section)
 * Whatever the admin leaves untouched keeps the default written here.
 */
return [
    'sections' => [

        'hero' => [
            'title' => 'Top banner (slider)',
            'help' => 'The big photo, heading, paragraph and main button are edited in Home Page → Hero Slider. The texts below are the small line above the heading and the two secondary buttons.',
            'fields' => [
                'hero_org' => ['label' => 'Small line above the heading', 'type' => 'text',
                    'en' => "GLOBAL WOMEN'S JUSTICE NETWORK · EST. 2021", 'fa' => 'شبکه جهانی عدالت برای زنان · تأسیس ۲۰۲۱'],
                'hero_heading' => ['label' => 'Heading (only used when no slider exists)', 'type' => 'text',
                    'en' => 'Advocating for justice, equality, freedom and the rights of women.', 'fa' => 'دفاع از عدالت، برابری، آزادی و حقوق زنان.'],
                'hero_text' => ['label' => 'Paragraph (only used when no slider exists)', 'type' => 'textarea',
                    'en' => 'An international network of women human rights defenders, organising campaigns, public actions and legal advocacy across fourteen countries.',
                    'fa' => 'شبکه‌ای بین‌المللی از مدافعان حقوق بشر زنان که در چهارده کشور کمپاین، اقدام عمومی و پیگیری حقوقی سازمان‌دهی می‌کند.'],
                'hero_btn1' => ['label' => 'Main button (only used when the slider has no button)', 'type' => 'text',
                    'en' => 'Join us', 'fa' => 'عضویت در شبکه'],
                'hero_btn2' => ['label' => 'Second button (goes to Events)', 'type' => 'text',
                    'en' => 'Upcoming events', 'fa' => 'رویدادهای پیش رو'],
                'hero_btn3' => ['label' => 'Third button (goes to About)', 'type' => 'text',
                    'en' => 'About us', 'fa' => 'درباره ما'],
            ],
        ],

        'stats' => [
            'title' => 'Numbers bar (four counters)',
            'help' => 'The white bar with four figures under the banner.',
            'toggle' => 'show_stats',
            'fields' => [
                'stats_1_num' => ['label' => 'Counter 1 - number', 'type' => 'shared', 'en' => '14'],
                'stats_1_label' => ['label' => 'Counter 1 - title', 'type' => 'text', 'en' => 'Countries', 'fa' => 'کشور'],
                'stats_1_caption' => ['label' => 'Counter 1 - small text', 'type' => 'text', 'en' => 'Across four continents', 'fa' => 'در چهار قاره'],
                'stats_2_num' => ['label' => 'Counter 2 - number', 'type' => 'shared', 'en' => '26'],
                'stats_2_label' => ['label' => 'Counter 2 - title', 'type' => 'text', 'en' => 'Cities', 'fa' => 'شهر'],
                'stats_2_caption' => ['label' => 'Counter 2 - small text', 'type' => 'text', 'en' => 'With active members', 'fa' => 'با اعضای فعال'],
                'stats_3_num' => ['label' => 'Counter 3 - number', 'type' => 'shared', 'en' => '38'],
                'stats_3_label' => ['label' => 'Counter 3 - title', 'type' => 'text', 'en' => 'Public actions', 'fa' => 'اقدام عمومی'],
                'stats_3_caption' => ['label' => 'Counter 3 - small text', 'type' => 'text', 'en' => 'Held since 2021', 'fa' => 'از سال ۲۰۲۱'],
                'stats_4_num' => ['label' => 'Counter 4 - number', 'type' => 'shared', 'en' => '61'],
                'stats_4_label' => ['label' => 'Counter 4 - title', 'type' => 'text', 'en' => 'Partner organisations', 'fa' => 'سازمان شریک'],
                'stats_4_caption' => ['label' => 'Counter 4 - small text', 'type' => 'text', 'en' => 'Signed our declaration', 'fa' => 'امضاکنندهٔ اعلامیهٔ ما'],
            ],
        ],

        'about' => [
            'title' => 'About section',
            'help' => 'Heading, paragraph, photo and button are edited in About → About Item. Show/hide this section in Home Page → Home Page Settings.',
            'fields' => [
                'about_badge_num' => ['label' => 'Badge on the photo - big text', 'type' => 'text', 'en' => '2021', 'fa' => '۲۰۲۱'],
                'about_badge_label' => ['label' => 'Badge on the photo - small text', 'type' => 'text', 'en' => 'Founded', 'fa' => 'سال تأسیس'],
                'about_more' => ['label' => 'Button text (only when About Item has no button)', 'type' => 'text', 'en' => 'Read more about us', 'fa' => 'مطالعهٔ کامل'],
            ],
        ],

        'campaign' => [
            'title' => 'Campaign banner',
            'help' => 'Title and text come from Pages → Custom Pages → Campaigns. Its background photo is set there too.',
            'toggle' => 'show_campaign',
            'fields' => [
                'campaign_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Current campaign', 'fa' => 'کمپاین فعلی'],
                'campaign_button' => ['label' => 'Button text', 'type' => 'text', 'en' => 'Read the full campaign', 'fa' => 'مطالعهٔ کامل کمپاین'],
                'campaign_num' => ['label' => 'Big number on the right', 'type' => 'shared', 'en' => '14'],
                'campaign_num_label' => ['label' => 'Text under the number', 'type' => 'text', 'en' => 'countries active', 'fa' => 'کشور در حال فعالیت'],
            ],
        ],

        'next' => [
            'title' => 'Next event strip',
            'help' => 'The coloured strip showing the nearest event (taken automatically from Events).',
            'toggle' => 'show_next',
            'fields' => [
                'next_chip' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Next public action', 'fa' => 'نزدیک‌ترین اقدام عمومی'],
                'next_btn' => ['label' => 'Button text (only when the event has no button text)', 'type' => 'text', 'en' => 'Join', 'fa' => 'پیوستن'],
            ],
        ],

        'work' => [
            'title' => 'Areas of work',
            'help' => 'The cards come from the menu (Our Work / News & Media sub-items).',
            'toggle' => 'show_work',
            'fields' => [
                'work_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'What we do', 'fa' => 'کار ما'],
                'work_heading' => ['label' => 'Heading', 'type' => 'text', 'en' => 'Areas of work', 'fa' => 'حوزه‌های کاری ما'],
                'work_desc' => ['label' => 'Short description', 'type' => 'textarea',
                    'en' => 'Each area combines documentation, public mobilisation and advocacy before international bodies.',
                    'fa' => 'هر حوزه، مستندسازی، بسیج عمومی و پیگیری در نهادهای بین‌المللی را در کنار هم دارد.'],
                'work_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'All our work', 'fa' => 'همهٔ فعالیت‌ها'],
                'work_card' => ['label' => 'Card text (when a page has no content)', 'type' => 'text', 'en' => 'Learn more', 'fa' => 'مشاهدهٔ جزئیات'],
            ],
        ],

        'team' => [
            'title' => 'Team section',
            'help' => 'Members come from Team Members. The heading and show/hide are in Home Page → Home Page Settings (if left empty there, the heading below is used).',
            'fields' => [
                'team_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Our people', 'fa' => 'تیم ما'],
                'team_heading' => ['label' => 'Heading (fallback)', 'type' => 'text', 'en' => 'Network coordinators', 'fa' => 'هماهنگ‌کنندگان شبکه'],
                'team_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'All members', 'fa' => 'همهٔ اعضا'],
            ],
        ],

        'events' => [
            'title' => 'Events section',
            'help' => 'Events come from Events. The heading can also be set in Home Page → Home Page Settings (if left empty there, the heading below is used).',
            'fields' => [
                'events_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Events', 'fa' => 'رویدادها'],
                'events_heading' => ['label' => 'Heading (fallback)', 'type' => 'text', 'en' => 'Protests, conferences and meetings', 'fa' => 'اعتراضات، کنفرانس‌ها و نشست‌ها'],
                'events_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'Full events calendar', 'fa' => 'تقویم کامل رویدادها'],
                'events_details' => ['label' => 'Button on each event', 'type' => 'text', 'en' => 'Details', 'fa' => 'جزئیات', 'scope' => '*'],
            ],
        ],

        'gallery' => [
            'title' => 'Gallery section',
            'help' => 'Photos come from Gallery.',
            'toggle' => 'show_gallery',
            'fields' => [
                'gallery_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Gallery', 'fa' => 'گالری'],
                'gallery_heading' => ['label' => 'Heading', 'type' => 'text', 'en' => 'Recent activities', 'fa' => 'فعالیت‌های اخیر'],
                'gallery_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'Photos & videos', 'fa' => 'عکس‌ها و ویدیوها'],
            ],
        ],

        'pubs' => [
            'title' => 'Publications section',
            'help' => 'Reports come from Publications.',
            'toggle' => 'show_pubs',
            'fields' => [
                'pubs_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Publications', 'fa' => 'انتشارات'],
                'pubs_heading' => ['label' => 'Heading', 'type' => 'text', 'en' => 'Recently published reports', 'fa' => 'گزارش‌های اخیر'],
                'pubs_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'All publications', 'fa' => 'همهٔ گزارش‌ها'],
                'pubs_download' => ['label' => 'Download button', 'type' => 'text', 'en' => 'Download PDF', 'fa' => 'دانلود PDF', 'scope' => '*'],
            ],
        ],

        'news' => [
            'title' => 'News section',
            'help' => 'Posts come from Blog → Posts. The heading can also be set in Home Page → Home Page Settings (if left empty there, the heading below is used).',
            'fields' => [
                'news_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'News & updates', 'fa' => 'اخبار و به‌روزرسانی‌ها'],
                'news_heading' => ['label' => 'Heading (fallback)', 'type' => 'text', 'en' => 'Latest from the network', 'fa' => 'تازه‌ترین‌های شبکه'],
                'news_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'All news', 'fa' => 'همهٔ اخبار'],
                'news_readmore' => ['label' => 'Button at the bottom of each news card (all pages)', 'type' => 'text', 'en' => 'Read more', 'fa' => 'ادامه مطلب', 'scope' => '*'],
            ],
        ],

        'paths' => [
            'title' => 'Get involved (three boxes)',
            'help' => 'Leave a link empty to use the default page.',
            'toggle' => 'show_paths',
            'fields' => [
                'paths_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Get involved', 'fa' => 'مشارکت'],
                'paths_heading' => ['label' => 'Heading', 'type' => 'text', 'en' => 'Three ways to take part', 'fa' => 'سه راه برای مشارکت'],
                'paths_1_title' => ['label' => 'Box 1 - title', 'type' => 'text', 'en' => 'Become a member', 'fa' => 'عضویت در شبکه'],
                'paths_1_text' => ['label' => 'Box 1 - text', 'type' => 'textarea',
                    'en' => 'Join a country chapter and help shape which campaigns we run next.',
                    'fa' => 'به شعبهٔ کشوری بپیوندید و در تعیین کمپاین‌های بعدی سهیم شوید.'],
                'paths_1_btn' => ['label' => 'Box 1 - button', 'type' => 'text', 'en' => 'Apply for membership', 'fa' => 'درخواست عضویت'],
                'paths_1_link' => ['label' => 'Box 1 - button link', 'type' => 'link', 'route' => 'register'],
                'paths_2_title' => ['label' => 'Box 2 - title', 'type' => 'text', 'en' => 'Volunteer or coordinate', 'fa' => 'داوطلبی یا هماهنگی'],
                'paths_2_text' => ['label' => 'Box 2 - text', 'type' => 'textarea',
                    'en' => 'Translate, document, organise a local action, or coordinate the network in your city.',
                    'fa' => 'ترجمه کنید، مستند کنید یا شبکه را در شهر خود هماهنگ کنید.'],
                'paths_2_btn' => ['label' => 'Box 2 - button', 'type' => 'text', 'en' => 'Volunteer with us', 'fa' => 'داوطلب شوید'],
                'paths_2_link' => ['label' => 'Box 2 - button link', 'type' => 'link', 'route' => 'contact'],
                'paths_3_title' => ['label' => 'Box 3 - title', 'type' => 'text', 'en' => 'Support our work', 'fa' => 'حمایت از کار ما'],
                'paths_3_text' => ['label' => 'Box 3 - text', 'type' => 'textarea',
                    'en' => 'Funds go to campaigns, delegate travel and independent documentation.',
                    'fa' => 'کمک‌ها صرف کمپاین‌ها، سفر نمایندگان و مستندسازی مستقل می‌شود.'],
                'paths_3_btn' => ['label' => 'Box 3 - button', 'type' => 'text', 'en' => 'Support our work', 'fa' => 'حمایت مالی'],
                'paths_3_link' => ['label' => 'Box 3 - button link', 'type' => 'link', 'route' => 'donate'],
            ],
        ],

        'partners' => [
            'title' => 'Partnerships section',
            'help' => 'The partner tiles come from the menu (Partnerships sub-items).',
            'toggle' => 'show_partners',
            'fields' => [
                'partners_kicker' => ['label' => 'Small label', 'type' => 'text', 'en' => 'Partnerships', 'fa' => 'همکاری‌ها'],
                'partners_heading' => ['label' => 'Heading', 'type' => 'text', 'en' => 'We work with organisations and institutions', 'fa' => 'همکاری با سازمان‌ها و نهادها'],
                'partners_desc' => ['label' => 'Short description', 'type' => 'textarea',
                    'en' => 'We partner with different kinds of organisations, from human rights groups to international bodies.',
                    'fa' => 'با انواع مختلف نهادها همکاری می‌کنیم — از سازمان‌های حقوق بشری تا نهادهای بین‌المللی.'],
                'partners_more' => ['label' => 'Link on the right', 'type' => 'text', 'en' => 'Propose a partnership', 'fa' => 'درخواست همکاری'],
            ],
        ],
    ],
];
