<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@stimulus-components/dialog' => [
        'version' => '1.0.1',
    ],
    'Toasthandler' => [
        'path' => './assets/utils/Toasthandler.js',
    ],
    'Req' => [
        'path' => './assets/utils/Req.js',
    ],
    'image_recognition' => [
        'path' => './assets/image_recognition.js',
        'entrypoint' => true,
    ],
    'moment' => [
        'version' => '2.30.1',
    ],
    'jszip' => [
        'version' => '3.10.1',
    ],
    'file-saver' => [
        'version' => '2.0.5',
    ],
    'long' => [
        'version' => '4.0.0',
    ],
    'seedrandom' => [
        'version' => '3.0.5',
    ],
    '@tensorflow/tfjs' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-core' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-core/dist/register_all_gradients' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-core/dist/public/chained_ops/register_all_chained_ops' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-layers' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-converter' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-data' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-backend-cpu' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-backend-webgl' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-core/dist/ops/ops_for_converter' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-backend-cpu/dist/shared' => [
        'version' => '4.22.0',
    ],
    '@tensorflow/tfjs-core/dist/io/io_utils' => [
        'version' => '4.22.0',
    ],
];
