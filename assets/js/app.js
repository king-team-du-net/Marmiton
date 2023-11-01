/*
 * App Marmiton Recipe | Bootstrap 5
 * Copyright 2020-2023 rdequidt
 * Theme core scripts
*/

// css & scss
import '../css/app.css';

// jQuery
const $ = require('jquery');
global.$ = global.jQuery = $;

// start the Stimulus application
import '../bootstrap';

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap';

// Libs JS
import './libs.js';
