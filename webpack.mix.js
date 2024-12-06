/*!
 * Copyright (c) 2023 Eclipse Foundation, Inc.
 *
 * This program and the accompanying materials are made available under the
 * terms of the Eclipse Public License v. 2.0 which is available at
 * http://www.eclipse.org/legal/epl-2.0.
 *
 * Contributors:
 *   Christopher Guindon <chris.guindon@eclipse-foundation.org>
 *   Olivier Goulet <olivier.goulet@eclipse-foundation.org>
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require('./node_modules/eclipsefdn-solstice-assets/webpack-solstice-assets.mix.js');
let mix = require('laravel-mix');
mix.EclipseFdnSolsticeAssets();

mix.setPublicPath('static/public');
mix.setResourceRoot('../');

mix.less('./less/styles.less', 'static/public/css/styles.css');
mix.less('./less/pages/home.less', 'static/public/css/home.css');

mix.js('js/main.js', './static/public/js/main.js');
