"use strict";

(function ($) {
  $(document).ready(function () {
    if (typeof tinyMCE !== 'undefined') {
      editorCustomStyles();
    }
    function editorCustomStyles() {
      var editor = tinyMCE.get(data.editor_id);
      var body_dark_styles = data.theme_fonts ? "\n                body {\n                    line-height: normal;\n                    background-color: rgba(23,23,23,1);\n                    color: rgba(255,255,255,0.7); }\n                " : "\n                body {\n                    font-family: 'Albert Sans', sans-serif;\n                    line-height: normal;\n                    background-color: rgba(23,23,23,1);\n                    color: rgba(255,255,255,0.7); }\n                ";
      var body_light_styles = data.theme_fonts ? "\n                body {\n                    line-height: normal;\n                    background-color: rgba(255,255,255,1);\n                    color: rgba(0,25,49,1);\n                " : "\n                body {\n                    font-family: 'Albert Sans', sans-serif;\n                    line-height: normal;\n                    background-color: rgba(255,255,255,1);\n                    color: rgba(0,25,49,1);\n                ";
      if (editor.iframeElement === undefined) {
        setTimeout(function () {
          editorCustomStyles();
        }, 500);
      } else {
        var customStyles = data.dark_mode ? body_dark_styles : body_light_styles;
        var iframeDocument = editor.iframeElement.contentDocument || editor.iframeElement.contentWindow.document;
        var _styleElement = iframeDocument.createElement('style');
        _styleElement.innerHTML = customStyles;
        iframeDocument.head.appendChild(_styleElement);
      }
      var styleElement = document.createElement('style');
      var styles = data.dark_mode ? "\n            body .mce-container.mce-panel.mce-floatpanel {\n                background-color: rgba(30,30,30,1);\n                border: 1px solid rgba(255,255,255,.05);\n                border-radius: 4px;\n                color: rgba(255,255,255,1);\n                margin-top: 3px;\n            }\n            body .mce-container.mce-panel.mce-floatpanel .mce-menu-item:hover {\n                background-color: rgba(255,255,255,.05);\n            }\n            body .mce-container.mce-panel.mce-floatpanel .mce-menu-item.mce-active {\n                background-color: rgba(255,255,255,.05);\n            }\n            " : "\n            body .mce-container.mce-panel.mce-floatpanel {\n                background-color: rgba(255,255,255,1);\n                border: 1px solid rgba(238,241,247,1);\n                border-radius: 4px;\n                color: rgba(0,25,49,1);\n                margin-top: 3px;\n            }\n            body .mce-container.mce-panel.mce-floatpanel .mce-menu-item:hover {\n                background-color: rgba(34,122,255,1);\n                color: rgba(255,255,255,1);\n            }\n            body .mce-container.mce-panel.mce-floatpanel .mce-menu-item.mce-active {\n                background-color: rgba(34,122,255,1);\n                color: rgba(255,255,255,1);\n            }\n            ";
      styleElement.textContent = styles;
      document.head.appendChild(styleElement);
    }
  });
})(jQuery);