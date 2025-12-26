/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Version: 4.3.0
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Common Plugins Js File
*/

//Common plugins
// Note: Toastify is loaded from CDN, Choices.js and Flatpickr are loaded in vendor-scripts.blade.php
if (document.querySelectorAll("[toast-list]") || document.querySelectorAll('[data-choices]') || document.querySelectorAll("[data-provider]")) {
    document.writeln("<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'><\/script>");
    // Choices.js and Flatpickr are now loaded directly in vendor-scripts.blade.php with proper URLs
}

