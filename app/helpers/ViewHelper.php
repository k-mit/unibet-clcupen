<?php namespace App\Helpers;

/**
 * This helper is registered in the composer.json file to be autoloaded into the application
 */

use App\Snippet;

class ViewHelper {

    protected $snippets;

    public function __construct () {
        $this->snippets = Snippet::get()->keyBy('snippet_name');
    }

    /**
     * Returns the snippet requested, if in debug returns the $key as a string.
     *
     * @param $key
     * @return string
     */
    public function snippet ($key) {

        try {
            return $this->snippets->get($key)->snippet_value;
        } catch (\Exception $exception) {
            return getenv('APP_DEBUG')?$key:'';
        }


    }

}
