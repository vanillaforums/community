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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@vanillaforums/theme-boilerplate/src/js/index.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@vanillaforums/theme-boilerplate/src/js/index.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _mobileNavigation = __webpack_require__(/*! ./mobileNavigation */ \"./node_modules/@vanillaforums/theme-boilerplate/src/js/mobileNavigation.js\");\n\n$(function () {\n  (0, _mobileNavigation.setupMobileNavigation)();\n\n  $(\"select\").wrap('<div class=\"SelectWrapper\"></div>');\n}); /*!\n     * @author Isis (igraziatto) Graziatto <isis.g@vanillaforums.com>\n     * @copyright 2009-2018 Vanilla Forums Inc.\n     * @license GPL-2.0-only\n     *///# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvQHZhbmlsbGFmb3J1bXMvdGhlbWUtYm9pbGVycGxhdGUvc3JjL2pzL2luZGV4LmpzP2UzMWIiXSwibmFtZXMiOlsiJCIsIndyYXAiXSwibWFwcGluZ3MiOiI7O0FBTUE7O0FBRUFBLEVBQUUsWUFBTTtBQUNKOztBQUVBQSxJQUFFLFFBQUYsRUFBWUMsSUFBWixDQUFpQixtQ0FBakI7QUFDSCxDQUpELEUsQ0FSQSIsImZpbGUiOiIuL25vZGVfbW9kdWxlcy9AdmFuaWxsYWZvcnVtcy90aGVtZS1ib2lsZXJwbGF0ZS9zcmMvanMvaW5kZXguanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvKiFcbiAqIEBhdXRob3IgSXNpcyAoaWdyYXppYXR0bykgR3JhemlhdHRvIDxpc2lzLmdAdmFuaWxsYWZvcnVtcy5jb20+XG4gKiBAY29weXJpZ2h0IDIwMDktMjAxOCBWYW5pbGxhIEZvcnVtcyBJbmMuXG4gKiBAbGljZW5zZSBHUEwtMi4wLW9ubHlcbiAqL1xuXG5pbXBvcnQgeyBzZXR1cE1vYmlsZU5hdmlnYXRpb24gfSBmcm9tIFwiLi9tb2JpbGVOYXZpZ2F0aW9uXCI7XG5cbiQoKCkgPT4ge1xuICAgIHNldHVwTW9iaWxlTmF2aWdhdGlvbigpO1xuXG4gICAgJChcInNlbGVjdFwiKS53cmFwKCc8ZGl2IGNsYXNzPVwiU2VsZWN0V3JhcHBlclwiPjwvZGl2PicpO1xufSk7XG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./node_modules/@vanillaforums/theme-boilerplate/src/js/index.js\n");

/***/ }),

