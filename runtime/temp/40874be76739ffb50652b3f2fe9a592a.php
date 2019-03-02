<?php /*a:2:{s:63:"E:\car_subsystem/application/webapi\view\contract\car_info.html";i:1541317492;s:60:"E:\car_subsystem/application/webapi\view\public\header2.html";i:1541059213;}*/ ?>
<html lang="en" data-dpr="1" style="font-size: 41.4px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>深圳车厘子-车辆状态</title>
    <link rel="stylesheet" href="/static/bank/index.css">
    <link rel="stylesheet" href="/static/bank/common.css">
    <script type="text/javascript" src="/static/webapp/js/jquery-1.11.0.js"></script>
<link rel="icon" href="http://chexinyuan.com/clz.ico"/>
<link href="http://chexinyuan.com/clz.ico"
      rel="apple-touch-icon-precomposed">
<link href="http://chexinyuan.com/clz.ico" rel="Shortcut Icon"
      type="image/x-icon"/>

    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body style="font-size: 14px; ">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="__ANTD_MOBILE_SVG_SPRITE_NODE__"
     style="position:absolute;width:0;height:0">
    <defs>
        <symbol id="check" viewBox="0 0 44 44">
            <path fill-rule="evenodd" d="M34.538 8L38 11.518 17.808 32 8 22.033l3.462-3.518 6.346 6.45z"></path>
        </symbol>
        <symbol id="check-circle" viewBox="0 0 48 48">
            <path d="M24 48c13.255 0 24-10.745 24-24S37.255 0 24 0 0 10.745 0 24s10.745 24 24 24zM13.1 23.2l-2.2 2.1 10 9.9L38.1 15l-2.2-2-15.2 17.8-7.6-7.6z"
                  fill-rule="evenodd"></path>
        </symbol>
        <symbol id="check-circle-o" viewBox="0 0 48 48">
            <g fill-rule="evenodd">
                <path d="M24 48c13.255 0 24-10.745 24-24S37.255 0 24 0 0 10.745 0 24s10.745 24 24 24zm0-3c11.598 0 21-9.402 21-21S35.598 3 24 3 3 12.402 3 24s9.402 21 21 21z"></path>
                <path d="M12.2 23.2L10 25.3l10 9.9L37.2 15 35 13 19.8 30.8z"></path>
            </g>
        </symbol>
        <symbol id="cross" viewBox="0 0 44 44">
            <path fill-rule="evenodd"
                  d="M24.008 21.852l8.97-8.968L31.092 11l-8.97 8.968L13.157 11l-1.884 1.884 8.968 8.968-9.24 9.24 1.884 1.885 9.24-9.24 9.24 9.24 1.885-1.884-9.24-9.24z"></path>
        </symbol>
        <symbol id="cross-circle" viewBox="0 0 48 48">
            <g fill-rule="evenodd">
                <path d="M24 48c13.255 0 24-10.745 24-24S37.255 0 24 0 0 10.745 0 24s10.745 24 24 24zm0-3c11.598 0 21-9.402 21-21S35.598 3 24 3 3 12.402 3 24s9.402 21 21 21z"></path>
                <path d="M24.34 22.22l-7.775-7.775a1.5 1.5 0 1 0-2.12 2.12l7.773 7.775-7.774 7.775a1.5 1.5 0 1 0 2.12 2.12l7.775-7.773 7.774 7.774a1.5 1.5 0 1 0 2.12-2.12L26.46 24.34l7.774-7.774a1.5 1.5 0 1 0-2.12-2.12l-7.776 7.773z"></path>
            </g>
        </symbol>
        <symbol id="cross-circle-o" viewBox="0 0 48 48">
            <path d="M24 48c13.255 0 24-10.745 24-24S37.255 0 24 0 0 10.745 0 24s10.745 24 24 24zm.353-25.77l-7.593-7.593c-.797-.8-1.538-.822-2.263-.207-.724.614-.56 1.617-.124 2.067l7.852 7.847-7.72 7.723c-.727.728-.56 1.646-.066 2.177.493.532 1.553.683 2.31-.174l7.588-7.584 7.644 7.623c.796.798 1.608.724 2.21.145.605-.58.72-1.442-.074-2.24l-7.657-7.67 7.545-7.52c.81-.697.9-1.76.297-2.34-.92-.885-1.85-.338-2.264.078l-7.685 7.667z"
                  fill-rule="evenodd"></path>
        </symbol>
        <symbol id="left" viewBox="0 0 44 44">
            <defs>
                <path id="a" d="M-129-845h24v24h-24z"></path>
            </defs>
            <clipPath id="b">
                <use xlink:href="#a" overflow="visible"></use>
            </clipPath>
            <g clip-path="url(#b)">
                <defs>
                    <path id="c" d="M-903-949H947V996H-903z"></path>
                </defs>
            </g>
            <path d="M16.247 21.4L28.48 9.165l2.12 2.12-10.117 10.12L30.6 31.524l-2.12 2.12-12.233-12.232.007-.006z"></path>
        </symbol>
        <symbol id="right" viewBox="0 0 44 44">
            <defs>
                <path id="a" d="M-129-845h24v24h-24z"></path>
            </defs>
            <clipPath id="b">
                <use xlink:href="#a" overflow="visible"></use>
            </clipPath>
            <g clip-path="url(#b)">
                <defs>
                    <path id="c" d="M-903-949H947V996H-903z"></path>
                </defs>
            </g>
            <path d="M30.6 21.4L18.37 9.165l-2.12 2.12 10.117 10.12-10.118 10.118 2.12 2.12 12.234-12.232-.005-.006z"></path>
        </symbol>
        <symbol id="down" viewBox="0 0 44 44">
            <path d="M22.355 28.237l-11.483-10.9c-.607-.576-1.714-.396-2.48.41l.674-.71c-.763.802-.73 2.07-.282 2.496l11.37 10.793-.04.04 2.088 2.195L23.3 31.52l12.308-11.682c.447-.425.48-1.694-.282-2.496l.674.71c-.766-.806-1.873-.986-2.48-.41L22.355 28.237z"
                  fill-rule="evenodd"></path>
        </symbol>
        <symbol id="up" viewBox="0 0 44 44">
            <path fill="none" d="M-1-1h46v46H-1z"></path>
            <defs>
                <path id="a" d="M-129-845h24v24h-24z"></path>
            </defs>
            <clipPath id="b">
                <use xlink:href="#a"></use>
            </clipPath>
            <g clip-path="url(#b)">
                <defs>
                    <path id="c" d="M-903-949H947V996H-903z"></path>
                </defs>
            </g>
            <path d="M23.417 14.23L11.184 26.46l2.12 2.12 10.12-10.117 10.118 10.118 2.12-2.12L23.43 14.228l-.006.005z"></path>
        </symbol>
        <symbol id="loading" viewBox="0 -2 59.75 60.25">
            <path fill="#ccc"
                  d="M29.69-.527C14.044-.527 1.36 12.158 1.36 27.806S14.043 56.14 29.69 56.14c15.65 0 28.334-12.686 28.334-28.334S45.34-.527 29.69-.527zm.185 53.75c-14.037 0-25.417-11.38-25.417-25.417S15.838 2.39 29.875 2.39s25.417 11.38 25.417 25.417-11.38 25.416-25.417 25.416z"></path>
            <path fill="none" stroke="#108ee9" stroke-width="3" stroke-linecap="round" stroke-miterlimit="10"
                  d="M56.587 29.766c.37-7.438-1.658-14.7-6.393-19.552"></path>
        </symbol>
        <symbol id="search" viewBox="0 0 44 44">
            <path d="M32.98 29.255l8.915 8.293L39.603 40l-8.86-8.242a15.952 15.952 0 0 1-10.753 4.147C11.16 35.905 4 28.763 4 19.952 4 11.142 11.16 4 19.99 4s15.99 7.142 15.99 15.952c0 3.472-1.112 6.685-3 9.303zm.05-9.21c0 7.123-5.7 12.918-12.88 12.918-7.176 0-13.015-5.795-13.015-12.918 0-7.12 5.84-12.917 13.017-12.917 7.178 0 12.88 5.797 12.88 12.917z"
                  fill-rule="evenodd"></path>
        </symbol>
        <symbol id="ellipsis" viewBox="0 0 44 44">
            <circle cx="21.888" cy="22" r="4.045"></circle>
            <circle cx="5.913" cy="22" r="4.045"></circle>
            <circle cx="37.863" cy="22" r="4.045"></circle>
        </symbol>
        <symbol id="ellipsis-circle" viewBox="0 0 44 44">
            <g fill-rule="evenodd">
                <path d="M22.13.11C10.05.11.255 9.902.255 21.983S10.05 43.86 22.13 43.86s21.875-9.795 21.875-21.876S34.21.11 22.13.11zm0 40.7c-10.396 0-18.825-8.43-18.825-18.826S11.735 3.16 22.13 3.16c10.396 0 18.825 8.428 18.825 18.824S32.525 40.81 22.13 40.81z"></path>
                <circle cx="21.888" cy="22.701" r="2.445"></circle>
                <circle cx="12.23" cy="22.701" r="2.445"></circle>
                <circle cx="31.546" cy="22.701" r="2.445"></circle>
            </g>
        </symbol>
        <symbol id="exclamation-circle" viewBox="0 0 64 64">
            <path d="M59.58 40.89L41.193 9.11C39.135 5.382 35.723 3 31.387 3c-3.11 0-6.52 2.382-8.58 6.11L4.42 40.89c-2.788 4.635-3.126 8.81-1.225 12.22C5.015 56.208 7.572 58 13 58h36.773c5.428 0 9.21-1.792 11.03-4.89 1.9-3.41 1.565-7.583-1.224-12.22zm-2.452 11c-.635 1.694-3.802 2.443-7.354 2.443H13c-3.59 0-5.493-.75-6.13-2.444-1.71-2.41-1.374-5.263 0-8.557l18.387-31.777c2.116-3.168 4.394-4.89 6.13-4.89 2.96 0 5.238 1.722 7.354 4.89l18.387 31.777c1.374 3.294 1.713 6.146 0 8.556zm-25.74-33c-.405 0-1.227.835-1.227 2.443v15.89c0 1.608.823 2.444 1.227 2.444 1.628 0 2.452-.836 2.452-2.445v-15.89c0-1.607-.825-2.443-2.453-2.443zm0 23.22c-.405 0-1.227.79-1.227 1.223v2.445c0 .434.823 1.222 1.227 1.222 1.628 0 2.452-.788 2.452-1.222v-2.445c0-.434-.825-1.222-2.453-1.222z"
                  fill-rule="evenodd"></path>
        </symbol>
        <symbol id="info-circle" viewBox="0 0 44 44">
            <circle cx="13.828" cy="19.63" r="1.938"></circle>
            <circle cx="21.767" cy="19.63" r="1.938"></circle>
            <circle cx="29.767" cy="19.63" r="1.938"></circle>
            <path d="M22.102 4.16c-9.918 0-17.958 7.147-17.958 15.962 0 4.935 2.522 9.345 6.48 12.273v5.667l.04.012a2.627 2.627 0 1 0 4.5 1.455h.002l5.026-3.54c.628.06 1.265.094 1.91.094 9.92 0 17.96-7.146 17.96-15.96C40.06 11.306 32.02 4.16 22.1 4.16zm-.04 29.902c-.902 0-1.78-.08-2.642-.207l-5.882 4.234c-.024.024-.055.04-.083.06l-.008.005a.51.51 0 0 1-.284.095.525.525 0 0 1-.525-.525l.005-6.375c-3.91-2.516-6.456-6.544-6.456-11.1 0-7.628 7.107-13.812 15.875-13.812s15.875 6.184 15.875 13.812-7.107 13.812-15.875 13.812z"></path>
        </symbol>
        <symbol id="question-circle" viewBox="0 0 44 44">
            <g fill-rule="evenodd">
                <path d="M21.186 3c-10.853 0-19.36 8.506-19.36 19.358C1.827 32.494 10.334 41 21.187 41c10.133 0 18.64-8.506 18.64-18.642C39.827 11.506 31.32 3 21.187 3m15.64 19c0 8.823-7.178 16-16 16s-16-7.177-16-16 7.178-16 16-16 16 7.177 16 16z"></path>
                <path d="M22.827 31.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m4-15.48c0 .957-.203 1.822-.61 2.593-.427.792-1.117 1.612-2.073 2.457-.867.734-1.453 1.435-1.754 2.096-.302.7-.453 1.693-.453 2.98a.828.828 0 0 1-.823.854.828.828 0 0 1-.584-.22.877.877 0 0 1-.24-.635c0-1.305.168-2.38.506-3.227.336-.883.93-1.682 1.78-2.4 1.01-.883 1.71-1.692 2.1-2.428.336-.645.503-1.38.503-2.21-.02-.935-.3-1.7-.85-2.288-.655-.717-1.62-1.075-2.897-1.075-1.506 0-2.596.535-3.27 1.6-.46.754-.688 1.645-.688 2.677a.92.92 0 0 1-.266.66.747.747 0 0 1-.56.25.73.73 0 0 1-.584-.194c-.16-.164-.24-.393-.24-.69 0-1.82.585-3.272 1.755-4.357C18.645 11.486 19.928 11 21.434 11h.293c1.452 0 2.638.414 3.56 1.24 1.028.903 1.54 2.163 1.54 3.78z"></path>
            </g>
        </symbol>
        <symbol id="voice" viewBox="0 0 38 33">
            <g fill-rule="evenodd">
                <path d="M17.838 28.8c-.564-.468-1.192-.983-1.836-1.496-4.244-3.385-5.294-3.67-6.006-3.67-.014 0-.027.005-.04.005-.015 0-.028-.006-.042-.006H3.562c-.734 0-.903-.203-.903-.928v-12.62c0-.49.057-.8.66-.8H9.1c.694 0 1.76-.28 6.4-3.63.83-.596 1.638-1.196 2.337-1.722V28.8zM19.682.19c-.463-.22-1.014-.158-1.417.157-.02.016-1.983 1.552-4.152 3.125C10.34 6.21 9.243 6.664 9.02 6.737H3.676c-.027 0-.053.003-.08.004H1.183c-.608 0-1.1.487-1.1 1.086V25.14c0 .598.492 1.084 1.1 1.084h8.71c.22.08 1.257.55 4.605 3.24 1.947 1.562 3.694 3.088 3.712 3.103.25.22.568.333.89.333.186 0 .373-.038.55-.116.48-.213.79-.684.79-1.204V1.38c0-.506-.294-.968-.758-1.19z"
                      mask="url(#mask-2)"></path>
                <path d="M31.42 16.475c0-3.363-1.854-6.297-4.606-7.876-.125-.067-.42-.193-.625-.193-.613 0-1.11.488-1.11 1.09 0 .404.22.764.55.952 2.13 1.19 3.566 3.44 3.566 6.024 0 2.627-1.486 4.913-3.677 6.087-.32.19-.53.54-.53.935 0 .602.495 1.09 1.106 1.09.26.002.568-.15.568-.15 2.835-1.556 4.754-4.538 4.754-7.96"
                      mask="url(#mask-4)"></path>
                <path d="M30.14 3.057c-.205-.122-.41-.22-.658-.22-.608 0-1.1.485-1.1 1.084 0 .434.26.78.627.978 4.042 2.323 6.76 6.636 6.76 11.578 0 4.938-2.715 9.248-6.754 11.572-.354.19-.66.55-.66.993 0 .6.494 1.085 1.102 1.085.243 0 .438-.092.65-.213 4.692-2.695 7.848-7.7 7.848-13.435 0-5.723-3.142-10.718-7.817-13.418"
                      mask="url(#mask-6)"></path>
            </g>
        </symbol>
        <symbol id="plus" viewBox="0 0 30 30">
            <path d="M14 14H0v2h14v14h2V16h14v-2H16V0h-2v14z" fill-rule="evenodd"></path>
        </symbol>
        <symbol id="minus" viewBox="0 0 30 2">
            <path d="M0 0h30v2H0z" fill-rule="evenodd"></path>
        </symbol>
        <symbol id="dislike" viewBox="0 0 72 72">
            <g fill="none" fill-rule="evenodd">
                <path d="M36 72c19.882 0 36-16.118 36-36S55.882 0 36 0 0 16.118 0 36s16.118 36 36 36zm0-2c18.778 0 34-15.222 34-34S54.778 2 36 2 2 17.222 2 36s15.222 34 34 34z"
                      fill="#FFF"></path>
                <path fill="#FFF" d="M47 22h2v6h-2zm-24 0h2v6h-2z"></path>
                <path d="M21 51s4.6-7 15-7 15 7 15 7" stroke="#FFF" stroke-width="2"></path>
            </g>
        </symbol>
        <symbol id="fail" viewBox="0 0 72 72">
            <g fill="none" fill-rule="evenodd">
                <path d="M36 72c19.882 0 36-16.118 36-36S55.882 0 36 0 0 16.118 0 36s16.118 36 36 36zm0-2c18.778 0 34-15.222 34-34S54.778 2 36 2 2 17.222 2 36s15.222 34 34 34z"
                      fill="#FFF"></path>
                <path d="M22 22l28.304 28.304m-28.304 0L50.304 22" stroke="#FFF" stroke-width="2"></path>
            </g>
        </symbol>
        <symbol id="success" viewBox="0 0 72 72">
            <g fill="none" fill-rule="evenodd">
                <path d="M36 72c19.882 0 36-16.118 36-36S55.882 0 36 0 0 16.118 0 36s16.118 36 36 36zm0-2c18.778 0 34-15.222 34-34S54.778 2 36 2 2 17.222 2 36s15.222 34 34 34z"
                      fill="#FFF"></path>
                <path stroke="#FFF" stroke-width="2" d="M19 34.54l11.545 11.923L52.815 24"></path>
            </g>
        </symbol>
    </defs>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
     style="position: absolute; width: 0; height: 0" id="__SVG_SPRITE_NODE__">
    <symbol viewBox="0 0 30 24" id="icon_check">
        <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
        <title>check-simple</title>
        <desc>Created with Sketch.</desc>
        <defs></defs>
        <g id="icon_check_Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="icon_check_2.1-Application---Step-2" transform="translate(-48.000000, -101.000000)" fill="#72C472"
               fill-rule="nonzero">
                <g id="icon_check_check-simple" transform="translate(48.000000, 101.000000)">
                    <polygon id="icon_check_Shape" points="10.5 24 0 13.5 4.5 9 10.5 15 25.5 0 30 4.5"></polygon>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 28 28" id="icon_badge">
        <title>badge</title>
        <desc>Created with Sketch.</desc>
        <g id="icon_badge_Page-1">
            <g id="icon_badge__x32_.0-Application---Step-1" transform="translate(-174.000000, -98.000000)">
                <g id="icon_badge_Group-3" transform="translate(158.000000, 83.000000)">
                    <g id="icon_badge_badge" transform="translate(16.000000, 15.000000)">
                        <path id="icon_badge_Shape" class="st0"
                              d="M16.3,8.2h-4.7V2.3C11.7,1,12.7,0,14,0s2.3,1,2.3,2.3V8.2z"></path>
                        <path id="icon_badge_Shape_1_" class="st0"
                              d="M26.8,5.8h-8.2v4.7H9.3V5.8H1.2C0.5,5.8,0,6.4,0,7v19.8C0,27.5,0.5,28,1.2,28h25.7      c0.6,0,1.2-0.5,1.2-1.2V7C28,6.4,27.5,5.8,26.8,5.8z M9.3,14c1.3,0,2.3,1,2.3,2.3c0,1.3-1,2.3-2.3,2.3S7,17.6,7,16.3      C7,15,8,14,9.3,14z M4.7,23.3c0-1.9,1.6-3.5,3.5-3.5h2.3c1.9,0,3.5,1.6,3.5,3.5H4.7z M23.3,22.2h-7v-2.3h7V22.2z M23.3,17.5h-7      v-2.3h7V17.5z"></path>
                    </g>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 22 22" id="icon_edit">
        <title>sign copy</title>
        <desc>Created with Sketch.</desc>
        <g id="icon_edit_Page-1">
            <g id="icon_edit__x31_.0-Login" transform="translate(-70.000000, -329.000000)">
                <g id="icon_edit_Group" transform="translate(38.000000, 315.000000)">
                    <g id="icon_edit_sign-copy" transform="translate(33.000000, 15.000000)">
                        <path d="M20,20.8H0c-0.4,0-0.8-0.3-0.8-0.8s0.3-0.8,0.8-0.8h20c0.4,0,0.8,0.3,0.8,0.8S20.4,20.8,20,20.8z"></path>
                        <path d="M4.3,17.4c-0.8,0-1.6-0.3-2.2-0.9l0,0c-0.6-0.6-0.9-1.4-0.9-2.2s0.3-1.6,0.9-2.2l12-12c1.2-1.2,3.2-1.2,4.4,0      c0.6,0.6,0.9,1.4,0.9,2.2S19.1,4,18.5,4.6l-12,12C5.9,17.1,5.2,17.4,4.3,17.4z M3.2,15.5c0.6,0.6,1.6,0.6,2.3,0l12-12      c0.3-0.3,0.5-0.7,0.5-1.1s-0.2-0.8-0.5-1.1c-0.6-0.6-1.7-0.6-2.3,0l-12,12c-0.3,0.3-0.5,0.7-0.5,1.1C2.7,14.8,2.9,15.2,3.2,15.5      L3.2,15.5z"></path>
                        <path d="M1.3,18.1c-0.2,0-0.4-0.1-0.5-0.2c-0.3-0.3-0.3-0.8,0-1.1l1.3-1.3c0.3-0.3,0.8-0.3,1.1,0s0.3,0.8,0,1.1l-1.3,1.3      C1.7,18,1.5,18.1,1.3,18.1z"></path>
                        <path d="M15.3,13.4c-0.2,0-0.4-0.1-0.5-0.2c-0.3-0.3-0.3-0.8,0-1.1l3.5-3.5l-0.8-0.8c-0.3-0.3-0.3-0.8,0-1.1s0.8-0.3,1.1,0      l1.3,1.3c0.3,0.3,0.3,0.8,0,1.1l-4,4C15.7,13.3,15.5,13.4,15.3,13.4z"></path>
                    </g>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 24 22" id="icon_check_progress">
        <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
        <title>check-circle-07</title>
        <desc>Created with Sketch.</desc>
        <defs></defs>
        <g id="icon_check_progress_Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
           stroke-linecap="round" stroke-linejoin="round">
            <g id="icon_check_progress_1.0-Login" transform="translate(-70.000000, -459.000000)" stroke="#FFFFFF"
               stroke-width="1.5">
                <g id="icon_check_progress_check-circle-07" transform="translate(71.000000, 460.000000)">
                    <path d="M19.1757143,8.57 C19.2485714,9.03571429 19.2857143,9.51428571 19.2857143,10 C19.2857143,15.1285714 15.1285714,19.2857143 10,19.2857143 C4.87142857,19.2857143 0.714285714,15.1285714 0.714285714,10 C0.714285714,4.87142857 4.87142857,0.714285714 10,0.714285714 C11.5457143,0.714285714 13.0028571,1.09142857 14.2857143,1.76"
                          id="icon_check_progress_Shape"></path>
                    <polyline id="icon_check_progress_Shape"
                              points="5.71428571 7.85714286 10 12.1428571 21.4285714 0.714285714"></polyline>
                </g>
            </g>
        </g>
    </symbol>
    <symbol viewBox="0 0 22 22" id="icon_card_add">
        <!-- Generator: Sketch 49 (51002) - http://www.bohemiancoding.com/sketch -->
        <title>card-add</title>
        <desc>Created with Sketch.</desc>
        <defs></defs>
        <g id="icon_card_add_Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
           stroke-linecap="round" stroke-linejoin="round">
            <g id="icon_card_add_1.0-Login" transform="translate(-71.000000, -394.000000)" stroke="#FFFFFF"
               stroke-width="1.5">
                <g id="icon_card_add_card-add" transform="translate(72.000000, 395.000000)">
                    <path d="M0.625,5.625 L19.375,5.625" id="icon_card_add_Shape"></path>
                    <path d="M5.625,15.625 L1.875,15.625 C1.185,15.625 0.625,15.065 0.625,14.375 L0.625,1.875 C0.625,1.185 1.185,0.625 1.875,0.625 L18.125,0.625 C18.815,0.625 19.375,1.185 19.375,1.875 L19.375,8.125"
                          id="icon_card_add_Shape"></path>
                    <circle id="icon_card_add_Oval" cx="14.375" cy="14.375" r="5"></circle>
                    <path d="M14.375,11.875 L14.375,16.875" id="icon_card_add_Shape"></path>
                    <path d="M11.875,14.375 L16.875,14.375" id="icon_card_add_Shape"></path>
                </g>
            </g>
        </g>
    </symbol>
