exports.ids = [1];
exports.modules = {

/***/ 133:
/***/ (function(module, exports) {

// Exports
module.exports = {

};


/***/ }),

/***/ 140:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_ref_3_oneOf_1_0_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_ref_3_oneOf_1_1_node_modules_nuxt_components_dist_loader_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_avatar_vue_vue_type_style_index_0_id_01b11bdc_prod_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(133);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_ref_3_oneOf_1_0_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_ref_3_oneOf_1_1_node_modules_nuxt_components_dist_loader_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_avatar_vue_vue_type_style_index_0_id_01b11bdc_prod_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_cjs_js_ref_3_oneOf_1_0_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_ref_3_oneOf_1_1_node_modules_nuxt_components_dist_loader_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_avatar_vue_vue_type_style_index_0_id_01b11bdc_prod_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_css_loader_dist_cjs_js_ref_3_oneOf_1_0_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_ref_3_oneOf_1_1_node_modules_nuxt_components_dist_loader_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_avatar_vue_vue_type_style_index_0_id_01b11bdc_prod_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_css_loader_dist_cjs_js_ref_3_oneOf_1_0_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_ref_3_oneOf_1_1_node_modules_nuxt_components_dist_loader_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_avatar_vue_vue_type_style_index_0_id_01b11bdc_prod_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ 145:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/vuetify-loader/lib/loader.js??ref--4!./node_modules/babel-loader/lib??ref--2-0!./node_modules/vue-loader/lib/loaders/templateLoader.js??ref--7!./node_modules/@nuxt/components/dist/loader.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/nuxt/components/avatar.vue?vue&type=template&id=01b11bdc&scoped=true
var render = function render() {
  var _vm = this,
    _c = _vm._self._c,
    _setup = _vm._self._setupProxy;
  return _c('svg', {
    staticClass: "avatar-svg",
    style: _setup.svgStyle,
    attrs: {
      "xmlns": "http://www.w3.org/2000/svg",
      "xmlns:xlink": "http://www.w3.org/1999/xlink",
      "viewBox": "0 0 152 152"
    }
  }, [_vm._ssrNode("<g data-v-01b11bdc><image x=\"14\" y=\"14\" width=\"125\" height=\"125\"" + _vm._ssrAttr("href", _vm.avatarHref) + _vm._ssrAttr("xlink:href", _vm.avatarHref) + " preserveAspectRatio=\"xMidYMid slice\" loading=\"lazy\" data-v-01b11bdc></image> <image x=\"0\" y=\"0\" width=\"152\" height=\"152\"" + _vm._ssrAttr("href", _vm.frameHref) + _vm._ssrAttr("xlink:href", _vm.frameHref) + " preserveAspectRatio=\"none\" loading=\"lazy\" data-v-01b11bdc></image></g>")]);
};
var staticRenderFns = [];

// CONCATENATED MODULE: ./resources/nuxt/components/avatar.vue?vue&type=template&id=01b11bdc&scoped=true

// EXTERNAL MODULE: external "vue"
var external_vue_ = __webpack_require__(1);

// CONCATENATED MODULE: ./node_modules/babel-loader/lib??ref--2-0!./node_modules/@nuxt/components/dist/loader.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/nuxt/components/avatar.vue?vue&type=script&setup=true&lang=js

/* harmony default export */ var avatarvue_type_script_setup_true_lang_js = ({
  __name: 'avatar',
  props: {
    avatarHref: {
      type: String,
      required: true
    },
    frameHref: {
      type: String,
      required: false,
      default: null
    },
    size: {
      type: [Number, String],
      default: 48
    }
  },
  setup(__props) {
    const props = __props;
    const svgStyle = Object(external_vue_["computed"])(() => ({
      width: `${props.size}px`,
      height: `${props.size}px`
    }));
    return {
      __sfc: true,
      props,
      svgStyle
    };
  }
});
// CONCATENATED MODULE: ./resources/nuxt/components/avatar.vue?vue&type=script&setup=true&lang=js
 /* harmony default export */ var components_avatarvue_type_script_setup_true_lang_js = (avatarvue_type_script_setup_true_lang_js); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__(14);

// CONCATENATED MODULE: ./resources/nuxt/components/avatar.vue



function injectStyles (context) {
  
  var style0 = __webpack_require__(140)
if (style0.__inject__) style0.__inject__(context)

}

/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  components_avatarvue_type_script_setup_true_lang_js,
  render,
  staticRenderFns,
  false,
  injectStyles,
  "01b11bdc",
  "5d06692c"
  
)

/* harmony default export */ var avatar = __webpack_exports__["default"] = (component.exports);

/***/ })

};;
//# sourceMappingURL=avatar.js.map