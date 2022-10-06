/*!
 * bootstrap-fileinput v4.5.2
 * http://plugins.krajee.com/file-input
 *
 * Author: Kartik Visweswaran
 * Copyright: 2014 - 2018, Kartik Visweswaran, Krajee.com
 *
 * Licensed under the BSD 3-Clause
 * https://github.com/kartik-v/bootstrap-fileinput/blob/master/LICENSE.md
 */
!function (e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof module && module.exports ? module.exports = e(require("jquery")) : e(window.jQuery)
}(function (e) {
    "use strict";
    e.fn.fileinputLocales = {}, e.fn.fileinputThemes = {}, String.prototype.setTokens = function (e) {
        var t, i, a = this.toString();
        for (t in e) e.hasOwnProperty(t) && (i = new RegExp("{" + t + "}", "g"), a = a.replace(i, e[t]));
        return a
    };
    var t, i;
    t = {
        FRAMES: ".kv-preview-thumb",
        SORT_CSS: "file-sortable",
        OBJECT_PARAMS: '<param name="controller" value="true" />\n<param name="allowFullScreen" value="true" />\n<param name="allowScriptAccess" value="always" />\n<param name="autoPlay" value="false" />\n<param name="autoStart" value="false" />\n<param name="quality" value="high" />\n',
        DEFAULT_PREVIEW: '<div class="file-preview-other">\n<span class="{previewFileIconClass}">{previewFileIcon}</span>\n</div>',
        MODAL_ID: "kvFileinputModal",
        MODAL_EVENTS: ["show", "shown", "hide", "hidden", "loaded"],
        objUrl: window.URL || window.webkitURL,
        createObjectURL: function (e) {
            return t.objUrl && t.objUrl.createObjectURL && e ? t.objUrl.createObjectURL(e) : ""
        },
        revokeObjectURL: function (e) {
            t.objUrl && t.objUrl.revokeObjectURL && e && t.objUrl.revokeObjectURL(e)
        },
        compare: function (e, t, i) {
            return void 0 !== e && (i ? e === t : e.match(t))
        },
        isIE: function (e) {
            var t, i;
            return "Microsoft Internet Explorer" !== navigator.appName ? !1 : 10 === e ? new RegExp("msie\\s" + e, "i").test(navigator.userAgent) : (t = document.createElement("div"), t.innerHTML = "<!--[if IE " + e + "]> <i></i> <![endif]-->", i = t.getElementsByTagName("i").length, document.body.appendChild(t), t.parentNode.removeChild(t), i)
        },
        canAssignFilesToInput: function () {
            var e = document.createElement("input");
            try {
                return e.type = "file", e.files = null, !0
            } catch (t) {
                return !1
            }
        },
        getDragDropFolders: function (e) {
            var t, i, a = e ? e.length : 0, r = 0;
            if (a > 0 && e[0].webkitGetAsEntry()) for (t = 0; a > t; t++) i = e[t].webkitGetAsEntry(), i && i.isDirectory && r++;
            return r
        },
        initModal: function (t) {
            var i = e("body");
            i.length && t.appendTo(i)
        },
        isEmpty: function (t, i) {
            return void 0 === t || null === t || 0 === t.length || i && "" === e.trim(t)
        },
        isArray: function (e) {
            return Array.isArray(e) || "[object Array]" === Object.prototype.toString.call(e)
        },
        ifSet: function (e, t, i) {
            return i = i || "", t && "object" == typeof t && e in t ? t[e] : i
        },
        cleanArray: function (e) {
            return e instanceof Array || (e = []), e.filter(function (e) {
                return void 0 !== e && null !== e
            })
        },
        spliceArray: function (t, i, a) {
            var r, n, o = 0, l = [];
            if (!(t instanceof Array)) return [];
            for (n = e.extend(!0, [], t), a && n.reverse(), r = 0; r < n.length; r++) r !== i && (l[o] = n[r], o++);
            return a && l.reverse(), l
        },
        getNum: function (e, t) {
            return t = t || 0, "number" == typeof e ? e : ("string" == typeof e && (e = parseFloat(e)), isNaN(e) ? t : e)
        },
        hasFileAPISupport: function () {
            return !(!window.File || !window.FileReader)
        },
        hasDragDropSupport: function () {
            var e = document.createElement("div");
            return !t.isIE(9) && (void 0 !== e.draggable || void 0 !== e.ondragstart && void 0 !== e.ondrop)
        },
        hasFileUploadSupport: function () {
            return t.hasFileAPISupport() && window.FormData
        },
        hasBlobSupport: function () {
            try {
                return !!window.Blob && Boolean(new Blob)
            } catch (e) {
                return !1
            }
        },
        hasArrayBufferViewSupport: function () {
            try {
                return 100 === new Blob([new Uint8Array(100)]).size
            } catch (e) {
                return !1
            }
        },
        dataURI2Blob: function (e) {
            var i, a, r, n, o, l,
                s = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder || window.MSBlobBuilder,
                d = t.hasBlobSupport(), c = (d || s) && window.atob && window.ArrayBuffer && window.Uint8Array;
            if (!c) return null;
            for (i = e.split(",")[0].indexOf("base64") >= 0 ? atob(e.split(",")[1]) : decodeURIComponent(e.split(",")[1]), a = new ArrayBuffer(i.length), r = new Uint8Array(a), n = 0; n < i.length; n += 1) r[n] = i.charCodeAt(n);
            return o = e.split(",")[0].split(":")[1].split(";")[0], d ? new Blob([t.hasArrayBufferViewSupport() ? r : a], {type: o}) : (l = new s, l.append(a), l.getBlob(o))
        },
        arrayBuffer2String: function (e) {
            if (window.TextDecoder) return new TextDecoder("utf-8").decode(e);
            var t, i, a, r, n = Array.prototype.slice.apply(new Uint8Array(e)), o = "", l = 0;
            for (t = n.length; t > l;) switch (i = n[l++], i >> 4) {
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                    o += String.fromCharCode(i);
                    break;
                case 12:
                case 13:
                    a = n[l++], o += String.fromCharCode((31 & i) << 6 | 63 & a);
                    break;
                case 14:
                    a = n[l++], r = n[l++], o += String.fromCharCode((15 & i) << 12 | (63 & a) << 6 | (63 & r) << 0)
            }
            return o
        },
        isHtml: function (e) {
            var t = document.createElement("div");
            t.innerHTML = e;
            for (var i = t.childNodes, a = i.length; a--;) if (1 === i[a].nodeType) return !0;
            return !1
        },
        isSvg: function (e) {
            return e.match(/^\s*<\?xml/i) && (e.match(/<!DOCTYPE svg/i) || e.match(/<svg/i))
        },
        getMimeType: function (e, t, i) {
            switch (e) {
                case"ffd8ffe0":
                case"ffd8ffe1":
                case"ffd8ffe2":
                    return "image/jpeg";
                case"89504E47":
                    return "image/png";
                case"47494638":
                    return "image/gif";
                case"49492a00":
                    return "image/tiff";
                case"52494646":
                    return "image/webp";
                case"66747970":
                    return "video/3gp";
                case"4f676753":
                    return "video/ogg";
                case"1a45dfa3":
                    return "video/mkv";
                case"000001ba":
                case"000001b3":
                    return "video/mpeg";
                case"3026b275":
                    return "video/wmv";
                case"25504446":
                    return "application/pdf";
                case"25215053":
                    return "application/ps";
                case"504b0304":
                case"504b0506":
                case"504b0508":
                    return "application/zip";
                case"377abcaf":
                    return "application/7z";
                case"75737461":
                    return "application/tar";
                case"7801730d":
                    return "application/dmg";
                default:
                    switch (e.substring(0, 6)) {
                        case"435753":
                            return "application/x-shockwave-flash";
                        case"494433":
                            return "audio/mp3";
                        case"425a68":
                            return "application/bzip";
                        default:
                            switch (e.substring(0, 4)) {
                                case"424d":
                                    return "image/bmp";
                                case"fffb":
                                    return "audio/mp3";
                                case"4d5a":
                                    return "application/exe";
                                case"1f9d":
                                case"1fa0":
                                    return "application/zip";
                                case"1f8b":
                                    return "application/gzip";
                                default:
                                    return t && !t.match(/[^\u0000-\u007f]/) ? "application/text-plain" : i
                            }
                    }
            }
        },
        addCss: function (e, t) {
            e.removeClass(t).addClass(t)
        },
        getElement: function (i, a, r) {
            return t.isEmpty(i) || t.isEmpty(i[a]) ? r : e(i[a])
        },
        uniqId: function () {
            return Math.round((new Date).getTime()) + "_" + Math.round(100 * Math.random())
        },
        htmlEncode: function (e, t) {
            return void 0 === e ? t || null : e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&apos;")
        },
        replaceTags: function (t, i) {
            var a = t;
            return i ? (e.each(i, function (e, t) {
                "function" == typeof t && (t = t()), a = a.split(e).join(t)
            }), a) : a
        },
        cleanMemory: function (e) {
            var i = e.is("img") ? e.attr("src") : e.find("source").attr("src");
            t.revokeObjectURL(i)
        },
        findFileName: function (e) {
            var t = e.lastIndexOf("/");
            return -1 === t && (t = e.lastIndexOf("\\")), e.split(e.substring(t, t + 1)).pop()
        },
        checkFullScreen: function () {
            return document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement
        },
        toggleFullScreen: function (e) {
            var i = document, a = i.documentElement;
            a && e && !t.checkFullScreen() ? a.requestFullscreen ? a.requestFullscreen() : a.msRequestFullscreen ? a.msRequestFullscreen() : a.mozRequestFullScreen ? a.mozRequestFullScreen() : a.webkitRequestFullscreen && a.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT) : i.exitFullscreen ? i.exitFullscreen() : i.msExitFullscreen ? i.msExitFullscreen() : i.mozCancelFullScreen ? i.mozCancelFullScreen() : i.webkitExitFullscreen && i.webkitExitFullscreen()
        },
        moveArray: function (t, i, a, r) {
            var n = e.extend(!0, [], t);
            if (r && n.reverse(), a >= n.length) for (var o = a - n.length; o-- + 1;) n.push(void 0);
            return n.splice(a, 0, n.splice(i, 1)[0]), r && n.reverse(), n
        },
        cleanZoomCache: function (e) {
            var t = e.closest(".kv-zoom-cache-theme");
            t.length || (t = e.closest(".kv-zoom-cache")), t.remove()
        },
        closeButton: function (e) {
            return e = e ? "close " + e : "close", '<button type="button" class="' + e + '" aria-label="Close">\n  <span aria-hidden="true">&times;</span>\n</button>'
        },
        getRotation: function (e) {
            switch (e) {
                case 2:
                    return "rotateY(180deg)";
                case 3:
                    return "rotate(180deg)";
                case 4:
                    return "rotate(180deg) rotateY(180deg)";
                case 5:
                    return "rotate(270deg) rotateY(180deg)";
                case 6:
                    return "rotate(90deg)";
                case 7:
                    return "rotate(90deg) rotateY(180deg)";
                case 8:
                    return "rotate(270deg)";
                default:
                    return ""
            }
        },
        setTransform: function (e, t) {
            e && (e.style.transform = t, e.style.webkitTransform = t, e.style["-moz-transform"] = t, e.style["-ms-transform"] = t, e.style["-o-transform"] = t)
        },
        setImageOrientation: function (e, i, a) {
            if (e && e.length) {
                var r = "load.fileinputimageorient";
                e.off(r).on(r, function () {
                    var r = e.get(0), n = i && i.length ? i.get(0) : null, o = r.offsetHeight, l = r.offsetWidth,
                        s = t.getRotation(a);
                    if (e.data("orientation", a), n && i.data("orientation", a), 5 > a) return t.setTransform(r, s), void t.setTransform(n, s);
                    var d = Math.atan(l / o), c = Math.sqrt(Math.pow(o, 2) + Math.pow(l, 2)),
                        p = c ? o / Math.cos(Math.PI / 2 + d) / c : 1, u = " scale(" + Math.abs(p) + ")";
                    t.setTransform(r, s + u), t.setTransform(n, s + u)
                })
            }
        }
    }, i = function (i, a) {
        var r = this;
        r.$element = e(i), r.$parent = r.$element.parent(), r._validate() && (r.isPreviewable = t.hasFileAPISupport(), r.isIE9 = t.isIE(9), r.isIE10 = t.isIE(10), (r.isPreviewable || r.isIE9) && (r._init(a), r._listen()), r.$element.removeClass("file-loading"))
    }, i.prototype = {
        constructor: i, _cleanup: function () {
            var e = this;
            e.reader = null, e.formdata = {}, e.uploadCount = 0, e.uploadStatus = {}, e.uploadLog = [], e.uploadAsyncCount = 0, e.loadedImages = [], e.totalImagesCount = 0, e.ajaxRequests = [], e.clearStack(), e.fileBatchCompleted = !0, e.isPreviewable || (e.showPreview = !1), e.isError = !1, e.ajaxAborted = !1, e.cancelling = !1
        }, _init: function (i, a) {
            var r, n, o, l, s = this, d = s.$element;
            s.options = i, e.each(i, function (e, i) {
                switch (e) {
                    case"minFileCount":
                    case"maxFileCount":
                    case"minFileSize":
                    case"maxFileSize":
                    case"maxFilePreviewSize":
                    case"resizeImageQuality":
                    case"resizeIfSizeMoreThan":
                    case"progressUploadThreshold":
                    case"initialPreviewCount":
                    case"zoomModalHeight":
                    case"minImageHeight":
                    case"maxImageHeight":
                    case"minImageWidth":
                    case"maxImageWidth":
                        s[e] = t.getNum(i);
                        break;
                    default:
                        s[e] = i
                }
            }), s.rtl && (l = s.previewZoomButtonIcons.prev, s.previewZoomButtonIcons.prev = s.previewZoomButtonIcons.next, s.previewZoomButtonIcons.next = l), a || s._cleanup(), s.$form = d.closest("form"), s._initTemplateDefaults(), s.uploadFileAttr = t.isEmpty(d.attr("name")) ? "file_data" : d.attr("name"), o = s._getLayoutTemplate("progress"), s.progressTemplate = o.replace("{class}", s.progressClass), s.progressCompleteTemplate = o.replace("{class}", s.progressCompleteClass), s.progressErrorTemplate = o.replace("{class}", s.progressErrorClass), s.isDisabled = d.attr("disabled") || d.attr("readonly"), s.isDisabled && d.attr("disabled", !0), s.isClickable = s.browseOnZoneClick && s.showPreview && (s.dropZoneEnabled || !t.isEmpty(s.defaultPreviewContent)), s.isAjaxUpload = t.hasFileUploadSupport() && !t.isEmpty(s.uploadUrl), s.dropZoneEnabled = t.hasDragDropSupport() && s.dropZoneEnabled, s.isAjaxUpload || (s.dropZoneEnabled = s.dropZoneEnabled && t.canAssignFilesToInput()), s.slug = "function" == typeof i.slugCallback ? i.slugCallback : s._slugDefault, s.mainTemplate = s.showCaption ? s._getLayoutTemplate("main1") : s._getLayoutTemplate("main2"), s.captionTemplate = s._getLayoutTemplate("caption"), s.previewGenericTemplate = s._getPreviewTemplate("generic"), !s.imageCanvas && s.resizeImage && (s.maxImageWidth || s.maxImageHeight) && (s.imageCanvas = document.createElement("canvas"), s.imageCanvasContext = s.imageCanvas.getContext("2d")), t.isEmpty(d.attr("id")) && d.attr("id", t.uniqId()), s.namespace = ".fileinput_" + d.attr("id").replace(/-/g, "_"), void 0 === s.$container ? s.$container = s._createContainer() : s._refreshContainer(), n = s.$container, s.$dropZone = n.find(".file-drop-zone"), s.$progress = n.find(".kv-upload-progress"), s.$btnUpload = n.find(".fileinput-upload"), s.$captionContainer = t.getElement(i, "elCaptionContainer", n.find(".file-caption")), s.$caption = t.getElement(i, "elCaptionText", n.find(".file-caption-name")), t.isEmpty(s.msgPlaceholder) || (r = d.attr("multiple") ? s.filePlural : s.fileSingle, s.$caption.attr("placeholder", s.msgPlaceholder.replace("{files}", r))), s.$captionIcon = s.$captionContainer.find(".file-caption-icon"), s.$previewContainer = t.getElement(i, "elPreviewContainer", n.find(".file-preview")), s.$preview = t.getElement(i, "elPreviewImage", n.find(".file-preview-thumbnails")), s.$previewStatus = t.getElement(i, "elPreviewStatus", n.find(".file-preview-status")), s.$errorContainer = t.getElement(i, "elErrorContainer", s.$previewContainer.find(".kv-fileinput-error")), s._validateDisabled(), t.isEmpty(s.msgErrorClass) || t.addCss(s.$errorContainer, s.msgErrorClass), a ? s._errorsExist() || s.$errorContainer.hide() : (s.$errorContainer.hide(), s.previewInitId = "preview-" + t.uniqId(), s._initPreviewCache(), s._initPreview(!0), s._initPreviewActions(), s.$parent.hasClass("file-loading") && (s.$container.insertBefore(s.$parent), s.$parent.remove())), s._setFileDropZoneTitle(), d.attr("disabled") && s.disable(), s._initZoom(), s.hideThumbnailContent && t.addCss(s.$preview, "hide-content")
        }, _initTemplateDefaults: function () {
            var i, a, r, n, o, l, s, d, c, p, u, f, m, v, g, h, w, _, b, C, y, x, T, E, S, k, F, P, I, A, D, z, $, j, U,
                R, B, O, L, M, Z = this;
            i = '{preview}\n<div class="kv-upload-progress kv-hidden"></div><div class="clearfix"></div>\n<div class="input-group {class}">\n  {caption}\n<div class="input-group-btn input-group-append">\n      {remove}\n      {cancel}\n      {upload}\n      {browse}\n    </div>\n</div>', a = '{preview}\n<div class="kv-upload-progress kv-hidden"></div>\n<div class="clearfix"></div>\n{remove}\n{cancel}\n{upload}\n{browse}\n', r = '<div class="file-preview {class}">\n    {close}    <div class="{dropClass}">\n    <div class="file-preview-thumbnails">\n    </div>\n    <div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>\n    <div class="kv-fileinput-error"></div>\n    </div>\n</div>', o = t.closeButton("fileinput-remove"), n = '<i class="icon-file"></i>', l = '<div class="file-caption form-control {class}" tabindex="500">\n  <span class="file-caption-icon"></span>\n  <input class="file-caption-name" onkeydown="return false;" onpaste="return false;">\n</div>', s = '<button type="{type}" tabindex="500" title="{title}" class="{css}" {status}>{icon} {label}</button>', d = '<a href="{href}" tabindex="500" title="{title}" class="{css}" {status}>{icon} {label}</a>', c = '<div tabindex="500" class="{css}" {status}>{icon} {label}</div>', p = '<div id="' + t.MODAL_ID + '" class="file-zoom-dialog modal fade" tabindex="-1" aria-labelledby="' + t.MODAL_ID + 'Label"></div>', u = '<div class="modal-dialog modal-lg{rtl}" role="document">\n  <div class="modal-content">\n    <div class="modal-header">\n      <h5 class="modal-title">{heading}</h5>\n      <span class="kv-zoom-title"></span>\n      <div class="kv-zoom-actions">{toggleheader}{fullscreen}{borderless}{close}</div>\n    </div>\n    <div class="modal-body">\n      <div class="floating-buttons"></div>\n      <div class="kv-zoom-body file-zoom-content {zoomFrameClass}"></div>\n{prev} {next}\n    </div>\n  </div>\n</div>\n', f = '<div class="progress">\n    <div class="{class}" role="progressbar" aria-valuenow="{percent}" aria-valuemin="0" aria-valuemax="100" style="width:{percent}%;">\n        {status}\n     </div>\n</div>', m = " <samp>({sizeText})</samp>", v = '<div class="file-thumbnail-footer">\n    <div class="file-footer-caption" title="{caption}">\n        <div class="file-caption-info">{caption}</div>\n        <div class="file-size-info">{size}</div>\n    </div>\n    {progress}\n{indicator}\n{actions}\n</div>', g = '<div class="file-actions">\n    <div class="file-footer-buttons">\n        {download} {upload} {delete} {zoom} {other}    </div>\n</div>\n{drag}\n<div class="clearfix"></div>', h = '<button type="button" class="kv-file-remove {removeClass}" title="{removeTitle}" {dataUrl}{dataKey}>{removeIcon}</button>\n', w = '<button type="button" class="kv-file-upload {uploadClass}" title="{uploadTitle}">{uploadIcon}</button>', _ = '<a class="kv-file-download {downloadClass}" title="{downloadTitle}" href="{downloadUrl}" download="{caption}" target="_blank">{downloadIcon}</a>', b = '<button type="button" class="kv-file-zoom {zoomClass}" title="{zoomTitle}">{zoomIcon}</button>', C = '<span class="file-drag-handle {dragClass}" title="{dragTitle}">{dragIcon}</span>', y = '<div class="file-upload-indicator" title="{indicatorTitle}">{indicator}</div>', x = '<div class="file-preview-frame {frameClass}" id="{previewId}" data-fileindex="{fileindex}" data-template="{template}"', T = x + '><div class="kv-file-content">\n', E = x + ' title="{caption}"><div class="kv-file-content">\n', S = "</div>{footer}\n</div>\n", k = "{content}\n", O = " {style}", F = '<div class="kv-preview-data file-preview-html" title="{caption}"' + O + ">{data}</div>\n", P = '<img src="{data}" class="file-preview-image kv-preview-data" title="{caption}" alt="{caption}"' + O + ">\n", I = '<textarea class="kv-preview-data file-preview-text" title="{caption}" readonly' + O + ">{data}</textarea>\n", A = '<iframe class="kv-preview-data file-preview-office" src="https://view.officeapps.live.com/op/embed.aspx?src={data}"' + O + "></iframe>", D = '<iframe class="kv-preview-data file-preview-gdocs" src="https://docs.google.com/gview?url={data}&embedded=true"' + O + "></iframe>", z = '<video class="kv-preview-data file-preview-video" controls' + O + '>\n<source src="{data}" type="{type}">\n' + t.DEFAULT_PREVIEW + "\n</video>\n", $ = '<!--suppress ALL --><audio class="kv-preview-data file-preview-audio" controls' + O + '>\n<source src="{data}" type="{type}">\n' + t.DEFAULT_PREVIEW + "\n</audio>\n", j = '<embed class="kv-preview-data file-preview-flash" src="{data}" type="application/x-shockwave-flash"' + O + ">\n", R = '<embed class="kv-preview-data file-preview-pdf" src="{data}" type="application/pdf"' + O + ">\n", U = '<object class="kv-preview-data file-preview-object file-object {typeCss}" data="{data}" type="{type}"' + O + '>\n<param name="movie" value="{caption}" />\n' + t.OBJECT_PARAMS + " " + t.DEFAULT_PREVIEW + "\n</object>\n", B = '<div class="kv-preview-data file-preview-other-frame"' + O + ">\n" + t.DEFAULT_PREVIEW + "\n</div>\n", L = '<div class="kv-zoom-cache" style="display:none">{zoomContent}</div>', M = {
                width: "100%",
                height: "100%",
                "min-height": "480px"
            }, Z._isPdfRendered() && (R = Z.pdfRendererTemplate.replace("{renderer}", Z._encodeURI(Z.pdfRendererUrl))), Z.defaults = {
                layoutTemplates: {
                    main1: i,
                    main2: a,
                    preview: r,
                    close: o,
                    fileIcon: n,
                    caption: l,
                    modalMain: p,
                    modal: u,
                    progress: f,
                    size: m,
                    footer: v,
                    indicator: y,
                    actions: g,
                    actionDelete: h,
                    actionUpload: w,
                    actionDownload: _,
                    actionZoom: b,
                    actionDrag: C,
                    btnDefault: s,
                    btnLink: d,
                    btnBrowse: c,
                    zoomCache: L
                },
                previewMarkupTags: {tagBefore1: T, tagBefore2: E, tagAfter: S},
                previewContentTemplates: {
                    generic: k,
                    html: F,
                    image: P,
                    text: I,
                    office: A,
                    gdocs: D,
                    video: z,
                    audio: $,
                    flash: j,
                    object: U,
                    pdf: R,
                    other: B
                },
                allowedPreviewTypes: ["image", "html", "text", "video", "audio", "flash", "pdf", "object"],
                previewTemplates: {},
                previewSettings: {
                    image: {width: "auto", height: "auto", "max-width": "100%", "max-height": "100%"},
                    html: {width: "213px", height: "160px"},
                    text: {width: "213px", height: "160px"},
                    office: {width: "213px", height: "160px"},
                    gdocs: {width: "213px", height: "160px"},
                    video: {width: "213px", height: "160px"},
                    audio: {width: "100%", height: "30px"},
                    flash: {width: "213px", height: "160px"},
                    object: {width: "213px", height: "160px"},
                    pdf: {width: "100%", height: "160px"},
                    other: {width: "213px", height: "160px"}
                },
                previewSettingsSmall: {
                    image: {
                        width: "auto",
                        height: "auto",
                        "max-width": "100%",
                        "max-height": "100%"
                    },
                    html: {width: "100%", height: "160px"},
                    text: {width: "100%", height: "160px"},
                    office: {width: "100%", height: "160px"},
                    gdocs: {width: "100%", height: "160px"},
                    video: {width: "100%", height: "auto"},
                    audio: {width: "100%", height: "30px"},
                    flash: {width: "100%", height: "auto"},
                    object: {width: "100%", height: "auto"},
                    pdf: {width: "100%", height: "160px"},
                    other: {width: "100%", height: "160px"}
                },
                previewZoomSettings: {
                    image: {width: "auto", height: "auto", "max-width": "100%", "max-height": "100%"},
                    html: M,
                    text: M,
                    office: {width: "100%", height: "100%", "max-width": "100%", "min-height": "480px"},
                    gdocs: {width: "100%", height: "100%", "max-width": "100%", "min-height": "480px"},
                    video: {width: "auto", height: "100%", "max-width": "100%"},
                    audio: {width: "100%", height: "30px"},
                    flash: {width: "auto", height: "480px"},
                    object: {width: "auto", height: "100%", "max-width": "100%", "min-height": "480px"},
                    pdf: M,
                    other: {width: "auto", height: "100%", "min-height": "480px"}
                },
                mimeTypeAliases: {"video/quicktime": "video/mp4"},
                fileTypeSettings: {
                    image: function (e, i) {
                        return t.compare(e, "image.*") && !t.compare(e, /(tiff?|wmf)$/i) || t.compare(i, /\.(gif|png|jpe?g)$/i)
                    }, html: function (e, i) {
                        return t.compare(e, "text/html") || t.compare(i, /\.(htm|html)$/i)
                    }, office: function (e, i) {
                        return t.compare(e, /(word|excel|powerpoint|office)$/i) || t.compare(i, /\.(docx?|xlsx?|pptx?|pps|potx?)$/i)
                    }, gdocs: function (e, i) {
                        return t.compare(e, /(word|excel|powerpoint|office|iwork-pages|tiff?)$/i) || t.compare(i, /\.(docx?|xlsx?|pptx?|pps|potx?|rtf|ods|odt|pages|ai|dxf|ttf|tiff?|wmf|e?ps)$/i)
                    }, text: function (e, i) {
                        return t.compare(e, "text.*") || t.compare(i, /\.(xml|javascript)$/i) || t.compare(i, /\.(txt|md|csv|nfo|ini|json|php|js|css)$/i)
                    }, video: function (e, i) {
                        return t.compare(e, "video.*") && (t.compare(e, /(ogg|mp4|mp?g|mov|webm|3gp)$/i) || t.compare(i, /\.(og?|mp4|webm|mp?g|mov|3gp)$/i))
                    }, audio: function (e, i) {
                        return t.compare(e, "audio.*") && (t.compare(i, /(ogg|mp3|mp?g|wav)$/i) || t.compare(i, /\.(og?|mp3|mp?g|wav)$/i))
                    }, flash: function (e, i) {
                        return t.compare(e, "application/x-shockwave-flash", !0) || t.compare(i, /\.(swf)$/i)
                    }, pdf: function (e, i) {
                        return t.compare(e, "application/pdf", !0) || t.compare(i, /\.(pdf)$/i)
                    }, object: function () {
                        return !0
                    }, other: function () {
                        return !0
                    }
                },
                fileActionSettings: {
                    showRemove: !0,
                    showUpload: !0,
                    showDownload: !0,
                    showZoom: !0,
                    showDrag: !0,
                    removeIcon: '<i class="icon-trash"></i>',
                    removeClass: "btn btn-sm btn-kv btn-default btn-outline-secondary",
                    removeErrorClass: "btn btn-sm btn-kv btn-danger",
                    removeTitle: "Remove file",
                    uploadIcon: '<i class="icon-upload"></i>',
                    uploadClass: "btn btn-sm btn-kv btn-default btn-outline-secondary",
                    uploadTitle: "Upload file",
                    uploadRetryIcon: '<i class="icon-repeat"></i>',
                    uploadRetryTitle: "Retry upload",
                    downloadIcon: '<i class="icon-download"></i>',
                    downloadClass: "btn btn-sm btn-kv btn-default btn-outline-secondary",
                    downloadTitle: "Download file",
                    zoomIcon: '<i class="la la-search-plus"></i>',
                    zoomClass: "btn btn-sm btn-kv btn-default btn-outline-secondary",
                    zoomTitle: "View Details",
                    dragIcon: '<i class="la la-arrows"></i>',
                    dragClass: "text-info",
                    dragTitle: "Move / Rearrange",
                    dragSettings: {},
                    indicatorNew: '<i class="icon-plus-sign text-warning"></i>',
                    indicatorSuccess: '<i class="icon-ok-sign text-success"></i>',
                    indicatorError: '<i class="icon-exclamation-sign text-danger"></i>',
                    indicatorLoading: '<i class="icon-hourglass text-muted"></i>',
                    indicatorNewTitle: "Not uploaded yet",
                    indicatorSuccessTitle: "Uploaded",
                    indicatorErrorTitle: "Upload Error",
                    indicatorLoadingTitle: "Uploading ..."
                }
            }, e.each(Z.defaults, function (t, i) {
                return "allowedPreviewTypes" === t ? void (void 0 === Z.allowedPreviewTypes && (Z.allowedPreviewTypes = i)) : void (Z[t] = e.extend(!0, {}, i, Z[t]))
            }), Z._initPreviewTemplates()
        }, _initPreviewTemplates: function () {
            var i, a = this, r = a.previewMarkupTags, n = r.tagAfter;
            e.each(a.previewContentTemplates, function (e, o) {
                t.isEmpty(a.previewTemplates[e]) && (i = r.tagBefore2, "generic" !== e && "image" !== e && "html" !== e && "text" !== e || (i = r.tagBefore1), a._isPdfRendered() && "pdf" === e && (i = i.replace("kv-file-content", "kv-file-content kv-pdf-rendered")), a.previewTemplates[e] = i + o + n)
            })
        }, _initPreviewCache: function () {
            var i = this;
            i.previewCache = {
                data: {}, init: function () {
                    var e = i.initialPreview;
                    e.length > 0 && !t.isArray(e) && (e = e.split(i.initialPreviewDelimiter)), i.previewCache.data = {
                        content: e,
                        config: i.initialPreviewConfig,
                        tags: i.initialPreviewThumbTags
                    }
                }, count: function () {
                    return i.previewCache.data && i.previewCache.data.content ? i.previewCache.data.content.length : 0
                }, get: function (a, r) {
                    var n, o, l, s, d, c, p, u = "init_" + a, f = i.previewCache.data, m = f.config[a],
                        v = f.content[a], g = i.previewInitId + "-" + u,
                        h = t.ifSet("previewAsData", m, i.initialPreviewAsData),
                        w = function (e, a, r, n, o, l, s, d, c) {
                            return d = " file-preview-initial " + t.SORT_CSS + (d ? " " + d : ""), i._generatePreviewTemplate(e, a, r, n, o, !1, null, d, l, s, c)
                        };
                    return v ? (r = void 0 === r ? !0 : r, l = t.ifSet("type", m, i.initialPreviewFileType || "generic"), d = t.ifSet("filename", m, t.ifSet("caption", m)), c = t.ifSet("filetype", m, l), s = i.previewCache.footer(a, r, m && m.size || null), p = t.ifSet("frameClass", m), n = h ? w(l, v, d, c, g, s, u, p) : w("generic", v, d, c, g, s, u, p, l).setTokens({content: f.content[a]}), f.tags.length && f.tags[a] && (n = t.replaceTags(n, f.tags[a])), t.isEmpty(m) || t.isEmpty(m.frameAttr) || (o = e(document.createElement("div")).html(n), o.find(".file-preview-initial").attr(m.frameAttr), n = o.html(), o.remove()), n) : ""
                }, add: function (e, a, r, n) {
                    var o, l = i.previewCache.data;
                    return t.isArray(e) || (e = e.split(i.initialPreviewDelimiter)), n ? (o = l.content.push(e) - 1, l.config[o] = a, l.tags[o] = r) : (o = e.length - 1, l.content = e, l.config = a, l.tags = r), i.previewCache.data = l, o
                }, set: function (e, a, r, n) {
                    var o, l, s = i.previewCache.data;
                    if (e && e.length && (t.isArray(e) || (e = e.split(i.initialPreviewDelimiter)), l = e.filter(function (e) {
                        return null !== e
                    }), l.length)) {
                        if (void 0 === s.content && (s.content = []), void 0 === s.config && (s.config = []), void 0 === s.tags && (s.tags = []), n) {
                            for (o = 0; o < e.length; o++) e[o] && s.content.push(e[o]);
                            for (o = 0; o < a.length; o++) a[o] && s.config.push(a[o]);
                            for (o = 0; o < r.length; o++) r[o] && s.tags.push(r[o])
                        } else s.content = e, s.config = a, s.tags = r;
                        i.previewCache.data = s
                    }
                }, unset: function (e) {
                    var a = i.previewCache.count(), r = i.reversePreviewOrder;
                    if (a) {
                        if (1 === a) return i.previewCache.data.content = [], i.previewCache.data.config = [], i.previewCache.data.tags = [], i.initialPreview = [], i.initialPreviewConfig = [], void (i.initialPreviewThumbTags = []);
                        i.previewCache.data.content = t.spliceArray(i.previewCache.data.content, e, r), i.previewCache.data.config = t.spliceArray(i.previewCache.data.config, e, r), i.previewCache.data.tags = t.spliceArray(i.previewCache.data.tags, e, r)
                    }
                }, out: function () {
                    var e, t, a, r = "", n = i.previewCache.count();
                    if (0 === n) return {content: "", caption: ""};
                    for (t = 0; n > t; t++) a = i.previewCache.get(t), r = i.reversePreviewOrder ? a + r : r + a;
                    return e = i._getMsgSelected(n), {content: r, caption: e}
                }, footer: function (e, a, r) {
                    var n = i.previewCache.data || {};
                    if (t.isEmpty(n.content)) return "";
                    (t.isEmpty(n.config) || t.isEmpty(n.config[e])) && (n.config[e] = {}), a = void 0 === a ? !0 : a;
                    var o, l = n.config[e], s = t.ifSet("caption", l), d = t.ifSet("width", l, "auto"),
                        c = t.ifSet("url", l, !1), p = t.ifSet("key", l, null), u = i.fileActionSettings,
                        f = i.initialPreviewShowDelete || !1, m = l.downloadUrl || i.initialPreviewDownloadUrl || "",
                        v = l.filename || l.caption || "", g = !!m,
                        h = t.ifSet("showRemove", l, t.ifSet("showRemove", u, f)),
                        w = t.ifSet("showDownload", l, t.ifSet("showDownload", u, g)),
                        _ = t.ifSet("showZoom", l, t.ifSet("showZoom", u, !0)),
                        b = t.ifSet("showDrag", l, t.ifSet("showDrag", u, !0)), C = c === !1 && a;
                    return w = w && l.downloadUrl !== !1 && !!m, o = i._renderFileActions(!1, w, h, _, b, C, c, p, !0, m, v), i._getLayoutTemplate("footer").setTokens({
                        progress: i._renderThumbProgress(),
                        actions: o,
                        caption: s,
                        size: i._getSize(r),
                        width: d,
                        indicator: ""
                    })
                }
            }, i.previewCache.init()
        }, _isPdfRendered: function () {
            var e = this, t = e.usePdfRenderer, i = "function" == typeof t ? t() : !!t;
            return i && e.pdfRendererUrl
        }, _handler: function (e, t, i) {
            var a = this, r = a.namespace, n = t.split(" ").join(r + " ") + r;
            e && e.length && e.off(n).on(n, i)
        }, _encodeURI: function (e) {
            var t = this;
            return t.encodeUrl ? encodeURI(e) : e
        }, _log: function (e) {
            var t = this, i = t.$element.attr("id");
            i && (e = '"' + i + '": ' + e), e = "bootstrap-fileinput: " + e, "undefined" != typeof window.console.log ? window.console.log(e) : window.alert(e)
        }, _validate: function () {
            var e = this, t = "file" === e.$element.attr("type");
            return t || e._log('The input "type" must be set to "file" for initializing the "bootstrap-fileinput" plugin.'), t
        }, _errorsExist: function () {
            var t, i = this, a = i.$errorContainer.find("li");
            return a.length ? !0 : (t = e(document.createElement("div")).html(i.$errorContainer.html()), t.find(".kv-error-close").remove(), t.find("ul").remove(), !!e.trim(t.text()).length)
        }, _errorHandler: function (e, t) {
            var i = this, a = e.target.error, r = function (e) {
                i._showError(e.replace("{name}", t))
            };
            r(a.code === a.NOT_FOUND_ERR ? i.msgFileNotFound : a.code === a.SECURITY_ERR ? i.msgFileSecured : a.code === a.NOT_READABLE_ERR ? i.msgFileNotReadable : a.code === a.ABORT_ERR ? i.msgFilePreviewAborted : i.msgFilePreviewError)
        }, _addError: function (e) {
            var t = this, i = t.$errorContainer;
            e && i.length && (i.html(t.errorCloseButton + e), t._handler(i.find(".kv-error-close"), "click", function () {
                setTimeout(function () {
                    t.showPreview && !t.getFrames().length && t.clear(), i.fadeOut("slow")
                }, 10)
            }))
        }, _setValidationError: function (e) {
            var i = this;
            e = (e ? e + " " : "") + "has-error", i.$container.removeClass(e).addClass("has-error"), t.addCss(i.$captionContainer, "is-invalid")
        }, _resetErrors: function (e) {
            var t = this, i = t.$errorContainer;
            t.isError = !1, t.$container.removeClass("has-error"), t.$captionContainer.removeClass("is-invalid"), i.html(""), e ? i.fadeOut("slow") : i.hide()
        }, _showFolderError: function (e) {
            var t, i = this, a = i.$errorContainer;
            e && (i.isAjaxUpload || i._clearFileInput(), t = i.msgFoldersNotAllowed.replace("{n}", e), i._addError(t), i._setValidationError(), a.fadeIn(800), i._raise("filefoldererror", [e, t]))
        }, _showUploadError: function (e, t, i) {
            var a = this, r = a.$errorContainer, n = i || "fileuploaderror",
                o = t && t.id ? '<li data-file-id="' + t.id + '">' + e + "</li>" : "<li>" + e + "</li>";
            return 0 === r.find("ul").length ? a._addError("<ul>" + o + "</ul>") : r.find("ul").append(o), r.fadeIn(800), a._raise(n, [t, e]), a._setValidationError("file-input-new"), !0
        }, _showError: function (e, t, i) {
            var a = this, r = a.$errorContainer, n = i || "fileerror";
            return t = t || {}, t.reader = a.reader, a._addError(e), r.fadeIn(800), a._raise(n, [t, e]), a.isAjaxUpload || a._clearFileInput(), a._setValidationError("file-input-new"), a.$btnUpload.attr("disabled", !0), !0
        }, _noFilesError: function (e) {
            var t = this, i = t.minFileCount > 1 ? t.filePlural : t.fileSingle,
                a = t.msgFilesTooLess.replace("{n}", t.minFileCount).replace("{files}", i), r = t.$errorContainer;
            t._addError(a), t.isError = !0, t._updateFileDetails(0), r.fadeIn(800), t._raise("fileerror", [e, a]), t._clearFileInput(), t._setValidationError()
        }, _parseError: function (t, i, a, r) {
            var n, o = this, l = e.trim(a + ""),
                s = void 0 !== i.responseJSON && void 0 !== i.responseJSON.error ? i.responseJSON.error : i.responseText;
            return o.cancelling && o.msgUploadAborted && (l = o.msgUploadAborted), o.showAjaxErrorDetails && s && (s = e.trim(s.replace(/\n\s*\n/g, "\n")), n = s.length ? "<pre>" + s + "</pre>" : "", l += l ? n : s), l || (l = o.msgAjaxError.replace("{operation}", t)), o.cancelling = !1, r ? "<b>" + r + ": </b>" + l : l
        }, _parseFileType: function (e, i) {
            var a, r, n, o, l = this, s = l.allowedPreviewTypes || [];
            if ("application/text-plain" === e) return "text";
            for (o = 0; o < s.length; o++) if (n = s[o], a = l.fileTypeSettings[n], r = a(e, i) ? n : "", !t.isEmpty(r)) return r;
            return "other"
        }, _getPreviewIcon: function (t) {
            var i, a = this, r = null;
            return t && t.indexOf(".") > -1 && (i = t.split(".").pop(), a.previewFileIconSettings && (r = a.previewFileIconSettings[i] || a.previewFileIconSettings[i.toLowerCase()] || null), a.previewFileExtSettings && e.each(a.previewFileExtSettings, function (e, t) {
                return a.previewFileIconSettings[e] && t(i) ? void (r = a.previewFileIconSettings[e]) : void 0
            })), r
        }, _parseFilePreviewIcon: function (e, t) {
            var i = this, a = i._getPreviewIcon(t) || i.previewFileIcon, r = e;
            return r.indexOf("{previewFileIcon}") > -1 && (r = r.setTokens({
                previewFileIconClass: i.previewFileIconClass,
                previewFileIcon: a
            })), r
        }, _raise: function (t, i) {
            var a = this, r = e.Event(t);
            if (void 0 !== i ? a.$element.trigger(r, i) : a.$element.trigger(r), r.isDefaultPrevented() || r.result === !1) return !1;
            switch (t) {
                case"filebatchuploadcomplete":
                case"filebatchuploadsuccess":
                case"fileuploaded":
                case"fileclear":
                case"filecleared":
                case"filereset":
                case"fileerror":
                case"filefoldererror":
                case"fileuploaderror":
                case"filebatchuploaderror":
                case"filedeleteerror":
                case"filecustomerror":
                case"filesuccessremove":
                    break;
                default:
                    a.ajaxAborted || (a.ajaxAborted = r.result)
            }
            return !0
        }, _listenFullScreen: function (e) {
            var t, i, a = this, r = a.$modal;
            r && r.length && (t = r && r.find(".btn-fullscreen"), i = r && r.find(".btn-borderless"), t.length && i.length && (t.removeClass("active").attr("aria-pressed", "false"), i.removeClass("active").attr("aria-pressed", "false"), e ? t.addClass("active").attr("aria-pressed", "true") : i.addClass("active").attr("aria-pressed", "true"), r.hasClass("file-zoom-fullscreen") ? a._maximizeZoomDialog() : e ? a._maximizeZoomDialog() : i.removeClass("active").attr("aria-pressed", "false")))
        }, _listen: function () {
            var i, a = this, r = a.$element, n = a.$form, o = a.$container;
            a._handler(r, "click", function (e) {
                r.hasClass("file-no-browse") && (r.data("zoneClicked") ? r.data("zoneClicked", !1) : e.preventDefault())
            }), a._handler(r, "change", e.proxy(a._change, a)), a.showBrowse && a._handler(a.$btnFile, "click", e.proxy(a._browse, a)), a._handler(o.find(".fileinput-remove:not([disabled])"), "click", e.proxy(a.clear, a)), a._handler(o.find(".fileinput-cancel"), "click", e.proxy(a.cancel, a)), a._initDragDrop(), a._handler(n, "reset", e.proxy(a.clear, a)), a.isAjaxUpload || a._handler(n, "submit", e.proxy(a._submitForm, a)), a._handler(a.$container.find(".fileinput-upload"), "click", e.proxy(a._uploadClick, a)), a._handler(e(window), "resize", function () {
                a._listenFullScreen(screen.width === window.innerWidth && screen.height === window.innerHeight)
            }), i = "webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange", a._handler(e(document), i, function () {
                a._listenFullScreen(t.checkFullScreen())
            }), a._autoFitContent(), a._initClickable(), a._refreshPreview()
        }, _autoFitContent: function () {
            var t, i = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth, a = this,
                r = 400 > i ? a.previewSettingsSmall || a.defaults.previewSettingsSmall : a.previewSettings || a.defaults.previewSettings;
            e.each(r, function (e, i) {
                t = ".file-preview-frame .file-preview-" + e, a.$preview.find(t + ".kv-preview-data," + t + " .kv-preview-data").css(i)
            })
        }, _scanDroppedItems: function (e, t, i) {
            i = i || "";
            var a, r, n, o = this, l = function (e) {
                o._log("Error scanning dropped files!"), o._log(e)
            };
            e.isFile ? e.file(function (e) {
                t.push(e)
            }, l) : e.isDirectory && (r = e.createReader(), (n = function () {
                r.readEntries(function (r) {
                    if (r && r.length > 0) {
                        for (a = 0; a < r.length; a++) o._scanDroppedItems(r[a], t, i + e.name + "/");
                        n()
                    }
                    return null
                }, l)
            })())
        }, _initDragDrop: function () {
            var t = this, i = t.$dropZone;
            t.dropZoneEnabled && t.showPreview && (t._handler(i, "dragenter dragover", e.proxy(t._zoneDragEnter, t)), t._handler(i, "dragleave", e.proxy(t._zoneDragLeave, t)), t._handler(i, "drop", e.proxy(t._zoneDrop, t)), t._handler(e(document), "dragenter dragover drop", t._zoneDragDropInit))
        }, _zoneDragDropInit: function (e) {
            e.stopPropagation(), e.preventDefault()
        }, _zoneDragEnter: function (i) {
            var a = this, r = i.originalEvent.dataTransfer, n = e.inArray("Files", r.types) > -1;
            return a._zoneDragDropInit(i), a.isDisabled || !n ? (i.originalEvent.dataTransfer.effectAllowed = "none", void (i.originalEvent.dataTransfer.dropEffect = "none")) : void (a._raise("fileDragEnter", {
                sourceEvent: i,
                files: r.types.Files
            }) && t.addCss(a.$dropZone, "file-highlighted"))
        }, _zoneDragLeave: function (e) {
            var t = this;
            t._zoneDragDropInit(e), t.isDisabled || t._raise("fileDragLeave", {sourceEvent: e}) && t.$dropZone.removeClass("file-highlighted")
        }, _zoneDrop: function (e) {
            var i, a = this, r = a.$element, n = e.originalEvent.dataTransfer, o = n.files, l = n.items,
                s = t.getDragDropFolders(l), d = function () {
                    a.isAjaxUpload ? a._change(e, o) : (a.changeTriggered = !0, r.get(0).files = o, setTimeout(function () {
                        a.changeTriggered = !1, r.trigger("change" + a.namespace)
                    }, 10)), a.$dropZone.removeClass("file-highlighted")
                };
            if (e.preventDefault(), !a.isDisabled && !t.isEmpty(o) && a._raise("fileDragDrop", {
                sourceEvent: e,
                files: o
            })) if (s > 0) {
                if (!a.isAjaxUpload) return void a._showFolderError(s);
                for (o = [], i = 0; i < l.length; i++) {
                    var c = l[i].webkitGetAsEntry();
                    c && a._scanDroppedItems(c, o)
                }
                setTimeout(function () {
                    d()
                }, 500)
            } else d()
        }, _uploadClick: function (e) {
            var i, a = this, r = a.$container.find(".fileinput-upload"),
                n = !r.hasClass("disabled") && t.isEmpty(r.attr("disabled"));
            if (!e || !e.isDefaultPrevented()) {
                if (!a.isAjaxUpload) return void (n && "submit" !== r.attr("type") && (i = r.closest("form"), i.length && i.trigger("submit"), e.preventDefault()));
                e.preventDefault(), n && a.upload()
            }
        }, _submitForm: function () {
            var e = this;
            return e._isFileSelectionValid() && !e._abort({})
        }, _clearPreview: function () {
            var i = this, a = i.$preview,
                r = i.showUploadedThumbs ? i.getFrames(":not(.file-preview-success)") : i.getFrames();
            r.each(function () {
                var i = e(this);
                i.remove(), t.cleanZoomCache(a.find("#zoom-" + i.attr("id")))
            }), i.getFrames().length && i.showPreview || i._resetUpload(), i._validateDefaultPreview()
        }, _initSortable: function () {
            var i, a = this, r = a.$preview, n = "." + t.SORT_CSS, o = a.reversePreviewOrder;
            window.KvSortable && 0 !== r.find(n).length && (i = {
                handle: ".drag-handle-init",
                dataIdAttr: "data-preview-id",
                scroll: !1,
                draggable: n,
                onSort: function (i) {
                    var r = i.oldIndex, n = i.newIndex, l = 0;
                    a.initialPreview = t.moveArray(a.initialPreview, r, n, o), a.initialPreviewConfig = t.moveArray(a.initialPreviewConfig, r, n, o), a.previewCache.init(), a.getFrames(".file-preview-initial").each(function () {
                        e(this).attr("data-fileindex", "init_" + l), l++
                    }), a._raise("filesorted", {
                        previewId: e(i.item).attr("id"),
                        oldIndex: r,
                        newIndex: n,
                        stack: a.initialPreviewConfig
                    })
                }
            }, r.data("kvsortable") && r.kvsortable("destroy"), e.extend(!0, i, a.fileActionSettings.dragSettings), r.kvsortable(i))
        }, _setPreviewContent: function (e) {
            var t = this;
            t.$preview.html(e), t._autoFitContent()
        }, _initPreview: function (e) {
            var i, a = this, r = a.initialCaption || "";
            return a.previewCache.count() ? (i = a.previewCache.out(), r = e && a.initialCaption ? a.initialCaption : i.caption, a._setPreviewContent(i.content), a._setInitThumbAttr(), a._setCaption(r), a._initSortable(), void (t.isEmpty(i.content) || a.$container.removeClass("file-input-new"))) : (a._clearPreview(), void (e ? a._setCaption(r) : a._initCaption()))
        }, _getZoomButton: function (e) {
            var t = this, i = t.previewZoomButtonIcons[e], a = t.previewZoomButtonClasses[e],
                r = ' title="' + (t.previewZoomButtonTitles[e] || "") + '" ',
                n = r + ("close" === e ? ' data-dismiss="modal" aria-hidden="true"' : "");
            return "fullscreen" !== e && "borderless" !== e && "toggleheader" !== e || (n += ' data-toggle="button" aria-pressed="false" autocomplete="off"'), '<button type="button" class="' + a + " btn-" + e + '"' + n + ">" + i + "</button>"
        }, _getModalContent: function () {
            var e = this;
            return e._getLayoutTemplate("modal").setTokens({
                rtl: e.rtl ? " kv-rtl" : "",
                zoomFrameClass: e.frameClass,
                heading: e.msgZoomModalHeading,
                prev: e._getZoomButton("prev"),
                next: e._getZoomButton("next"),
                toggleheader: e._getZoomButton("toggleheader"),
                fullscreen: e._getZoomButton("fullscreen"),
                borderless: e._getZoomButton("borderless"),
                close: e._getZoomButton("close")
            })
        }, _listenModalEvent: function (e) {
            var i = this, a = i.$modal, r = function (e) {
                return {sourceEvent: e, previewId: a.data("previewId"), modal: a}
            };
            a.on(e + ".bs.modal", function (n) {
                var o = a.find(".btn-fullscreen"), l = a.find(".btn-borderless");
                i._raise("filezoom" + e, r(n)), "shown" === e && (l.removeClass("active").attr("aria-pressed", "false"), o.removeClass("active").attr("aria-pressed", "false"), a.hasClass("file-zoom-fullscreen") && (i._maximizeZoomDialog(), t.checkFullScreen() ? o.addClass("active").attr("aria-pressed", "true") : l.addClass("active").attr("aria-pressed", "true")))
            })
        }, _initZoom: function () {
            var i, a = this, r = a._getLayoutTemplate("modalMain"), n = "#" + t.MODAL_ID;
            a.showPreview && (a.$modal = e(n), a.$modal && a.$modal.length || (i = e(document.createElement("div")).html(r).insertAfter(a.$container), a.$modal = e(n).insertBefore(i), i.remove()), t.initModal(a.$modal), a.$modal.html(a._getModalContent()), e.each(t.MODAL_EVENTS, function (e, t) {
                a._listenModalEvent(t)
            }))
        }, _initZoomButtons: function () {
            var t, i, a = this, r = a.$modal.data("previewId") || "", n = a.getFrames().toArray(), o = n.length,
                l = a.$modal.find(".btn-prev"), s = a.$modal.find(".btn-next");
            return n.length < 2 ? (l.hide(), void s.hide()) : (l.show(), s.show(), void (o && (t = e(n[0]), i = e(n[o - 1]), l.removeAttr("disabled"), s.removeAttr("disabled"), t.length && t.attr("id") === r && l.attr("disabled", !0), i.length && i.attr("id") === r && s.attr("disabled", !0))))
        }, _maximizeZoomDialog: function () {
            var t = this, i = t.$modal, a = i.find(".modal-header:visible"), r = i.find(".modal-footer:visible"),
                n = i.find(".modal-body"), o = e(window).height(), l = 0;
            i.addClass("file-zoom-fullscreen"), a && a.length && (o -= a.outerHeight(!0)), r && r.length && (o -= r.outerHeight(!0)), n && n.length && (l = n.outerHeight(!0) - n.height(), o -= l), i.find(".kv-zoom-body").height(o)
        }, _resizeZoomDialog: function (e) {
            var i = this, a = i.$modal, r = a.find(".btn-fullscreen"), n = a.find(".btn-borderless");
            if (a.hasClass("file-zoom-fullscreen")) t.toggleFullScreen(!1), e ? r.hasClass("active") || (a.removeClass("file-zoom-fullscreen"), i._resizeZoomDialog(!0), n.hasClass("active") && n.removeClass("active").attr("aria-pressed", "false")) : r.hasClass("active") ? r.removeClass("active").attr("aria-pressed", "false") : (a.removeClass("file-zoom-fullscreen"), i.$modal.find(".kv-zoom-body").css("height", i.zoomModalHeight)); else {
                if (!e) return void i._maximizeZoomDialog();
                t.toggleFullScreen(!0)
            }
            a.focus()
        }, _setZoomContent: function (i, a) {
            var r, n, o, l, s, d, c, p, u, f, m = this, v = i.attr("id"), g = m.$preview.find("#zoom-" + v),
                h = m.$modal, w = h.find(".btn-fullscreen"), _ = h.find(".btn-borderless"),
                b = h.find(".btn-toggleheader");
            n = g.attr("data-template") || "generic", r = g.find(".kv-file-content"), o = r.length ? r.html() : "", u = i.data("caption") || "", f = i.data("size") || "", l = u + " " + f, h.find(".kv-zoom-title").attr("title", e("<div/>").html(l).text()).html(l), s = h.find(".kv-zoom-body"), h.removeClass("kv-single-content"), a ? (p = s.addClass("file-thumb-loading").clone().insertAfter(s), s.html(o).hide(), p.fadeOut("fast", function () {
                s.fadeIn("fast", function () {
                    s.removeClass("file-thumb-loading")
                }), p.remove()
            })) : s.html(o), c = m.previewZoomSettings[n], c && (d = s.find(".kv-preview-data"), t.addCss(d, "file-zoom-detail"), e.each(c, function (e, t) {
                d.css(e, t), (d.attr("width") && "width" === e || d.attr("height") && "height" === e) && d.removeAttr(e)
            })), h.data("previewId", v), m._handler(h.find(".btn-prev"), "click", function () {
                m._zoomSlideShow("prev", v)
            }), m._handler(h.find(".btn-next"), "click", function () {
                m._zoomSlideShow("next", v)
            }), m._handler(w, "click", function () {
                m._resizeZoomDialog(!0)
            }), m._handler(_, "click", function () {
                m._resizeZoomDialog(!1)
            }), m._handler(b, "click", function () {
                var e, t = h.find(".modal-header"), i = h.find(".modal-body .floating-buttons"),
                    a = t.find(".kv-zoom-actions"), r = function (e) {
                        var i = m.$modal.find(".kv-zoom-body"), a = m.zoomModalHeight;
                        h.hasClass("file-zoom-fullscreen") && (a = i.outerHeight(!0), e || (a -= t.outerHeight(!0))), i.css("height", e ? a + e : a)
                    };
                t.is(":visible") ? (e = t.outerHeight(!0), t.slideUp("slow", function () {
                    a.find(".btn").appendTo(i), r(e)
                })) : (i.find(".btn").appendTo(a), t.slideDown("slow", function () {
                    r()
                })), h.focus()
            }), m._handler(h, "keydown", function (t) {
                var i = t.which || t.keyCode, a = e(this).find(".btn-prev"), r = e(this).find(".btn-next"),
                    n = e(this).data("previewId"), o = m.rtl ? 39 : 37, l = m.rtl ? 37 : 39;
                i === o && a.length && !a.attr("disabled") && m._zoomSlideShow("prev", n), i === l && r.length && !r.attr("disabled") && m._zoomSlideShow("next", n)
            })
        }, _zoomPreview: function (e) {
            var i, a = this, r = a.$modal;
            if (!e.length) throw"Cannot zoom to detailed preview!";
            t.initModal(r), r.html(a._getModalContent()), i = e.closest(t.FRAMES), a._setZoomContent(i), r.modal("show"), a._initZoomButtons()
        }, _zoomSlideShow: function (t, i) {
            var a, r, n, o = this, l = o.$modal.find(".kv-zoom-actions .btn-" + t), s = o.getFrames().toArray(),
                d = s.length;
            if (!l.attr("disabled")) {
                for (r = 0; d > r; r++) if (e(s[r]).attr("id") === i) {
                    n = "prev" === t ? r - 1 : r + 1;
                    break
                }
                0 > n || n >= d || !s[n] || (a = e(s[n]), a.length && o._setZoomContent(a, !0), o._initZoomButtons(), o._raise("filezoom" + t, {
                    previewId: i,
                    modal: o.$modal
                }))
            }
        }, _initZoomButton: function () {
            var t = this;
            t.$preview.find(".kv-file-zoom").each(function () {
                var i = e(this);
                t._handler(i, "click", function () {
                    t._zoomPreview(i)
                })
            })
        }, _inputFileCount: function () {
            return this.$element.get(0).files.length
        }, _refreshPreview: function () {
            var e, t = this;
            t._inputFileCount() && t.showPreview && t.isPreviewable && (t.isAjaxUpload ? (e = t.getFileStack(), t.filestack = [], e.length ? t._clearFileInput() : e = t.$element.get(0).files) : e = t.$element.get(0).files, e && e.length && (t.readFiles(e), t._setFileDropZoneTitle()))
        }, _clearObjects: function (t) {
            t.find("video audio").each(function () {
                this.pause(), e(this).remove()
            }), t.find("img object div").each(function () {
                e(this).remove()
            })
        }, _clearFileInput: function () {
            var t, i, a, r = this, n = r.$element;
            r._inputFileCount() && (t = n.closest("form"), i = e(document.createElement("form")), a = e(document.createElement("div")), n.before(a), t.length ? t.after(i) : a.after(i), i.append(n).trigger("reset"), a.before(n).remove(), i.remove())
        }, _resetUpload: function () {
            var e = this;
            e.uploadCache = {
                content: [],
                config: [],
                tags: [],
                append: !0
            }, e.uploadCount = 0, e.uploadStatus = {}, e.uploadLog = [], e.uploadAsyncCount = 0, e.loadedImages = [], e.totalImagesCount = 0, e.$btnUpload.removeAttr("disabled"), e._setProgress(0), e.$progress.hide(), e._resetErrors(!1), e.ajaxAborted = !1, e.ajaxRequests = [], e._resetCanvas(), e.cacheInitialPreview = {}, e.overwriteInitial && (e.initialPreview = [], e.initialPreviewConfig = [], e.initialPreviewThumbTags = [], e.previewCache.data = {
                content: [],
                config: [],
                tags: []
            })
        }, _resetCanvas: function () {
            var e = this;
            e.canvas && e.imageCanvasContext && e.imageCanvasContext.clearRect(0, 0, e.canvas.width, e.canvas.height)
        }, _hasInitialPreview: function () {
            var e = this;
            return !e.overwriteInitial && e.previewCache.count()
        }, _resetPreview: function () {
            var e, t, i = this;
            i.previewCache.count() ? (e = i.previewCache.out(), i._setPreviewContent(e.content), i._setInitThumbAttr(), t = i.initialCaption ? i.initialCaption : e.caption, i._setCaption(t)) : (i._clearPreview(), i._initCaption()), i.showPreview && (i._initZoom(), i._initSortable())
        }, _clearDefaultPreview: function () {
            var e = this;
            e.$preview.find(".file-default-preview").remove()
        }, _validateDefaultPreview: function () {
            var e = this;
            e.showPreview && !t.isEmpty(e.defaultPreviewContent) && (e._setPreviewContent('<div class="file-default-preview">' + e.defaultPreviewContent + "</div>"), e.$container.removeClass("file-input-new"), e._initClickable())
        }, _resetPreviewThumbs: function (e) {
            var t, i = this;
            return e ? (i._clearPreview(), void i.clearStack()) : void (i._hasInitialPreview() ? (t = i.previewCache.out(), i._setPreviewContent(t.content), i._setInitThumbAttr(), i._setCaption(t.caption), i._initPreviewActions()) : i._clearPreview())
        }, _getLayoutTemplate: function (e) {
            var i = this, a = i.layoutTemplates[e];
            return t.isEmpty(i.customLayoutTags) ? a : t.replaceTags(a, i.customLayoutTags)
        }, _getPreviewTemplate: function (e) {
            var i = this, a = i.previewTemplates[e];
            return t.isEmpty(i.customPreviewTags) ? a : t.replaceTags(a, i.customPreviewTags)
        }, _getOutData: function (e, t, i) {
            var a = this;
            return e = e || {}, t = t || {}, i = i || a.filestack.slice(0) || {}, {
                form: a.formdata,
                files: i,
                filenames: a.filenames,
                filescount: a.getFilesCount(),
                extra: a._getExtraData(),
                response: t,
                reader: a.reader,
                jqXHR: e
            }
        }, _getMsgSelected: function (e) {
            var t = this, i = 1 === e ? t.fileSingle : t.filePlural;
            return e > 0 ? t.msgSelected.replace("{n}", e).replace("{files}", i) : t.msgNoFilesSelected
        }, _getFrame: function (t) {
            var i = this, a = e("#" + t);
            return a.length ? a : (i._log('Invalid thumb frame with id: "' + t + '".'), null)
        }, _getThumbs: function (e) {
            return e = e || "", this.getFrames(":not(.file-preview-initial)" + e)
        }, _getExtraData: function (e, t) {
            var i = this, a = i.uploadExtraData;
            return "function" == typeof i.uploadExtraData && (a = i.uploadExtraData(e, t)), a
        }, _initXhr: function (e, t, i) {
            var a = this;
            return e.upload && e.upload.addEventListener("progress", function (e) {
                var r = 0, n = e.total, o = e.loaded || e.position;
                e.lengthComputable && (r = Math.floor(o / n * 100)), t ? a._setAsyncUploadStatus(t, r, i) : a._setProgress(r)
            }, !1), e
        }, _initAjaxSettings: function () {
            var t = this;
            t._ajaxSettings = e.extend(!0, {}, t.ajaxSettings), t._ajaxDeleteSettings = e.extend(!0, {}, t.ajaxDeleteSettings)
        }, _mergeAjaxCallback: function (e, t, i) {
            var a, r = this, n = r._ajaxSettings, o = r.mergeAjaxCallbacks;
            "delete" === i && (n = r._ajaxDeleteSettings, o = r.mergeAjaxDeleteCallbacks), a = n[e], o && "function" == typeof a ? "before" === o ? n[e] = function () {
                a.apply(this, arguments), t.apply(this, arguments)
            } : n[e] = function () {
                t.apply(this, arguments), a.apply(this, arguments)
            } : n[e] = t
        }, _ajaxSubmit: function (t, i, a, r, n, o) {
            var l, s, d = this;
            d._raise("filepreajax", [n, o]) && (d._uploadExtra(n, o), d._initAjaxSettings(), d._mergeAjaxCallback("beforeSend", t), d._mergeAjaxCallback("success", i), d._mergeAjaxCallback("complete", a), d._mergeAjaxCallback("error", r), s = o && d.uploadUrlThumb ? d.uploadUrlThumb : d.uploadUrl, l = e.extend(!0, {}, {
                xhr: function () {
                    var t = e.ajaxSettings.xhr();
                    return d._initXhr(t, n, d.getFileStack().length)
                },
                url: d._encodeURI(s),
                type: "POST",
                dataType: "json",
                data: d.formdata,
                cache: !1,
                processData: !1,
                contentType: !1
            }, d._ajaxSettings), d.ajaxRequests.push(e.ajax(l)))
        }, _mergeArray: function (e, i) {
            var a = this, r = t.cleanArray(a[e]), n = t.cleanArray(i);
            a[e] = r.concat(n)
        }, _initUploadSuccess: function (i, a, r) {
            var n, o, l, s, d, c, p, u, f, m = this;
            m.showPreview && "object" == typeof i && !e.isEmptyObject(i) && void 0 !== i.initialPreview && i.initialPreview.length > 0 && (m.hasInitData = !0, c = i.initialPreview || [], p = i.initialPreviewConfig || [], u = i.initialPreviewThumbTags || [], n = void 0 === i.append || i.append, c.length > 0 && !t.isArray(c) && (c = c.split(m.initialPreviewDelimiter)), m._mergeArray("initialPreview", c), m._mergeArray("initialPreviewConfig", p), m._mergeArray("initialPreviewThumbTags", u), void 0 !== a ? r ? (f = a.attr("data-fileindex"), m.uploadCache.content[f] = c[0], m.uploadCache.config[f] = p[0] || [], m.uploadCache.tags[f] = u[0] || [], m.uploadCache.append = n) : (l = m.previewCache.add(c, p[0], u[0], n), o = m.previewCache.get(l, !1), s = e(document.createElement("div")).html(o).hide().insertAfter(a), d = s.find(".kv-zoom-cache"), d && d.length && d.insertAfter(a), a.fadeOut("slow", function () {
                var e = s.find(".file-preview-frame");
                e && e.length && e.insertBefore(a).fadeIn("slow").css("display:inline-block"), m._initPreviewActions(), m._clearFileInput(), t.cleanZoomCache(m.$preview.find("#zoom-" + a.attr("id"))), a.remove(), s.remove(), m._initSortable()
            })) : (m.previewCache.set(c, p, u, n), m._initPreview(), m._initPreviewActions()))
        }, _initSuccessThumbs: function () {
            var i = this;
            i.showPreview && i._getThumbs(t.FRAMES + ".file-preview-success").each(function () {
                var a = e(this), r = i.$preview, n = a.find(".kv-file-remove");
                n.removeAttr("disabled"), i._handler(n, "click", function () {
                    var e = a.attr("id"), n = i._raise("filesuccessremove", [e, a.attr("data-fileindex")]);
                    t.cleanMemory(a), n !== !1 && a.fadeOut("slow", function () {
                        t.cleanZoomCache(r.find("#zoom-" + e)), a.remove(), i.getFrames().length || i.reset()
                    })
                })
            })
        }, _checkAsyncComplete: function () {
            var t, i, a = this;
            for (i = 0; i < a.filestack.length; i++) if (a.filestack[i] && (t = a.previewInitId + "-" + i, -1 === e.inArray(t, a.uploadLog))) return !1;
            return a.uploadAsyncCount === a.uploadLog.length
        }, _uploadExtra: function (t, i) {
            var a = this, r = a._getExtraData(t, i);
            0 !== r.length && e.each(r, function (e, t) {
                a.formdata.append(e, t)
            })
        }, _uploadSingle: function (i, a) {
            var r, n, o, l, s, d, c, p, u, f, m, v = this, g = v.getFileStack().length, h = new FormData,
                w = v.previewInitId + "-" + i, _ = v.filestack.length > 0 || !e.isEmptyObject(v.uploadExtraData),
                b = e("#" + w).find(".file-thumb-progress"), C = {id: w, index: i};
            v.formdata = h, v.showPreview && (n = e("#" + w + ":not(.file-preview-initial)"), l = n.find(".kv-file-upload"), s = n.find(".kv-file-remove"), b.show()), 0 === g || !_ || l && l.hasClass("disabled") || v._abort(C) || (m = function (e, t) {
                d || v.updateStack(e, void 0), v.uploadLog.push(t), v._checkAsyncComplete() && (v.fileBatchCompleted = !0)
            }, o = function () {
                var e, i, a, r = v.uploadCache, n = 0, o = v.cacheInitialPreview;
                v.fileBatchCompleted && (o && o.content && (n = o.content.length), setTimeout(function () {
                    var l = 0 === v.getFileStack(!0).length;
                    if (v.showPreview) {
                        if (v.previewCache.set(r.content, r.config, r.tags, r.append), n) {
                            for (i = 0; i < r.content.length; i++) a = i + n, o.content[a] = r.content[i], o.config.length && (o.config[a] = r.config[i]), o.tags.length && (o.tags[a] = r.tags[i]);
                            v.initialPreview = t.cleanArray(o.content), v.initialPreviewConfig = t.cleanArray(o.config), v.initialPreviewThumbTags = t.cleanArray(o.tags)
                        } else v.initialPreview = r.content, v.initialPreviewConfig = r.config, v.initialPreviewThumbTags = r.tags;
                        v.cacheInitialPreview = {}, v.hasInitData && (v._initPreview(), v._initPreviewActions())
                    }
                    v.unlock(l), l && v._clearFileInput(), e = v.$preview.find(".file-preview-initial"), v.uploadAsync && e.length && (t.addCss(e, t.SORT_CSS), v._initSortable()), v._raise("filebatchuploadcomplete", [v.filestack, v._getExtraData()]), v.uploadCount = 0, v.uploadStatus = {}, v.uploadLog = [], v._setProgress(101), v.ajaxAborted = !1
                }, 100))
            }, c = function (o) {
                r = v._getOutData(o), v.fileBatchCompleted = !1, a || (v.ajaxAborted = !1), v.showPreview && (n.hasClass("file-preview-success") || (v._setThumbStatus(n, "Loading"), t.addCss(n, "file-uploading")), l.attr("disabled", !0), s.attr("disabled", !0)), a || v.lock(), v._raise("filepreupload", [r, w, i]), e.extend(!0, C, r), v._abort(C) && (o.abort(), a || (v._setThumbStatus(n, "New"), n.removeClass("file-uploading"), l.removeAttr("disabled"), s.removeAttr("disabled"), v.unlock()), v._setProgressCancelled())
            }, p = function (o, s, c) {
                var p = v.showPreview && n.attr("id") ? n.attr("id") : w;
                r = v._getOutData(c, o), e.extend(!0, C, r), setTimeout(function () {
                    t.isEmpty(o) || t.isEmpty(o.error) ? (v.showPreview && (v._setThumbStatus(n, "Success"), l.hide(), v._initUploadSuccess(o, n, a), v._setProgress(101, b)), v._raise("fileuploaded", [r, p, i]), a ? m(i, p) : v.updateStack(i, void 0)) : (d = !0, v._showUploadError(o.error, C), v._setPreviewError(n, i, v.filestack[i], v.retryErrorUploads), v.retryErrorUploads || l.hide(), a && m(i, p), v._setProgress(101, e("#" + p).find(".file-thumb-progress"), v.msgUploadError))
                }, 100)
            }, u = function () {
                setTimeout(function () {
                    v.showPreview && (l.removeAttr("disabled"), s.removeAttr("disabled"), n.removeClass("file-uploading")), a ? o() : (v.unlock(!1), v._clearFileInput()), v._initSuccessThumbs()
                }, 100)
            }, f = function (t, r, o) {
                var s = v.ajaxOperations.uploadThumb,
                    c = v._parseError(s, t, o, a && v.filestack[i].name ? v.filestack[i].name : null);
                d = !0, setTimeout(function () {
                    a && m(i, w), v.uploadStatus[w] = 100, v._setPreviewError(n, i, v.filestack[i], v.retryErrorUploads), v.retryErrorUploads || l.hide(), e.extend(!0, C, v._getOutData(t)), v._setProgress(101, b, v.msgAjaxProgressError.replace("{operation}", s)), v._setProgress(101, e("#" + w).find(".file-thumb-progress"), v.msgUploadError), v._showUploadError(c, C)
                }, 100)
            }, h.append(v.uploadFileAttr, v.filestack[i], v.filenames[i]), h.append("file_id", i), v._ajaxSubmit(c, p, u, f, w, i))
        }, _uploadBatch: function () {
            var i, a, r, n, o, l = this, s = l.filestack, d = s.length, c = {},
                p = l.filestack.length > 0 || !e.isEmptyObject(l.uploadExtraData);
            l.formdata = new FormData, 0 !== d && p && !l._abort(c) && (o = function () {
                e.each(s, function (e) {
                    l.updateStack(e, void 0)
                }), l._clearFileInput()
            }, i = function (i) {
                l.lock();
                var a = l._getOutData(i);
                l.ajaxAborted = !1, l.showPreview && l._getThumbs().each(function () {
                    var i = e(this), a = i.find(".kv-file-upload"), r = i.find(".kv-file-remove");
                    i.hasClass("file-preview-success") || (l._setThumbStatus(i, "Loading"), t.addCss(i, "file-uploading")), a.attr("disabled", !0), r.attr("disabled", !0)
                }), l._raise("filebatchpreupload", [a]), l._abort(a) && (i.abort(), l._getThumbs().each(function () {
                    var t = e(this), i = t.find(".kv-file-upload"), a = t.find(".kv-file-remove");
                    t.hasClass("file-preview-loading") && (l._setThumbStatus(t, "New"), t.removeClass("file-uploading")), i.removeAttr("disabled"), a.removeAttr("disabled")
                }), l._setProgressCancelled())
            }, a = function (i, a, r) {
                var n = l._getOutData(r, i), s = 0, d = l._getThumbs(":not(.file-preview-success)"),
                    c = t.isEmpty(i) || t.isEmpty(i.errorkeys) ? [] : i.errorkeys;
                t.isEmpty(i) || t.isEmpty(i.error) ? (l._raise("filebatchuploadsuccess", [n]), o(), l.showPreview ? (d.each(function () {
                    var t = e(this);
                    l._setThumbStatus(t, "Success"), t.removeClass("file-uploading"), t.find(".kv-file-upload").hide().removeAttr("disabled")
                }), l._initUploadSuccess(i)) : l.reset(), l._setProgress(101)) : (l.showPreview && (d.each(function () {
                    var t = e(this), i = t.attr("data-fileindex");
                    t.removeClass("file-uploading"), t.find(".kv-file-upload").removeAttr("disabled"), t.find(".kv-file-remove").removeAttr("disabled"), 0 === c.length || -1 !== e.inArray(s, c) ? (l._setPreviewError(t, i, l.filestack[i], l.retryErrorUploads), l.retryErrorUploads || (t.find(".kv-file-upload").hide(), l.updateStack(i, void 0))) : (t.find(".kv-file-upload").hide(), l._setThumbStatus(t, "Success"), l.updateStack(i, void 0)), t.hasClass("file-preview-error") && !l.retryErrorUploads || s++
                }), l._initUploadSuccess(i)), l._showUploadError(i.error, n, "filebatchuploaderror"), l._setProgress(101, l.$progress, l.msgUploadError))
            }, n = function () {
                l.unlock(), l._initSuccessThumbs(), l._clearFileInput(), l._raise("filebatchuploadcomplete", [l.filestack, l._getExtraData()])
            }, r = function (t, i, a) {
                var r = l._getOutData(t), n = l.ajaxOperations.uploadBatch, o = l._parseError(n, t, a);
                l._showUploadError(o, r, "filebatchuploaderror"), l.uploadFileCount = d - 1, l.showPreview && (l._getThumbs().each(function () {
                    var t = e(this), i = t.attr("data-fileindex");
                    t.removeClass("file-uploading"), void 0 !== l.filestack[i] && l._setPreviewError(t)
                }), l._getThumbs().removeClass("file-uploading"), l._getThumbs(" .kv-file-upload").removeAttr("disabled"), l._getThumbs(" .kv-file-delete").removeAttr("disabled"), l._setProgress(101, l.$progress, l.msgAjaxProgressError.replace("{operation}", n)))
            }, e.each(s, function (e, i) {
                t.isEmpty(s[e]) || l.formdata.append(l.uploadFileAttr, i, l.filenames[e])
            }), l._ajaxSubmit(i, a, n, r))
        }, _uploadExtraOnly: function () {
            var e, i, a, r, n = this, o = {};
            n.formdata = new FormData, n._abort(o) || (e = function (e) {
                n.lock();
                var t = n._getOutData(e);
                n._raise("filebatchpreupload", [t]), n._setProgress(50), o.data = t, o.xhr = e, n._abort(o) && (e.abort(), n._setProgressCancelled())
            }, i = function (e, i, a) {
                var r = n._getOutData(a, e);
                t.isEmpty(e) || t.isEmpty(e.error) ? (n._raise("filebatchuploadsuccess", [r]), n._clearFileInput(), n._initUploadSuccess(e), n._setProgress(101)) : n._showUploadError(e.error, r, "filebatchuploaderror")
            }, a = function () {
                n.unlock(), n._clearFileInput(), n._raise("filebatchuploadcomplete", [n.filestack, n._getExtraData()])
            }, r = function (e, t, i) {
                var a = n._getOutData(e), r = n.ajaxOperations.uploadExtra, l = n._parseError(r, e, i);
                o.data = a, n._showUploadError(l, a, "filebatchuploaderror"), n._setProgress(101, n.$progress, n.msgAjaxProgressError.replace("{operation}", r))
            }, n._ajaxSubmit(e, i, a, r))
        }, _deleteFileIndex: function (i) {
            var a = this, r = i.attr("data-fileindex"), n = a.reversePreviewOrder;
            "init_" === r.substring(0, 5) && (r = parseInt(r.replace("init_", "")), a.initialPreview = t.spliceArray(a.initialPreview, r, n), a.initialPreviewConfig = t.spliceArray(a.initialPreviewConfig, r, n), a.initialPreviewThumbTags = t.spliceArray(a.initialPreviewThumbTags, r, n), a.getFrames().each(function () {
                var t = e(this), i = t.attr("data-fileindex");
                "init_" === i.substring(0, 5) && (i = parseInt(i.replace("init_", "")), i > r && (i--, t.attr("data-fileindex", "init_" + i)))
            }), a.uploadAsync && (a.cacheInitialPreview = a.getPreview()))
        }, _initFileActions: function () {
            var i = this, a = i.$preview;
            i.showPreview && (i._initZoomButton(), i.getFrames(" .kv-file-remove").each(function () {
                var r, n, o, l, s = e(this), d = s.closest(t.FRAMES), c = d.attr("id"), p = d.attr("data-fileindex");
                i._handler(s, "click", function () {
                    return l = i._raise("filepreremove", [c, p]), l !== !1 && i._validateMinCount() ? (r = d.hasClass("file-preview-error"), t.cleanMemory(d), void d.fadeOut("slow", function () {
                        t.cleanZoomCache(a.find("#zoom-" + c)), i.updateStack(p, void 0), i._clearObjects(d), d.remove(), c && r && i.$errorContainer.find('li[data-file-id="' + c + '"]').fadeOut("fast", function () {
                            e(this).remove(), i._errorsExist() || i._resetErrors()
                        }), i._clearFileInput();
                        var l = i.getFileStack(!0), s = i.previewCache.count(), u = l.length,
                            f = i.showPreview && i.getFrames().length;
                        0 !== u || 0 !== s || f ? (n = s + u, o = n > 1 ? i._getMsgSelected(n) : l[0] ? i._getFileNames()[0] : "", i._setCaption(o)) : i.reset(), i._raise("fileremoved", [c, p])
                    })) : !1
                })
            }), i.getFrames(" .kv-file-upload").each(function () {
                var a = e(this);
                i._handler(a, "click", function () {
                    var e = a.closest(t.FRAMES), r = e.attr("data-fileindex");
                    i.$progress.hide(), e.hasClass("file-preview-error") && !i.retryErrorUploads || i._uploadSingle(r, !1)
                })
            }))
        }, _initPreviewActions: function () {
            var i = this, a = i.$preview, r = i.deleteExtraData || {}, n = t.FRAMES + " .kv-file-remove",
                o = i.fileActionSettings, l = o.removeClass, s = o.removeErrorClass, d = function () {
                    var e = i.isAjaxUpload ? i.previewCache.count() : i._inputFileCount();
                    a.find(t.FRAMES).length || e || (i._setCaption(""), i.reset(), i.initialCaption = "")
                };
            i._initZoomButton(), a.find(n).each(function () {
                var n, o, c, p = e(this), u = p.data("url") || i.deleteUrl, f = p.data("key");
                if (!t.isEmpty(u) && void 0 !== f) {
                    var m, v, g, h, w = p.closest(t.FRAMES), _ = i.previewCache.data, b = w.attr("data-fileindex");
                    b = parseInt(b.replace("init_", "")), g = t.isEmpty(_.config) && t.isEmpty(_.config[b]) ? null : _.config[b], h = t.isEmpty(g) || t.isEmpty(g.extra) ? r : g.extra, "function" == typeof h && (h = h()), v = {
                        id: p.attr("id"),
                        key: f,
                        extra: h
                    }, n = function (e) {
                        i.ajaxAborted = !1, i._raise("filepredelete", [f, e, h]), i._abort() ? e.abort() : (p.removeClass(s), t.addCss(w, "file-uploading"), t.addCss(p, "disabled " + l))
                    }, o = function (e, r, n) {
                        var o, c;
                        return t.isEmpty(e) || t.isEmpty(e.error) ? (w.removeClass("file-uploading").addClass("file-deleted"), void w.fadeOut("slow", function () {
                            b = parseInt(w.attr("data-fileindex").replace("init_", "")), i.previewCache.unset(b), i._deleteFileIndex(w), o = i.previewCache.count(), c = o > 0 ? i._getMsgSelected(o) : "", i._setCaption(c), i._raise("filedeleted", [f, n, h]), t.cleanZoomCache(a.find("#zoom-" + w.attr("id"))), i._clearObjects(w), w.remove(), d()
                        })) : (v.jqXHR = n, v.response = e, i._showError(e.error, v, "filedeleteerror"), w.removeClass("file-uploading"), p.removeClass("disabled " + l).addClass(s), void d())
                    }, c = function (e, t, a) {
                        var r = i.ajaxOperations.deleteThumb, n = i._parseError(r, e, a);
                        v.jqXHR = e, v.response = {}, i._showError(n, v, "filedeleteerror"), w.removeClass("file-uploading"), p.removeClass("disabled " + l).addClass(s), d()
                    }, i._initAjaxSettings(), i._mergeAjaxCallback("beforeSend", n, "delete"), i._mergeAjaxCallback("success", o, "delete"), i._mergeAjaxCallback("error", c, "delete"), m = e.extend(!0, {}, {
                        url: i._encodeURI(u),
                        type: "POST",
                        dataType: "json",
                        data: e.extend(!0, {}, {key: f}, h)
                    }, i._ajaxDeleteSettings), i._handler(p, "click", function () {
                        return i._validateMinCount() ? (i.ajaxAborted = !1, i._raise("filebeforedelete", [f, h]), void (i.ajaxAborted instanceof Promise ? i.ajaxAborted.then(function (t) {
                            t || e.ajax(m)
                        }) : i.ajaxAborted || e.ajax(m))) : !1
                    })
                }
            })
        }, _hideFileIcon: function () {
            var e = this;
            e.overwriteInitial && e.$captionContainer.removeClass("icon-visible")
        }, _showFileIcon: function () {
            var e = this;
            t.addCss(e.$captionContainer, "icon-visible")
        }, _getSize: function (t) {
            var i, a, r, n = this, o = parseFloat(t), l = n.fileSizeGetter;
            return e.isNumeric(t) && e.isNumeric(o) ? ("function" == typeof l ? r = l(o) : 0 === o ? r = "0.00 B" : (i = Math.floor(Math.log(o) / Math.log(1024)), a = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"], r = 1 * (o / Math.pow(1024, i)).toFixed(2) + " " + a[i]), n._getLayoutTemplate("size").replace("{sizeText}", r)) : ""
        }, _getFileType: function (e) {
            var t = this;
            return t.mimeTypeAliases[e] || e
        }, _generatePreviewTemplate: function (i, a, r, n, o, l, s, d, c, p, u) {
            var f, m, v = this, g = v.slug(r), h = "", w = "",
                _ = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
                b = 400 > _ ? v.previewSettingsSmall[i] || v.defaults.previewSettingsSmall[i] : v.previewSettings[i] || v.defaults.previewSettings[i],
                C = c || v._renderFileFooter(g, s, "auto", l), y = v._getPreviewIcon(r), x = "type-default",
                T = y && v.preferIconicPreview, E = y && v.preferIconicZoomPreview;
            return b && e.each(b, function (e, t) {
                w += e + ":" + t + ";"
            }), m = function (a, l, s, c) {
                var f = s ? "zoom-" + o : o, m = v._getPreviewTemplate(a), h = (d || "") + " " + c;
                return v.frameClass && (h = v.frameClass + " " + h), s && (h = h.replace(" " + t.SORT_CSS, "")), m = v._parseFilePreviewIcon(m, r), "text" === a && (l = t.htmlEncode(l)), "object" !== i || n || e.each(v.defaults.fileTypeSettings, function (e, t) {
                    "object" !== e && "other" !== e && t(r, n) && (x = "type-" + e)
                }), m.setTokens({
                    previewId: f,
                    caption: g,
                    frameClass: h,
                    type: v._getFileType(n),
                    fileindex: p,
                    typeCss: x,
                    footer: C,
                    data: l,
                    template: u || i,
                    style: w ? 'style="' + w + '"' : ""
                })
            }, p = p || o.slice(o.lastIndexOf("-") + 1), v.fileActionSettings.showZoom && (h = m(E ? "other" : i, a, !0, "kv-zoom-thumb")), h = "\n" + v._getLayoutTemplate("zoomCache").replace("{zoomContent}", h), f = m(T ? "other" : i, a, !1, "kv-preview-thumb"), f + h
        }, _addToPreview: function (e, t) {
            var i = this;
            return i.reversePreviewOrder ? e.prepend(t) : e.append(t)
        }, _previewDefault: function (i, a, r) {
            var n = this, o = n.$preview;
            if (n.showPreview) {
                var l, s = i ? i.name : "", d = i ? i.type : "", c = i.size || 0, p = n.slug(s),
                    u = r === !0 && !n.isAjaxUpload, f = t.createObjectURL(i);
                n._clearDefaultPreview(), l = n._generatePreviewTemplate("other", f, s, d, a, u, c), n._addToPreview(o, l), n._setThumbAttr(a, p, c), r === !0 && n.isAjaxUpload && n._setThumbStatus(e("#" + a), "Error")
            }
        }, _previewFile: function (e, t, i, a, r, n) {
            if (this.showPreview) {
                var o, l = this, s = t ? t.name : "", d = n.type, c = n.name, p = l._parseFileType(d, s),
                    u = l.allowedPreviewTypes, f = l.allowedPreviewMimeTypes, m = l.$preview, v = t.size || 0,
                    g = u && u.indexOf(p) >= 0, h = f && -1 !== f.indexOf(d),
                    w = "text" === p || "html" === p || "image" === p ? i.target.result : r;
                if ("html" === p && l.purifyHtml && window.DOMPurify && (w = window.DOMPurify.sanitize(w)), g || h) {
                    o = l._generatePreviewTemplate(p, w, s, d, a, !1, v), l._clearDefaultPreview(), l._addToPreview(m, o);
                    var _ = m.find("#" + a + " img");
                    l._validateImageOrientation(_, t, a, c, d, v, w)
                } else l._previewDefault(t, a);
                l._setThumbAttr(a, c, v), l._initSortable()
            }
        }, _setThumbAttr: function (t, i, a) {
            var r = this, n = e("#" + t);
            n.length && (a = a && a > 0 ? r._getSize(a) : "", n.data({caption: i, size: a}))
        }, _setInitThumbAttr: function () {
            var e, i, a, r, n = this, o = n.previewCache.data, l = n.previewCache.count();
            if (0 !== l) for (var s = 0; l > s; s++) e = o.config[s], r = n.previewInitId + "-init_" + s, i = t.ifSet("caption", e, t.ifSet("filename", e)), a = t.ifSet("size", e), n._setThumbAttr(r, i, a)
        }, _slugDefault: function (e) {
            return t.isEmpty(e) ? "" : String(e).replace(/[\[\]\/\{}:;#%=\(\)\*\+\?\\\^\$\|<>&"']/g, "_")
        }, _updateFileDetails: function (e) {
            var i = this, a = i.$element, r = i.getFileStack(),
                n = t.isIE(9) && t.findFileName(a.val()) || a[0].files[0] && a[0].files[0].name || r.length && r[0].name || "",
                o = i.slug(n), l = i.isAjaxUpload ? r.length : e, s = i.previewCache.count() + l,
                d = 1 === l ? o : i._getMsgSelected(s);
            i.isError ? (i.$previewContainer.removeClass("file-thumb-loading"), i.$previewStatus.html(""), i.$captionContainer.removeClass("icon-visible")) : i._showFileIcon(), i._setCaption(d, i.isError), i.$container.removeClass("file-input-new file-input-ajax-new"), 1 === arguments.length && i._raise("fileselect", [e, o]), i.previewCache.count() && i._initPreviewActions()
        }, _setThumbStatus: function (e, t) {
            var i = this;
            if (i.showPreview) {
                var a = "indicator" + t, r = a + "Title", n = "file-preview-" + t.toLowerCase(),
                    o = e.find(".file-upload-indicator"), l = i.fileActionSettings;
                e.removeClass("file-preview-success file-preview-error file-preview-loading"), "Success" === t && e.find(".file-drag-handle").remove(), o.html(l[a]), o.attr("title", l[r]), e.addClass(n), "Error" !== t || i.retryErrorUploads || e.find(".kv-file-upload").attr("disabled", !0)
            }
        }, _setProgressCancelled: function () {
            var e = this;
            e._setProgress(101, e.$progress, e.msgCancelled)
        }, _setProgress: function (e, i, a) {
            var r, n = this, o = Math.min(e, 100), l = n.progressUploadThreshold,
                s = 100 >= e ? n.progressTemplate : n.progressCompleteTemplate,
                d = 100 > o ? n.progressTemplate : a ? n.progressErrorTemplate : s;
            i = i || n.$progress, t.isEmpty(d) || (r = l && o > l && 100 >= e ? d.setTokens({
                percent: l,
                status: n.msgUploadThreshold
            }) : d.setTokens({
                percent: o,
                status: e > 100 ? n.msgUploadEnd : o + "%"
            }), i.html(r), a && i.find('[role="progressbar"]').html(a))
        }, _setFileDropZoneTitle: function () {
            var e, i = this, a = i.$container.find(".file-drop-zone"), r = i.dropZoneTitle;
            i.isClickable && (e = t.isEmpty(i.$element.attr("multiple")) ? i.fileSingle : i.filePlural, r += i.dropZoneClickTitle.replace("{files}", e)), a.find("." + i.dropZoneTitleClass).remove(), !i.showPreview || 0 === a.length || i.getFileStack().length > 0 || !i.dropZoneEnabled || !i.isAjaxUpload && i.$element.files || (0 === a.find(t.FRAMES).length && t.isEmpty(i.defaultPreviewContent) && a.prepend('<div class="' + i.dropZoneTitleClass + '">' + r + "</div>"), i.$container.removeClass("file-input-new"), t.addCss(i.$container, "file-input-ajax-new"))
        }, _setAsyncUploadStatus: function (t, i, a) {
            var r = this, n = 0;
            r._setProgress(i, e("#" + t).find(".file-thumb-progress")), r.uploadStatus[t] = i, e.each(r.uploadStatus, function (e, t) {
                n += t
            }), r._setProgress(Math.floor(n / a))
        }, _validateMinCount: function () {
            var e = this, t = e.isAjaxUpload ? e.getFileStack().length : e._inputFileCount();
            return e.validateInitialCount && e.minFileCount > 0 && e._getFileCount(t - 1) < e.minFileCount ? (e._noFilesError({}), !1) : !0
        }, _getFileCount: function (e) {
            var t = this, i = 0;
            return t.validateInitialCount && !t.overwriteInitial && (i = t.previewCache.count(), e += i), e
        }, _getFileId: function (e) {
            var t, i = this, a = i.generateFileId;
            return "function" == typeof a ? a(e, event) : e ? (t = String(e.webkitRelativePath || e.fileName || e.name || null), t ? e.size + "-" + t.replace(/[^0-9a-zA-Z_-]/gim, "") : null) : null
        }, _getFileName: function (e) {
            return e && e.name ? this.slug(e.name) : void 0
        }, _getFileIds: function (e) {
            var t = this;
            return t.fileids.filter(function (t) {
                return e ? void 0 !== t : void 0 !== t && null !== t
            })
        }, _getFileNames: function (e) {
            var t = this;
            return t.filenames.filter(function (t) {
                return e ? void 0 !== t : void 0 !== t && null !== t
            })
        }, _setPreviewError: function (e, t, i, a) {
            var r = this;
            if (void 0 !== t && r.updateStack(t, i), r.showPreview) {
                if (r.removeFromPreviewOnError && !a) return void e.remove();
                r._setThumbStatus(e, "Error"), r._refreshUploadButton(e, a)
            }
        }, _refreshUploadButton: function (e, t) {
            var i = this, a = e.find(".kv-file-upload"), r = i.fileActionSettings, n = r.uploadIcon, o = r.uploadTitle;
            a.length && (t && (n = r.uploadRetryIcon, o = r.uploadRetryTitle), a.attr("title", o).html(n))
        }, _checkDimensions: function (e, i, a, r, n, o, l) {
            var s, d, c, p, u = this, f = "Small" === i ? "min" : "max", m = u[f + "Image" + o];
            !t.isEmpty(m) && a.length && (c = a[0], d = "Width" === o ? c.naturalWidth || c.width : c.naturalHeight || c.height, p = "Small" === i ? d >= m : m >= d, p || (s = u["msgImage" + o + i].setTokens({
                name: n,
                size: m
            }), u._showUploadError(s, l), u._setPreviewError(r, e, null)))
        }, _getExifObj: function (e) {
            var t = this, i = null;
            try {
                i = window.piexif ? window.piexif.load(e) : null
            } catch (a) {
                i = null
            }
            return i || t._log("Error loading the piexif.js library."), i
        }, _validateImageOrientation: function (e, i, a, r, n, o, l) {
            var s, d, c = this;
            return s = e.length && c.autoOrientImage ? c._getExifObj(l) : null, (d = s ? s["0th"][piexif.ImageIFD.Orientation] : null) ? (t.setImageOrientation(e, c.$preview.find("#zoom-" + a + " img"), d), c._raise("fileimageoriented", {
                $img: e,
                file: i
            }), void c._validateImage(a, r, n, o, l, s)) : void c._validateImage(a, r, n, o, l, s)
        }, _validateImage: function (t, i, a, r, n, o) {
            var l, s, d, c = this, p = c.$preview, u = p.find("#" + t), f = u.attr("data-fileindex"), m = u.find("img");
            i = i || "Untitled", m.one("load", function () {
                s = u.width(), d = p.width(), s > d && m.css("width", "100%"), l = {
                    ind: f,
                    id: t
                }, c._checkDimensions(f, "Small", m, u, i, "Width", l), c._checkDimensions(f, "Small", m, u, i, "Height", l), c.resizeImage || (c._checkDimensions(f, "Large", m, u, i, "Width", l), c._checkDimensions(f, "Large", m, u, i, "Height", l)), c._raise("fileimageloaded", [t]), c.loadedImages.push({
                    ind: f,
                    img: m,
                    thumb: u,
                    pid: t,
                    typ: a,
                    siz: r,
                    validated: !1,
                    imgData: n,
                    exifObj: o
                }), u.data("exif", o), c._validateAllImages()
            }).one("error", function () {
                c._raise("fileimageloaderror", [t])
            }).each(function () {
                this.complete ? e(this).trigger("load") : this.error && e(this).trigger("error")
            })
        }, _validateAllImages: function () {
            var e, t, i, a = this, r = {val: 0}, n = a.loadedImages.length, o = a.resizeIfSizeMoreThan;
            if (n === a.totalImagesCount && (a._raise("fileimagesloaded"), a.resizeImage)) for (e = 0; e < a.loadedImages.length; e++) t = a.loadedImages[e], t.validated || (i = t.siz, i && i > 1e3 * o && a._getResizedImage(t, r, n), a.loadedImages[e].validated = !0)
        }, _getResizedImage: function (i, a, r) {
            var n, o, l, s, d, c, p, u = this, f = e(i.img)[0], m = f.naturalWidth, v = f.naturalHeight, g = 1,
                h = u.maxImageWidth || m, w = u.maxImageHeight || v, _ = !(!m || !v), b = u.imageCanvas,
                C = u.imageCanvasContext, y = i.typ, x = i.pid, T = i.ind, E = i.thumb, S = i.exifObj;
            if (d = function (e, t, i) {
                u.isAjaxUpload ? u._showUploadError(e, t, i) : u._showError(e, t, i), u._setPreviewError(E, T)
            }, (!u.filestack[T] || !_ || h >= m && w >= v) && (_ && u.filestack[T] && u._raise("fileimageresized", [x, T]), a.val++, a.val === r && u._raise("fileimagesresized"), !_)) return void d(u.msgImageResizeError, {
                id: x,
                index: T
            }, "fileimageresizeerror");
            y = y || u.resizeDefaultImageType, o = m > h, l = v > w, g = "width" === u.resizePreference ? o ? h / m : l ? w / v : 1 : l ? w / v : o ? h / m : 1, u._resetCanvas(), m *= g, v *= g, b.width = m, b.height = v;
            try {
                C.drawImage(f, 0, 0, m, v), s = b.toDataURL(y, u.resizeQuality), S && (p = window.piexif.dump(S), s = window.piexif.insert(p, s)), n = t.dataURI2Blob(s), u.filestack[T] = n, u._raise("fileimageresized", [x, T]), a.val++, a.val === r && u._raise("fileimagesresized", [void 0, void 0]), n instanceof Blob || d(u.msgImageResizeError, {
                    id: x,
                    index: T
                }, "fileimageresizeerror")
            } catch (k) {
                a.val++, a.val === r && u._raise("fileimagesresized", [void 0, void 0]), c = u.msgImageResizeException.replace("{errors}", k.message), d(c, {
                    id: x,
                    index: T
                }, "fileimageresizeexception")
            }
        }, _initBrowse: function (e) {
            var i = this, a = i.$element;
            i.showBrowse ? i.$btnFile = e.find(".btn-file").append(a) : (a.appendTo(e).attr("tabindex", -1), t.addCss(a, "file-no-browse"))
        }, _initClickable: function () {
            var i, a, r = this;
            r.isClickable && (i = r.$dropZone, r.isAjaxUpload || (a = r.$preview.find(".file-default-preview"), a.length && (i = a)), t.addCss(i, "clickable"), i.attr("tabindex", -1), r._handler(i, "click", function (t) {
                var a = e(t.target);
                e(r.elErrorContainer + ":visible").length || a.parents(".file-preview-thumbnails").length && !a.parents(".file-default-preview").length || (r.$element.data("zoneClicked", !0).trigger("click"), i.blur())
            }))
        }, _initCaption: function () {
            var e = this, i = e.initialCaption || "";
            return e.overwriteInitial || t.isEmpty(i) ? (e.$caption.val(""), !1) : (e._setCaption(i), !0)
        }, _setCaption: function (i, a) {
            var r, n, o, l, s, d = this, c = d.getFileStack();
            if (d.$caption.length) {
                if (d.$captionContainer.removeClass("icon-visible"), a) r = e("<div>" + d.msgValidationError + "</div>").text(), l = c.length, s = l ? 1 === l && c[0] ? d._getFileNames()[0] : d._getMsgSelected(l) : d._getMsgSelected(d.msgNo), n = t.isEmpty(i) ? s : i, o = '<span class="' + d.msgValidationErrorClass + '">' + d.msgValidationErrorIcon + "</span>"; else {
                    if (t.isEmpty(i)) return;
                    r = e("<div>" + i + "</div>").text(), n = r, o = d._getLayoutTemplate("fileIcon")
                }
                d.$captionContainer.addClass("icon-visible"), d.$caption.attr("title", r).val(n), d.$captionIcon.html(o)
            }
        }, _createContainer: function () {
            var t = this, i = {"class": "file-input file-input-new" + (t.rtl ? " kv-rtl" : "")},
                a = e(document.createElement("div")).attr(i).html(t._renderMain());
            return a.insertBefore(t.$element), t._initBrowse(a), t.theme && a.addClass("theme-" + t.theme), a
        }, _refreshContainer: function () {
            var e = this, t = e.$container, i = e.$element;
            i.insertAfter(t), t.html(e._renderMain()), e._initBrowse(t), e._validateDisabled()
        }, _validateDisabled: function () {
            var e = this;
            e.$caption.attr({readonly: e.isDisabled})
        }, _renderMain: function () {
            var e = this, t = e.dropZoneEnabled ? " file-drop-zone" : "file-drop-disabled",
                i = e.showClose ? e._getLayoutTemplate("close") : "",
                a = e.showPreview ? e._getLayoutTemplate("preview").setTokens({
                    "class": e.previewClass,
                    dropClass: t
                }) : "", r = e.isDisabled ? e.captionClass + " file-caption-disabled" : e.captionClass,
                n = e.captionTemplate.setTokens({"class": r + " kv-fileinput-caption"});
            return e.mainTemplate.setTokens({
                "class": e.mainClass + (!e.showBrowse && e.showCaption ? " no-browse" : ""),
                preview: a,
                close: i,
                caption: n,
                upload: e._renderButton("upload"),
                remove: e._renderButton("remove"),
                cancel: e._renderButton("cancel"),
                browse: e._renderButton("browse")
            })
        }, _renderButton: function (e) {
            var i = this, a = i._getLayoutTemplate("btnDefault"), r = i[e + "Class"], n = i[e + "Title"],
                o = i[e + "Icon"], l = i[e + "Label"], s = i.isDisabled ? " disabled" : "", d = "button";
            switch (e) {
                case"remove":
                    if (!i.showRemove) return "";
                    break;
                case"cancel":
                    if (!i.showCancel) return "";
                    r += " kv-hidden";
                    break;
                case"upload":
                    if (!i.showUpload) return "";
                    i.isAjaxUpload && !i.isDisabled ? a = i._getLayoutTemplate("btnLink").replace("{href}", i.uploadUrl) : d = "submit";
                    break;
                case"browse":
                    if (!i.showBrowse) return "";
                    a = i._getLayoutTemplate("btnBrowse");
                    break;
                default:
                    return ""
            }
            return r += "browse" === e ? " btn-file" : " fileinput-" + e + " fileinput-" + e + "-button", t.isEmpty(l) || (l = ' <span class="' + i.buttonLabelClass + '">' + l + "</span>"), a.setTokens({
                type: d,
                css: r,
                title: n,
                status: s,
                icon: o,
                label: l
            })
        }, _renderThumbProgress: function () {
            var e = this;
            return '<div class="file-thumb-progress kv-hidden">' + e.progressTemplate.setTokens({
                percent: "0",
                status: e.msgUploadBegin
            }) + "</div>"
        }, _renderFileFooter: function (e, i, a, r) {
            var n, o = this, l = o.fileActionSettings, s = l.showRemove, d = l.showDrag, c = l.showUpload,
                p = l.showZoom, u = o._getLayoutTemplate("footer"), f = o._getLayoutTemplate("indicator"),
                m = r ? l.indicatorError : l.indicatorNew, v = r ? l.indicatorErrorTitle : l.indicatorNewTitle,
                g = f.setTokens({indicator: m, indicatorTitle: v});
            return i = o._getSize(i), n = o.isAjaxUpload ? u.setTokens({
                actions: o._renderFileActions(c, !1, s, p, d, !1, !1, !1),
                caption: e,
                size: i,
                width: a,
                progress: o._renderThumbProgress(),
                indicator: g
            }) : u.setTokens({
                actions: o._renderFileActions(!1, !1, !1, p, d, !1, !1, !1),
                caption: e,
                size: i,
                width: a,
                progress: "",
                indicator: g
            }), n = t.replaceTags(n, o.previewThumbTags)
        }, _renderFileActions: function (e, t, i, a, r, n, o, l, s, d, c) {
            if (!(e || t || i || a || r)) return "";
            var p, u = this, f = o === !1 ? "" : ' data-url="' + o + '"', m = l === !1 ? "" : ' data-key="' + l + '"',
                v = "", g = "", h = "", w = "", _ = "", b = u._getLayoutTemplate("actions"), C = u.fileActionSettings,
                y = u.otherActionButtons.setTokens({dataKey: m, key: l}),
                x = n ? C.removeClass + " disabled" : C.removeClass;
            return i && (v = u._getLayoutTemplate("actionDelete").setTokens({
                removeClass: x,
                removeIcon: C.removeIcon,
                removeTitle: C.removeTitle,
                dataUrl: f,
                dataKey: m,
                key: l
            })), e && (g = u._getLayoutTemplate("actionUpload").setTokens({
                uploadClass: C.uploadClass,
                uploadIcon: C.uploadIcon,
                uploadTitle: C.uploadTitle
            })), t && (h = u._getLayoutTemplate("actionDownload").setTokens({
                downloadClass: C.downloadClass,
                downloadIcon: C.downloadIcon,
                downloadTitle: C.downloadTitle,
                downloadUrl: d || u.initialPreviewDownloadUrl
            }), h = h.setTokens({
                filename: c,
                key: l
            })), a && (w = u._getLayoutTemplate("actionZoom").setTokens({
                zoomClass: C.zoomClass,
                zoomIcon: C.zoomIcon,
                zoomTitle: C.zoomTitle
            })), r && s && (p = "drag-handle-init " + C.dragClass, _ = u._getLayoutTemplate("actionDrag").setTokens({
                dragClass: p,
                dragTitle: C.dragTitle,
                dragIcon: C.dragIcon
            })), b.setTokens({"delete": v, upload: g, download: h, zoom: w, drag: _, other: y})
        }, _browse: function (e) {
            var t = this;
            e && e.isDefaultPrevented() || !t._raise("filebrowse") || (t.isError && !t.isAjaxUpload && t.clear(), t.$captionContainer.focus())
        }, _filterDuplicate: function (e, t, i) {
            var a = this, r = a._getFileId(e);
            r && i && i.indexOf(r) > -1 || (i || (i = []), t.push(e), i.push(r))
        }, _change: function (i) {
            var a = this;
            if (!a.changeTriggered) {
                var r, n, o = a.$element, l = arguments.length > 1, s = a.isAjaxUpload, d = [],
                    c = l ? arguments[1] : o.get(0).files, p = !s && t.isEmpty(o.attr("multiple")) ? 1 : a.maxFileCount,
                    u = a.filestack.length, f = t.isEmpty(o.attr("multiple")), m = f && u > 0, v = a._getFileIds(),
                    g = function (t, i, r, n) {
                        var o = e.extend(!0, {}, a._getOutData({}, {}, c), {id: r, index: n}),
                            l = {id: r, index: n, file: i, files: c};
                        return s ? a._showUploadError(t, o) : a._showError(t, l)
                    }, h = function (e, t) {
                        var i = a.msgFilesTooMany.replace("{m}", t).replace("{n}", e);
                        a.isError = g(i, null, null, null), a.$captionContainer.removeClass("icon-visible"), a._setCaption("", !0), a.$container.removeClass("file-input-new file-input-ajax-new")
                    };
                if (a.reader = null, a._resetUpload(), a._hideFileIcon(), a.dropZoneEnabled && a.$container.find(".file-drop-zone ." + a.dropZoneTitleClass).remove(), s ? e.each(c, function (e, t) {
                    a._filterDuplicate(t, d, v)
                }) : (c = i.target && void 0 === i.target.files ? i.target.value ? [{name: i.target.value.replace(/^.+\\/, "")}] : [] : i.target.files || {}, d = c), t.isEmpty(d) || 0 === d.length) return s || a.clear(), void a._raise("fileselectnone");
                if (a._resetErrors(), n = d.length, r = a._getFileCount(s ? a.getFileStack().length + n : n), p > 0 && r > p) {
                    if (!a.autoReplace || n > p) return void h(a.autoReplace && n > p ? n : r, p);
                    r > p && a._resetPreviewThumbs(s)
                } else !s || m ? (a._resetPreviewThumbs(!1), m && a.clearStack()) : !s || 0 !== u || a.previewCache.count() && !a.overwriteInitial || a._resetPreviewThumbs(!0);
                a.isPreviewable ? a.readFiles(d) : a._updateFileDetails(1)
            }
        }, _abort: function (t) {
            var i, a = this;
            return a.ajaxAborted && "object" == typeof a.ajaxAborted && void 0 !== a.ajaxAborted.message ? (i = e.extend(!0, {}, a._getOutData(), t), i.abortData = a.ajaxAborted.data || {}, i.abortMessage = a.ajaxAborted.message, a._setProgress(101, a.$progress, a.msgCancelled), a._showUploadError(a.ajaxAborted.message, i, "filecustomerror"), a.cancel(), !0) : !!a.ajaxAborted
        }, _resetFileStack: function () {
            var i = this, a = 0, r = [], n = [], o = [];
            i._getThumbs().each(function () {
                var l = e(this), s = l.attr("data-fileindex"), d = i.filestack[s], c = l.attr("id");
                "-1" !== s && -1 !== s && (void 0 !== d ? (r[a] = d, n[a] = i._getFileName(d), o[a] = i._getFileId(d), l.attr({
                    id: i.previewInitId + "-" + a,
                    "data-fileindex": a
                }), a++) : l.attr({
                    id: "uploaded-" + t.uniqId(),
                    "data-fileindex": "-1"
                }), i.$preview.find("#zoom-" + c).attr({
                    id: "zoom-" + l.attr("id"),
                    "data-fileindex": l.attr("data-fileindex")
                }))
            }), i.filestack = r, i.filenames = n, i.fileids = o
        }, _isFileSelectionValid: function (e) {
            var t = this;
            return e = e || 0, t.required && !t.getFilesCount() ? (t.$errorContainer.html(""), t._showUploadError(t.msgFileRequired), !1) : t.minFileCount > 0 && t._getFileCount(e) < t.minFileCount ? (t._noFilesError({}), !1) : !0
        }, clearStack: function () {
            var e = this;
            return e.filestack = [], e.filenames = [], e.fileids = [], e.$element
        }, updateStack: function (e, t) {
            var i = this;
            return i.filestack[e] = t, i.filenames[e] = i._getFileName(t), i.fileids[e] = t && i._getFileId(t) || null, i.$element
        }, addToStack: function (e) {
            var t = this;
            return t.filestack.push(e), t.filenames.push(t._getFileName(e)), t.fileids.push(t._getFileId(e)), t.$element
        }, getFileStack: function (e) {
            var t = this;
            return t.filestack.filter(function (t) {
                return e ? void 0 !== t : void 0 !== t && null !== t
            })
        }, getFilesCount: function () {
            var e = this, t = e.isAjaxUpload ? e.getFileStack().length : e._inputFileCount();
            return e._getFileCount(t)
        }, readFiles: function (i) {
            this.reader = new FileReader;
            var a, r = this, n = r.$element, o = r.$preview, l = r.reader, s = r.$previewContainer,
                d = r.$previewStatus, c = r.msgLoading, p = r.msgProgress, u = r.previewInitId, f = i.length,
                m = r.fileTypeSettings, v = r.filestack.length, g = r.allowedFileTypes, h = g ? g.length : 0,
                w = r.allowedFileExtensions, _ = t.isEmpty(w) ? "" : w.join(", "),
                b = r.maxFilePreviewSize && parseFloat(r.maxFilePreviewSize), C = o.length && (!b || isNaN(b)),
                y = function (t, n, o, l) {
                    var s, d = e.extend(!0, {}, r._getOutData({}, {}, i), {id: o, index: l}),
                        c = {id: o, index: l, file: n, files: i};
                    r._previewDefault(n, o, !0), r.isAjaxUpload ? (r.addToStack(void 0), setTimeout(function () {
                        a(l + 1)
                    }, 100)) : f = 0, r._initFileActions(), s = e("#" + o), s.find(".kv-file-upload").hide(), r.removeFromPreviewOnError && s.remove(), r.isError = r.isAjaxUpload ? r._showUploadError(t, d) : r._showError(t, c), r._updateFileDetails(f)
                };
            r.loadedImages = [], r.totalImagesCount = 0, e.each(i, function (e, t) {
                var i = r.fileTypeSettings.image;
                i && i(t.type) && r.totalImagesCount++
            }), a = function (x) {
                if (t.isEmpty(n.attr("multiple")) && (f = 1), x >= f) return r.isAjaxUpload && r.filestack.length > 0 ? r._raise("filebatchselected", [r.getFileStack()]) : r._raise("filebatchselected", [i]), s.removeClass("file-thumb-loading"), void d.html("");
                var T, E, S, k, F, P, I, A, D, z, $, j, U = v + x, R = u + "-" + U, B = i[x], O = m.text, L = m.image,
                    M = m.html, Z = B && B.name ? r.slug(B.name) : "", N = (B && B.size || 0) / 1e3, H = "",
                    q = t.createObjectURL(B), W = 0, V = "", K = 0, Y = function () {
                        var e = p.setTokens({index: x + 1, files: f, percent: 50, name: Z});
                        setTimeout(function () {
                            d.html(e), r._updateFileDetails(f), a(x + 1)
                        }, 100), r._raise("fileloaded", [B, R, x, l])
                    };
                if (B) {
                    if (h > 0) for (E = 0; h > E; E++) P = g[E], I = r.msgFileTypes[P] || P, V += 0 === E ? I : ", " + I;
                    if (Z === !1) return void a(x + 1);
                    if (0 === Z.length) return S = r.msgInvalidFileName.replace("{name}", t.htmlEncode(B.name, "[unknown]")), void y(S, B, R, x);
                    if (t.isEmpty(w) || (H = new RegExp("\\.(" + w.join("|") + ")$", "i")), T = N.toFixed(2), r.maxFileSize > 0 && N > r.maxFileSize) return S = r.msgSizeTooLarge.setTokens({
                        name: Z,
                        size: T,
                        maxSize: r.maxFileSize
                    }), void y(S, B, R, x);
                    if (null !== r.minFileSize && N <= t.getNum(r.minFileSize)) return S = r.msgSizeTooSmall.setTokens({
                        name: Z,
                        size: T,
                        minSize: r.minFileSize
                    }), void y(S, B, R, x);
                    if (!t.isEmpty(g) && t.isArray(g)) {
                        for (E = 0; E < g.length; E += 1) k = g[E], A = m[k], W += A && "function" == typeof A && A(B.type, B.name) ? 1 : 0;
                        if (0 === W) return S = r.msgInvalidFileType.setTokens({name: Z, types: V}), void y(S, B, R, x)
                    }
                    if (0 === W && !t.isEmpty(w) && t.isArray(w) && !t.isEmpty(H) && (F = t.compare(Z, H), W += t.isEmpty(F) ? 0 : F.length, 0 === W)) return S = r.msgInvalidFileExtension.setTokens({
                        name: Z,
                        extensions: _
                    }), void y(S, B, R, x);
                    if (!r.showPreview) return r.isAjaxUpload && r.addToStack(B), setTimeout(function () {
                        a(x + 1), r._updateFileDetails(f)
                    }, 100), void r._raise("fileloaded", [B, R, x, l]);
                    if (!C && N > b) return r.addToStack(B), s.addClass("file-thumb-loading"), r._previewDefault(B, R), r._initFileActions(), r._updateFileDetails(f), void a(x + 1);
                    o.length && void 0 !== FileReader ? (D = O(B.type, Z), z = M(B.type, Z), $ = L(B.type, Z), d.html(c.replace("{index}", x + 1).replace("{files}", f)), s.addClass("file-thumb-loading"), l.onerror = function (e) {
                        r._errorHandler(e, Z)
                    }, l.onload = function (i) {
                        var a, n, o, s, d, c, p = [], u = function (e) {
                            var t = new FileReader;
                            t.onerror = function (e) {
                                r._errorHandler(e, Z)
                            }, t.onload = function (e) {
                                r._previewFile(x, B, e, R, q, n), r._initFileActions(), Y()
                            }, e ? t.readAsText(B, r.textEncoding) : t.readAsDataURL(B)
                        };
                        if (n = {name: Z, type: B.type}, e.each(m, function (e, t) {
                            "object" !== e && "other" !== e && "function" == typeof t && t(B.type, Z) && K++
                        }), 0 === K) {
                            for (o = new Uint8Array(i.target.result), E = 0; E < o.length; E++) s = o[E].toString(16), p.push(s);
                            if (a = p.join("").toLowerCase().substring(0, 8), c = t.getMimeType(a, "", ""), t.isEmpty(c) && (d = t.arrayBuffer2String(l.result), c = t.isSvg(d) ? "image/svg+xml" : t.getMimeType(a, d, B.type)), n = {
                                name: Z,
                                type: c
                            }, D = O(c, ""), z = M(c, ""), $ = L(c, ""), j = D || z, j || $) return void u(j)
                        }
                        r._previewFile(x, B, i, R, q, n), r._initFileActions(), Y()
                    }, l.onprogress = function (e) {
                        if (e.lengthComputable) {
                            var t = e.loaded / e.total * 100, i = Math.ceil(t);
                            S = p.setTokens({index: x + 1, files: f, percent: i, name: Z}), setTimeout(function () {
                                d.html(S)
                            }, 100)
                        }
                    }, D || z ? l.readAsText(B, r.textEncoding) : $ ? l.readAsDataURL(B) : l.readAsArrayBuffer(B)) : (r._previewDefault(B, R), setTimeout(function () {
                        a(x + 1), r._updateFileDetails(f)
                    }, 100), r._raise("fileloaded", [B, R, x, l])), r.addToStack(B)
                }
            }, a(0), r._updateFileDetails(f, !1)
        }, lock: function () {
            var e = this;
            return e._resetErrors(), e.disable(), e.showRemove && e.$container.find(".fileinput-remove").hide(), e.showCancel && e.$container.find(".fileinput-cancel").show(), e._raise("filelock", [e.filestack, e._getExtraData()]), e.$element
        }, unlock: function (e) {
            var t = this;
            return void 0 === e && (e = !0), t.enable(), t.showCancel && t.$container.find(".fileinput-cancel").hide(), t.showRemove && t.$container.find(".fileinput-remove").show(), e && t._resetFileStack(), t._raise("fileunlock", [t.filestack, t._getExtraData()]), t.$element
        }, cancel: function () {
            var t, i = this, a = i.ajaxRequests, r = a.length;
            if (r > 0) for (t = 0; r > t; t += 1) i.cancelling = !0, a[t].abort();
            return i._setProgressCancelled(), i._getThumbs().each(function () {
                var t = e(this), a = t.attr("data-fileindex");
                t.removeClass("file-uploading"), void 0 !== i.filestack[a] && (t.find(".kv-file-upload").removeClass("disabled").removeAttr("disabled"), t.find(".kv-file-remove").removeClass("disabled").removeAttr("disabled")), i.unlock()
            }), i.$element
        }, clear: function () {
            var i, a = this;
            if (a._raise("fileclear")) return a.$btnUpload.removeAttr("disabled"), a._getThumbs().find("video,audio,img").each(function () {
                t.cleanMemory(e(this))
            }), a._clearFileInput(), a._resetUpload(), a.clearStack(), a._resetErrors(!0), a._hasInitialPreview() ? (a._showFileIcon(), a._resetPreview(), a._initPreviewActions(), a.$container.removeClass("file-input-new")) : (a._getThumbs().each(function () {
                a._clearObjects(e(this))
            }), a.isAjaxUpload && (a.previewCache.data = {}), a.$preview.html(""), i = !a.overwriteInitial && a.initialCaption.length > 0 ? a.initialCaption : "", a.$caption.attr("title", "").val(i), t.addCss(a.$container, "file-input-new"), a._validateDefaultPreview()), 0 === a.$container.find(t.FRAMES).length && (a._initCaption() || a.$captionContainer.removeClass("icon-visible")), a._hideFileIcon(), a._raise("filecleared"), a.$captionContainer.focus(), a._setFileDropZoneTitle(), a.$element
        }, reset: function () {
            var e = this;
            if (e._raise("filereset")) return e._resetPreview(), e.$container.find(".fileinput-filename").text(""), t.addCss(e.$container, "file-input-new"), (e.getFrames().length || e.dropZoneEnabled) && e.$container.removeClass("file-input-new"), e.clearStack(), e.formdata = {}, e._setFileDropZoneTitle(), e.$element
        }, disable: function () {
            var e = this;
            return e.isDisabled = !0, e._raise("filedisabled"), e.$element.attr("disabled", "disabled"), e.$container.find(".kv-fileinput-caption").addClass("file-caption-disabled"), e.$container.find(".fileinput-remove, .fileinput-upload, .file-preview-frame button").attr("disabled", !0), t.addCss(e.$container.find(".btn-file"), "disabled"), e._initDragDrop(), e.$element
        }, enable: function () {
            var e = this;
            return e.isDisabled = !1, e._raise("fileenabled"), e.$element.removeAttr("disabled"), e.$container.find(".kv-fileinput-caption").removeClass("file-caption-disabled"), e.$container.find(".fileinput-remove, .fileinput-upload, .file-preview-frame button").removeAttr("disabled"), e.$container.find(".btn-file").removeClass("disabled"), e._initDragDrop(), e.$element
        }, upload: function () {
            var i, a, r, n = this, o = n.getFileStack().length, l = !e.isEmptyObject(n._getExtraData());
            if (n.isAjaxUpload && !n.isDisabled && n._isFileSelectionValid(o)) {
                if (n._resetUpload(), 0 === o && !l) return void n._showUploadError(n.msgUploadEmpty);
                if (n.$progress.show(), n.uploadCount = 0, n.uploadStatus = {}, n.uploadLog = [], n.lock(), n._setProgress(2), 0 === o && l) return void n._uploadExtraOnly();
                if (r = n.filestack.length, n.hasInitData = !1, !n.uploadAsync) return n._uploadBatch(), n.$element;
                for (a = n._getOutData(), n._raise("filebatchpreupload", [a]), n.fileBatchCompleted = !1, n.uploadCache = {
                    content: [],
                    config: [],
                    tags: [],
                    append: !0
                }, n.uploadAsyncCount = n.getFileStack().length, i = 0; r > i; i++) n.uploadCache.content[i] = null, n.uploadCache.config[i] = null, n.uploadCache.tags[i] = null;
                for (n.$preview.find(".file-preview-initial").removeClass(t.SORT_CSS), n._initSortable(), n.cacheInitialPreview = n.getPreview(), i = 0; r > i; i++) n.filestack[i] && n._uploadSingle(i, !0)
            }
        }, destroy: function () {
            var t = this, i = t.$form, a = t.$container, r = t.$element, n = t.namespace;
            return e(document).off(n), e(window).off(n), i && i.length && i.off(n), t.isAjaxUpload && t._clearFileInput(), t._cleanup(), t._initPreviewCache(), r.insertBefore(a).off(n).removeData(), a.off().remove(), r
        }, refresh: function (i) {
            var a = this, r = a.$element;
            return i = "object" != typeof i || t.isEmpty(i) ? a.options : e.extend(!0, {}, a.options, i), a._init(i, !0), a._listen(), r
        }, zoom: function (e) {
            var i = this, a = i._getFrame(e), r = i.$modal;
            a && (t.initModal(r), r.html(i._getModalContent()), i._setZoomContent(a), r.modal("show"), i._initZoomButtons())
        }, getExif: function (e) {
            var t = this, i = t._getFrame(e);
            return i && i.data("exif") || null
        }, getFrames: function (i) {
            var a, r = this;
            return i = i || "", a = r.$preview.find(t.FRAMES + i), r.reversePreviewOrder && (a = e(a.get().reverse())), a
        }, getPreview: function () {
            var e = this;
            return {content: e.initialPreview, config: e.initialPreviewConfig, tags: e.initialPreviewThumbTags}
        }
    }, e.fn.fileinput = function (a) {
        if (t.hasFileAPISupport() || t.isIE(9)) {
            var r = Array.apply(null, arguments), n = [];
            switch (r.shift(), this.each(function () {
                var o, l = e(this), s = l.data("fileinput"), d = "object" == typeof a && a,
                    c = d.theme || l.data("theme"), p = {}, u = {},
                    f = d.language || l.data("language") || e.fn.fileinput.defaults.language || "en";
                s || (c && (u = e.fn.fileinputThemes[c] || {}), "en" === f || t.isEmpty(e.fn.fileinputLocales[f]) || (p = e.fn.fileinputLocales[f] || {}), o = e.extend(!0, {}, e.fn.fileinput.defaults, u, e.fn.fileinputLocales.en, p, d, l.data()), s = new i(this, o), l.data("fileinput", s)), "string" == typeof a && n.push(s[a].apply(s, r))
            }), n.length) {
                case 0:
                    return this;
                case 1:
                    return n[0];
                default:
                    return n
            }
        }
    }, e.fn.fileinput.defaults = {
        language: "en",
        showCaption: !0,
        showBrowse: !0,
        showPreview: !0,
        showRemove: !0,
        showUpload: !0,
        showCancel: !0,
        showClose: !0,
        showUploadedThumbs: !0,
        browseOnZoneClick: !1,
        autoReplace: !1,
        autoOrientImage: !1,
        required: !1,
        rtl: !1,
        hideThumbnailContent: !1,
        encodeUrl: !0,
        generateFileId: null,
        previewClass: "",
        captionClass: "",
        frameClass: "krajee-default",
        mainClass: "file-caption-main",
        mainTemplate: null,
        purifyHtml: !0,
        fileSizeGetter: null,
        initialCaption: "",
        initialPreview: [],
        initialPreviewDelimiter: "*$$*",
        initialPreviewAsData: !1,
        initialPreviewFileType: "image",
        initialPreviewConfig: [],
        initialPreviewThumbTags: [],
        previewThumbTags: {},
        initialPreviewShowDelete: !0,
        initialPreviewDownloadUrl: "",
        removeFromPreviewOnError: !1,
        deleteUrl: "",
        deleteExtraData: {},
        overwriteInitial: !0,
        previewZoomButtonIcons: {
            prev: '<i class="la la-arrow-circle-right"></i>',
            next: '<i class="la la-arrow-circle-left"></i>',
            toggleheader: '<i class="la la-expand"></i>',
            fullscreen: '<i class="fa fa-expand-arrows-alt"></i>',
            borderless: '<i class="fa fa-arrows-alt-h"></i>',
            close: '<i class="la la-remove"></i>'
        },
        previewZoomButtonClasses: {
            prev: "btn btn-navigate",
            next: "btn btn-navigate",
            toggleheader: "btn btn-sm btn-kv btn-default btn-outline-secondary",
            fullscreen: "btn btn-sm btn-kv btn-default btn-outline-secondary",
            borderless: "btn btn-sm btn-kv btn-default btn-outline-secondary",
            close: "btn btn-sm btn-kv btn-default btn-outline-secondary"
        },
        previewTemplates: {},
        previewContentTemplates: {},
        preferIconicPreview: !1,
        preferIconicZoomPreview: !1,
        allowedPreviewTypes: void 0,
        allowedPreviewMimeTypes: null,
        allowedFileTypes: null,
        allowedFileExtensions: null,
        defaultPreviewContent: null,
        customLayoutTags: {},
        customPreviewTags: {},
        previewFileIcon: '<i class="la la-search"></i>',
        previewFileIconClass: "file-other-icon",
        previewFileIconSettings: {},
        previewFileExtSettings: {},
        buttonLabelClass: "hidden-xs",
        browseIcon: '<i class="icon-folder-open"></i>&nbsp;',
        browseClass: "btn btn-primary",
        removeIcon: '<i class="icon-trash"></i>',
        removeClass: "btn btn-default btn-secondary",
        cancelIcon: '<i class="icon-ban-circle"></i>',
        cancelClass: "btn btn-default btn-secondary",
        uploadIcon: '<i class="icon-upload"></i>',
        uploadClass: "btn btn-default btn-secondary",
        uploadUrl: null,
        uploadUrlThumb: null,
        uploadAsync: !0,
        uploadExtraData: {},
        zoomModalHeight: 480,
        minImageWidth: null,
        minImageHeight: null,
        maxImageWidth: null,
        maxImageHeight: null,
        resizeImage: !1,
        resizePreference: "width",
        resizeQuality: .92,
        resizeDefaultImageType: "image/jpeg",
        resizeIfSizeMoreThan: 0,
        minFileSize: 0,
        maxFileSize: 0,
        maxFilePreviewSize: 25600,
        minFileCount: 0,
        maxFileCount: 0,
        validateInitialCount: !1,
        msgValidationErrorClass: "text-danger",
        msgValidationErrorIcon: '<i class="icon-exclamation-sign"></i> ',
        msgErrorClass: "file-error-message",
        progressThumbClass: "progress-bar bg-success progress-bar-success progress-bar-striped active",
        progressClass: "progress-bar bg-success progress-bar-success progress-bar-striped active",
        progressCompleteClass: "progress-bar bg-success progress-bar-success",
        progressErrorClass: "progress-bar bg-danger progress-bar-danger",
        progressUploadThreshold: 99,
        previewFileType: "image",
        elCaptionContainer: null,
        elCaptionText: null,
        elPreviewContainer: null,
        elPreviewImage: null,
        elPreviewStatus: null,
        elErrorContainer: null,
        errorCloseButton: t.closeButton("kv-error-close"),
        slugCallback: null,
        dropZoneEnabled: !0,
        dropZoneTitleClass: "file-drop-zone-title",
        fileActionSettings: {},
        otherActionButtons: "",
        textEncoding: "UTF-8",
        ajaxSettings: {},
        ajaxDeleteSettings: {},
        showAjaxErrorDetails: !0,
        mergeAjaxCallbacks: !1,
        mergeAjaxDeleteCallbacks: !1,
        retryErrorUploads: !0,
        reversePreviewOrder: !1
    }, e.fn.fileinputLocales.en = {
        fileSingle: "file",
        filePlural: "files",
        browseLabel: "Browse &hellip;",
        removeLabel: "Remove",
        removeTitle: "Clear selected files",
        cancelLabel: "Cancel",
        cancelTitle: "Abort ongoing upload",
        uploadLabel: "Upload",
        uploadTitle: "Upload selected files",
        msgNo: "No",
        msgNoFilesSelected: "No files selected",
        msgCancelled: "Cancelled",
        msgPlaceholder: "Select {files}...",
        msgZoomModalHeading: "Detailed Preview",
        msgFileRequired: "You must select a file to upload.",
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'File "{name}" (<b>{size} KB</b>) exceeds maximum allowed upload size of <b>{maxSize} KB</b>.',
        msgFilesTooLess: "You must select at least <b>{n}</b> {files} to upload.",
        msgFilesTooMany: "Number of files selected for upload <b>({n})</b> exceeds maximum allowed limit of <b>{m}</b>.",
        msgFileNotFound: 'File "{name}" not found!',
        msgFileSecured: 'Security restrictions prevent reading the file "{name}".',
        msgFileNotReadable: 'File "{name}" is not readable.',
        msgFilePreviewAborted: 'File preview aborted for "{name}".',
        msgFilePreviewError: 'An error occurred while reading the file "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Invalid type for file "{name}". Only "{types}" files are supported.',
        msgInvalidFileExtension: 'Invalid extension for file "{name}". Only "{extensions}" files are supported.',
        msgFileTypes: {
            image: "image",
            html: "HTML",
            text: "text",
            video: "video",
            audio: "audio",
            flash: "flash",
            pdf: "PDF",
            object: "object"
        },
        msgUploadAborted: "The file upload was aborted",
        msgUploadThreshold: "Processing...",
        msgUploadBegin: "Initializing...",
        msgUploadEnd: "Done",
        msgUploadEmpty: "No valid data available for upload.",
        msgUploadError: "Error",
        msgValidationError: "Validation Error",
        msgLoading: "Loading file {index} of {files} &hellip;",
        msgProgress: "Loading file {index} of {files} - {name} - {percent}% completed.",
        msgSelected: "{n} {files} selected",
        msgFoldersNotAllowed: "Drag & drop files only! {n} folder(s) dropped were skipped.",
        msgImageWidthSmall: 'Width of image file "{name}" must be at least {size} px.',
        msgImageHeightSmall: 'Height of image file "{name}" must be at least {size} px.',
        msgImageWidthLarge: 'Width of image file "{name}" cannot exceed {size} px.',
        msgImageHeightLarge: 'Height of image file "{name}" cannot exceed {size} px.',
        msgImageResizeError: "Could not get the image dimensions to resize.",
        msgImageResizeException: "Error while resizing the image.<pre>{errors}</pre>",
        msgAjaxError: "Something went wrong with the {operation} operation. Please try again later!",
        msgAjaxProgressError: "{operation} failed",
        ajaxOperations: {
            deleteThumb: "file delete",
            uploadThumb: "file upload",
            uploadBatch: "batch file upload",
            uploadExtra: "form data upload"
        },
        dropZoneTitle: "Drag & drop files here &hellip;",
        dropZoneClickTitle: "<br>(or click to select {files})",
        previewZoomButtonTitles: {
            prev: "View previous file",
            next: "View next file",
            toggleheader: "Toggle header",
            fullscreen: "Toggle full screen",
            borderless: "Toggle borderless mode",
            close: "Close detailed preview"
        },
        usePdfRenderer: function () {
            var e = !!window.MSInputMethodContext && !!document.documentMode;
            return !!navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/i) || e
        },
        pdfRendererUrl: "",
        pdfRendererTemplate: '<iframe class="kv-preview-data file-preview-pdf" src="{renderer}?file={data}" {style}></iframe>'
    }, e.fn.fileinput.Constructor = i, e(document).ready(function () {
        var t = e("input.file[type=file]");
        t.length && t.fileinput()
    })
});

