/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/js/pages/form-mask.init.js ***!
  \**********************************************/
/*
Template Name: Minible - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Form mask Js File
*/

// date style1

var DateStyle1 = document.getElementById("input-date1");
var im = new Inputmask("dd/mm/yyyy");
im.mask(DateStyle1);

//  Date Style 2

var DateStyle2 = document.getElementById("input-date2");
var im = new Inputmask("mm/dd/yyyy");
im.mask(DateStyle2);

// Date time

var DateTime = document.getElementById("input-datetime");
var im = new Inputmask("yyyy-mm-ddHH:MM:ss");
im.mask(DateTime);

// Currency:
var currency = document.getElementById("input-currency");
var im = new Inputmask("yyyy-mm-dd'T'HH:MM:ss");
im.mask(currency);

// IP address

var ipSelector = document.getElementById("input-ip");
var im = new Inputmask("99.99.99.99");
im.mask(ipSelector);

// email

var emailSelector = document.getElementById("input-email");
var im = new Inputmask("_@_._");
im.mask(emailSelector);

// repeat
var repeatSelector = document.getElementById("input-repeat");
var im = new Inputmask("9999999999");
im.mask(repeatSelector);

// Mask

var selector = document.getElementById("input-mask");
var im = new Inputmask("99-9999999");
im.mask(selector);
/******/ })()
;