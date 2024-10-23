/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/app/themes/feliz7play/dist/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = getLanguage;
document.addEventListener('DOMContentLoaded', function () {
    var idioma = getLanguage(),
          menu = document.querySelector('ul#' + idioma),
          menuConfig = document.querySelector('#user-' + idioma),
          Genre = document.getElementById('genre-' + idioma);

    activeClass(menu);
    activeClass(menuConfig);
    activeClass(Genre);
});

function getLanguage() {
    var url = window.location.pathname,
          idioma = url.split('/')[1];

    return idioma.toUpperCase();
}

function activeClass (className) {
    if (className) {
        className.classList.add('active');
    }
}

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
module.exports = __webpack_require__(4);


/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__layouts_header__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__layouts_language__ = __webpack_require__(0);



/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__language__ = __webpack_require__(0);


document.addEventListener('DOMContentLoaded', function () {
    var $header = document.getElementById('header'),
          $buttonLanguage = document.getElementById('button-language'),
          $headerLanguage = document.querySelector('.header__language'),
          $buttonUser = document.getElementById('button-user'),
          $buttonMenuMobile = document.getElementById('button-menu-mobile'),
          $headerGenre = document.querySelector('.header__genre'),
          $dropdownGenreTarget = document.querySelector('[data-genre='+ Object(__WEBPACK_IMPORTED_MODULE_0__language__["a" /* getLanguage */])() +']');
   
    $headerLanguage.addEventListener('mouseenter', function () { return $headerLanguage.classList.add('active'); }); 
    $headerLanguage.addEventListener('mouseleave', function () { return $headerLanguage.classList.remove('active'); });
    $buttonLanguage.addEventListener('click', function () { return $buttonMenuMobile.classList.toggle('active'); });
    

    if (window.matchMedia('(max-width: 560px)').matches) {
        $headerGenre.addEventListener('click', function () { return $dropdownGenreTarget.classList.toggle('open'); });
    } else {
        $headerGenre.addEventListener('mouseenter', function () { return $dropdownGenreTarget.classList.add('open'); });    
        $headerGenre.addEventListener('mouseleave', function () { return $dropdownGenreTarget.classList.remove('open'); }); 
    }

    if($buttonUser)
        { $buttonUser.addEventListener('click', function () { return $buttonUser.classList.toggle('active'); }); }

    document.addEventListener('click', function (event) {
        if(!event.target.closest('#button-language'))
            { $buttonLanguage.classList.remove('active'); }
        if(!event.target.closest('#button-user') && $buttonUser)
            { $buttonUser.classList.remove('active'); }
    }, false);

    window.addEventListener('scroll', function () { return $header.classList.toggle('black', window.scrollY > 100); }); 
});

/***/ }),
/* 4 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=main.js.map