<?php

return [
    /**
     * Blade components namespace
     */
    'namespace' => '',

    /**
     * Addition class for invalid form elements
     * Null - to disable this feature
     */
    'error' => null,

    /**
     * Replacement to the old values on validation failure
     */
    'old' => true,

    /**
     * Default HTML attributes (for merging)
     */
    'default_attributes' => [
        'input' => [],
        'select' => [],
        'textarea' => [],
    ],
];