/***/ "./node_modules/@vanillaforums/theme-boilerplate/src/js/mobileNavigation.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@vanillaforums/theme-boilerplate/src/js/mobileNavigation.js ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n    value: true\n});\nexports.setupMobileNavigation = setupMobileNavigation;\n/*!\n * @author Isis (igraziatto) Graziatto <isis.g@vanillaforums.com>\n * @copyright 2009-2018 Vanilla Forums Inc.\n * @license GPL-2.0-only\n */\n\nvar INIT_CLASS = \"needsInitialization\";\nvar CALC_HEIGHT_ATTR = \"data-height\";\nvar COLLAPSED_HEIGHT = \"0px\";\n\nfunction setupMobileNavigation() {\n\n    var menuButton = document.querySelector(\"#menu-button\");\n    /** @type {HTMLElement} */\n    var navdrawer = document.querySelector(\".js-nav\");\n    /** @type {HTMLElement} */\n    var mobileMebox = document.querySelector(\".js-mobileMebox\");\n    var mobileMeBoxBtn = document.querySelector(\".mobileMeBox-button\");\n    var mobileMeboxBtnClose = document.querySelector(\".mobileMebox-buttonClose\");\n    var mainHeader = document.querySelector(\"#MainHeader\");\n\n    // Calculate the values initially.\n    prepareElement(mobileMebox);\n    prepareElement(navdrawer);\n\n    // Update the calculated values on resize.\n    window.addEventListener(\"resize\", function () {\n        requestAnimationFrame(function () {\n            prepareElement(mobileMebox);\n            prepareElement(navdrawer);\n        });\n    });\n\n    menuButton.addEventListener(\"click\", function () {\n        menuButton.classList.toggle(\"isToggled\");\n        mainHeader.classList.toggle(\"hasOpenNavigation\");\n        collapseElement(mobileMebox);\n        toggleElement(navdrawer);\n    });\n\n    mobileMeBoxBtn.addEventListener(\"click\", function () {\n        mobileMeBoxBtn.classList.toggle(\"isToggled\");\n        mainHeader.classList.remove(\"hasOpenNavigation\");\n        menuButton.classList.remove(\"isToggled\");\n        collapseElement(navdrawer);\n        toggleElement(mobileMebox);\n    });\n\n    mobileMeboxBtnClose.addEventListener(\"click\", function () {\n        collapseElement(mobileMebox);\n    });\n\n    /**\n     * @param {HTMLElement} element\n     */\n    function toggleElement(element) {\n        if (element.style.height === COLLAPSED_HEIGHT) {\n            expandElement(element);\n        } else {\n            collapseElement(element);\n        }\n    }\n\n    /**\n     * @param {HTMLElement} element\n     */\n    function collapseElement(element) {\n        element.style.height = COLLAPSED_HEIGHT;\n    }\n\n    /**\n     *\n     * @param {HTMLElement} element\n     */\n    function expandElement(element) {\n        element.style.height = element.getAttribute(CALC_HEIGHT_ATTR) + \"px\";\n    }\n\n    /**\n     * Get the calculated height of an element and\n     *\n     * @param {HTMLElement} element\n     */\n    function prepareElement(element) {\n        element.classList.add(INIT_CLASS);\n        element.style.height = \"auto\";\n        var calcedHeight = element.getBoundingClientRect().height;\n\n        // Visual hide the element.\n        element.setAttribute(CALC_HEIGHT_ATTR, calcedHeight.toString());\n        collapseElement(element);\n        element.classList.remove(INIT_CLASS);\n    }\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvQHZhbmlsbGFmb3J1bXMvdGhlbWUtYm9pbGVycGxhdGUvc3JjL2pzL21vYmlsZU5hdmlnYXRpb24uanM/ZDk1YiJdLCJuYW1lcyI6WyJzZXR1cE1vYmlsZU5hdmlnYXRpb24iLCJJTklUX0NMQVNTIiwiQ0FMQ19IRUlHSFRfQVRUUiIsIkNPTExBUFNFRF9IRUlHSFQiLCJtZW51QnV0dG9uIiwiZG9jdW1lbnQiLCJxdWVyeVNlbGVjdG9yIiwibmF2ZHJhd2VyIiwibW9iaWxlTWVib3giLCJtb2JpbGVNZUJveEJ0biIsIm1vYmlsZU1lYm94QnRuQ2xvc2UiLCJtYWluSGVhZGVyIiwicHJlcGFyZUVsZW1lbnQiLCJ3aW5kb3ciLCJhZGRFdmVudExpc3RlbmVyIiwicmVxdWVzdEFuaW1hdGlvbkZyYW1lIiwiY2xhc3NMaXN0IiwidG9nZ2xlIiwiY29sbGFwc2VFbGVtZW50IiwidG9nZ2xlRWxlbWVudCIsInJlbW92ZSIsImVsZW1lbnQiLCJzdHlsZSIsImhlaWdodCIsImV4cGFuZEVsZW1lbnQiLCJnZXRBdHRyaWJ1dGUiLCJhZGQiLCJjYWxjZWRIZWlnaHQiLCJnZXRCb3VuZGluZ0NsaWVudFJlY3QiLCJzZXRBdHRyaWJ1dGUiLCJ0b1N0cmluZyJdLCJtYXBwaW5ncyI6Ijs7Ozs7UUFVZ0JBLHFCLEdBQUFBLHFCO0FBVmhCOzs7Ozs7QUFNQSxJQUFNQyxhQUFhLHFCQUFuQjtBQUNBLElBQU1DLG1CQUFtQixhQUF6QjtBQUNBLElBQU1DLG1CQUFtQixLQUF6Qjs7QUFFTyxTQUFTSCxxQkFBVCxHQUFpQzs7QUFFcEMsUUFBTUksYUFBYUMsU0FBU0MsYUFBVCxDQUF1QixjQUF2QixDQUFuQjtBQUNBO0FBQ0EsUUFBTUMsWUFBWUYsU0FBU0MsYUFBVCxDQUF1QixTQUF2QixDQUFsQjtBQUNBO0FBQ0EsUUFBTUUsY0FBY0gsU0FBU0MsYUFBVCxDQUF1QixpQkFBdkIsQ0FBcEI7QUFDQSxRQUFNRyxpQkFBaUJKLFNBQVNDLGFBQVQsQ0FBdUIscUJBQXZCLENBQXZCO0FBQ0EsUUFBTUksc0JBQXNCTCxTQUFTQyxhQUFULENBQXVCLDBCQUF2QixDQUE1QjtBQUNBLFFBQU1LLGFBQWFOLFNBQVNDLGFBQVQsQ0FBdUIsYUFBdkIsQ0FBbkI7O0FBRUE7QUFDQU0sbUJBQWVKLFdBQWY7QUFDQUksbUJBQWVMLFNBQWY7O0FBRUE7QUFDQU0sV0FBT0MsZ0JBQVAsQ0FBd0IsUUFBeEIsRUFBa0MsWUFBTTtBQUNwQ0MsOEJBQXNCLFlBQU07QUFDeEJILDJCQUFlSixXQUFmO0FBQ0FJLDJCQUFlTCxTQUFmO0FBQ0gsU0FIRDtBQUlILEtBTEQ7O0FBT0FILGVBQVdVLGdCQUFYLENBQTRCLE9BQTVCLEVBQXFDLFlBQU07QUFDdkNWLG1CQUFXWSxTQUFYLENBQXFCQyxNQUFyQixDQUE0QixXQUE1QjtBQUNBTixtQkFBV0ssU0FBWCxDQUFxQkMsTUFBckIsQ0FBNEIsbUJBQTVCO0FBQ0FDLHdCQUFnQlYsV0FBaEI7QUFDQVcsc0JBQWNaLFNBQWQ7QUFDSCxLQUxEOztBQU9BRSxtQkFBZUssZ0JBQWYsQ0FBZ0MsT0FBaEMsRUFBeUMsWUFBTTtBQUMzQ0wsdUJBQWVPLFNBQWYsQ0FBeUJDLE1BQXpCLENBQWdDLFdBQWhDO0FBQ0FOLG1CQUFXSyxTQUFYLENBQXFCSSxNQUFyQixDQUE0QixtQkFBNUI7QUFDQWhCLG1CQUFXWSxTQUFYLENBQXFCSSxNQUFyQixDQUE0QixXQUE1QjtBQUNBRix3QkFBZ0JYLFNBQWhCO0FBQ0FZLHNCQUFjWCxXQUFkO0FBQ0gsS0FORDs7QUFRQUUsd0JBQW9CSSxnQkFBcEIsQ0FBcUMsT0FBckMsRUFBOEMsWUFBTTtBQUNoREksd0JBQWdCVixXQUFoQjtBQUNILEtBRkQ7O0FBSUE7OztBQUdBLGFBQVNXLGFBQVQsQ0FBdUJFLE9BQXZCLEVBQWdDO0FBQzVCLFlBQUlBLFFBQVFDLEtBQVIsQ0FBY0MsTUFBZCxLQUF5QnBCLGdCQUE3QixFQUErQztBQUMzQ3FCLDBCQUFjSCxPQUFkO0FBQ0gsU0FGRCxNQUVPO0FBQ0hILDRCQUFnQkcsT0FBaEI7QUFDSDtBQUNKOztBQUVEOzs7QUFHQSxhQUFTSCxlQUFULENBQXlCRyxPQUF6QixFQUFrQztBQUM5QkEsZ0JBQVFDLEtBQVIsQ0FBY0MsTUFBZCxHQUF1QnBCLGdCQUF2QjtBQUNIOztBQUVEOzs7O0FBSUEsYUFBU3FCLGFBQVQsQ0FBdUJILE9BQXZCLEVBQWdDO0FBQzVCQSxnQkFBUUMsS0FBUixDQUFjQyxNQUFkLEdBQXVCRixRQUFRSSxZQUFSLENBQXFCdkIsZ0JBQXJCLElBQXlDLElBQWhFO0FBQ0g7O0FBRUQ7Ozs7O0FBS0EsYUFBU1UsY0FBVCxDQUF3QlMsT0FBeEIsRUFBaUM7QUFDN0JBLGdCQUFRTCxTQUFSLENBQWtCVSxHQUFsQixDQUFzQnpCLFVBQXRCO0FBQ0FvQixnQkFBUUMsS0FBUixDQUFjQyxNQUFkLEdBQXVCLE1BQXZCO0FBQ0EsWUFBTUksZUFBZU4sUUFBUU8scUJBQVIsR0FBZ0NMLE1BQXJEOztBQUVBO0FBQ0FGLGdCQUFRUSxZQUFSLENBQXFCM0IsZ0JBQXJCLEVBQXVDeUIsYUFBYUcsUUFBYixFQUF2QztBQUNBWix3QkFBZ0JHLE9BQWhCO0FBQ0FBLGdCQUFRTCxTQUFSLENBQWtCSSxNQUFsQixDQUF5Qm5CLFVBQXpCO0FBQ0g7QUFDSiIsImZpbGUiOiIuL25vZGVfbW9kdWxlcy9AdmFuaWxsYWZvcnVtcy90aGVtZS1ib2lsZXJwbGF0ZS9zcmMvanMvbW9iaWxlTmF2aWdhdGlvbi5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8qIVxuICogQGF1dGhvciBJc2lzIChpZ3JhemlhdHRvKSBHcmF6aWF0dG8gPGlzaXMuZ0B2YW5pbGxhZm9ydW1zLmNvbT5cbiAqIEBjb3B5cmlnaHQgMjAwOS0yMDE4IFZhbmlsbGEgRm9ydW1zIEluYy5cbiAqIEBsaWNlbnNlIEdQTC0yLjAtb25seVxuICovXG5cbmNvbnN0IElOSVRfQ0xBU1MgPSBcIm5lZWRzSW5pdGlhbGl6YXRpb25cIjtcbmNvbnN0IENBTENfSEVJR0hUX0FUVFIgPSBcImRhdGEtaGVpZ2h0XCI7XG5jb25zdCBDT0xMQVBTRURfSEVJR0hUID0gXCIwcHhcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIHNldHVwTW9iaWxlTmF2aWdhdGlvbigpIHtcblxuICAgIGNvbnN0IG1lbnVCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiI21lbnUtYnV0dG9uXCIpO1xuICAgIC8qKiBAdHlwZSB7SFRNTEVsZW1lbnR9ICovXG4gICAgY29uc3QgbmF2ZHJhd2VyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5qcy1uYXZcIik7XG4gICAgLyoqIEB0eXBlIHtIVE1MRWxlbWVudH0gKi9cbiAgICBjb25zdCBtb2JpbGVNZWJveCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIuanMtbW9iaWxlTWVib3hcIik7XG4gICAgY29uc3QgbW9iaWxlTWVCb3hCdG4gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLm1vYmlsZU1lQm94LWJ1dHRvblwiKTtcbiAgICBjb25zdCBtb2JpbGVNZWJveEJ0bkNsb3NlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tb2JpbGVNZWJveC1idXR0b25DbG9zZVwiKTtcbiAgICBjb25zdCBtYWluSGVhZGVyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIiNNYWluSGVhZGVyXCIpO1xuXG4gICAgLy8gQ2FsY3VsYXRlIHRoZSB2YWx1ZXMgaW5pdGlhbGx5LlxuICAgIHByZXBhcmVFbGVtZW50KG1vYmlsZU1lYm94KTtcbiAgICBwcmVwYXJlRWxlbWVudChuYXZkcmF3ZXIpO1xuXG4gICAgLy8gVXBkYXRlIHRoZSBjYWxjdWxhdGVkIHZhbHVlcyBvbiByZXNpemUuXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoXCJyZXNpemVcIiwgKCkgPT4ge1xuICAgICAgICByZXF1ZXN0QW5pbWF0aW9uRnJhbWUoKCkgPT4ge1xuICAgICAgICAgICAgcHJlcGFyZUVsZW1lbnQobW9iaWxlTWVib3gpO1xuICAgICAgICAgICAgcHJlcGFyZUVsZW1lbnQobmF2ZHJhd2VyKTtcbiAgICAgICAgfSlcbiAgICB9KVxuXG4gICAgbWVudUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuICAgICAgICBtZW51QnV0dG9uLmNsYXNzTGlzdC50b2dnbGUoXCJpc1RvZ2dsZWRcIik7XG4gICAgICAgIG1haW5IZWFkZXIuY2xhc3NMaXN0LnRvZ2dsZShcImhhc09wZW5OYXZpZ2F0aW9uXCIpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnQobW9iaWxlTWVib3gpO1xuICAgICAgICB0b2dnbGVFbGVtZW50KG5hdmRyYXdlcik7XG4gICAgfSk7XG5cbiAgICBtb2JpbGVNZUJveEJ0bi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xuICAgICAgICBtb2JpbGVNZUJveEJ0bi5jbGFzc0xpc3QudG9nZ2xlKFwiaXNUb2dnbGVkXCIpO1xuICAgICAgICBtYWluSGVhZGVyLmNsYXNzTGlzdC5yZW1vdmUoXCJoYXNPcGVuTmF2aWdhdGlvblwiKTtcbiAgICAgICAgbWVudUJ1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKFwiaXNUb2dnbGVkXCIpO1xuICAgICAgICBjb2xsYXBzZUVsZW1lbnQobmF2ZHJhd2VyKVxuICAgICAgICB0b2dnbGVFbGVtZW50KG1vYmlsZU1lYm94KTtcbiAgICB9KTtcblxuICAgIG1vYmlsZU1lYm94QnRuQ2xvc2UuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsICgpID0+IHtcbiAgICAgICAgY29sbGFwc2VFbGVtZW50KG1vYmlsZU1lYm94KTtcbiAgICB9KTtcblxuICAgIC8qKlxuICAgICAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBmdW5jdGlvbiB0b2dnbGVFbGVtZW50KGVsZW1lbnQpIHtcbiAgICAgICAgaWYgKGVsZW1lbnQuc3R5bGUuaGVpZ2h0ID09PSBDT0xMQVBTRURfSEVJR0hUKSB7XG4gICAgICAgICAgICBleHBhbmRFbGVtZW50KGVsZW1lbnQpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY29sbGFwc2VFbGVtZW50KGVsZW1lbnQpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogQHBhcmFtIHtIVE1MRWxlbWVudH0gZWxlbWVudFxuICAgICAqL1xuICAgIGZ1bmN0aW9uIGNvbGxhcHNlRWxlbWVudChlbGVtZW50KSB7XG4gICAgICAgIGVsZW1lbnQuc3R5bGUuaGVpZ2h0ID0gQ09MTEFQU0VEX0hFSUdIVDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKlxuICAgICAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGVsZW1lbnRcbiAgICAgKi9cbiAgICBmdW5jdGlvbiBleHBhbmRFbGVtZW50KGVsZW1lbnQpIHtcbiAgICAgICAgZWxlbWVudC5zdHlsZS5oZWlnaHQgPSBlbGVtZW50LmdldEF0dHJpYnV0ZShDQUxDX0hFSUdIVF9BVFRSKSArIFwicHhcIjtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBHZXQgdGhlIGNhbGN1bGF0ZWQgaGVpZ2h0IG9mIGFuIGVsZW1lbnQgYW5kXG4gICAgICpcbiAgICAgKiBAcGFyYW0ge0hUTUxFbGVtZW50fSBlbGVtZW50XG4gICAgICovXG4gICAgZnVuY3Rpb24gcHJlcGFyZUVsZW1lbnQoZWxlbWVudCkge1xuICAgICAgICBlbGVtZW50LmNsYXNzTGlzdC5hZGQoSU5JVF9DTEFTUyk7XG4gICAgICAgIGVsZW1lbnQuc3R5bGUuaGVpZ2h0ID0gXCJhdXRvXCI7XG4gICAgICAgIGNvbnN0IGNhbGNlZEhlaWdodCA9IGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCkuaGVpZ2h0O1xuXG4gICAgICAgIC8vIFZpc3VhbCBoaWRlIHRoZSBlbGVtZW50LlxuICAgICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZShDQUxDX0hFSUdIVF9BVFRSLCBjYWxjZWRIZWlnaHQudG9TdHJpbmcoKSk7XG4gICAgICAgIGNvbGxhcHNlRWxlbWVudChlbGVtZW50KTtcbiAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKElOSVRfQ0xBU1MpO1xuICAgIH1cbn1cbiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./node_modules/@vanillaforums/theme-boilerplate/src/js/mobileNavigation.js\n");