</svg>

<style>
    .contentWrapper___rHVlL h3 {
        text-align: left !important;
        width: 100%;
        margin-top: .42667rem;
        margin-bottom: .42667rem;
    }

    .info {
        line-height: .753rem
    }
</style>

<div id="root">
    <div class="am-wingblank am-wingblank-lg applyConatiner___Z-1Gc">
        <section class="contentWrapper___rHVlL">
            <h2>车辆状态</h2>
            <div class="divider___3fgf_"></div>

            <?php foreach($list as $key =>$val): if($val['status'] != 1): ?>
            <div class="info row-s">
                <div class="col-12-4 text-left col-888">
                    <?php echo htmlentities($val['create_at']); ?>
                </div>
                <div class="col-12-8 text-right">
                    <?php echo htmlentities((isset($val['desc']) && ($val['desc'] !== '')?$val['desc']:'')); ?>
                </div>
            </div>
            <div class="divider___3fgf_"></div>
            <?php else: ?>
            <div class="info row-s">
                <div class="col-12-4 text-left col-888">
                    <?php echo htmlentities($val['create_at']); ?>
                </div>
                <div class="col-12-8 text-right">
                    <?php echo htmlentities((isset($val['desc']) && ($val['desc'] !== '')?$val['desc']:'')); ?>
                </div>
            </div>
            <div class="info row-s">
                <div class="col-12-12 text-left col-888">
                    <?php if(isset($val['images']) && $val['images']['ahead_image'] != ''): ?>
                    <img class="mui-media-object mui-pull-left"
                         style="max-width: 100%;height: auto;margin-bottom: 10px;"
                         src="<?php echo htmlentities($val['images']['ahead_image']); ?>">
                    <?php endif; if(isset($val['images']) && $val['images']['side_image'] != ''): ?>
                    <img class="mui-media-object mui-pull-left"
                         style="max-width: 100%;height: auto;margin-bottom: 10px;"
                         src="<?php echo htmlentities($val['images']['side_image']); ?>">
                    <?php endif; if(isset($val['images']) && $val['images']['back_image']): ?>
                    <img class="mui-media-object mui-pull-left"
                         style="max-width: 100%;height: auto;margin-bottom: 10px;"
                         src="<?php echo htmlentities($val['images']['back_image']); ?>">
                    <?php endif; if(isset($val['images']) && $val['images']['inside_image_one']): ?>
                    <img class="mui-media-object mui-pull-left"
                         style="max-width: 100%;height: auto;margin-bottom: 10px;"
                         src="<?php echo htmlentities($val['images']['inside_image_one']); ?>">
                    <?php endif; if(isset($val['images']) && $val['images']['inside_image_two']): ?>
                    <img class="mui-media-object mui-pull-left"
                         style="max-width: 100%;height: auto;margin-bottom: 10px;"
                         src="<?php echo htmlentities($val['images']['inside_image_two']); ?>">
                    <?php endif; if(isset($val['images']) && $val['images']['nameplate_image']): ?>
                    <img class="mui-media-object mui-pull-left"
                         style="max-width: 100%;height: auto;margin-bottom: 10px;"
                         src="<?php echo htmlentities($val['images']['nameplate_image']); ?>">
                    <?php endif; ?>

                </div>
            </div>
            <div class="divider___3fgf_"></div>
            <?php endif; endforeach; ?>

            <div class="am-checkbox-agree radioCheckBox___3HunQ">

            </div>
            <div class="container___1F_I1" style="margin-top: 16px;">
                <div class="button___1dfGc"><a
                        style="    display: block;    width: 100%;    text-align: center;    height: 100%;    line-height: 1.23333rem;"
                        href="#" onclick="javascript:history.back(-1);"><span
                        class="buttonText___3_BSg">确定返回</span></a></div>
            </div>
        </section>
    </div>
</div>
<script src="//as.alipayobjects.com/g/component/fastclick/1.0.6/fastclick.js" async=""></script>

<script type="text/javascript" src="/static/webapp/js/jquery-1.11.0.js"></script>
<script>
    var window_height = $(window).height();
    $("body").height(window_height)
</script>


</body>
</html>