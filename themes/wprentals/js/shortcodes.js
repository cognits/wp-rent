/*global tinymce, jQuery, $, tb_show, tinyMCE, tb_remove */
/////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - places list
/////////////////////////////////////////////////////////////////////////////////////////



(function () {
    "use strict";
    tinymce.create('tinymce.plugins.places_list', {
        init: function (ed, url) {
            ed.addButton('places_list', {
                title: 'Places List',
                image: url + '/tiny_icons/places-list.png',
                onclick: function () {
                    ed.selection.setContent('[places_list place_list="ID,s of places separated by comma" place_per_row="4" ][/places_list]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('places_list', tinymce.plugins.places_list);
})();



///////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - recent_posts_slider_pictures
/////////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.slider_recent_items', {
        init: function (ed, url) {
            ed.addButton('slider_recent_items', {
                title: 'Recent Items',
                image: url + '/tiny_icons/recent-items-slider.png',
                onclick: function () {
                    ed.selection.setContent('[slider_recent_items title="Title Here" type="properties or articles" category_ids="" action_ids="" city_ids="" area_ids="" number="how many items"  show_featured_only="yes/no"][/slider_recent_items]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('slider_recent_items', tinymce.plugins.slider_recent_items);
})();





////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////  container-box
////////////////////////////////////////////////////////////////////////////////////////////////////////////////..

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.icon_container', {
        init: function (ed, url) {
            ed.addButton('icon_container', {
                title: 'Insert a Icon Content Box',
                image: url + '/tiny_icons/regular_container.png',
                onclick: function () {
                    ed.selection.setContent('[icon_container title="Box Title" image="image url" content_box="Box Content Here"  link="" icon_type="left or center" title_font_size="24"][/icon_container]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('icon_container', tinymce.plugins.icon_container);
})();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////  shortcode - spacer
////////////////////////////////////////////////////////////////////////////////////////////////////////////////..
(function () {
    "use strict";
    tinymce.create('tinymce.plugins.spacer', {
        init: function (ed, url) {
            ed.addButton('spacer', {
                title: 'Insert Blank Space',
                image: url + '/tiny_icons/spacer.png',
                onclick: function () {
                    ed.selection.setContent('[spacer type="1" height="40"][spacer]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('spacer', tinymce.plugins.spacer);
})();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////..
///  shortcode - advanced search
////////////////////////////////////////////////////////////////////////////////////////////////////////////////..

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.advanced_search', {
        init: function (ed, url) {
            ed.addButton('advanced_search', {
                title: 'Advanced Search',
                image: url + '/tiny_icons/advanced_search.png',
                onclick: function () {
                    ed.selection.setContent('[advanced_search title="Title here"][/advanced_search]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('advanced_search', tinymce.plugins.advanced_search);
})();


/////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - Item by id
///////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.list_items_by_id', {
        init: function (ed, url) {
            ed.addButton('list_items_by_id', {
                title: 'List Items by Id',
                image: url + '/tiny_icons/recent-items-2.png',
                onclick: function () {
                    ed.selection.setContent('[list_items_by_id title="Title Here" type="properties or articles" ids="" number="how many items/row"  align="vertical or horizontal" link="link to global listing"][/list_items_by_id]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('list_items_by_id', tinymce.plugins.list_items_by_id);
})();




///////////////////////////////////////////////////////////////////////////////////////
/////  shortcode - login form 
///////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.login_form', {
        init: function (ed, url) {
            ed.addButton('login_form', {
                title: 'Add a login_form',
                image: url + '/tiny_icons/user-login.png',
                onclick: function () {
                    ed.selection.setContent('[login_form register_label="register here" register_url="..." ]' + ed.selection.getContent() + '[/login_form]');

                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('login_form', tinymce.plugins.login_form);
})();




///////////////////////////////////////////////////////////////////////////////////////
///  shortcode - register_form 
///////////////////////////////////////////////////////////////////////////////////////


(function () {
    "use strict";
    tinymce.create('tinymce.plugins.register_form', {
        init: function (ed, url) {
            ed.addButton('register_form', {
                title: 'Insert Register Form',
                image: url + '/tiny_icons/register_form.png',
                onclick: function () {
                    ed.selection.setContent('[register_form][/register_form]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('register_form', tinymce.plugins.register_form);
})();




///////////////////////////////////////////////////////////////////////////////////////
///  shortcode - featured_property 
///////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.featured_property', {
        init: function (ed, url) {
            ed.addButton('featured_property', {
                title: 'Insert Featured Property',
                image: url + '/tiny_icons/featured-property.png',
                onclick: function () {
                    ed.selection.setContent('[featured_property id="property id" sale_line="sale line goes here"][/featured_property]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('featured_property', tinymce.plugins.featured_property);
})();





///////////////////////////////////////////////////////////////////////////////////////
///  shortcode - featured article
///////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.featured_article', {
        init: function (ed, url) {
            ed.addButton('featured_article', {
                title: 'Insert Featured Article',
                image: url + '/tiny_icons/featured-article.png',
                onclick: function () {
                    ed.selection.setContent('[featured_article id="article id" second_line="featured article"][/featured_article]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('featured_article', tinymce.plugins.featured_article);
})();




///////////////////////////////////////////////////////////////////////////////////////
///  shortcode - featured agent
///////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.featured_agent', {
        init: function (ed, url) {
            ed.addButton('featured_agent', {
                title: 'Insert Featured Agent',
                image: url + '/tiny_icons/featured-agent.png',
                onclick: function () {
                    ed.selection.setContent('[featured_agent id="agent id" notes="notes about agent"][/featured_agent]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('featured_agent', tinymce.plugins.featured_agent);
})();



/////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - recent_posts_pictures
/////////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.recent_items', {
        init: function (ed, url) {
            ed.addButton('recent_items', {
                title: 'Recent Items',
                image: url + '/tiny_icons/recent_posts.png',
                onclick: function () {
                    ed.selection.setContent('[recent_items title="Title Here" type="properties or articles" category_ids="" action_ids="" city_ids="" area_ids="" number="how many items" rownumber="no of items per row" link="link to global listing" full_row="yes/no"][/recent_items]');
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('recent_items', tinymce.plugins.recent_items);
})();


/////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - testimonials
/////////////////////////////////////////////////////////////////////////////////////////

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.testimonials', {
        init: function (ed, url) {
            ed.addButton('testimonials', {
                title: 'Insert Testimonials',
                image: url + '/tiny_icons/testimonial.png',
                onclick: function () {
                    ed.selection.setContent('[testimonial client_name="Name Here" title_client="happy client" imagelinks="link to client image" testimonial_text="Testimonial Text Here."][/testimonial]');

                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('testimonials', tinymce.plugins.testimonials);
})();



////////////////////////////////////////////////////////////////////////////////////////////////////////////////..
///  shortcode - font awesome
////////////////////////////////////////////////////////////////////////////////////////////////////////////////..

(function () {
    "use strict";
    tinymce.create('tinymce.plugins.font_awesome', {
        init: function (ed, url) {
            ed.addButton('font_awesome', {
                title: 'Font Awesome',
                image: url + '/tiny_icons/font_awesome.png',
                onclick: function () {
                    fontawesomeMenu();
                    //   ed.selection.setContent('[font_awesome][/font_awesome]');  
                }
            });
        },
        createControl: function (n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('font_awesome', tinymce.plugins.font_awesome);
})();



var ICONS = ["fa-adjust",
    "fa-anchor",
    "fa-archive",
    "fa-arrows",
    "fa-arrows-h",
    "fa-arrows-v",
    "fa-asterisk",
    "fa-ban",
    "fa-bar-chart-o",
    "fa-barcode",
    "fa-bars",
    "fa-beer",
    "fa-bell",
    "fa-bell-o",
    "fa-bolt",
    "fa-book",
    "fa-bookmark",
    "fa-bookmark-o",
    "fa-briefcase",
    "fa-bug",
    "fa-building-o",
    "fa-bullhorn",
    "fa-bullseye",
    "fa-calendar",
    "fa-calendar-o",
    "fa-camera",
    "fa-camera-retro",
    "fa-caret-square-o-down",
    "fa-caret-square-o-left",
    "fa-caret-square-o-right",
    "fa-caret-square-o-up",
    "fa-certificate",
    "fa-check",
    "fa-check-circle",
    "fa-check-circle-o",
    "fa-check-square",
    "fa-check-square-o",
    "fa-circle",
    "fa-circle-o",
    "fa-clock-o",
    "fa-cloud",
    "fa-cloud-download",
    "fa-cloud-upload",
    "fa-code",
    "fa-code-fork",
    "fa-coffee",
    "fa-cog",
    "fa-cogs",
    "fa-comment",
    "fa-comment-o",
    "fa-comments",
    "fa-comments-o",
    "fa-compass",
    "fa-credit-card",
    "fa-crop",
    "fa-crosshairs",
    "fa-cutlery",
    "fa-dashboard",
    "fa-desktop",
    "fa-dot-circle-o",
    "fa-download",
    "fa-edit",
    "fa-ellipsis-h",
    "fa-ellipsis-v",
    "fa-envelope",
    "fa-envelope-o",
    "fa-eraser",
    "fa-exchange",
    "fa-exclamation",
    "fa-exclamation-circle",
    "fa-exclamation-triangle",
    "fa-external-link",
    "fa-external-link-square",
    "fa-eye",
    "fa-eye-slash",
    "fa-female",
    "fa-fighter-jet",
    "fa-film",
    "fa-filter",
    "fa-fire",
    "fa-fire-extinguisher",
    "fa-flag",
    "fa-flag-checkered",
    "fa-flag-o",
    "fa-flash",
    "fa-flask",
    "fa-folder",
    "fa-folder-o",
    "fa-folder-open",
    "fa-folder-open-o",
    "fa-frown-o",
    "fa-gamepad",
    "fa-gavel",
    "fa-gear",
    "fa-gears",
    "fa-gift",
    "fa-glass",
    "fa-globe",
    "fa-group",
    "fa-hdd-o",
    "fa-headphones",
    "fa-heart",
    "fa-heart-o",
    "fa-home",
    "fa-inbox",
    "fa-info",
    "fa-info-circle",
    "fa-key",
    "fa-keyboard-o",
    "fa-laptop",
    "fa-leaf",
    "fa-legal",
    "fa-lemon-o",
    "fa-level-down",
    "fa-level-up",
    "fa-lightbulb-o",
    "fa-location-arrow",
    "fa-lock",
    "fa-magic",
    "fa-magnet",
    "fa-mail-forward",
    "fa-mail-reply",
    "fa-mail-reply-all",
    "fa-male",
    "fa-map-marker",
    "fa-meh-o",
    "fa-microphone",
    "fa-microphone-slash",
    "fa-minus",
    "fa-minus-circle",
    "fa-minus-square",
    "fa-minus-square-o",
    "fa-mobile",
    "fa-mobile-phone",
    "fa-money",
    "fa-moon-o",
    "fa-music",
    "fa-pencil",
    "fa-pencil-square",
    "fa-pencil-square-o",
    "fa-phone",
    "fa-phone-square",
    "fa-picture-o",
    "fa-plane",
    "fa-plus",
    "fa-plus-circle",
    "fa-plus-square",
    "fa-plus-square-o",
    "fa-power-off",
    "fa-print",
    "fa-puzzle-piece",
    "fa-qrcode",
    "fa-question",
    "fa-question-circle",
    "fa-quote-left",
    "fa-quote-right",
    "fa-random",
    "fa-refresh",
    "fa-reply",
    "fa-reply-all",
    "fa-retweet",
    "fa-road",
    "fa-rocket",
    "fa-rss",
    "fa-rss-square",
    "fa-search",
    "fa-search-minus",
    "fa-search-plus",
    "fa-share",
    "fa-share-square",
    "fa-share-square-o",
    "fa-shield",
    "fa-shopping-cart",
    "fa-sign-in",
    "fa-sign-out",
    "fa-signal",
    "fa-sitemap",
    "fa-smile-o",
    "fa-sort",
    "fa-sort-alpha-asc",
    "fa-sort-alpha-desc",
    "fa-sort-amount-asc",
    "fa-sort-amount-desc",
    "fa-sort-asc",
    "fa-sort-desc",
    "fa-sort-down",
    "fa-sort-numeric-asc",
    "fa-sort-numeric-desc",
    "fa-sort-up",
    "fa-spinner",
    "fa-square",
    "fa-square-o",
    "fa-star",
    "fa-star-half",
    "fa-star-half-empty",
    "fa-star-half-full",
    "fa-star-half-o",
    "fa-star-o",
    "fa-subscript",
    "fa-suitcase",
    "fa-sun-o",
    "fa-superscript",
    "fa-tablet",
    "fa-tachometer",
    "fa-tag",
    "fa-tags",
    "fa-tasks",
    "fa-terminal",
    "fa-thumb-tack",
    "fa-thumbs-down",
    "fa-thumbs-o-down",
    "fa-thumbs-o-up",
    "fa-thumbs-up",
    "fa-ticket",
    "fa-times",
    "fa-times-circle",
    "fa-times-circle-o",
    "fa-tint",
    "fa-toggle-down",
    "fa-toggle-left",
    "fa-toggle-right",
    "fa-toggle-up",
    "fa-trash-o",
    "fa-trophy",
    "fa-truck",
    "fa-umbrella",
    "fa-unlock",
    "fa-unlock-alt",
    "fa-unsorted",
    "fa-upload",
    "fa-user",
    "fa-users",
    "fa-video-camera",
    "fa-volume-down",
    "fa-volume-off",
    "fa-volume-up",
    "fa-warning",
    "fa-wheelchair",
    "fa-wrench",
    "fa-check-square",
    "fa-check-square-o",
    "fa-circle",
    "fa-circle-o",
    "fa-dot-circle-o",
    "fa-minus-square",
    "fa-minus-square-o",
    "fa-plus-square-o",
    "fa-square",
    "fa-square-o",
    "fa-bitcoin",
    "fa-btc",
    "fa-cny",
    "fa-dollar",
    "fa-eur",
    "fa-euro",
    "fa-gbp",
    "fa-inr",
    "fa-jpy",
    "fa-krw",
    "fa-money",
    "fa-rmb",
    "fa-rouble",
    "fa-rub",
    "fa-ruble",
    "fa-rupee",
    "fa-try",
    "fa-turkish-lira",
    "fa-usd",
    "fa-won",
    "fa-yen",
    "fa-align-center",
    "fa-align-justify",
    "fa-align-left",
    "fa-align-right",
    "fa-bold",
    "fa-chain",
    "fa-chain-broken",
    "fa-clipboard",
    "fa-columns",
    "fa-copy",
    "fa-cut",
    "fa-dedent",
    "fa-eraser",
    "fa-file",
    "fa-file-o",
    "fa-file-text",
    "fa-file-text-o",
    "fa-files-o",
    "fa-floppy-o",
    "fa-font",
    "fa-indent",
    "fa-italic",
    "fa-link",
    "fa-list",
    "fa-list-alt",
    "fa-list-ol",
    "fa-list-ul",
    "fa-outdent",
    "fa-paperclip",
    "fa-paste",
    "fa-repeat",
    "fa-rotate-left",
    "fa-rotate-right",
    "fa-save",
    "fa-scissors",
    "fa-strikethrough",
    "fa-table",
    "fa-text-height",
    "fa-text-width",
    "fa-th",
    "fa-th-large",
    "fa-th-list",
    "fa-underline",
    "fa-undo",
    "fa-unlink",
    "fa-angle-double-down",
    "fa-angle-double-left",
    "fa-angle-double-right",
    "fa-angle-double-up",
    "fa-angle-down",
    "fa-angle-left",
    "fa-angle-right",
    "fa-angle-up",
    "fa-arrow-circle-down",
    "fa-arrow-circle-left",
    "fa-arrow-circle-o-down",
    "fa-arrow-circle-o-left",
    "fa-arrow-circle-o-right",
    "fa-arrow-circle-o-up",
    "fa-arrow-circle-right",
    "fa-arrow-circle-up",
    "fa-arrow-down",
    "fa-arrow-left",
    "fa-arrow-right",
    "fa-arrow-up",
    "fa-arrows",
    "fa-arrows-alt",
    "fa-arrows-h",
    "fa-arrows-v",
    "fa-caret-down",
    "fa-caret-left",
    "fa-caret-right",
    "fa-caret-square-o-down",
    "fa-caret-square-o-left",
    "fa-caret-square-o-right",
    "fa-caret-square-o-up",
    "fa-caret-up",
    "fa-chevron-circle-down",
    "fa-chevron-circle-left",
    "fa-chevron-circle-right",
    "fa-chevron-circle-up",
    "fa-chevron-down",
    "fa-chevron-left",
    "fa-chevron-right",
    "fa-chevron-up",
    "fa-hand-o-down",
    "fa-hand-o-left",
    "fa-hand-o-right",
    "fa-hand-o-up",
    "fa-long-arrow-down",
    "fa-long-arrow-left",
    "fa-long-arrow-right",
    "fa-long-arrow-up",
    "fa-toggle-down",
    "fa-toggle-left",
    "fa-toggle-right",
    "fa-toggle-up",
    "fa-arrows-alt",
    "fa-backward",
    "fa-compress",
    "fa-eject",
    "fa-expand",
    "fa-fast-backward",
    "fa-fast-forward",
    "fa-forward",
    "fa-pause",
    "fa-play",
    "fa-play-circle",
    "fa-play-circle-o",
    "fa-step-backward",
    "fa-step-forward",
    "fa-stop",
    "fa-youtube-play",
    "fa-adn",
    "fa-android",
    "fa-apple",
    "fa-bitbucket",
    "fa-bitbucket-square",
    "fa-bitcoin",
    "fa-btc",
    "fa-css3",
    "fa-dribbble",
    "fa-dropbox",
    "fa-facebook",
    "fa-facebook-square",
    "fa-flickr",
    "fa-foursquare",
    "fa-github",
    "fa-github-alt",
    "fa-github-square",
    "fa-gittip",
    "fa-google-plus",
    "fa-google-plus-square",
    "fa-html5",
    "fa-instagram",
    "fa-linkedin",
    "fa-linkedin-square",
    "fa-linux",
    "fa-maxcdn",
    "fa-pagelines",
    "fa-pinterest",
    "fa-pinterest-square",
    "fa-renren",
    "fa-skype",
    "fa-stack-exchange",
    "fa-stack-overflow",
    "fa-trello",
    "fa-tumblr",
    "fa-tumblr-square",
    "fa-twitter",
    "fa-twitter-square",
    "fa-vimeo-square",
    "fa-vk",
    "fa-weibo",
    "fa-windows",
    "fa-xing",
    "fa-xing-square",
    "fa-youtube",
    "fa-youtube-play",
    "fa-youtube-square" ];
function allfonticons() {
    "use strict";
    var icon, return_string, i;
    for (i = 0; i < ICONS.length; i++) {
        icon = ICONS[i];
        return_string = return_string + '<li><i class="fa ' + icon + '"></i><span>' + icon + '</span></li>';
    }
    return return_string;
}

function fontawesomeMenu() {
    "use strict";
    var iconslist, width;
    iconslist = jQuery('<style>.font-awesome-icons li:hover span { color:#000 !important; cursor: pointer !important } .font-awesome-icons li {margin-bottom: 15px;width: 22%;display: block;margin-right: 3%;float: left;line-height: 15px;}.font-awesome-icons li span{color:#aaa;margin-left: 10px;};.font-awesome-icons li span:before {content:"\a"}</style>\n\<div id="thundercodes-form">\n\ <ul class="font-awesome-icons">' + allfonticons() + '</ul></div>');
    iconslist.appendTo('body').hide();
    tb_show('Font awesome Icons', '#TB_inline?inlineId=thundercodes-form');
    jQuery("#TB_window").css("height", "415");
    jQuery("#TB_window").css("overflow-y", "auto");
    jQuery("#TB_window").css("overflow-x", "hidden");
    jQuery("#TB_ajaxContent").css("width", "auto");
    jQuery("#TB_ajaxContent").css("height", "auto");

    jQuery(".font-awesome-icons li").click(function () {
        insertShortcode(jQuery(this).find("i").attr("class"));
    });

}

function insertShortcode(daclass) {
    "use strict";
    tinyMCE.activeEditor.execCommand('mceInsertContent', 0, "[font_awesome icon='" + daclass + "' size='15px']");
    tb_remove();
}