/***/ }),

/***/ "./src/js/index.js":
/*!*************************!*\
  !*** ./src/js/index.js ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\n__webpack_require__(/*! ../../node_modules/@vanillaforums/theme-boilerplate/src/js/index */ \"./node_modules/@vanillaforums/theme-boilerplate/src/js/index.js\");//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9zcmMvanMvaW5kZXguanM/N2JhNSJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiOztBQU1FIiwiZmlsZSI6Ii4vc3JjL2pzL2luZGV4LmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyohXG4gKiBAYXV0aG9yIElzaXMgKGlncmF6aWF0dG8pIEdyYXppYXR0byA8aXNpcy5nQHZhbmlsbGFmb3J1bXMuY29tPlxuICogQGNvcHlyaWdodCAyMDA5LTIwMTggVmFuaWxsYSBGb3J1bXMgSW5jLlxuICogQGxpY2Vuc2UgR1BMLTIuMC1vbmx5XG4gKi9cblxuICBpbXBvcnQgXCIuLi8uLi9ub2RlX21vZHVsZXMvQHZhbmlsbGFmb3J1bXMvdGhlbWUtYm9pbGVycGxhdGUvc3JjL2pzL2luZGV4XCI7XG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./src/js/index.js\n");

/***/ })

/******/ });